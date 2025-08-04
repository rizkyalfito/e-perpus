<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Form Transaksi Pinjam Buku
            <small>
                <script type='text/javascript'>
                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var thisDay = date.getDay(),
                        thisDay = myDays[thisDay];
                    var yy = date.getYear();
                    var year = (yy < 1000) ? yy + 1900 : yy;
                    document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                </script>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Form Transaksi Pinjam Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Form Transaksi Pinjam Buku</h3>
                    </div>
                    
                    <!-- Form Peminjaman -->
                    <form id="formPeminjaman" action="pages/function/Peminjaman.php?aksi=tambah" method="POST">
                        <div class="box-body">
                            <div class="row">
                                <!-- Data Anggota -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kode Anggota</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kodeAnggota" placeholder="Masukkan kode anggota atau scan barcode">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success" onclick="scanAnggotaBarcode()">
                                                    <i class="fa fa-qrcode"></i> Scan
                                                </button>
                                                <button type="button" class="btn btn-info" onclick="cariAnggotaManual()">
                                                    <i class="fa fa-search"></i> Cari
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Anggota</label>
                                        <input type="text" class="form-control" name="namaAnggota" id="namaAnggota" readonly>
                                        <input type="hidden" id="idAnggota">
                                    </div>
                                </div>
                            </div>

                            <!-- Info Anggota -->
                            <div class="row" id="infoAnggotaRow" style="display: none;">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h5><strong>Informasi Anggota:</strong></h5>
                                        <div class="row">
                                            <div class="col-md-3"><strong>NIS:</strong> <span id="displayNIS"></span></div>
                                            <div class="col-md-3"><strong>Kelas:</strong> <span id="displayKelas"></span></div>
                                            <div class="col-md-3"><strong>Buku Dipinjam:</strong> <span id="displayJumlahPinjam">0</span>/3</div>
                                            <div class="col-md-3"><strong>Alamat:</strong> <span id="displayAlamat"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Data Buku -->
                                <div class="col-md-8">
                                    <div class="form-group">
<label>Barcode Buku</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="isbnBuku" name="isbn" placeholder="Masukkan ISBN atau Barcode Unit buku, atau scan barcode">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary" onclick="scanBukuBarcode()">
                                                    <i class="fa fa-qrcode"></i> Scan
                                                </button>
                                                <button type="button" class="btn btn-info" onclick="cariBukuManual()">
                                                    <i class="fa fa-search"></i> Cari
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Judul Buku</label>
                                        <input type="text" class="form-control" name="judulBuku" id="judulBuku" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Buku -->
                            <div class="row" id="infoBukuRow" style="display: none;">
                                <div class="col-md-12">
                                    <div class="alert alert-success">
                                        <h5><strong>Informasi Buku:</strong></h5>
                                        <div class="row">
                                            <div class="col-md-3"><strong>Kategori:</strong> <span id="displayKategori"></span></div>
                                            <div class="col-md-3"><strong>Pengarang:</strong> <span id="displayPengarang"></span></div>
                                            <div class="col-md-3"><strong>Penerbit:</strong> <span id="displayPenerbit"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Pinjam</label>
                                        <div class="input-group date">
                                            <input type="date" class="form-control" name="tanggalPinjam" id="tanggalPinjam" value="<?= date('Y-m-d') ?>" required>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Kembali</label>
                                        <div class="input-group date">
                                            <input type="date" class="form-control" name="tanggalKembali" id="tanggalKembali" required>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kondisi Buku</label>
                                        <select class="form-control" name="kondisiBuku" id="kondisiBuku" required>
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block" id="btnSubmit" disabled>
                                        <i class="fa fa-save"></i> Simpan Peminjaman
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning btn-block" onclick="resetForm()">
                                        <i class="fa fa-refresh"></i> Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Scan Anggota -->
