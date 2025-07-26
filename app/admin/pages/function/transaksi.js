// transaksi.js - Database Integrated Version (Complete)
let scannerActive = false;
let currentTransaction = 'peminjaman';
let currentLoanData = null; // Store current loan data for return process

// API Base URL - adjust according to your project structure
const API_BASE_URL = 'pages/function/Peminjaman.php';

// Switch between transaction types
function switchTransaction(type) {
    currentTransaction = type;
    
    // Update button states
    document.getElementById('peminjamanBtn').classList.remove('active');
    document.getElementById('pengembalianBtn').classList.remove('active');
    
    if (type === 'peminjaman') {
        document.getElementById('peminjamanBtn').classList.add('active');
        document.getElementById('peminjamanSection').style.display = 'block';
        document.getElementById('pengembalianSection').style.display = 'none';
    } else {
        document.getElementById('pengembalianBtn').classList.add('active');
        document.getElementById('peminjamanSection').style.display = 'none';
        document.getElementById('pengembalianSection').style.display = 'block';
    }
    
    // Clear all forms when switching
    clearAllForms();
}

function toggleScanner() {
    const section = document.getElementById('scannerSection');
    const button = document.getElementById('scannerToggle');
    
    if (section.style.display === 'none') {
        section.style.display = 'block';
        button.innerHTML = '<i class="fa fa-camera"></i> Sembunyikan Scanner';
        button.className = 'btn btn-warning btn-sm';
    } else {
        section.style.display = 'none';
        button.innerHTML = '<i class="fa fa-camera"></i> Aktifkan Scanner';
        button.className = 'btn btn-info btn-sm';
        stopScanner();
    }
}

function startScanner() {
    if (scannerActive) return;
    
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                width: 400,
                height: 300,
                facingMode: "environment"
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: 2,
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.log(err);
            alert('Tidak dapat mengakses kamera!');
            return;
        }
        console.log("Initialization finished. Ready to start");
        Quagga.start();
        scannerActive = true;
        document.getElementById('startBtn').disabled = true;
        document.getElementById('stopBtn').disabled = false;
    });

    Quagga.onDetected(detected);
}

function stopScanner() {
    if (!scannerActive) return;
    
    Quagga.stop();
    scannerActive = false;
    document.getElementById('startBtn').disabled = false;
    document.getElementById('stopBtn').disabled = true;
}

function detected(result) {
    const code = result.codeResult.code;
    console.log("Barcode detected:", code);
    
    // Stop scanner after successful scan
    stopScanner();
    
    // Process the scanned code
    processScannedCode(code);
}

function processScannedCode(code) {
    // Check if it's an anggota code (starts with letters)
    if (/^[A-Z]/.test(code)) {
        processAnggotaCode(code);
    } 
    // Check if it's a book ISBN (numeric, usually 10 or 13 digits)
    else if (/^\d{10,13}$/.test(code)) {
        processBukuCode(code);
    }
    else {
        alert('Format barcode tidak sesuai. Pastikan scan kartu anggota atau ISBN buku.');
    }
}

// Process anggota code with database integration
function processAnggotaCode(kode) {
    const suffix = currentTransaction === 'peminjaman' ? 'Pinjam' : 'Kembali';
    
    // Show loading state
    showLoading('Memverifikasi anggota...');
    
    fetch(`${API_BASE_URL}?action=getAnggota&kode=${encodeURIComponent(kode)}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.status === 'success') {
                document.getElementById('kodeAnggota' + suffix).value = data.data.kode_user;
                document.getElementById('namaAnggota' + suffix).value = data.data.nama;
                document.getElementById('kelasAnggota' + suffix).value = data.data.kelas;
                document.getElementById('anggotaStatus' + suffix).style.display = 'block';
                
                showAlert('success', `Anggota Ditemukan!\n${data.data.nama} - ${data.data.kelas}`);
            } else {
                if (currentTransaction === 'peminjaman') {
                    clearAnggotaPinjam();
                } else {
                    clearAnggotaKembali();
                }
                showAlert('error', `Anggota Tidak Ditemukan\n${data.message}`);
            }
            
            checkFormCompletion();
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memverifikasi anggota');
        });
}

// Process buku code with database integration
function processBukuCode(isbn) {
    const suffix = currentTransaction === 'peminjaman' ? 'Pinjam' : 'Kembali';
    
    // Show loading state
    showLoading('Memverifikasi buku...');
    
    fetch(`${API_BASE_URL}?action=getBuku&isbn=${encodeURIComponent(isbn)}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('isbnBuku' + suffix).value = data.data.isbn;
                document.getElementById('judulBuku' + suffix).value = data.data.judul;
                
                if (currentTransaction === 'peminjaman') {
                    document.getElementById('pengarangBuku' + suffix).value = data.data.pengarang;
                    document.getElementById('stokBuku' + suffix).value = data.data.stok_tersedia;
                    
                    if (!data.data.available) {
                        showAlert('warning', 'Buku tidak tersedia untuk dipinjam (stok habis)');
                        return;
                    }
                } else {
                    // For return, check loan data
                    const anggotaKode = document.getElementById('kodeAnggotaKembali').value;
                    if (anggotaKode) {
                        checkLoanData(anggotaKode, isbn);
                    }
                }
                
                document.getElementById('bukuStatus' + suffix).style.display = 'block';
                showAlert('success', `Buku Ditemukan!\n${data.data.judul} - ${data.data.pengarang}`);
            } else {
                if (currentTransaction === 'peminjaman') {
                    clearBukuPinjam();
                } else {
                    clearBukuKembali();
                }
                showAlert('error', `Buku Tidak Ditemukan\n${data.message}`);
            }
            
            hideLoading();
            checkFormCompletion();
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memverifikasi buku');
        });
}

