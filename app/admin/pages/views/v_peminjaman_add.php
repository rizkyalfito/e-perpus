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

                        <form action="function/Peminjaman.php?aksi=pinjam" method="POST">
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

                        <form action="function/Peminjaman.php?aksi=pengembalian" method="POST">
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

<!-- Include QuaggaJS for Barcode Scanning -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>

<script>
let scannerActive = false;
let currentTransaction = 'peminjaman';

// Sample data - In real implementation, this would come from database via AJAX
const anggotaData = {
    'AP001': { nama: 'Gunawan', kelas: 'X - Administrasi Perkantoran' },
    'AP002': { nama: 'Siti Nurhaliza', kelas: 'XI - Administrasi Perkantoran' },
    'RPL001': { nama: 'Ahmad Rizki', kelas: 'XII - Rekayasa Perangkat Lunak' }
};

const bukuData = {
    '9781234567890': { 
        judul: 'Pemrograman Web Dasar', 
        pengarang: 'John Doe', 
        stok: '5 buku tersedia' 
    },
    '9789876543210': { 
        judul: 'Basis Data MySQL', 
        pengarang: 'Jane Smith', 
        stok: '3 buku tersedia' 
    }
};

// Sample data for borrowed books (for return process)
const peminjamanData = {
    'RPL001_9781234567890': {
        tanggal_peminjaman: '15-07-2025',
        batas_kembali: '29-07-2025',
        status: 'Belum Terlambat'
    },
    'AP001_9789876543210': {
        tanggal_peminjaman: '10-07-2025',
        batas_kembali: '24-07-2025',
        status: 'Terlambat'
    }
};

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

function processAnggotaCode(kode) {
    const suffix = currentTransaction === 'peminjaman' ? 'Pinjam' : 'Kembali';
    
    document.getElementById('kodeAnggota' + suffix).value = kode;
    
    // In real implementation, this would be an AJAX call to get member data
    if (anggotaData[kode]) {
        document.getElementById('namaAnggota' + suffix).value = anggotaData[kode].nama;
        document.getElementById('kelasAnggota' + suffix).value = anggotaData[kode].kelas;
        document.getElementById('anggotaStatus' + suffix).style.display = 'block';
        
        alert(`Anggota Ditemukan!\n${anggotaData[kode].nama} - ${anggotaData[kode].kelas}`);
    } else {
        if (currentTransaction === 'peminjaman') {
            clearAnggotaPinjam();
        } else {
            clearAnggotaKembali();
        }
        alert(`Anggota Tidak Ditemukan\nKode anggota ${kode} tidak terdaftar dalam sistem.`);
    }
    
    checkFormCompletion();
}

function processBukuCode(isbn) {
    const suffix = currentTransaction === 'peminjaman' ? 'Pinjam' : 'Kembali';
    
    document.getElementById('isbnBuku' + suffix).value = isbn;
    
    // In real implementation, this would be an AJAX call to get book data
    if (bukuData[isbn]) {
        document.getElementById('judulBuku' + suffix).value = bukuData[isbn].judul;
        
        if (currentTransaction === 'peminjaman') {
            document.getElementById('pengarangBuku' + suffix).value = bukuData[isbn].pengarang;
            document.getElementById('stokBuku' + suffix).value = bukuData[isbn].stok;
        } else {
            // For return, get loan data
            const anggotaKode = document.getElementById('kodeAnggotaKembali').value;
            const loanKey = anggotaKode + '_' + isbn;
            
            if (peminjamanData[loanKey]) {
                document.getElementById('tanggalPeminjamanKembali').value = peminjamanData[loanKey].tanggal_peminjaman;
                document.getElementById('statusKeterlambatan').value = peminjamanData[loanKey].status;
            }
        }
        
        document.getElementById('bukuStatus' + suffix).style.display = 'block';
        
        alert(`Buku Ditemukan!\n${bukuData[isbn].judul} - ${bukuData[isbn].pengarang}`);
    } else {
        if (currentTransaction === 'peminjaman') {
            clearBukuPinjam();
        } else {
            clearBukuKembali();
        }
        alert(`Buku Tidak Ditemukan\nISBN ${isbn} tidak terdaftar dalam sistem.`);
    }
    
    checkFormCompletion();
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
    checkFormCompletion();
}

function clearBukuKembali() {
    document.getElementById('isbnBukuKembali').value = '';
    document.getElementById('judulBukuKembali').value = '';
    document.getElementById('tanggalPeminjamanKembali').value = '';
    document.getElementById('statusKeterlambatan').value = '';
    document.getElementById('bukuStatusKembali').style.display = 'none';
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
        
        if (anggota && buku) {
            submitBtn.disabled = false;
            submitBtn.className = 'btn btn-danger btn-block';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'btn btn-danger btn-block';
        }
    }
}

// Handle Enter key for manual input
document.getElementById('manualInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        processManualInput();
    }
});

// Handle kondisi buku change for denda calculation
document.addEventListener('change', function(e) {
    if (e.target.name === 'kondisiBukuSaatDikembalikan') {
        const kondisi = e.target.value;
        const dendaField = document.getElementById('dendaPreview');
        
        if (kondisi === 'Baik') {
            dendaField.value = 'Tidak ada denda';
        } else if (kondisi === 'Rusak') {
            dendaField.value = 'Rp 20.000';
        } else if (kondisi === 'Hilang') {
            dendaField.value = 'Rp 50.000';
        }
    }
});

// Initialize form check on page load
document.addEventListener('DOMContentLoaded', function() {
    checkFormCompletion();
});
</script>