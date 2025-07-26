<!-- v_peminjaman.add.php  -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Transaksi Perpustakaan
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
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="data-peminjaman">Data Peminjaman</a></li>
            <li class="active">Transaksi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- Transaction Type Toggle -->
                <div class="box">
                    <div class="box-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: white;">
                            <i class="fa fa-exchange-alt"></i> Pilih Jenis Transaksi
                        </h3>
                    </div>
                    <div class="box-body" style="text-align: center; padding: 30px;">
                        <div class="btn-group btn-group-lg" role="group">
                            <button type="button" class="btn btn-primary active" id="peminjamanBtn" onclick="switchTransaction('peminjaman')" style="padding: 15px 40px; border-radius: 25px 0 0 25px;">
                                <i class="fa fa-plus-circle"></i> Peminjaman Buku
                            </button>
                            <button type="button" class="btn btn-success" id="pengembalianBtn" onclick="switchTransaction('pengembalian')" style="padding: 15px 40px; border-radius: 0 25px 25px 0;">
                                <i class="fa fa-undo"></i> Pengembalian Buku
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Scanner Section -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                            <i class="fa fa-qrcode"></i> Scanner Barcode
                        </h3>
                        <div class="pull-right">
                            <button type="button" onclick="toggleScanner()" class="btn btn-info btn-sm" id="scannerToggle">
                                <i class="fa fa-camera"></i> Aktifkan Scanner
                            </button>
                        </div>
                    </div>
                    
                    <div id="scannerSection" style="display: none; padding: 15px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;">
                        <div class="row">
                            <div class="col-md-6">
                                <div style="text-align: center; border: 2px dashed #007bb6; padding: 20px; border-radius: 10px; background: white;">
                                    <h4 style="color: #007bb6; margin-bottom: 15px;">
                                        <i class="fa fa-qrcode"></i> Scanner Barcode
                                    </h4>
                                    <div id="scanner" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                                    <div style="margin-top: 15px;">
                                        <button type="button" onclick="startScanner()" class="btn btn-success btn-sm" id="startBtn">
                                            <i class="fa fa-play"></i> Mulai Scan
                                        </button>
                                        <button type="button" onclick="stopScanner()" class="btn btn-danger btn-sm" id="stopBtn" disabled>
                                            <i class="fa fa-stop"></i> Stop Scan
                                        </button>
                                    </div>
                                    <p style="font-size: 12px; color: #666; margin-top: 10px;">
                                        Arahkan kamera ke barcode anggota atau buku
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; background: white;">
                                    <h5 style="color: #333; margin-bottom: 15px;">
                                        <i class="fa fa-info-circle"></i> Petunjuk Scan
                                    </h5>
                                    <ul style="font-size: 13px; color: #666;">
                                        <li><strong>Kartu Anggota:</strong> Scan barcode pada kartu anggota untuk mengisi data peminjam</li>
                                        <li><strong>Buku:</strong> Scan barcode ISBN pada buku untuk memilih judul buku</li>
                                        <li><strong>Pencahayaan:</strong> Pastikan area cukup terang untuk hasil scan optimal</li>
                                        <li><strong>Jarak:</strong> Jaga jarak 10-20cm antara kamera dan barcode</li>
                                    </ul>
                                    
                                    <!-- Manual Input Alternative -->
                                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                        <h6 style="color: #333; margin-bottom: 10px;">Input Manual:</h6>
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <input type="text" id="manualInput" class="form-control input-sm" placeholder="Ketik kode anggota atau ISBN" style="font-size: 12px;">
                                        </div>
                                        <button type="button" onclick="processManualInput()" class="btn btn-primary btn-xs">
                                            <i class="fa fa-check"></i> Proses
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PEMINJAMAN SECTION -->
                <div id="peminjamanSection" class="transaction-section">
                    <div class="box">
                        <div class="box-header" style="background-color: #28a745; color: white; border-radius: 10px 10px 0 0;">
                            <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: white;">
                                <i class="fa fa-plus-circle"></i> Formulir Peminjaman Buku
                            </h3>
                        </div>

                        <form id="peminjamanForm" onsubmit="return submitTransaksi(this, 'peminjaman')">
                            <div class="box-body">
                                <!-- Data Anggota Section -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="border: 2px solid #28a745; border-radius: 10px; padding: 15px; background-color: #f8fff8;">
                                            <h4 style="color: #28a745; margin-bottom: 15px;">
                                                <i class="fa fa-user"></i> Data Anggota
                                            </h4>
                                            
                                            <div class="form-group">
                                                <label>Kode Anggota <small style="color: #28a745;">* Scan kartu anggota</small></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="kodeAnggotaPinjam" name="kodeAnggota" 
                                                           placeholder="Scan kartu anggota atau ketik kode" readonly>
                                                    <span class="input-group-btn">
                                                        <button type="button" onclick="clearAnggotaPinjam()" class="btn btn-warning">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Nama Anggota</label>
                                                <input type="text" class="form-control" id="namaAnggotaPinjam" name="namaAnggota" 
                                                       placeholder="Nama akan muncul otomatis" readonly required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Kelas</label>
                                                <input type="text" class="form-control" id="kelasAnggotaPinjam" 
                                                       placeholder="Kelas akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div id="anggotaStatusPinjam" style="display: none;">
                                                <div class="alert alert-success" style="padding: 8px; font-size: 12px;">
                                                    <i class="fa fa-check-circle"></i> <strong>Anggota Terverifikasi!</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Data Buku Section -->
                                    <div class="col-md-6">
                                        <div style="border: 2px solid #007bff; border-radius: 10px; padding: 15px; background-color: #f8f9ff;">
                                            <h4 style="color: #007bff; margin-bottom: 15px;">
                                                <i class="fa fa-book"></i> Data Buku
                                            </h4>
                                            
                                            <div class="form-group">
                                                <label>ISBN / Kode Buku <small style="color: #007bff;">* Scan barcode buku</small></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="isbnBukuPinjam" name="isbnBuku" 
                                                           placeholder="Scan barcode buku atau ketik ISBN" readonly>
                                                    <span class="input-group-btn">
                                                        <button type="button" onclick="clearBukuPinjam()" class="btn btn-warning">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Judul Buku</label>
                                                <input type="text" class="form-control" id="judulBukuPinjam" name="judulBuku" 
                                                       placeholder="Judul akan muncul otomatis" readonly required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Pengarang</label>
                                                <input type="text" class="form-control" id="pengarangBukuPinjam" 
                                                       placeholder="Pengarang akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Stok Tersedia</label>
                                                <input type="text" class="form-control" id="stokBukuPinjam" 
                                                       placeholder="Stok akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div id="bukuStatusPinjam" style="display: none;">
                                                <div class="alert alert-success" style="padding: 8px; font-size: 12px;">
                                                    <i class="fa fa-check-circle"></i> <strong>Buku Tersedia!</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Details -->
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Peminjaman</label>
                                            <input type="text" class="form-control" name="tanggalPeminjaman" 
                                                   value="<?= date('d-m-Y'); ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kondisi Buku Saat Dipinjam</label>
                                            <select class="form-control" name="kondisiBukuSaatDipinjam" required>
                                                <option selected disabled>-- Pilih Kondisi Buku --</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak">Rusak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-block" id="submitBtnPinjam" disabled>
                                    <i class="fa fa-save"></i> Simpan Peminjaman
                                </button>
                                <p style="text-align: center; font-size: 12px; color: #666; margin-top: 10px;">
                                    * Scan atau isi data anggota dan buku terlebih dahulu
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PENGEMBALIAN SECTION -->
                <div id="pengembalianSection" class="transaction-section" style="display: none;">
                    <div class="box">
                        <div class="box-header" style="background-color: #dc3545; color: white; border-radius: 10px 10px 0 0;">
                            <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: white;">
                                <i class="fa fa-undo"></i> Formulir Pengembalian Buku
                            </h3>
                        </div>

                        <form id="pengembalianForm" onsubmit="return submitTransaksi(this, 'pengembalian')">
                            <div class="box-body">
                                <!-- Data Anggota Section -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="border: 2px solid #dc3545; border-radius: 10px; padding: 15px; background-color: #fff8f8;">
                                            <h4 style="color: #dc3545; margin-bottom: 15px;">
                                                <i class="fa fa-user"></i> Data Anggota
                                            </h4>
                                            
                                            <div class="form-group">
                                                <label>Kode Anggota <small style="color: #dc3545;">* Scan kartu anggota</small></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="kodeAnggotaKembali" name="kodeAnggota" 
                                                           placeholder="Scan kartu anggota atau ketik kode" readonly>
                                                    <span class="input-group-btn">
                                                        <button type="button" onclick="clearAnggotaKembali()" class="btn btn-warning">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Nama Anggota</label>
                                                <input type="text" class="form-control" id="namaAnggotaKembali" name="namaAnggota" 
                                                       placeholder="Nama akan muncul otomatis" readonly required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Kelas</label>
                                                <input type="text" class="form-control" id="kelasAnggotaKembali" 
                                                       placeholder="Kelas akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div id="anggotaStatusKembali" style="display: none;">
                                                <div class="alert alert-success" style="padding: 8px; font-size: 12px;">
                                                    <i class="fa fa-check-circle"></i> <strong>Anggota Terverifikasi!</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Data Buku Section -->
                                    <div class="col-md-6">
                                        <div style="border: 2px solid #ffc107; border-radius: 10px; padding: 15px; background-color: #fffef8;">
                                            <h4 style="color: #ffc107; margin-bottom: 15px;">
                                                <i class="fa fa-book"></i> Data Buku yang Dikembalikan
                                            </h4>
                                            
                                            <div class="form-group">
                                                <label>ISBN / Kode Buku <small style="color: #ffc107;">* Scan barcode buku</small></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="isbnBukuKembali" name="isbnBuku" 
                                                           placeholder="Scan barcode buku atau ketik ISBN" readonly>
                                                    <span class="input-group-btn">
                                                        <button type="button" onclick="clearBukuKembali()" class="btn btn-warning">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Judul Buku</label>
                                                <input type="text" class="form-control" id="judulBukuKembali" name="judulBuku" 
                                                       placeholder="Judul akan muncul otomatis" readonly required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Tanggal Peminjaman</label>
                                                <input type="text" class="form-control" id="tanggalPeminjamanKembali" 
                                                       placeholder="Tanggal peminjaman akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Status Keterlambatan</label>
                                                <input type="text" class="form-control" id="statusKeterlambatan" 
                                                       placeholder="Status akan muncul otomatis" readonly>
                                            </div>
                                            
                                            <div id="bukuStatusKembali" style="display: none;">
                                                <div class="alert alert-success" style="padding: 8px; font-size: 12px;">
                                                    <i class="fa fa-check-circle"></i> <strong>Buku Ditemukan!</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Details -->
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Pengembalian</label>
                                            <input type="text" class="form-control" name="tanggalPengembalian" 
                                                   value="<?= date('d-m-Y'); ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kondisi Buku Saat Dikembalikan</label>
                                            <select class="form-control" name="kondisiBukuSaatDikembalikan" required>
                                                <option selected disabled>-- Pilih Kondisi Buku --</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak">Rusak</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Denda</label>
                                            <input type="text" class="form-control" id="dendaPreview" 
                                                   placeholder="Denda akan dihitung otomatis" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-danger btn-block" id="submitBtnKembali" disabled>
                                    <i class="fa fa-undo"></i> Proses Pengembalian
                                </button>
                                <p style="text-align: center; font-size: 12px; color: #666; margin-top: 10px;">
                                    * Scan atau isi data anggota dan buku terlebih dahulu
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!-- Include Required Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Custom Transaction JavaScript -->
<script src="../function/transaksi.js"></script>

<style>
/* Custom Alert Styles */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

.alert-warning {
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;
}

.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}

/* Scanner Styles */
#scanner canvas {
    width: 100% !important;
    height: auto !important;
    border: 2px solid #007bb6;
    border-radius: 5px;
}

/* Form Animation */
.transaction-section {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Button Hover Effects */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

/* Loading Spinner */
.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-lg .btn {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    #scanner {
        max-width: 300px;
    }
    
    .box-body {
        padding: 10px;
    }
}
</style>