// Check loan data for return process
function checkLoanData(kodeAnggota, isbn) {
    fetch(`${API_BASE_URL}?action=checkPeminjaman&kode=${encodeURIComponent(kodeAnggota)}&isbn=${encodeURIComponent(isbn)}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                currentLoanData = data.data;
                document.getElementById('tanggalPeminjamanKembali').value = data.data.tanggal_peminjaman;
                document.getElementById('statusKeterlambatan').value = data.data.status;
                
                // Show warning if overdue
                if (data.data.is_overdue) {
                    showAlert('warning', `Buku terlambat ${data.data.days_overdue} hari!\nDenda keterlambatan akan dikenakan.`);
                }
            } else {
                showAlert('error', `Data Peminjaman Tidak Ditemukan\n${data.message}`);
                clearBukuKembali();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memeriksa data peminjaman');
        });
}

function processManualInput() {
    const input = document.getElementById('manualInput').value.trim();
    if (input) {
        processScannedCode(input);
        document.getElementById('manualInput').value = '';
    }
}

// Clear functions for peminjaman
function clearAnggotaPinjam() {
    document.getElementById('kodeAnggotaPinjam').value = '';
    document.getElementById('namaAnggotaPinjam').value = '';
    document.getElementById('kelasAnggotaPinjam').value = '';
    document.getElementById('anggotaStatusPinjam').style.display = 'none';
    checkFormCompletion();
}

function clearBukuPinjam() {
    document.getElementById('isbnBukuPinjam').value = '';
    document.getElementById('judulBukuPinjam').value = '';
    document.getElementById('pengarangBukuPinjam').value = '';
    document.getElementById('stokBukuPinjam').value = '';
    document.getElementById('bukuStatusPinjam').style.display = 'none';
    checkFormCompletion();
}

// Clear functions for pengembalian
function clearAnggotaKembali() {
    document.getElementById('kodeAnggotaKembali').value = '';
    document.getElementById('namaAnggotaKembali').value = '';
    document.getElementById('kelasAnggotaKembali').value = '';
    document.getElementById('anggotaStatusKembali').style.display = 'none';
    currentLoanData = null;
    checkFormCompletion();
}

function clearBukuKembali() {
    document.getElementById('isbnBukuKembali').value = '';
    document.getElementById('judulBukuKembali').value = '';
    document.getElementById('tanggalPeminjamanKembali').value = '';
    document.getElementById('statusKeterlambatan').value = '';
    document.getElementById('bukuStatusKembali').style.display = 'none';
    currentLoanData = null;
    checkFormCompletion();
}

function clearAllForms() {
    clearAnggotaPinjam();
    clearBukuPinjam();
    clearAnggotaKembali();
    clearBukuKembali();
}

function checkFormCompletion() {
    if (currentTransaction === 'peminjaman') {
        const anggota = document.getElementById('namaAnggotaPinjam').value;
        const buku = document.getElementById('judulBukuPinjam').value;
        const submitBtn = document.getElementById('submitBtnPinjam');
        
        if (anggota && buku) {
            submitBtn.disabled = false;
            submitBtn.className = 'btn btn-success btn-block';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'btn btn-success btn-block';
        }
    } else {
        const anggota = document.getElementById('namaAnggotaKembali').value;
        const buku = document.getElementById('judulBukuKembali').value;
        const submitBtn = document.getElementById('submitBtnKembali');
        
        if (anggota && buku && currentLoanData) {
            submitBtn.disabled = false;
            submitBtn.className = 'btn btn-danger btn-block';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'btn btn-danger btn-block';
        }
    }
}

// Calculate denda with database integration
function calculateDenda(kondisi) {
    if (!currentLoanData) return;
    
    const days = currentLoanData.days_overdue || 0;
    
    fetch(`${API_BASE_URL}?action=calculateDenda&kondisi=${encodeURIComponent(kondisi)}&days=${days}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('dendaPreview').value = data.data.formatted;
            }
        })
        .catch(error => {
            console.error('Error calculating denda:', error);
        });
}

