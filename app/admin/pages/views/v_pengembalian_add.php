<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Form Pengembalian Buku
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
            <li class="active">Form Pengembalian Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Form Pengembalian Buku</h3>
                    </div>
                    
                    <!-- Search Form -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cari Peminjaman</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchPeminjaman" placeholder="Masukkan nama anggota, judul buku, atau scan barcode anggota">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-success" onclick="scanPeminjamanBarcode()">
                                                <i class="fa fa-qrcode"></i> Scan Anggota
                                            </button>
                                            <button type="button" class="btn btn-info" onclick="cariPeminjaman()">
                                                <i class="fa fa-search"></i> Cari
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-block" onclick="scanBukuReturn()">
                                        <i class="fa fa-qrcode"></i> Scan Barcode Buku
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-warning btn-block" onclick="resetPencarian()">
                                        <i class="fa fa-refresh"></i> Reset Pencarian
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Daftar Peminjaman -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablePeminjaman">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Nama Anggota</th>
                                        <th width="20%">Judul Buku</th>
                                        <th width="12%">Tgl Pinjam</th>
                                        <th width="12%">Tgl Kembali</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Denda</th>
                                        <th width="16%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="daftarPeminjaman">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <i class="fa fa-search"></i> Silakan cari peminjaman yang ingin dikembalikan
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Scan Peminjaman -->
<div class="modal fade" id="modalScanPeminjaman">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-qrcode"></i> Scan Barcode Anggota</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="reader-peminjaman" style="width: 100%; height: 300px; border: 2px dashed #ccc;"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> Petunjuk:</h5>
                            <p>1. Posisikan barcode anggota di depan kamera</p>
                            <p>2. Sistem akan mencari semua peminjaman aktif dari anggota tersebut</p>
                            <p>3. Tunggu hingga barcode terbaca otomatis</p>
                        </div>
                        
                        <div id="scanStatusPeminjaman" class="alert" style="display: none;">
                            <span id="scanMessagePeminjaman"></span>
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

<!-- Modal Scan Buku untuk Pengembalian -->
<div class="modal fade" id="modalScanBukuReturn">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-qrcode"></i> Scan Barcode Buku Unit</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="reader-buku-return" style="width: 100%; height: 300px; border: 2px dashed #ccc;"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> Petunjuk:</h5>
                            <p>1. Posisikan barcode unit buku di depan kamera</p>
                            <p>2. Sistem akan mencari peminjaman aktif untuk buku tersebut</p>
                            <p>3. Tunggu hingga barcode terbaca otomatis</p>
                            <p><strong>Format barcode:</strong> BK00001-001, ISBN, atau format lainnya</p>
                        </div>
                        
                        <div id="scanStatusBukuReturn" class="alert" style="display: none;">
                            <span id="scanMessageBukuReturn"></span>
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

<!-- Modal Konfirmasi Pengembalian -->
<div class="modal fade" id="modalKonfirmasiKembali">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-undo"></i> Konfirmasi Pengembalian</h4>
            </div>
            <form id="formPengembalian" action="pages/function/Peminjaman.php?aksi=kembali" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="idPeminjamanKembali" name="idPeminjaman">
                    
                    <div class="alert alert-info">
                        <h5><strong>Detail Peminjaman:</strong></h5>
                        <p><strong>Anggota:</strong> <span id="detailNamaAnggota"></span></p>
                        <p><strong>Buku:</strong> <span id="detailJudulBuku"></span></p>
                        <p><strong>Tanggal Pinjam:</strong> <span id="detailTanggalPinjam"></span></p>
                        <p><strong>Tanggal Kembali:</strong> <span id="detailTanggalKembali"></span></p>
                        <p><strong>Barcode Unit:</strong> <span id="detailBarcodeBuku" class="label label-primary"></span></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Kondisi Buku Saat Dikembalikan</label>
                        <select class="form-control" name="kondisiBuku" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Denda (Otomatis Terhitung)</label>
                        <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="number" class="form-control" name="denda" id="dendaInput" readonly>
                        </div>
                        <small class="text-muted">Denda Rp 1.000 per hari keterlambatan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Perpanjangan -->
