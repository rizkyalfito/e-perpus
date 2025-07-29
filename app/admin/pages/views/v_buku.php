<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Data Buku
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
            <li class="active">Data Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Data Buku</h3>
                        <div class="form-group m-b-2 text-right" style="margin-top: -20px; margin-bottom: -5px;">
                            <button type="button" onclick="tambahBuku()" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Buku</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Buku Baik</th>
                                    <th>Buku Rusak</th>
                                    <th>Jumlah Buku</th>
                                    <th>Barcode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php
                            include "../../config/koneksi.php";

                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM buku");
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tbody>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['judul_buku']; ?></td>
                                        <td><?= $row['pengarang']; ?></td>
                                        <td><?= $row['penerbit_buku']; ?></td>
                                        <td><?= $row['j_buku_baik']; ?></td>
                                        <td><?= $row['j_buku_rusak']; ?></td>
                                        <td><?php
                                            $j_buku_rusak = $row['j_buku_rusak'];
                                            $j_buku_baik = $row['j_buku_baik'];
                                            echo $j_buku_rusak + $j_buku_baik;
                                            ?></td>
                                        <td style="text-align: center;">
                                            <button onclick="lihatBarcode('<?= $row['isbn']; ?>', '<?= addslashes($row['judul_buku']); ?>', '<?= addslashes($row['pengarang']); ?>')" 
                                                    class="btn btn-success btn-sm" title="Lihat Barcode">
                                                <i class="fa fa-barcode"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <a href="#" data-target="#modalEditBuku<?= $row['id_buku']; ?>" data-toggle="modal" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="pages/function/Buku.php?act=hapus&id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm btn-del"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEditBuku<?= $row['id_buku']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Edit Buku ( <?= $row['judul_buku']; ?> - <?= $row['pengarang']; ?> )</h4>
                                                </div>
                                                <form action="pages/function/Buku.php?act=edit" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                                        <div class="form-group">
                                                            <label>Judul Buku <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="text" class="form-control" value="<?= $row['judul_buku']; ?>" name="judulBuku" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kategori Buku <small style="color: red;">* Wajib diisi</small></label>
                                                            <select class="form-control" name="kategoriBuku" required>
                                                                <option selected value="<?= $row['kategori_buku']; ?>"><?= $row['kategori_buku']; ?> ( Dipilih Sebelumnya )</option>
                                                                <?php
                                                                include "../../config/koneksi.php";
                                                                $sql = mysqli_query($koneksi, "SELECT * FROM kategori");
                                                                while ($data = mysqli_fetch_array($sql)) {
                                                                ?>
                                                                    <option value="<?= $data['nama_kategori']; ?>"> <?= $data['nama_kategori']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Penerbit Buku <small style="color: red;">* Wajib diisi</small></label>
                                                            <select class="form-control select2" name="penerbitBuku" required>
                                                                <option selected value="<?= $row['penerbit_buku']; ?>"><?= $row['penerbit_buku']; ?> ( Dipilih Sebelumnya )</option>
                                                                <?php
                                                                include "../../config/koneksi.php";
                                                                $sql = mysqli_query($koneksi, "SELECT * FROM penerbit");
                                                                while ($data = mysqli_fetch_array($sql)) {
                                                                ?>
                                                                    <option value="<?= $data['nama_penerbit']; ?>"><?= $data['nama_penerbit']; ?> ( <?= $data['verif_penerbit']; ?> )</option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Pengarang <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="text" class="form-control" value="<?= $row['pengarang']; ?>" name="pengarang" placeholder="Masukkan nama pengarang" required>
                                                            <small class="text-muted">Contoh: Ahmad Tohari, Pramoedya Ananta Toer, dll.</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tahun Terbit <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="number" min="1900" max="<?= date('Y') + 5; ?>" class="form-control" value="<?= $row['tahun_terbit']; ?>" name="tahunTerbit" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>ISBN <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="text" class="form-control" value="<?= $row['isbn']; ?>" name="iSbn" placeholder="Contoh: 978-602-8519-93-9" required>
                                                            <small class="text-muted">Masukkan nomor ISBN lengkap dengan tanda hubung</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jumlah Buku Baik <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="number" min="0" class="form-control" value="<?= $row['j_buku_baik']; ?>" name="jumlahBukuBaik" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jumlah Buku Rusak <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="number" min="0" class="form-control" value="<?= $row['j_buku_rusak']; ?>" name="jumlahBukuRusak" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /. Modal Edit -->
                                </tbody>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Buku</h4>
            </div>
            <form action="pages/function/Buku.php?act=tambah" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Buku <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan judul buku" name="judulBuku" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori Buku <small style="color: red;">* Wajib diisi</small></label>
                        <select class="form-control" name="kategoriBuku" required>
                            <option value="" disabled selected>-- Pilih kategori buku --</option>
                            <?php
                            include "../../config/koneksi.php";
                            $sql = mysqli_query($koneksi, "SELECT * FROM kategori");
                            while ($data = mysqli_fetch_array($sql)) {
                            ?>
                                <option value="<?= $data['nama_kategori']; ?>"> <?= $data['nama_kategori']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Penerbit Buku <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan nama penerbit" name="penerbitBuku" required>
                        <small class="text-muted">Contoh: Gramedia Pustaka Utama, Mizan, Erlangga, dll.</small>
                    </div>
                    <div class="form-group">
                        <label>Pengarang <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukkan nama pengarang" name="pengarang" required>
                        <small class="text-muted">Contoh: Ahmad Tohari, Pramoedya Ananta Toer, dll.</small>
                    </div>
                    <div class="form-group">
                        <label>Tahun Terbit <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="1900" max="<?= date('Y') + 5; ?>" class="form-control" placeholder="Contoh: <?= date('Y'); ?>" name="tahunTerbit" required>
                    </div>
                    <div class="form-group">
                        <label>ISBN <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Contoh: 978-602-8519-93-9" name="iSbn" required>
                        <small class="text-muted">Masukkan nomor ISBN lengkap dengan tanda hubung</small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Buku Baik <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="0" class="form-control" placeholder="Masukkan jumlah buku baik" name="jumlahBukuBaik" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Buku Rusak <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" min="0" class="form-control" placeholder="Masukkan jumlah buku rusak" name="jumlahBukuRusak" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Barcode Buku -->
<div class="modal fade" id="modalBarcodeBuku" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                    <i class="fa fa-barcode"></i> Barcode Buku
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Informasi Buku -->
                    <div class="col-md-6">
                        <div style="border: 2px solid #3c8dbc; border-radius: 10px; padding: 15px; background-color: #f9f9f9;">
                            <h4 style="color: #3c8dbc; font-weight: bold; margin-bottom: 15px;">Informasi Buku</h4>
                            <p><strong>Judul:</strong> <span id="bukuJudul"></span></p>
                            <p><strong>Pengarang:</strong> <span id="bukuPengarang"></span></p>
                            <p><strong>ISBN:</strong> <span id="bukuISBN"></span></p>
                            <p style="font-size: 12px; color: #666; margin-top: 10px;">
                                * Barcode dibuat berdasarkan ISBN buku untuk memudahkan identifikasi dan peminjaman
                            </p>
                        </div>
                    </div>
                    
                    <!-- Barcode -->
                    <div class="col-md-6">
                        <div style="text-align: center; border: 2px dashed #28a745; border-radius: 10px; padding: 20px; background-color: #f8f9fa;">
                            <h4 style="color: #28a745; font-weight: bold; margin-bottom: 15px;">Barcode Buku</h4>
                            
                            <!-- Barcode Image -->
                            <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                                <img id="barcodeImage" src="" alt="Barcode" style="max-width: 200px; height: 60px;">
                            </div>
                            
                            <!-- ISBN Number -->
                            <p style="font-weight: bold; font-size: 14px; color: #333; margin: 10px 0;">
                                ISBN: <span id="barcodeNumber"></span>
                            </p>
                            
                            <!-- Buttons -->
                            <div style="margin-top: 15px;">
                                <button onclick="cetakBarcode()" class="btn btn-success btn-sm" style="margin-right: 10px;">
                                    <i class="fa fa-print"></i> Cetak Label
                                </button>
                                <button onclick="downloadBarcode()" class="btn btn-info btn-sm">
                                    <i class="fa fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Template untuk cetak multiple label -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background-color: #fff;">
                            <h5 style="font-weight: bold; color: #333;">
                                <i class="fa fa-tags"></i> Cetak Multiple Label
                            </h5>
                            <div class="form-group" style="margin-top: 10px;">
                                <label>Jumlah Label yang akan dicetak:</label>
                                <div style="display: flex; align-items: center;">
                                    <input type="number" id="jumlahLabel" class="form-control" style="width: 100px; margin-right: 10px;" value="1" min="1" max="50">
                                    <button onclick="cetakMultipleLabel()" class="btn btn-primary btn-sm">
                                        <i class="fa fa-print"></i> Cetak Multiple
                                    </button>
                                </div>
                                <small class="text-muted">Maksimal 50 label per sekali cetak</small>
                            </div>
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

<!-- Hidden div untuk template cetak -->
<div id="templateCetak" style="display: none;">
    <div id="contentCetak"></div>
</div>

<script>
function tambahBuku() {
    $('#modalTambahBuku').modal('show');
}

function lihatBarcode(isbn, judul, pengarang) {
    // Set informasi buku
    document.getElementById('bukuJudul').textContent = judul;
    document.getElementById('bukuPengarang').textContent = pengarang;
    document.getElementById('bukuISBN').textContent = isbn;
    document.getElementById('barcodeNumber').textContent = isbn;
    
    // Generate barcode image
    const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${isbn}&code=Code128&translate-esc=true&width=300&height=80`;
    document.getElementById('barcodeImage').src = barcodeUrl;
    
    // Show modal
    $('#modalBarcodeBuku').modal('show');
}

function cetakBarcode() {
    const isbn = document.getElementById('bukuISBN').textContent;
    const judul = document.getElementById('bukuJudul').textContent;
    const pengarang = document.getElementById('bukuPengarang').textContent;
    
    cetakLabel(isbn, judul, pengarang, 1);
}

function cetakMultipleLabel() {
    const jumlah = document.getElementById('jumlahLabel').value;
    const isbn = document.getElementById('bukuISBN').textContent;
    const judul = document.getElementById('bukuJudul').textContent;
    const pengarang = document.getElementById('bukuPengarang').textContent;
    
    if (jumlah < 1 || jumlah > 50) {
        alert('Jumlah label harus antara 1-50');
        return;
    }
    
    cetakLabel(isbn, judul, pengarang, parseInt(jumlah));
}

function cetakLabel(isbn, judul, pengarang, jumlah) {
    let content = `
        <html>
        <head>
            <title>Label Barcode Buku</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 10px;
                    background: white;
                }
                .label {
                    width: 2.5in;
                    height: 1in;
                    border: 1px solid #000;
                    padding: 5px;
                    margin: 5px;
                    display: inline-block;
                    text-align: center;
                    vertical-align: top;
                    page-break-inside: avoid;
                }
                .label h6 {
                    font-size: 9px;
                    margin: 2px 0;
                    font-weight: bold;
                }
                .label p {
                    font-size: 7px;
                    margin: 1px 0;
                }
                .label img {
                    width: 120px;
                    height: 25px;
                }
                @media print {
                    body { margin: 0; }
                    .label { 
                        margin: 2px;
                        border: 1px solid #000;
                    }
                }
            </style>
        </head>
        <body>
    `;
    
    // Generate barcode URL yang akan berfungsi saat print
    const barcodeImageUrl = `https://barcode.tec-it.com/barcode.ashx?data=${isbn}&code=Code128&translate-esc=true&width=200&height=50&dpi=300`;
    
    for (let i = 0; i < jumlah; i++) {
        content += `
            <div class="label">
                <h6>${judul && judul.length > 25 ? judul.substring(0, 25) + '...' : judul || 'Judul Tidak Tersedia'}</h6>
                <p>Pengarang: ${pengarang || 'Tidak Diketahui'}</p>
                <img src="${barcodeImageUrl}" alt="Barcode ${isbn}" onerror="this.alt='Barcode: ${isbn}';">
                <p><strong>ISBN: ${isbn}</strong></p>
            </div>
        `;
    }
    
    content += `
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(content);
    printWindow.document.close();
    
    // Tunggu gambar barcode dimuat sebelum print
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
        }, 1000); // Delay 1 detik untuk memastikan gambar dimuat
    };
}

function downloadBarcode() {
    const isbn = document.getElementById('bukuISBN').textContent;
    const judul = document.getElementById('bukuJudul').textContent;
    
    const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${isbn}&code=Code128&translate-esc=true&width=400&height=100&format=png&download=true`;
    
    const link = document.createElement('a');
    link.href = barcodeUrl;
    link.download = `barcode_${judul.replace(/[^a-zA-Z0-9]/g, '_')}_${isbn}.png`;
    link.setAttribute('target', '_blank');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Form validation untuk memastikan pengarang dan penerbit tidak kosong
document.addEventListener('DOMContentLoaded', function() {
    // Validasi untuk form tambah buku
    const formTambah = document.querySelector('#modalTambahBuku form');
    if (formTambah) {
        formTambah.addEventListener('submit', function(e) {
            const pengarang = this.querySelector('input[name="pengarang"]').value.trim();
            const penerbit = this.querySelector('input[name="penerbitBuku"]').value.trim();
            
            if (pengarang === '') {
                e.preventDefault();
                alert('Nama pengarang wajib diisi!');
                this.querySelector('input[name="pengarang"]').focus();
                return false;
            }
            
            if (penerbit === '') {
                e.preventDefault();
                alert('Nama penerbit wajib diisi!');
                this.querySelector('input[name="penerbitBuku"]').focus();
                return false;
            }
        });
    }
    
    // Validasi untuk semua form edit buku
    const formsEdit = document.querySelectorAll('form[action*="act=edit"]');
    formsEdit.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const pengarang = this.querySelector('input[name="pengarang"]').value.trim();
            const penerbit = this.querySelector('input[name="penerbitBuku"]').value.trim();
            
            if (pengarang === '') {
                e.preventDefault();
                alert('Nama pengarang wajib diisi!');
                this.querySelector('input[name="pengarang"]').focus();
                return false;
            }
            
            if (penerbit === '') {
                e.preventDefault();
                alert('Nama penerbit wajib diisi!');
                this.querySelector('input[name="penerbitBuku"]').focus();
                return false;
            }
        });
    });
});
</script>

<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Pesan Berhasil Edit -->
<script>
    <?php
    if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] <> '') {
        echo "swal({
            icon: 'success',
            title: 'Berhasil',
            text: '$_SESSION[berhasil]'
        })";
    }
    $_SESSION['berhasil'] = '';
    ?>
</script>

<!-- Notif Gagal -->
<script>
    <?php
    if (isset($_SESSION['gagal']) && $_SESSION['gagal'] <> '') {
        echo "swal({
                icon: 'error',
                title: 'Gagal',
                text: '$_SESSION[gagal]'
              })";
    }
    $_SESSION['gagal'] = '';
    ?>
</script>

<!-- Swal Hapus Data -->
<script>
    $('.btn-del').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        swal({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Apakah anda yakin ingin menghapus data buku ini ?',
                buttons: true,
                dangerMode: true,
                buttons: ['Tidak, Batalkan !', 'Iya, Hapus']
            })
            .then((willDelete) => {
                if (willDelete) {
                    document.location.href = href;
                } else {
                    swal({
                        icon: 'error',
                        title: 'Dibatalkan',
                        text: 'Data buku tersebut aman !'
                    })
                }
            });
    })
</script>