<div class="modal fade" id="modalScanAnggota">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-qrcode"></i> Scan Barcode Anggota</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="reader-anggota" style="width: 100%; height: 300px; border: 2px dashed #ccc;"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> Petunjuk:</h5>
                            <p>1. Posisikan barcode anggota di depan kamera</p>
                            <p>2. Pastikan barcode terlihat jelas dan tidak terpotong</p>
                            <p>3. Tunggu hingga barcode terbaca otomatis</p>
                        </div>
                        
                        <div id="scanStatusAnggota" class="alert" style="display: none;">
                            <span id="scanMessageAnggota"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scan Buku -->
<div class="modal fade" id="modalScanBuku">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-qrcode"></i> Scan Barcode Buku</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="reader-buku" style="width: 100%; height: 300px; border: 2px dashed #ccc;"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> Petunjuk:</h5>
                            <p>1. Posisikan barcode ISBN buku di depan kamera</p>
                            <p>2. Pastikan barcode terlihat jelas dan tidak terpotong</p>
                            <p>3. Tunggu hingga barcode terbaca otomatis</p>
                        </div>
                        
                        <div id="scanStatusBuku" class="alert" style="display: none;">
                            <span id="scanMessageBuku"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<script>
let html5QrCodeAnggota;
let html5QrCodeBuku;
let selectedAnggota = null;
let selectedBuku = null;

// Enhanced notification system
function showNotification(type, title, message, options = {}) {
    const config = {
        title: title,
        text: message,
        timer: options.timer || 3000,
        showConfirmButton: options.showConfirmButton !== false,
        ...options
    };

    switch(type) {
        case 'success':
            config.icon = 'success';
            break;
        case 'error':
            config.icon = 'error';
            break;
        case 'warning':
            config.icon = 'warning';
            break;
        case 'info':
            config.icon = 'info';
            break;
        case 'scan_success':
            config.icon = 'success';
            config.timer = 2000;
            break;
        case 'scan_error':
            config.icon = 'error';
            config.timer = 3000;
            break;
        case 'form_success':
            config.icon = 'success';
            config.timer = 4000;
            config.showConfirmButton = true;
            break;
        case 'form_error':
            config.icon = 'error';
            config.timer = 5000;
            config.showConfirmButton = true;
            break;
        default:
            config.icon = 'info';
    }

    swal(config);
}

// Set tanggal kembali otomatis (7 hari dari tanggal pinjam)
document.getElementById('tanggalPinjam').addEventListener('change', function() {
    const tanggalPinjam = new Date(this.value);
    const tanggalKembali = new Date(tanggalPinjam);
    tanggalKembali.setDate(tanggalKembali.getDate() + 7);
    
    const year = tanggalKembali.getFullYear();
    const month = String(tanggalKembali.getMonth() + 1).padStart(2, '0');
    const day = String(tanggalKembali.getDate()).padStart(2, '0');
    
    document.getElementById('tanggalKembali').value = `${year}-${month}-${day}`;
});

// Trigger tanggal kembali saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tanggalPinjam').dispatchEvent(new Event('change'));
});