// Form submission handlers
function submitTransaksi(form, type) {
    const formData = new FormData(form);
    const action = type === 'peminjaman' ? 'processPeminjaman' : 'processPengembalian';
    
    showLoading(`Memproses ${type}...`);
    
    fetch(`${API_BASE_URL}?action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.status === 'success') {
            showAlert('success', data.message);
            
            // Show denda info for returns
            if (type === 'pengembalian' && data.denda && data.denda.total > 0) {
                showAlert('info', `Denda yang harus dibayar: ${data.denda.formatted}`);
            }
            
            // Clear forms after successful submission
            clearAllForms();
            
            // Optionally redirect or refresh
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memproses transaksi');
    });
    
    return false; // Prevent default form submission
}

// Utility functions
function showLoading(message = 'Memproses...') {
    // Create or show loading overlay
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            font-family: Arial, sans-serif;
        `;
        
        overlay.innerHTML = `
            <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 0 auto 15px;"></div>
                <div style="color: #333; font-size: 14px;">${message}</div>
            </div>
        `;
        
        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(overlay);
    } else {
        overlay.querySelector('div div:last-child').textContent = message;
        overlay.style.display = 'flex';
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

function showAlert(type, message) {
    // Create alert modal or use browser alert
    let alertClass = '';
    let icon = '';
    
    switch(type) {
        case 'success':
            alertClass = 'alert-success';
            icon = 'fa-check-circle';
            break;
        case 'error':
            alertClass = 'alert-danger';
            icon = 'fa-exclamation-circle';
            break;
        case 'warning':
            alertClass = 'alert-warning';
            icon = 'fa-exclamation-triangle';
            break;
        case 'info':
            alertClass = 'alert-info';
            icon = 'fa-info-circle';
            break;
        default:
            alertClass = 'alert-info';
            icon = 'fa-info-circle';
    }
    
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} custom-alert`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        max-width: 500px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease-out;
    `;
    
    alertDiv.innerHTML = `
        <div style="display: flex; align-items: center;">
            <i class="fa ${icon}" style="margin-right: 10px; font-size: 18px;"></i>
            <div style="flex: 1; white-space: pre-line;">${message}</div>
            <button type="button" onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; margin-left: 10px;">&times;</button>
        </div>
    `;
    
    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    if (!document.querySelector('style[data-alert-animation]')) {
        style.setAttribute('data-alert-animation', 'true');
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Handle Enter key for manual input
    const manualInput = document.getElementById('manualInput');
    if (manualInput) {
        manualInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processManualInput();
            }
        });
    }
    
    // Handle kondisi buku change for denda calculation
    document.addEventListener('change', function(e) {
        if (e.target.name === 'kondisiBukuSaatDikembalikan') {
            const kondisi = e.target.value;
            calculateDenda(kondisi);
        }
    });
    
    // Handle form submissions
    const peminjamanForm = document.querySelector('#peminjamanSection form');
    const pengembalianForm = document.querySelector('#pengembalianSection form');
    
    if (peminjamanForm) {
        peminjamanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitTransaksi(this, 'peminjaman');
        });
    }
    
    if (pengembalianForm) {
        pengembalianForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitTransaksi(this, 'pengembalian');
        });
    }
    
    // Initialize form check
    checkFormCompletion();
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showAlert('error', 'Terjadi kesalahan pada aplikasi. Silakan refresh halaman.');
});

// Initialize scanner when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check if QuaggaJS is loaded
    if (typeof Quagga === 'undefined') {
        console.warn('QuaggaJS not loaded. Scanner functionality will be disabled.');
        const scannerSection = document.getElementById('scannerSection');
        if (scannerSection) {
            scannerSection.innerHTML = '<div class="alert alert-warning">Scanner tidak tersedia. Gunakan input manual.</div>';
        }
    }
});