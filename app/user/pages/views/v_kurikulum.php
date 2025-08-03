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
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Kurikulum</span>
                        <span class="info-box-number">
                            <?php
                            include "../../config/koneksi.php";
                            $total_kurikulum = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kurikulum");
                            $total = mysqli_fetch_assoc($total_kurikulum);
                            echo $total['total'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Buku</span>
                        <span class="info-box-number">
                            <?php
                            $total_buku = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_buku) as total FROM buku_kurikulum");
                            $buku = mysqli_fetch_assoc($total_buku);
                            echo $buku['total'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kurikulum Aktif</span>
                        <span class="info-box-number">
                            <?php
                            $aktif = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_kurikulum) as total FROM buku_kurikulum");
                            $kurikulum_aktif = mysqli_fetch_assoc($aktif);
                            echo $kurikulum_aktif['total'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-archive"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kurikulum Kosong</span>
                        <span class="info-box-number">
                            <?php
                            $kosong = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kurikulum k WHERE NOT EXISTS (SELECT 1 FROM buku_kurikulum bk WHERE bk.id_kurikulum = k.id_kurikulum)");
                            $kurikulum_kosong = mysqli_fetch_assoc($kosong);
                            echo $kurikulum_kosong['total'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Daftar Kurikulum</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        // Query untuk mengambil data kurikulum dengan jumlah buku
                        $query = mysqli_query($koneksi, "SELECT k.*, 
                                                        (SELECT COUNT(*) FROM buku_kurikulum bk WHERE bk.id_kurikulum = k.id_kurikulum) as jumlah_buku 
                                                        FROM kurikulum k 
                                                        ORDER BY k.nama_kurikulum ASC");
                        
                        if (mysqli_num_rows($query) > 0) {
                        ?>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="8%">No</th>
                                            <th width="15%">Kode</th>
                                            <th width="40%">Nama Kurikulum</th>
                                            <th width="15%">Jumlah Buku</th>
                                            <th width="22%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <span class="label label-primary"><?= $row['kode_kurikulum']; ?></span>
                                                </td>
                                                <td>
                                                    <strong><?= $row['nama_kurikulum']; ?></strong>
                                                </td>
                                                <td>
                                                    <?php if ($row['jumlah_buku'] > 0) { ?>
                                                        <span class="badge bg-green"><?= $row['jumlah_buku']; ?> Buku</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-red">Belum ada buku</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['jumlah_buku'] > 0) { ?>
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetailKurikulum<?php echo $row['id_kurikulum']; ?>">
                                                            <i class="fa fa-search"></i> Lihat Buku
                                                        </button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-default btn-sm" disabled>
                                                            <i class="fa fa-ban"></i> Tidak ada buku
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> Informasi</h4>
                                <p>Belum ada data kurikulum yang tersedia.</p>
                            </div>
                        <?php
                        }
                        ?>
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

<!-- Modal Detail Kurikulum - Dibuat terpisah -->
<?php
// Reset query untuk modal
$query_modal = mysqli_query($koneksi, "SELECT k.*, 
                                      (SELECT COUNT(*) FROM buku_kurikulum bk WHERE bk.id_kurikulum = k.id_kurikulum) as jumlah_buku 
                                      FROM kurikulum k 
                                      ORDER BY k.nama_kurikulum ASC");

while ($kurikulum = mysqli_fetch_assoc($query_modal)) {
    if ($kurikulum['jumlah_buku'] > 0) {
?>
        <div class="modal fade" id="modalDetailKurikulum<?php echo $kurikulum['id_kurikulum']; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
                            <i class="fa fa-book"></i> Daftar Buku - <?= $kurikulum['nama_kurikulum']; ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Kode Kurikulum:</strong> <?= $kurikulum['kode_kurikulum']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total Buku:</strong> <?= $kurikulum['jumlah_buku']; ?> Buku</p>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="8%">No</th>
                                        <th width="37%">Judul Buku</th>
                                        <th width="25%">Pengarang</th>
                                        <th width="20%">Penerbit</th>
                                        <th width="10%">Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no_buku = 1;
                                    $query_buku = mysqli_query($koneksi, "SELECT bk.*, b.judul_buku, b.pengarang, b.penerbit_buku, b.tahun_terbit 
                                                                           FROM buku_kurikulum bk 
                                                                           JOIN buku b ON bk.id_buku = b.id_buku 
                                                                           WHERE bk.id_kurikulum = " . $kurikulum['id_kurikulum'] . " 
                                                                           ORDER BY b.judul_buku ASC");
                                    
                                    if (mysqli_num_rows($query_buku) > 0) {
                                        while ($buku = mysqli_fetch_assoc($query_buku)) {
                                    ?>
                                            <tr>
                                                <td><?= $no_buku++; ?></td>
                                                <td><strong><?= htmlspecialchars($buku['judul_buku']); ?></strong></td>
                                                <td><?= htmlspecialchars($buku['pengarang']); ?></td>
                                                <td><?= htmlspecialchars($buku['penerbit_buku']); ?></td>
                                                <td>
                                                    <span class="label label-info"><?= $buku['tahun_terbit']; ?></span>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <i class="fa fa-info-circle"></i> Belum ada buku dalam kurikulum ini
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-times"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<!-- Scripts -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    if ($.fn.DataTable) {
        $('#example1').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'responsive': true,
            'language': {
                'lengthMenu': 'Tampilkan _MENU_ entri',
                'zeroRecords': 'Tidak ada data yang ditemukan',
                'info': 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                'infoEmpty': 'Menampilkan 0 sampai 0 dari 0 entri',
                'infoFiltered': '(disaring dari _MAX_ total entri)',
                'search': 'Cari:',
                'paginate': {
                    'first': 'Pertama',
                    'last': 'Terakhir',
                    'next': 'Selanjutnya',
                    'previous': 'Sebelumnya'
                }
            }
        });
    }
});
</script>