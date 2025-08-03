<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Kurikulum
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
                    //
                </script>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Katalog Buku</li>
            <li class="active">Kurikulum</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Kurikulum</h3>
                        <div class="form-group m-b-2 text-right" style="margin-top: -20px; margin-bottom: -5px;">
                            <button type="button" onclick="tambahKurikulum()" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Kurikulum</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kurikulum</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php
                            include "../../config/koneksi.php";

                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM kurikulum");
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tbody>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['nama_kurikulum']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEditKurikulum<?php echo $row['id_kurikulum']; ?>"><i class="fa fa-edit"></i></a>
                                            <a href="pages/function/Kurikulum.php?act=hapus&id=<?= $row['id_kurikulum']; ?>" class="btn btn-danger btn-sm btn-del" onclick="hapusKurikulum()"><i class="fa fa-trash"></i></a>
                                            <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambahBuku<?php echo $row['id_kurikulum']; ?>"><i class="fa fa-book"></i> Tambah Buku</a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modalEditKurikulum<?php echo $row['id_kurikulum']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Edit Kurikulum ( <?= $row['kode_kurikulum']; ?> - <?= $row['nama_kurikulum']; ?> )</h4>
                                                </div>
                                                <form action="pages/function/Kurikulum.php?act=edit" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" value="<?= $row['id_kurikulum']; ?>" name="idKurikulum">
                                                        <div class="form-group">
                                                            <label>Kode Kurikulum</label>
                                                            <input type="text" class="form-control" value="<?= $row['kode_kurikulum']; ?>" name="kodeKurikulum" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Kurikulum <small style="color: red;">* Wajib diisi</small></label>
                                                            <input type="text" class="form-control" value="<?= $row['nama_kurikulum']; ?>" name="namaKurikulum">
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

                                    <!-- Modal Tambah Buku -->
                                    <div class="modal fade" id="modalTambahBuku<?php echo $row['id_kurikulum']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Buku ke Kurikulum ( <?= $row['nama_kurikulum']; ?> )</h4>
                                                </div>
                                                <form action="pages/function/Kurikulum.php?act=tambah_buku" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" value="<?= $row['id_kurikulum']; ?>" name="idKurikulum">
                                                        <div class="form-group">
                                                            <label>Pilih Buku <small style="color: red;">* Wajib diisi</small></label>
                                                            <select class="form-control" name="idBuku" required>
                                                                <option value="" disabled selected>-- Pilih Buku --</option>
                                                                <?php
                                                                include "../../config/koneksi.php";
                                                                $sql = mysqli_query($koneksi, "SELECT * FROM buku");
                                                                while ($data = mysqli_fetch_array($sql)) {
                                                                ?>
                                                                    <option value="<?= $data['id_buku']; ?>"> <?= $data['judul_buku']; ?> - <?= $data['pengarang']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
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

                                    <!-- Daftar Buku dalam Kurikulum -->
                                    <tr>
                                        <td colspan="3">
                                            <div class="box box-success collapsed-box">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Daftar Buku (<?= $row['nama_kurikulum']; ?>)</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Judul Buku</th>
                                                                <th>Pengarang</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no_buku = 1;
                                                            $query_buku = mysqli_query($koneksi, "SELECT bk.*, b.judul_buku, b.pengarang FROM buku_kurikulum bk JOIN buku b ON bk.id_buku = b.id_buku WHERE bk.id_kurikulum = " . $row['id_kurikulum']);
                                                            while ($row_buku = mysqli_fetch_assoc($query_buku)) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $no_buku++; ?></td>
                                                                    <td><?= $row_buku['judul_buku']; ?></td>
                                                                    <td><?= $row_buku['pengarang']; ?></td>
                                                                    <td>
                                                                        <a href="pages/function/Kurikulum.php?act=hapus_buku&id=<?= $row_buku['id_buku_kurikulum']; ?>" class="btn btn-danger btn-xs btn-del-buku"><i class="fa fa-trash"></i> Hapus</a>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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

<!-- Modal Tambah Kurikulum -->
<form action="pages/function/Kurikulum.php?act=tambah" enctype="multipart/form-data" method="POST">
    <div class="modal fade" id="modalTambahKurikulum">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Tambah Kurikulum</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Kurikulum</label>
                        <?php
                        include "../../config/koneksi.php";

                        $query = mysqli_query($koneksi, "SELECT max(kode_kurikulum) as kodeKurikulumTerakhirDB FROM kurikulum");
                        $data = mysqli_fetch_array($query);
                        $kodeKurikulumTerakhir = $data['kodeKurikulumTerakhirDB'];

                        // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
                        // dan diubah ke integer dengan (int)
                        $urutan = (int) substr($kodeKurikulumTerakhir, 3, 3);

                        // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
                        $urutan++;

                        // membentuk kode barang baru
                        // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
                        // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
                        // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
                        $huruf = "KK-";
                        $kodeKurikulum = $huruf . sprintf("%03s", $urutan);
                        ?>
                        <input type="text" class="form-control" value="<?= $kodeKurikulum; ?>" name="kodeKurikulum" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Kurikulum <small style="color: red;">* Wajib diisi</small></label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Kurikulum" name="namaKurikulum" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</form>
<script>
    function tambahKurikulum() {
        $('#modalTambahKurikulum').modal('show');
    }

    function hapusKurikulum() {
        $('.btn-del').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href')

            swal({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Apakah anda yakin ingin menghapus data kurikulum ini ?',
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
                            text: 'Data kurikulum tersebut aman !'
                        })
                    }
                });
        })
    }

    function hapusBuku() {
        $('.btn-del-buku').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href')

            swal({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Apakah anda yakin ingin menghapus buku dari kurikulum ini ?',
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
                            text: 'Buku tetap dalam kurikulum !'
                        })
                    }
                });
        })
    }

    $(document).ready(function() {
        hapusKurikulum();
        hapusBuku();
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