<div class="modal fade" id="modalPerpanjangan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Perpanjangan Peminjaman</h4>
            </div>
            <form id="formPerpanjangan" action="pages/function/Peminjaman.php?aksi=perpanjang" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="idPeminjamanPerpanjang" name="idPeminjaman">
                    
                    <div class="alert alert-info">
                        <h5><strong>Detail Peminjaman:</strong></h5>
                        <p><strong>Anggota:</strong> <span id="detailNamaAnggotaPerpanjang"></span></p>
                        <p><strong>Buku:</strong> <span id="detailJudulBukuPerpanjang"></span></p>
                        <p><strong>Tanggal Kembali Saat Ini:</strong> <span id="detailTanggalKembaliPerpanjang"></span></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Kembali Baru</label>
                        <input type="date" class="form-control" name="tanggalKembaliBaru" id="tanggalKembaliBaru" required>
                        <small class="text-muted">Maksimal perpanjangan 7 hari dari tanggal kembali saat ini</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-calendar"></i> Perpanjang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<script>
let html5QrCodePeminjaman;
let html5QrCodeBukuReturn;
let allPeminjaman = [];

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
        default:
            config.icon = 'info';
    }

    swal(config);
}

// Load data peminjaman saat halaman dimuat
$(document).ready(function() {
    loadPeminjaman();
    showNotification('info', 'Sistem Siap', 'Form pengembalian buku siap digunakan. Scan barcode anggota/buku atau cari manual.', { timer: 3000 });
});

// Load semua data peminjaman
function loadPeminjaman(filter = '') {
    $.ajax({
        url: 'pages/function/Peminjaman.php?get_data=peminjaman',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            allPeminjaman = data;
            displayPeminjaman(data, filter);
        },
        error: function() {
            $('#daftarPeminjaman').html(`
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        <i class="fa fa-exclamation-triangle"></i> Error loading data
                    </td>
                </tr>
            `);
        }
    });
}

