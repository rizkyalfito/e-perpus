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
                            <div class="col-md-8">
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
                            <div class="col-md-4">
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
let allPeminjaman = [];

// Load data peminjaman saat halaman dimuat
$(document).ready(function() {
    loadPeminjaman();
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
                (item.kode_anggota && item.kode_anggota.toLowerCase().includes(searchTerm))
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
                    <td>${item.judul_buku}</td>
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

// Scan barcode untuk pencarian peminjaman
function scanPeminjamanBarcode() {
    $('#modalScanPeminjaman').modal('show');
    
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
                });
            } else {
                showScanStatusPeminjaman('error', 'Kamera tidak ditemukan');
            }
        }).catch(err => {
            showScanStatusPeminjaman('error', 'Error mengakses kamera: ' + err);
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
                
                swal({
                    icon: 'success',
                    title: 'Berhasil',
                    text: `Menampilkan peminjaman untuk: ${namaAnggota}`,
                    timer: 2000
                });
            } else {
                swal({
                    icon: 'error',
                    title: 'Anggota Tidak Ditemukan',
                    text: response.message
                });
            }
        },
        error: function() {
            swal({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mencari anggota'
            });
        }
    });
}

// Cari peminjaman manual
function cariPeminjaman() {
    const keyword = $('#searchPeminjaman').val().trim();
    if (keyword === '') {
        swal({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Mohon masukkan kata kunci pencarian'
        });
        return;
    }
    
    displayPeminjaman(allPeminjaman, keyword);
}

// Reset pencarian
function resetPencarian() {
    $('#searchPeminjaman').val('');
    displayPeminjaman(allPeminjaman);
}

// Konfirmasi pengembalian
function konfirmasiPengembalian(idPeminjaman) {
    const peminjaman = allPeminjaman.find(item => item.id_peminjaman == idPeminjaman);
    
    if (!peminjaman) {
        swal({
            icon: 'error',
            title: 'Error',
            text: 'Data peminjaman tidak ditemukan'
        });
        return;
    }
    
    // Fill modal data
    $('#idPeminjamanKembali').val(idPeminjaman);
    $('#detailNamaAnggota').text(peminjaman.nama_anggota);
    $('#detailJudulBuku').text(peminjaman.judul_buku);
    $('#detailTanggalPinjam').text(formatDate(peminjaman.tanggal_peminjaman));
    $('#detailTanggalKembali').text(formatDate(peminjaman.tanggal_pengembalian));
    
    // Hitung dan set denda
    const denda = hitungDenda(peminjaman.tanggal_pengembalian);
    $('#dendaInput').val(denda);
    
    $('#modalKonfirmasiKembali').modal('show');
}

// Perpanjang peminjaman
function perpanjangPeminjaman(idPeminjaman) {
    const peminjaman = allPeminjaman.find(item => item.id_peminjaman == idPeminjaman);
    
    if (!peminjaman) {
        swal({
            icon: 'error',
            title: 'Error',
            text: 'Data peminjaman tidak ditemukan'
        });
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

// Stop camera when modal is closed
$('#modalScanPeminjaman').on('hidden.bs.modal', function () {
    if (html5QrCodePeminjaman) {
        html5QrCodePeminjaman.stop().then(() => {
            html5QrCodePeminjaman.clear();
        }).catch(err => {
            console.error(err);
        });
    }
    $('#scanStatusPeminjaman').hide();
});

// Event listeners
$('#searchPeminjaman').on('keypress', function(e) {
    if (e.which === 13) { // Enter key
        cariPeminjaman();
    }
});

// Form submissions
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
            // Submit form
            this.submit();
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
            // Submit form
            this.submit();
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
        
        swal({
            icon: 'warning',
            title: 'Peringatan Jatuh Tempo',
            text: `${dueSoonBooks.length} buku akan jatuh tempo dalam 2 hari:\n\n${booksList}`,
            buttons: {
                ok: {
                    text: 'OK',
                    className: 'btn btn-warning'
                }
            }
        });
    }
}

// Check due soon books when page loads
$(document).ready(function() {
    setTimeout(checkDueSoon, 2000);
});
</script>