// Format ISBN dengan tanda hubung
function formatISBN(isbn) {
    // Remove all non-numeric characters
    const numbers = isbn.replace(/\D/g, '');
    
    // Format as ISBN-13 (978-x-xxx-xxxxx-x)
    if (numbers.length === 13) {
        return numbers.replace(/(\d{3})(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4-$5');
    }
    // Format as ISBN-10 (x-xxx-xxxxx-x)
    else if (numbers.length === 10) {
        return numbers.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    }
    
    return isbn; // Return original if not standard length
}

// Fungsi Scan Anggota
function scanAnggotaBarcode() {
    $('#modalScanAnggota').modal('show');
    showNotification('info', 'Memulai Scan', 'Menyiapkan kamera untuk scan barcode anggota...', { timer: 2000 });
    
    setTimeout(() => {
        html5QrCodeAnggota = new Html5Qrcode("reader-anggota");
        
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                
                html5QrCodeAnggota.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        showScanStatus('anggota', 'success', 'Barcode berhasil terbaca, mencari data anggota...');
                        showNotification('scan_success', 'Scan Berhasil', 'Barcode anggota berhasil terbaca, mencari data...');
                        cariAnggota(decodedText);
                        html5QrCodeAnggota.stop();
                        setTimeout(() => {
                            $('#modalScanAnggota').modal('hide');
                        }, 1500);
                    },
                    (errorMessage) => {
                        // Silent error, just keep scanning
                    }
                ).catch(err => {
                    showScanStatus('anggota', 'error', 'Tidak dapat mengakses kamera: ' + err);
                    showNotification('scan_error', 'Error Kamera', 'Tidak dapat mengakses kamera: ' + err);
                });
            } else {
                showScanStatus('anggota', 'error', 'Kamera tidak ditemukan');
                showNotification('scan_error', 'Kamera Tidak Tersedia', 'Tidak ada kamera yang tersedia untuk scanning');
            }
        }).catch(err => {
            showScanStatus('anggota', 'error', 'Error mengakses kamera: ' + err);
            showNotification('scan_error', 'Error Kamera', 'Gagal mengakses kamera: ' + err);
        });
    }, 500);
}

// Fungsi Scan Buku
function scanBukuBarcode() {
    $('#modalScanBuku').modal('show');
    showNotification('info', 'Memulai Scan', 'Menyiapkan kamera untuk scan barcode buku...', { timer: 2000 });
    
    setTimeout(() => {
        html5QrCodeBuku = new Html5Qrcode("reader-buku");
        
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                
                html5QrCodeBuku.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        showScanStatus('buku', 'success', 'Barcode berhasil terbaca, mencari data buku...');
                        showNotification('scan_success', 'Scan Berhasil', 'Barcode buku berhasil terbaca, mencari data...');
                        // Send raw decodedText without formatting
                        $('#isbnBuku').val(decodedText);
                        cariBuku(decodedText);
                        html5QrCodeBuku.stop();
                        setTimeout(() => {
                            $('#modalScanBuku').modal('hide');
                        }, 1500);
                    },
                    (errorMessage) => {
                        // Silent error, just keep scanning
                    }
                ).catch(err => {
                    showScanStatus('buku', 'error', 'Tidak dapat mengakses kamera: ' + err);
                    showNotification('scan_error', 'Error Kamera', 'Tidak dapat mengakses kamera: ' + err);
                });
            } else {
                showScanStatus('buku', 'error', 'Kamera tidak ditemukan');
                showNotification('scan_error', 'Kamera Tidak Tersedia', 'Tidak ada kamera yang tersedia untuk scanning');
            }
        }).catch(err => {
            showScanStatus('buku', 'error', 'Error mengakses kamera: ' + err);
            showNotification('scan_error', 'Error Kamera', 'Gagal mengakses kamera: ' + err);
        });
    }, 500);
}

// Show scan status
function showScanStatus(type, status, message) {
    const statusDiv = $('#scanStatus' + (type === 'anggota' ? 'Anggota' : 'Buku'));
    const messageSpan = $('#scanMessage' + (type === 'anggota' ? 'Anggota' : 'Buku'));
    
    statusDiv.removeClass('alert-success alert-danger alert-warning');
    
    if (status === 'success') {
        statusDiv.addClass('alert-success');
    } else if (status === 'error') {
        statusDiv.addClass('alert-danger');
    } else {
        statusDiv.addClass('alert-warning');
    }
    
    messageSpan.text(message);
    statusDiv.show();
}

// Cari Anggota berdasarkan kode (manual atau scan)
function cariAnggotaManual() {
    const kodeAnggota = $('#kodeAnggota').val().trim();
    if (kodeAnggota === '') {
        showNotification('warning', 'Input Kosong', 'Mohon masukkan kode anggota terlebih dahulu');
        return;
    }
    showNotification('info', 'Mencari Data', 'Sedang mencari data anggota...', { timer: 2000 });
    cariAnggota(kodeAnggota);
}