function displayPeminjaman(data, filter = '') {
    let html = '';
    let filteredData = data;
    
    // Filter data jika ada pencarian
    if (filter) {
        filteredData = data.filter(item => {
            const searchTerm = filter.toLowerCase();
            return (
                item.nama_anggota.toLowerCase().includes(searchTerm) ||
                item.judul_buku.toLowerCase().includes(searchTerm) ||
                // Tambahkan pencarian berdasarkan kode anggota
                (item.kode_anggota && item.kode_anggota.toLowerCase().includes(searchTerm)) ||
                // Tambahkan pencarian berdasarkan barcode buku
                (item.barcode_buku && item.barcode_buku.toLowerCase().includes(searchTerm))
            );
        });
    }
    
    // Filter hanya yang belum dikembalikan
    filteredData = filteredData.filter(item => item.kondisi_buku_saat_dikembalikan === '');
    
    if (filteredData.length === 0) {
        html = `
            <tr>
                <td colspan="8" class="text-center">
                    <i class="fa fa-info-circle"></i> ${filter ? 'Tidak ada data yang sesuai dengan pencarian' : 'Tidak ada peminjaman aktif'}
                </td>
            </tr>
        `;
    } else {
        filteredData.forEach((item, index) => {
            const isLate = new Date() > new Date(item.tanggal_pengembalian);
            const statusClass = isLate ? 'label-danger' : 'label-success';
            const statusText = isLate ? 'Terlambat' : 'Aktif';
            
            html += `
                <tr ${isLate ? 'class="danger"' : ''}>
                    <td>${index + 1}</td>
                    <td>${item.nama_anggota}</td>
                    <td>${item.judul_buku}${item.barcode_buku ? '<br><small class="text-muted">Unit: ' + item.barcode_buku + '</small>' : ''}</td>
                    <td>${formatDate(item.tanggal_peminjaman)}</td>
                    <td>${formatDate(item.tanggal_pengembalian)}</td>
                    <td><span class="label ${statusClass}">${statusText}</span></td>
                    <td>Rp ${number_format(item.denda_calculated)}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" onclick="konfirmasiPengembalian(${item.id_peminjaman})" title="Kembalikan Buku">
                            <i class="fa fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="perpanjangPeminjaman(${item.id_peminjaman})" title="Perpanjang">
                            <i class="fa fa-calendar-plus-o"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#daftarPeminjaman').html(html);
}

// Format tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Format number
function number_format(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Hitung denda
function hitungDenda(tanggalKembali) {
    const today = new Date();
    const dueDate = new Date(tanggalKembali);
    
    if (today > dueDate) {
        const diffTime = Math.abs(today - dueDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays * 1000; // Rp 1.000 per hari
    }
    
    return 0;
}

// Scan barcode untuk pencarian peminjaman berdasarkan anggota
function scanPeminjamanBarcode() {
    $('#modalScanPeminjaman').modal('show');
    showNotification('info', 'Memulai Scan', 'Menyiapkan kamera untuk scan barcode anggota...', { timer: 2000 });
    
    setTimeout(() => {
        html5QrCodePeminjaman = new Html5Qrcode("reader-peminjaman");
        
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                
                html5QrCodePeminjaman.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        showScanStatusPeminjaman('success', 'Barcode berhasil terbaca, mencari peminjaman...');
                        showNotification('scan_success', 'Scan Berhasil', 'Barcode anggota berhasil terbaca, mencari peminjaman...');
                        
                        // Cari anggota berdasarkan kode yang discan
                        cariPeminjamanByKodeAnggota(decodedText);
                        
                        html5QrCodePeminjaman.stop();
                        setTimeout(() => {
                            $('#modalScanPeminjaman').modal('hide');
                        }, 1500);
                    },
                    (errorMessage) => {
                        // Silent error, just keep scanning
                    }
                ).catch(err => {
                    showScanStatusPeminjaman('error', 'Tidak dapat mengakses kamera: ' + err);
                    showNotification('scan_error', 'Error Kamera', 'Tidak dapat mengakses kamera: ' + err);
                });
            } else {
                showScanStatusPeminjaman('error', 'Kamera tidak ditemukan');
                showNotification('scan_error', 'Kamera Tidak Tersedia', 'Tidak ada kamera yang tersedia untuk scanning');
            }
        }).catch(err => {
            showScanStatusPeminjaman('error', 'Error mengakses kamera: ' + err);
            showNotification('scan_error', 'Error Kamera', 'Gagal mengakses kamera: ' + err);
        });
    }, 500);
}

// NEW: Scan barcode buku untuk pengembalian langsung
function scanBukuReturn() {
    $('#modalScanBukuReturn').modal('show');
    showNotification('info', 'Memulai Scan', 'Menyiapkan kamera untuk scan barcode buku unit...', { timer: 2000 });
    
    setTimeout(() => {
        html5QrCodeBukuReturn = new Html5Qrcode("reader-buku-return");
        
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                
                html5QrCodeBukuReturn.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        showScanStatusBukuReturn('success', 'Barcode berhasil terbaca, mencari peminjaman aktif...');
                        showNotification('scan_success', 'Scan Berhasil', 'Barcode buku berhasil terbaca, mencari peminjaman aktif...');
                        
                        // Trim scanned barcode before processing
                        let scannedCode = decodedText.trim();
                        
                        // Cari peminjaman berdasarkan barcode buku yang discan
                        cariPeminjamanByBarcodeBuku(scannedCode);
                        
                        html5QrCodeBukuReturn.stop();
                        setTimeout(() => {
                            $('#modalScanBukuReturn').modal('hide');
                        }, 1500);
                    },
                    (errorMessage) => {
                        // Silent error, just keep scanning
                    }
                ).catch(err => {
                    showScanStatusBukuReturn('error', 'Tidak dapat mengakses kamera: ' + err);
                    showNotification('scan_error', 'Error Kamera', 'Tidak dapat mengakses kamera: ' + err);
                });
            } else {
                showScanStatusBukuReturn('error', 'Kamera tidak ditemukan');
                showNotification('scan_error', 'Kamera Tidak Tersedia', 'Tidak ada kamera yang tersedia untuk scanning');
            }
        }).catch(err => {
            showScanStatusBukuReturn('error', 'Error mengakses kamera: ' + err);
            showNotification('scan_error', 'Error Kamera', 'Gagal mengakses kamera: ' + err);
        });
    }, 500);
}

// Show scan status untuk peminjaman
function showScanStatusPeminjaman(status, message) {
    const statusDiv = $('#scanStatusPeminjaman');
    const messageSpan = $('#scanMessagePeminjaman');
    
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

// Show scan status untuk buku return
function showScanStatusBukuReturn(status, message) {
    const statusDiv = $('#scanStatusBukuReturn');
    const messageSpan = $('#scanMessageBukuReturn');
    
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

// Cari peminjaman berdasarkan kode anggota
function cariPeminjamanByKodeAnggota(kodeAnggota) {
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
                const namaAnggota = response.data.fullname;
                $('#searchPeminjaman').val(namaAnggota);
                displayPeminjaman(allPeminjaman, namaAnggota);
                
                showNotification('success', 'Anggota Ditemukan', `Menampilkan peminjaman untuk: ${namaAnggota}`);
            } else {
                showNotification('error', 'Anggota Tidak Ditemukan', response.message);
            }
        },
        error: function() {
            showNotification('error', 'Error Koneksi', 'Terjadi kesalahan saat mencari anggota');
        }
    });
}

// NEW: Cari peminjaman berdasarkan barcode buku
function cariPeminjamanByBarcodeBuku(barcodeBuku) {
    console.log('Mencari peminjaman untuk barcode:', barcodeBuku);
    
    // Tampilkan loading notification
    showNotification('info', 'Mencari...', 'Sedang mencari peminjaman berdasarkan barcode buku...', { 
        timer: 0, 
        showConfirmButton: false 
    });
    
    // Pertama, cari di data lokal yang sudah dimuat
    const localMatch = allPeminjaman.filter(item => {
        const isActive = item.kondisi_buku_saat_dikembalikan === '';
        const barcodeMatch = item.barcode_buku === barcodeBuku;
        const isbnMatch = item.isbn === barcodeBuku;
        const titleMatch = item.judul_buku && item.judul_buku.toLowerCase().includes(barcodeBuku.toLowerCase());
        
        return isActive && (barcodeMatch || isbnMatch || titleMatch);
    });
    
    console.log('Local matches found:', localMatch.length);
    
    if (localMatch.length > 0) {
        handleFoundPeminjaman(localMatch, barcodeBuku);
        return;
    }
    
    // Jika tidak ditemukan di data lokal, cari di server
    $.ajax({
        url: 'pages/function/Peminjaman.php',
        type: 'POST',
        data: {
            aksi: 'cari_peminjaman_by_barcode',
            barcode_buku: barcodeBuku
        },
        dataType: 'json',
        success: function(response) {
            console.log('Server response:', response);
            
            if (response.status === 'success' && response.data.length > 0) {
                handleFoundPeminjaman(response.data, barcodeBuku);
                
                // Update data lokal
                allPeminjaman = response.data.concat(
                    allPeminjaman.filter(item => 
                        !response.data.some(newItem => newItem.id_peminjaman === item.id_peminjaman)
                    )
                );
            } else {
                handleNotFoundPeminjaman(barcodeBuku);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error searching:', error);
            showNotification('error', 'Error Koneksi', 'Terjadi kesalahan saat mencari peminjaman berdasarkan barcode buku');
            
            // Fallback: coba pencarian alternatif
            fallbackSearch(barcodeBuku);
        }
    });
}

// Fungsi untuk menangani peminjaman yang ditemukan
function handleFoundPeminjaman(peminjaman, barcodeBuku) {
    $('#searchPeminjaman').val(barcodeBuku);
    displayPeminjaman(allPeminjaman, barcodeBuku);
    
    const activeLoans = peminjaman.filter(item => item.kondisi_buku_saat_dikembalikan === '');
    
    if (activeLoans.length === 1) {
        // Langsung buka modal konfirmasi jika hanya ada 1 hasil
        setTimeout(() => {
            konfirmasiPengembalian(activeLoans[0].id_peminjaman);
            showNotification('success', 'Buku Ditemukan', 
                `Membuka form pengembalian untuk "${activeLoans[0].judul_buku}" - ${activeLoans[0].nama_anggota}`);
        }, 1000);
    } else {
        showNotification('success', 'Buku Ditemukan', 
            `Ditemukan ${activeLoans.length} peminjaman aktif untuk barcode tersebut`);
    }
}

// Fungsi untuk menangani peminjaman yang tidak ditemukan
function handleNotFoundPeminjaman(barcodeBuku) {
    // Coba debug untuk melihat semua peminjaman aktif
    debugPeminjamanAktif();
    
    showNotification('error', 'Buku Tidak Ditemukan', 
        `Tidak ada peminjaman aktif untuk barcode "${barcodeBuku}". ` +
        'Pastikan:\n• Buku sedang dipinjam\n• Barcode sesuai dengan data\n• Buku belum dikembalikan', 
        { timer: 5000 });
}

// Fungsi fallback untuk pencarian alternatif
function fallbackSearch(barcodeBuku) {
    console.log('Trying fallback search for:', barcodeBuku);
    
    // Cari dengan metode yang lebih fleksibel
    const flexibleMatches = allPeminjaman.filter(item => {
        if (item.kondisi_buku_saat_dikembalikan !== '') return false;
        
        const searchTerm = barcodeBuku.toLowerCase();
        const bookTitle = item.judul_buku ? item.judul_buku.toLowerCase() : '';
        const memberName = item.nama_anggota ? item.nama_anggota.toLowerCase() : '';
        const memberCode = item.kode_anggota ? item.kode_anggota.toLowerCase() : '';
        const storedBarcode = item.barcode_buku ? item.barcode_buku.toLowerCase() : '';
        
        return bookTitle.includes(searchTerm) || 
               memberName.includes(searchTerm) || 
               memberCode.includes(searchTerm) ||
               storedBarcode.includes(searchTerm);
    });
    
    if (flexibleMatches.length > 0) {
        console.log('Fallback matches found:', flexibleMatches.length);
        handleFoundPeminjaman(flexibleMatches, barcodeBuku);
    } else {
        handleNotFoundPeminjaman(barcodeBuku);
    }
}

// Fungsi debug untuk melihat semua peminjaman aktif
function debugPeminjamanAktif() {
    $.ajax({
        url: 'pages/function/Peminjaman.php',
        type: 'POST',
        data: {
            aksi: 'debug_peminjaman_aktif'
        },
        dataType: 'json',
        success: function(response) {
            console.log('Debug - Peminjaman aktif:', response);
            if (response.status === 'success') {
                console.log(`Total peminjaman aktif: ${response.count}`);
                response.data.forEach((item, index) => {
                    console.log(`${index + 1}. ${item.nama_anggota} - ${item.judul_buku} - Barcode: ${item.barcode_buku || 'N/A'}`);
                });
            }
        },
        error: function() {
            console.error('Gagal mendapatkan debug info');
        }
    });
}

// Fungsi untuk debug struktur tabel
function debugTableStructure() {
    $.ajax({
        url: 'pages/function/Peminjaman.php',
        type: 'POST',
        data: {
            aksi: 'debug_table_structure'
        },
        dataType: 'json',
        success: function(response) {
            console.log('Table structures:', response.data);
        },
        error: function() {
            console.error('Gagal mendapatkan struktur tabel');
        }
    });
}

// Fungsi yang diperbaiki untuk scan buku dengan error handling yang lebih baik
function scanBukuReturn() {
    $('#modalScanBukuReturn').modal('show');
    showNotification('info', 'Memulai Scan', 'Menyiapkan kamera untuk scan barcode buku unit...', { timer: 2000 });
    
    setTimeout(() => {
        html5QrCodeBukuReturn = new Html5Qrcode("reader-buku-return");
        
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                
                html5QrCodeBukuReturn.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        showScanStatusBukuReturn('success', 'Barcode berhasil terbaca, mencari peminjaman aktif...');
                        showNotification('scan_success', 'Scan Berhasil', 'Barcode buku berhasil terbaca, mencari peminjaman aktif...');
                        
                        // Trim dan bersihkan barcode yang discan
                        let scannedCode = decodedText.trim();
                        console.log('Scanned barcode:', scannedCode);
                        
                        // Cari peminjaman berdasarkan barcode buku yang discan
                        cariPeminjamanByBarcodeBuku(scannedCode);
                        
                        html5QrCodeBukuReturn.stop();
                        setTimeout(() => {
                            $('#modalScanBukuReturn').modal('hide');
                        }, 1500);
                    },
                    (errorMessage) => {
                        // Silent error, just keep scanning
                    }
                ).catch(err => {
                    console.error('Camera error:', err);
                    showScanStatusBukuReturn('error', 'Tidak dapat mengakses kamera: ' + err);
                    showNotification('scan_error', 'Error Kamera', 'Tidak dapat mengakses kamera: ' + err);
                });
            } else {
                showScanStatusBukuReturn('error', 'Kamera tidak ditemukan');
                showNotification('scan_error', 'Kamera Tidak Tersedia', 'Tidak ada kamera yang tersedia untuk scanning');
            }
        }).catch(err => {
            console.error('Camera access error:', err);
            showScanStatusBukuReturn('error', 'Error mengakses kamera: ' + err);
            showNotification('scan_error', 'Error Kamera', 'Gagal mengakses kamera: ' + err);
        });
    }, 500);
}

// Tambahkan tombol debug di console untuk testing
console.log('Debug functions available:');
console.log('- debugPeminjamanAktif(): Lihat semua peminjaman aktif');
console.log('- debugTableStructure(): Lihat struktur tabel');
console.log('- cariPeminjamanByBarcodeBuku("barcode"): Test pencarian barcode');

// Cari peminjaman manual
function cariPeminjaman() {
    const keyword = $('#searchPeminjaman').val().trim();
    if (keyword === '') {
        showNotification('warning', 'Input Kosong', 'Mohon masukkan kata kunci pencarian');
        return;
    }
    
    displayPeminjaman(allPeminjaman, keyword);
    showNotification('info', 'Pencarian Selesai', `Menampilkan hasil pencarian untuk: "${keyword}"`, { timer: 2000 });
}

// Reset pencarian
function resetPencarian() {
    $('#searchPeminjaman').val('');
    displayPeminjaman(allPeminjaman);
    showNotification('success', 'Pencarian Direset', 'Menampilkan semua peminjaman aktif', { timer: 2000 });
}

// Konfirmasi pengembalian
function konfirmasiPengembalian(idPeminjaman) {
    const peminjaman = allPeminjaman.find(item => item.id_peminjaman == idPeminjaman);
    
    if (!peminjaman) {
        showNotification('error', 'Error', 'Data peminjaman tidak ditemukan');
        return;
    }
    
    // Fill modal data
    $('#idPeminjamanKembali').val(idPeminjaman);
    $('#detailNamaAnggota').text(peminjaman.nama_anggota);
    $('#detailJudulBuku').text(peminjaman.judul_buku);
    $('#detailTanggalPinjam').text(formatDate(peminjaman.tanggal_peminjaman));
    $('#detailTanggalKembali').text(formatDate(peminjaman.tanggal_pengembalian));
    
    // Tampilkan barcode buku jika ada
    if (peminjaman.barcode_buku) {
        $('#detailBarcodeBuku').text(peminjaman.barcode_buku).show();
    } else {
        $('#detailBarcodeBuku').text('Tidak tersedia').show();
    }
    
    // Hitung dan set denda
    const denda = hitungDenda(peminjaman.tanggal_pengembalian);
    $('#dendaInput').val(denda);
    
    $('#modalKonfirmasiKembali').modal('show');
}

// Perpanjang peminjaman
function perpanjangPeminjaman(idPeminjaman) {
    const peminjaman = allPeminjaman.find(item => item.id_peminjaman == idPeminjaman);
    
    if (!peminjaman) {
        showNotification('error', 'Error', 'Data peminjaman tidak ditemukan');
        return;
    }
    
    // Fill modal data
    $('#idPeminjamanPerpanjang').val(idPeminjaman);
    $('#detailNamaAnggotaPerpanjang').text(peminjaman.nama_anggota);
    $('#detailJudulBukuPerpanjang').text(peminjaman.judul_buku);
    $('#detailTanggalKembaliPerpanjang').text(formatDate(peminjaman.tanggal_pengembalian));
    
    // Set tanggal kembali baru (maksimal 7 hari dari tanggal kembali saat ini)
    const currentDueDate = new Date(peminjaman.tanggal_pengembalian);
    const maxExtendDate = new Date(currentDueDate);
    maxExtendDate.setDate(maxExtendDate.getDate() + 7);
    
    // Set default tanggal kembali baru (7 hari dari sekarang atau dari tanggal kembali saat ini)
    const today = new Date();
    const defaultNewDate = new Date(Math.max(today.getTime(), currentDueDate.getTime()));
    defaultNewDate.setDate(defaultNewDate.getDate() + 7);
    
    const year = defaultNewDate.getFullYear();
    const month = String(defaultNewDate.getMonth() + 1).padStart(2, '0');
    const day = String(defaultNewDate.getDate()).padStart(2, '0');
    
    $('#tanggalKembaliBaru').val(`${year}-${month}-${day}`);
    $('#tanggalKembaliBaru').attr('min', formatDateForInput(today));
    $('#tanggalKembaliBaru').attr('max', formatDateForInput(maxExtendDate));
    
    $('#modalPerpanjangan').modal('show');
}

// Format date for input
function formatDateForInput(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Stop cameras when modals are closed
$('#modalScanPeminjaman').on('hidden.bs.modal', function () {
    if (html5QrCodePeminjaman) {
        html5QrCodePeminjaman.stop().then(() => {
            html5QrCodePeminjaman.clear();
        }).catch(err => {
            console.error('Error stopping anggota camera:', err);
        });
    }
    $('#scanStatusPeminjaman').hide();
});

$('#modalScanBukuReturn').on('hidden.bs.modal', function () {
    if (html5QrCodeBukuReturn) {
        html5QrCodeBukuReturn.stop().then(() => {
            html5QrCodeBukuReturn.clear();
        }).catch(err => {
            console.error('Error stopping buku return camera:', err);
        });
    }
    $('#scanStatusBukuReturn').hide();
});

// Event listeners
$('#searchPeminjaman').on('keypress', function(e) {
    if (e.which === 13) { // Enter key
        cariPeminjaman();
    }
});

// Form submissions with enhanced notifications
$('#formPengembalian').on('submit', function(e) {
    e.preventDefault();
    
    swal({
        title: 'Konfirmasi Pengembalian',
        text: 'Apakah Anda yakin ingin memproses pengembalian buku ini?',
        icon: 'question',
        buttons: {
            cancel: {
                text: 'Batal',
                visible: true,
                className: 'btn btn-secondary'
            },
            confirm: {
                text: 'Ya, Kembalikan',
                className: 'btn btn-primary'
            }
        }
    }).then((willReturn) => {
        if (willReturn) {
            // Show processing notification
            showNotification('info', 'Memproses...', 'Sedang memproses pengembalian buku...', { 
                timer: 0, 
                showConfirmButton: false 
            });
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showNotification('success', 'Pengembalian Berhasil!', 
                            `Buku "${response.data.judul_buku}" dari ${response.data.nama_anggota} berhasil dikembalikan.`);
                        
                        $('#modalKonfirmasiKembali').modal('hide');
                        loadPeminjaman($('#searchPeminjaman').val());
                    } else {
                        showNotification('error', 'Pengembalian Gagal', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Terjadi kesalahan saat memproses pengembalian';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification('error', 'Error Server', errorMessage);
                }
            });
        }
    });
});

$('#formPerpanjangan').on('submit', function(e) {
    e.preventDefault();
    
    swal({
        title: 'Konfirmasi Perpanjangan',
        text: 'Apakah Anda yakin ingin memperpanjang peminjaman ini?',
        icon: 'question',
        buttons: {
            cancel: {
                text: 'Batal',
                visible: true,
                className: 'btn btn-secondary'
            },
            confirm: {
                text: 'Ya, Perpanjang',
                className: 'btn btn-warning'
            }
        }
    }).then((willExtend) => {
        if (willExtend) {
            // Show processing notification
            showNotification('info', 'Memproses...', 'Sedang memproses perpanjangan peminjaman...', { 
                timer: 0, 
                showConfirmButton: false 
            });
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showNotification('success', 'Perpanjangan Berhasil!', 
                            'Peminjaman berhasil diperpanjang. Tanggal kembali telah diupdate.');
                        
                        $('#modalPerpanjangan').modal('hide');
                        loadPeminjaman($('#searchPeminjaman').val());
                    } else {
                        showNotification('error', 'Perpanjangan Gagal', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Terjadi kesalahan saat memproses perpanjangan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification('error', 'Error Server', errorMessage);
                }
            });
        }
    });
});

// Auto refresh data setiap 30 detik
setInterval(function() {
    loadPeminjaman($('#searchPeminjaman').val());
}, 30000);

// Notifikasi untuk buku yang akan jatuh tempo (dalam 2 hari)
function checkDueSoon() {
    const dueSoonBooks = allPeminjaman.filter(item => {
        if (item.kondisi_buku_saat_dikembalikan !== '') return false;
        
        const dueDate = new Date(item.tanggal_pengembalian);
        const today = new Date();
        const diffTime = dueDate.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        return diffDays <= 2 && diffDays >= 0;
    });
    
    if (dueSoonBooks.length > 0) {
        const booksList = dueSoonBooks.map(book => 
            `• ${book.nama_anggota} - ${book.judul_buku} (${formatDate(book.tanggal_pengembalian)})`
        ).join('\n');
        
        showNotification('warning', 'Peringatan Jatuh Tempo', 
            `${dueSoonBooks.length} buku akan jatuh tempo dalam waktu dekat:\n\n${booksList}`, {
                timer: 8000,
                showConfirmButton: true
            });
    }
}

// Check due soon books when page loads
$(document).ready(function() {
    setTimeout(checkDueSoon, 3000);
});

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Alt + S untuk scan anggota
    if (e.altKey && e.keyCode === 83) {
        e.preventDefault();
        scanPeminjamanBarcode();
    }
    
    // Alt + B untuk scan buku
    if (e.altKey && e.keyCode === 66) {
        e.preventDefault();
        scanBukuReturn();
    }
    
    // Alt + R untuk reset
    if (e.altKey && e.keyCode === 82) {
        e.preventDefault();
        resetPencarian();
    }
});
</script>