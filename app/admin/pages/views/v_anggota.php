<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Data Anggota
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
            <li class="active">Data Anggota</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Data Anggota</h3>
                        <div class="form-group m-b-2 text-right" style="margin-top: -20px; margin-bottom: -5px;">
                            <button type="button" onclick="tambahAnggota()" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Anggota</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Anggota</th>
                                    <th>NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>Kelas</th>
                                    <th>Alamat</th>
                                    <th>Barcode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../../config/koneksi.php";

                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM user WHERE role = 'Anggota'");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    // Query untuk mendapatkan foto anggota
                                    $foto_anggota = $row['foto'];
                                    if (!empty($foto_anggota) && $foto_anggota != 'default-avatar.png' && file_exists('../../assets/img/users/' . $foto_anggota)) {
                                        $foto_path_anggota = '../../assets/img/users/' . $foto_anggota;
                                    } else {
                                        $foto_path_anggota = '../../assets/dist/img/avatar5.png';
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $row['kode_user']; ?></td>
                                        <td><?php echo $row['nis']; ?></td>
                                        <td><?php echo $row['fullname']; ?></td>
                                        <td><?php echo $row['kelas']; ?></td>
                                        <td><?php echo $row['alamat']; ?></td>
                                        <td style="text-align: center;">
                                            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $row['kode_user']; ?>&code=Code128&translate-esc=true" 
                                                 style="max-width: 100px; height: 30px;" alt="Barcode">
                                            <br>
                                            <small><?= $row['kode_user']; ?></small>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEditAnggota<?php echo $row['id_user']; ?>"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="btn btn-success btn-sm" onclick="lihatKartu('<?php echo $row['id_user']; ?>')"><i class="fa fa-id-card"></i></a>
                                            <a href="pages/function/Anggota.php?aksi=hapus&id=<?= $row['id_user']; ?>" class="btn btn-danger btn-sm btn-del"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal Edit Anggota -->
                                    <div class="modal fade" id="modalEditAnggota<?php echo $row['id_user']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                                                        Edit Anggota ( <?= $row['kode_user']; ?> - <?= $row['fullname']; ?> )
                                                    </h4>
                                                </div>
                                                <form action="pages/function/Anggota.php?aksi=edit" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" value="<?= $row['id_user']; ?>" name="idUser">
                                                        <div class="form-group">
                                                            <label>Kode Anggota <small style="color: red;">* Otomatis Terisi ( Tidak dapat diubah )</small></label>
                                                            <input type="text" class="form-control" value="<?= $row['kode_user'] ?>" name="kodeAnggota" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nomor Induk Siswa </label>
                                                            <input type="number" class="form-control" value="<?= $row['nis']; ?>" name="nis">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Lengkap </label>
                                                            <input type="text" class="form-control" value="<?= $row['fullname']; ?>" name="namaLengkap">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Pengguna </label>
                                                            <input type="text" class="form-control" value="<?= $row['username']; ?>" name="uSername">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kata Sandi </label>
                                                            <input type="text" class="form-control" value="<?= $row['password']; ?>" name="pAssword">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kelas <small style="color: red;">* Wajib diisi</small></label>
                                                            <select class="form-control" name="kElas" required>
                                                                <?php
                                                                if ($row['kelas'] == null || $row['kelas'] == '') {
                                                                    echo "<option value='' selected disabled>Silahkan pilih kelas dari [" . $row['fullname'] . "]</option>";
                                                                } else {
                                                                    echo "<option selected value='" . $row['kelas'] . "'>" . $row['kelas'] . " ( Dipilih Sebelumnya )</option>";
                                                                }
                                                                ?>
                                                                <option disabled>------------------------------------------</option>
                                                                <!-- VII -->
                                                                <option value="VII a">VII a</option>
                                                                <option value="VII b">VII b</option>
                                                                <option value="VII c">VII c</option>
                                                                <option value="VII d">VII d</option>
                                                                <option value="VII e">VII e</option>
                                                                <option value="VII f">VII f</option>
                                                                <option value="VII g">VII g</option>
                                                                <option value="VII h">VII h</option>
                                                                <!-- VIII -->
                                                                <option disabled>------------------------------------------</option>
                                                                <option value="VIII a">VIII a</option>
                                                                <option value="VIII b">VIII b</option>
                                                                <option value="VIII c">VIII c</option>
                                                                <option value="VIII d">VIII d</option>
                                                                <option value="VIII e">VIII e</option>
                                                                <option value="VIII f">VIII f</option>
                                                                <option value="VIII g">VIII g</option>
                                                                <option value="VIII h">VIII h</option>
                                                                <!-- IX -->
                                                                <option disabled>------------------------------------------</option>
                                                                <option value="IX a">IX a</option>
                                                                <option value="IX b">IX b</option>
                                                                <option value="IX c">IX c</option>
                                                                <option value="IX d">IX d</option>
                                                                <option value="IX e">IX e</option>
                                                                <option value="IX f">IX f</option>
                                                                <option value="IX g">IX g</option>
                                                                <option value="IX h">IX h</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Alamat </label>
                                                            <textarea class="form-control" style="resize: none; height: 70px;" name="aLamat"><?= $row['alamat']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /. Modal Edit Anggota-->

                                    <!-- Modal Lihat Kartu Anggota -->
                                    <div class="modal fade" id="modalKartuAnggota<?php echo $row['id_user']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                                                        Kartu Anggota - <?= $row['fullname']; ?>
                                                    </h4>
                                                </div>
                                                <div class="modal-body" id="kartuAnggota<?php echo $row['id_user']; ?>">
                                                    <!-- Konten Kartu -->
                                                    <div style="border: 3px solid #3c8dbc; border-radius: 15px; padding: 20px; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); max-width: 400px; margin: 0 auto;">
                                                        <!-- Header Kartu -->
                                                        <div style="text-align: center; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px; margin-bottom: 15px;">
                                                            <h3 style="color: #3c8dbc; font-weight: bold; margin: 0; font-family: 'Quicksand', sans-serif;">KARTU ANGGOTA</h3>
                                                            <p style="color: #666; margin: 5px 0; font-size: 14px;">Perpustakaan Sekolah</p>
                                                        </div>
                                                        
                                                        <!-- Foto dan Info -->
                                                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                                            <img src="<?= $foto_path_anggota; ?>" style="width: 80px; height: 80px; border-radius: 10px; margin-right: 15px; border: 2px solid #3c8dbc; object-fit: cover;">
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
                                                    <button type="button" onclick="printKartuAnggota('<?php echo $row['id_user']; ?>')" class="btn btn-primary">
                                                        <i class="fa fa-print"></i> Print Kartu
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /. Modal Kartu Anggota-->
                                <?php
                                }
                                ?>
                            </tbody>
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

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Anggota</h4>
            </div>
            <form action="pages/function/Anggota.php?aksi=tambah" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Anggota <small style="color: red;">* Otomatis Terisi</small></label>
                        <?php
                        // Koneksi untuk modal tambah anggota
                        include "../../config/koneksi.php";

                        $query_kode = mysqli_query($koneksi, "SELECT max(kode_user) as kodeTerakhir FROM user");
                        $data_kode = mysqli_fetch_array($query_kode);
                        $kodeTerakhir = $data_kode['kodeTerakhir'];

                        // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
                        // dan diubah ke integer dengan (int)
                        $urutan = (int) substr($kodeTerakhir, 2, 3);

                        // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
                        $urutan++;

                        // membentuk kode barang baru
                        // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
                        // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
                        // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
                        $huruf = "AP";
                        $Anggota = $huruf . sprintf("%03s", $urutan);
                        ?>
                        <input type="text" class="form-control" value="<?php echo $Anggota ?>" name="kodeAnggota" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nomor Induk Siswa <small style="color: red;">* Wajib diisi</small></label>
                        <input type="number" class="form-control" placeholder="Masukan NIS" name="nis" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Lengkap" name="namaLengkap" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Pengguna <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Pengguna" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukan Kata Sandi" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Kelas <small style="color: red;">* Wajib diisi</small></label>
                        <select class="form-control" name="kElas" required>
                            <option value="" selected disabled>-- Pilih Kelas --</option>
                            <option disabled>------------------------------------------</option>
                            <!-- VII -->
                            <option value="VII a">VII a</option>
                            <option value="VII b">VII b</option>
                            <option value="VII c">VII c</option>
                            <option value="VII d">VII d</option>
                            <option value="VII e">VII e</option>
                            <option value="VII f">VII f</option>
                            <option value="VII g">VII g</option>
                            <option value="VII h">VII h</option>
                            <!-- VIII -->
                            <option disabled>------------------------------------------</option>
                            <option value="VIII a">VIII a</option>
                            <option value="VIII b">VIII b</option>
                            <option value="VIII c">VIII c</option>
                            <option value="VIII d">VIII d</option>
                            <option value="VIII e">VIII e</option>
                            <option value="VIII f">VIII f</option>
                            <option value="VIII g">VIII g</option>
                            <option value="VIII h">VIII h</option>
                            <!-- IX -->
                            <option disabled>------------------------------------------</option>
                            <option value="IX a">IX a</option>
                            <option value="IX b">IX b</option>
                            <option value="IX c">IX c</option>
                            <option value="IX d">IX d</option>
                            <option value="IX e">IX e</option>
                            <option value="IX f">IX f</option>
                            <option value="IX g">IX g</option>
                            <option value="IX h">IX h</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Alamat <small style="color: red;">* Wajib diisi</small></label>
                        <textarea class="form-control" style="resize: none; height: 70px;" name="alamat" placeholder="Masukan alamat lengkap" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>

<!-- Scripts -->
<script>
    function tambahAnggota() {
        $('#modalTambahAnggota').modal('show');
    }

    function lihatKartu(idUser) {
        $('#modalKartuAnggota' + idUser).modal('show');
    }

    function printKartuAnggota(idUser) {
        var printContents = document.getElementById('kartuAnggota' + idUser).innerHTML;
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
        $('#modalKartuAnggota' + idUser).modal('hide');
        
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
                text: 'Apakah anda yakin ingin menghapus data anggota ini ?',
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
                        text: 'Data anggota tersebut aman !'
                    })
                }
            });
    })
</script>