function cariAnggota(kodeAnggota) {
    // Set kode ke input field
    $('#kodeAnggota').val(kodeAnggota);
    
    $.ajax({
        url: 'pages/function/Peminjaman.php',
        type: 'POST',
        data: {
            aksi: 'cari_anggota',
            kode_anggota: kodeAnggota
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                selectedAnggota = response.data;
                
                // Fill form fields
                $('#namaAnggota').val(response.data.fullname);
                $('#idAnggota').val(response.data.id_user);
                
                // Show info anggota
                $('#displayNIS').text(response.data.nis);
                $('#displayKelas').text(response.data.kelas);
                $('#displayAlamat').text(response.data.alamat);
                $('#displayJumlahPinjam').text(response.jumlah_pinjam);
                $('#infoAnggotaRow').show();
                
                checkFormValidation();
                
                showNotification('success', 'Data Ditemukan', `Anggota ${response.data.fullname} berhasil ditemukan`);
                
            } else {
                // Clear anggota data
                clearAnggotaData();
                
                showNotification('error', 'Data Tidak Ditemukan', response.message);
            }
        },
        error: function(xhr, status, error) {
            showNotification('error', 'Error Koneksi', 'Terjadi kesalahan saat mencari data anggota. Silakan coba lagi.');
        }
    });
}

// Cari Buku berdasarkan ISBN (manual atau scan)
function cariBukuManual() {
    let isbn = $('#isbnBuku').val().trim();
    if (isbn === '') {
        showNotification('warning', 'Input Kosong', 'Mohon masukkan ISBN buku terlebih dahulu');
        return;
    }
    
    // Format ISBN if it's just numbers
    if (/^\d+$/.test(isbn)) {
        isbn = formatISBN(isbn);
        $('#isbnBuku').val(isbn);
    }
    
    showNotification('info', 'Mencari Data', 'Sedang mencari data buku...', { timer: 2000 });
    cariBuku(isbn);
}

function cariBuku(isbn) {
    $.ajax({
        url: 'pages/function/Peminjaman.php',
        type: 'POST',
        data: {
            aksi: 'cari_buku',
            isbn: isbn
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                selectedBuku = response.data;
                
                // Fill form fields
                $('#judulBuku').val(response.data.judul_buku);
                
                // Show info buku
                $('#displayKategori').text(response.data.kategori_buku);
                $('#displayPengarang').text(response.data.pengarang);
                $('#displayPenerbit').text(response.data.penerbit_buku);
                $('#displayStok').text(response.data.j_buku_baik);
                $('#infoBukuRow').show();
                
                checkFormValidation();
                
                showNotification('success', 'Data Ditemukan', `Buku "${response.data.judul_buku}" berhasil ditemukan`);
                
            } else {
                // Clear buku data
                clearBukuData();
                
                showNotification('error', 'Data Tidak Ditemukan', response.message);
            }
        },
        error: function(xhr, status, error) {
            showNotification('error', 'Error Koneksi', 'Terjadi kesalahan saat mencari data buku. Silakan coba lagi.');
        }
    });
}

// Clear data functions
function clearAnggotaData() {
    selectedAnggota = null;
    $('#namaAnggota').val('');
    $('#idAnggota').val('');
    $('#infoAnggotaRow').hide();
    checkFormValidation();
}

function clearBukuData() {
    selectedBuku = null;
    $('#judulBuku').val('');
    $('#infoBukuRow').hide();
    checkFormValidation();
}

