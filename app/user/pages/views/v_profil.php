<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Profil Saya
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
            <li class="active">Profil Saya</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Edit Profil Saya</h3>
                    </div>
                    <!-- /.box-header -->
                    <form action="pages/function/Profile.php?aksi=edit" method="POST" enctype="multipart/form-data">
                        <?php
                        include "../../config/koneksi.php";

                        $id_user = $_SESSION['id_user'];
                        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
                        $row = mysqli_fetch_assoc($query);
                        ?>
                        <div class="box-body">
                            <input name="IdUser" type="hidden" value="<?= $row['id_user']; ?>">
                            
                            <!-- Upload Foto Section -->
                            <div class="form-group">
                                <label>Foto Profil</label>
                                <div style="margin-bottom: 10px;">
                                    <?php 
                                    $foto_path = !empty($row['foto']) && $row['foto'] != 'default-avatar.png' 
                                        ? '../../assets/img/users/' . $row['foto'] 
                                        : '../../assets/dist/img/avatar5.png';
                                    ?>
                                    <img id="preview-foto" src="<?= $foto_path; ?>" 
                                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;">
                                </div>
                                <input type="file" name="foto" id="foto" accept="image/*" class="form-control" 
                                       onchange="previewImage(this)">
                                <small style="color: #666;">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Anggota <small style="color: red;">* Tidak dapat dirubah</small></label>
                                <input type="text" class="form-control" value="<?= $row['kode_user']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>NIS <small style="color: red;">* Wajib diisi</small></label>
                                <input type="text" class="form-control" value="<?= $row['nis']; ?>" name="Nis" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Lengkap <small style="color: red;">* Wajib diisi</small></label>
                                <input type="text" class="form-control" value="<?= $row['fullname']; ?>" name="Fullname" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Pengguna <small style="color: red;">* Wajib diisi</small></label>
                                <input type="text" class="form-control" value="<?= $row['username']; ?>" name="Username" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi <small style="color: red;">* Wajib diisi</small></label>
                                <input type="text" class="form-control" value="<?= $row['password']; ?>" name="Password" required>
                            </div>
                            <div class="form-group">
                                <label>Kelas <small style="color: red;">* Wajib diisi</small></label>
                                <select class="form-control" name="Kelas" required>
                                    <?php
                                    if (empty($row['kelas'])) {
                                        echo "<option value='' selected disabled>Silahkan pilih kelas dari [" . $row['fullname'] . "]</option>";
                                    } else {
                                        echo "<option selected value='" . $row['kelas'] . "'>" . $row['kelas'] . " ( Dipilih Sebelumnya )</option>";
                                    }
                                    ?>
                                    <option disabled>------------------------------------------</option>
                                    <!-- X -->
                                    <option value="X - Administrasi Perkantoran">X - Administrasi Perkantoran</option>
                                    <option value="X - Farmasi">X - Farmasi</option>
                                    <option value="X - Perbankan">X - Perbankan</option>
                                    <option value="X - Rekayasa Perangkat Lunak">X - Rekayasa Perangkat Lunak</option>
                                    <option value="X - Tata Boga">X - Tata Boga</option>
                                    <option value="X - Teknik Kendaraan Ringan">X - Teknik Kendaraan Ringan</option>
                                    <option value="X - Teknik Komputer dan Jaringan">X - Teknik Komputer dan Jaringan</option>
                                    <option value="X - Teknik Sepeda Motor">X - Teknik Sepeda Motor</option>
                                    <!-- XI -->
                                    <option disabled>------------------------------------------</option>
                                    <option value="XI - Administrasi Perkantoran">XI - Administrasi Perkantoran</option>
                                    <option value="XI - Farmasi">XI - Farmasi</option>
                                    <option value="XI - Perbankan">XI - Perbankan</option>
                                    <option value="XI - Rekayasa Perangkat Lunak">XI - Rekayasa Perangkat Lunak</option>
                                    <option value="XI - Tata Boga">XI - Tata Boga</option>
                                    <option value="XI - Teknik Kendaraan Ringan">XI - Teknik Kendaraan Ringan</option>
                                    <option value="XI - Teknik Komputer dan Jaringan">XI - Teknik Komputer dan Jaringan</option>
                                    <option value="XI - Teknik Sepeda Motor">XI - Teknik Sepeda Motor</option>
                                    <!-- XII -->
                                    <option disabled>------------------------------------------</option>
                                    <option value="XII - Administrasi Perkantoran">XII - Administrasi Perkantoran</option>
                                    <option value="XII - Farmasi">XII - Farmasi</option>
                                    <option value="XII - Perbankan">XII - Perbankan</option>
                                    <option value="XII - Rekayasa Perangkat Lunak">XII - Rekayasa Perangkat Lunak</option>
                                    <option value="XII - Tata Boga">XII - Tata Boga</option>
                                    <option value="XII - Teknik Kendaraan Ringan">XII - Teknik Kendaraan Ringan</option>
                                    <option value="XII - Teknik Komputer dan Jaringan">XII - Teknik Komputer dan Jaringan</option>
                                    <option value="XII - Teknik Sepeda Motor">XII - Teknik Sepeda Motor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Alamat Lengkap <small style="color: red;">* Wajib diisi</small></label>
                                <textarea class="form-control" style="height: 80px; resize: none;" name="Alamat" required><?= $row['alamat']; ?></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Profil Saya</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Avatar -->
                        <?php 
                        $foto_display = !empty($row['foto']) && $row['foto'] != 'default-avatar.png' 
                            ? '../../assets/img/users/' . $row['foto'] 
                            : '../../assets/dist/img/avatar5.png';
                        ?>
                        <img src="<?= $foto_display; ?>" 
                             style="width: 125px; height: 125px; display: block; margin-left: auto; margin-right: auto; margin-top: -5px; margin-bottom: 15px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;" 
                             loop autoplay>
                        
                        <!-- Profile Information -->
                        <p style="font-weight: bold;">Kode Anggota : <?= $row['kode_user']; ?></p>
                        <p style="font-weight: bold;">NIS : <?= $row['nis']; ?></p>
                        <p style="font-weight: bold;">Nama Lengkap : <?= $row['fullname']; ?></p>
                        <p style="font-weight: bold;">Nama Pengguna : <?= $row['username']; ?></p>
                        <p style="font-weight: bold;">Kata Sandi : <?= $row['password']; ?></p>
                        <p style="font-weight: bold;">Kelas : <?= $row['kelas']; ?></p>
                        <p style="font-weight: bold;">Alamat Lengkap : <?= $row['alamat']; ?></p>
                        
                        <!-- Barcode Section -->
                        <div style="text-align: center; margin-top: 20px; padding: 15px; border: 2px dashed #ddd; background-color: #f9f9f9;">
                            <h4 style="font-family: 'Quicksand', sans-serif; font-weight: bold; margin-bottom: 10px;">Barcode Anggota</h4>
                            
                            <!-- Barcode Image menggunakan service online -->
                            <img id="barcode" src="https://barcode.tec-it.com/barcode.ashx?data=<?= $row['kode_user']; ?>&code=Code128&translate-esc=true" 
                                 style="max-width: 200px; height: 60px; margin: 10px 0;" alt="Barcode">
                            
                            <!-- Kode Anggota di bawah barcode -->
                            <p style="font-size: 14px; font-weight: bold; margin: 5px 0;"><?= $row['kode_user']; ?></p>
                        </div>
                        
                        <!-- Print Card Button -->
                        <div style="text-align: center; margin-top: 15px;">
                            <button onclick="cetakKartu()" class="btn btn-success btn-block" style="font-weight: bold;">
                                <i class="fa fa-print"></i> Cetak Kartu Anggota
                            </button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!-- Modal Cetak Kartu -->
