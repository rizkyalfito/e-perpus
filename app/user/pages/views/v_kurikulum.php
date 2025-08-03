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
                </script>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Kurikulum</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Daftar Kurikulum</h3>
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
                            <tbody>
                                <?php
                                include "../../config/koneksi.php";

                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM kurikulum");
                                while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['nama_kurikulum']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalDetailKurikulum<?php echo $row['id_kurikulum']; ?>"><i class="fa fa-search"></i> Lihat Buku</a>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Kurikulum -->
                                    <div class="modal fade" id="modalDetailKurikulum<?php echo $row['id_kurikulum']; ?>">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Daftar Buku ( <?= $row['nama_kurikulum']; ?> )</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Judul Buku</th>
                                                                <th>Pengarang</th>
                                                                <th>Penerbit</th>
                                                                <th>Tahun Terbit</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no_buku = 1;
                                                            $query_buku = mysqli_query($koneksi, "SELECT bk.*, b.judul_buku, b.pengarang, b.penerbit_buku, b.tahun_terbit FROM buku_kurikulum bk JOIN buku b ON bk.id_buku = b.id_buku WHERE bk.id_kurikulum = " . $row['id_kurikulum']);
                                                            while ($row_buku = mysqli_fetch_assoc($query_buku)) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $no_buku++; ?></td>
                                                                    <td><?= $row_buku['judul_buku']; ?></td>
                                                                    <td><?= $row_buku['pengarang']; ?></td>
                                                                    <td><?= $row_buku['penerbit_buku']; ?></td>
                                                                    <td><?= $row_buku['tahun_terbit']; ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
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
<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