// Check form validation
function checkFormValidation() {
    const namaAnggota = $('#namaAnggota').val();
    const judulBuku = $('#judulBuku').val();
    const tanggalPinjam = $('#tanggalPinjam').val();
    const tanggalKembali = $('#tanggalKembali').val();
    const kondisiBuku = $('#kondisiBuku').val();
    
    if (namaAnggota && judulBuku && tanggalPinjam && tanggalKembali && kondisiBuku) {
        $('#btnSubmit').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
    } else {
        $('#btnSubmit').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
    }
}

// Enhanced form submission with AJAX
$('#formPeminjaman').on('submit', function(e) {
    e.preventDefault();
    
    if (!selectedAnggota || !selectedBuku) {
        showNotification('error', 'Data Tidak Lengkap', 'Pastikan data anggota dan buku sudah dipilih dengan benar');
        return false;
    }
    
    // Show loading notification
    showNotification('info', 'Memproses...', 'Sedang menyimpan data peminjaman...', { 
        timer: 0, 
        showConfirmButton: false 
    });
    
    // Disable submit button
    $('#btnSubmit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            // Re-enable submit button
            $('#btnSubmit').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Peminjaman');
            
            if (response.status === 'success') {
                showNotification('form_success', 'Peminjaman Berhasil!', 
                    `Peminjaman buku "${response.data.judul_buku}" untuk anggota ${response.data.nama_anggota} berhasil disimpan.`);
                
                // Reset form after success
                setTimeout(() => {
                    resetForm();
                }, 2000);
            } else {
                showNotification('form_error', 'Peminjaman Gagal', response.message);
            }
        },
        error: function(xhr, status, error) {
            // Re-enable submit button
            $('#btnSubmit').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Peminjaman');
            
            let errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showNotification('form_error', 'Error Server', errorMessage);
        }
    });
});

// Reset Form
function resetForm() {
    document.getElementById('formPeminjaman').reset();
    clearAnggotaData();
    clearBukuData();
    $('#kodeAnggota').val('');
    $('#isbnBuku').val('');
    $('#btnSubmit').prop('disabled', true);
    
    // Reset tanggal
    document.getElementById('tanggalPinjam').dispatchEvent(new Event('change'));
    
    showNotification('success', 'Form Direset', 'Form berhasil direset dan siap untuk transaksi baru', { timer: 2000 });
}

// Stop cameras when modals are closed
$('#modalScanAnggota').on('hidden.bs.modal', function () {
    if (html5QrCodeAnggota) {
        html5QrCodeAnggota.stop().then(() => {
            html5QrCodeAnggota.clear();
        }).catch(err => {
            console.error('Error stopping anggota camera:', err);
        });
    }
    $('#scanStatusAnggota').hide();
});

$('#modalScanBuku').on('hidden.bs.modal', function () {
    if (html5QrCodeBuku) {
        html5QrCodeBuku.stop().then(() => {
            html5QrCodeBuku.clear();
        }).catch(err => {
            console.error('Error stopping buku camera:', err);
        });
    }
    $('#scanStatusBuku').hide();
});

// Event listeners for manual input
$('#kodeAnggota').on('keypress', function(e) {
    if (e.which === 13) { // Enter key
        e.preventDefault();
        cariAnggotaManual();
    }
});

$('#isbnBuku').on('keypress', function(e) {
    if (e.which === 13) { // Enter key
        e.preventDefault();
        cariBukuManual();
    }
});

// Auto format ISBN while typing
$('#isbnBuku').on('input', function() {
    let value = $(this).val();
    // Only format if it looks like a number string
    if (/^\d+$/.test(value) && (value.length === 10 || value.length === 13)) {
        $(this).val(formatISBN(value));
    }
});

// Form validation on field change
$('#namaAnggota, #judulBuku, #tanggalPinjam, #tanggalKembali, #kondisiBuku').on('change input', function() {
    checkFormValidation();
});

// Show initial welcome message
$(document).ready(function() {
    showNotification('info', 'Sistem Siap', 'Form peminjaman buku siap digunakan. Scan atau input kode anggota untuk memulai.', {
        timer: 3000
    });
});
</script>