<div class="modal fade" id="modalCetakKartu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Kartu Anggota Perpustakaan</h4>
            </div>
            <div class="modal-body" id="kartuAnggota">
                <!-- Konten Kartu -->
                <div style="border: 3px solid #3c8dbc; border-radius: 15px; padding: 20px; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); max-width: 400px; margin: 0 auto;">
                    <!-- Header Kartu -->
                    <div style="text-align: center; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px; margin-bottom: 15px;">
                        <h3 style="color: #3c8dbc; font-weight: bold; margin: 0; font-family: 'Quicksand', sans-serif;">KARTU ANGGOTA</h3>
                        <p style="color: #666; margin: 5px 0; font-size: 14px;">Perpustakaan Sekolah</p>
                    </div>
                    
                    <!-- Foto dan Info -->
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <img src="<?= $foto_display; ?>" 
                             style="width: 80px; height: 80px; border-radius: 10px; margin-right: 15px; border: 2px solid #3c8dbc; object-fit: cover;">
                        <div>
                            <p style="margin: 2px 0; font-size: 14px;"><strong>Nama:</strong> <?= $row['fullname']; ?></p>
                            <p style="margin: 2px 0; font-size: 14px;"><strong>NIS:</strong> <?= $row['nis']; ?></p>
                            <p style="margin: 2px 0; font-size: 14px;"><strong>Kelas:</strong> <?= $row['kelas']; ?></p>
                            <p style="margin: 2px 0; font-size: 14px;"><strong>Kode:</strong> <?= $row['kode_user']; ?></p>
                        </div>
                    </div>
                    
                    <!-- Barcode -->
                    <div style="text-align: center; background: white; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $row['kode_user']; ?>&code=Code128&translate-esc=true" 
                             style="max-width: 180px; height: 50px;" alt="Barcode">
                        <p style="font-size: 12px; font-weight: bold; margin: 5px 0;"><?= $row['kode_user']; ?></p>
                    </div>
                    
                    <!-- Footer -->
                    <div style="text-align: center; margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px;">
                        <p style="font-size: 11px; color: #666; margin: 0;">Valid untuk tahun akademik aktif</p>
                        <p style="font-size: 11px; color: #666; margin: 0;">Harap dibawa saat meminjam buku</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="printKartu()" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Script untuk Preview Image -->
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<!-- Script untuk Cetak Kartu -->
<script>
function cetakKartu() {
    $('#modalCetakKartu').modal('show');
}

function printKartu() {
    var printContents = document.getElementById('kartuAnggota').innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Cetak Kartu Anggota</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px;
                    background: white;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            ${printContents}
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
    $('#modalCetakKartu').modal('hide');
    
    // Reload scripts after print
    location.reload();
}
</script>

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

<!-- Pesan Gagal Edit -->
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
                text: 'Apakah anda yakin ingin menghapus data administrator ini ?',
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
                        text: 'Data administrator tersebut tetap aman !'
                    })
                }
            });
    })
</script>