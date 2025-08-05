<?php
// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../../config/koneksi.php";

// Cek apakah session tersedia dan valid
if (!isset($_SESSION['fullname']) || empty($_SESSION['fullname'])) {
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Data Pengembalian Buku</h1>
        </section>
        <section class="content">
            <div class="alert alert-danger">
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                Session tidak valid. Silakan login kembali.
            </div>
        </section>
    </div>
    <?php
    return;
}

// Ambil data peminjaman untuk user yang sedang login dengan error handling
$fullname = $_SESSION['fullname'];
$sql = mysqli_query($koneksi, "SELECT p.*, 
                               CASE 
                                   WHEN b.kategori_buku IS NULL OR b.kategori_buku = '' OR b.kategori_buku = '-- Harap pilih kategori buku --' 
                                   THEN 'Belum Dikategorikan' 
                                   ELSE b.kategori_buku 
                               END as kategori_buku
                               FROM peminjaman p 
                               LEFT JOIN buku b ON p.judul_buku = b.judul_buku 
                               WHERE p.nama_anggota = '$fullname' 
                               ORDER BY p.id_peminjaman DESC");

if (!$sql) {
    $sql_error = mysqli_error($koneksi);
}
?>

<!-- TAMBAHKAN CSS DATATABLES -->
<link rel="stylesheet" href="../../assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Data Pengembalian Buku
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
            <li class="active">Data Pengembalian Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Data Pengembalian Buku</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Pinjam</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (isset($sql_error)) {
                                    // Tampilkan error jika ada masalah dengan query
                                    ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; color: #d9534f; padding: 20px;">
                                            <i class="fa fa-exclamation-triangle"></i> Error: <?= htmlspecialchars($sql_error); ?>
                                        </td>
                                    </tr>
                                    <?php
                                } elseif ($sql && mysqli_num_rows($sql) > 0) {
                                    while ($data = mysqli_fetch_array($sql)) {
                                        // PERBAIKAN LOGIKA: Tentukan status berdasarkan kondisi_buku_saat_dikembalikan (SAMA SEPERTI ADMIN)
                                        if (empty($data['kondisi_buku_saat_dikembalikan'])) {
                                            $status = '<span class="label label-warning">Dipinjam</span>';
                                            $keterangan = '-';
                                            $denda = '-';
                                            // Tampilkan tanggal harus kembali dari tanggal_pengembalian
                                            try {
                                                $tgl_kembali = date('d-m-Y', strtotime(str_replace('-', '/', $data['tanggal_pengembalian'])));
                                            } catch (Exception $e) {
                                                $tgl_kembali = $data['tanggal_pengembalian'];
                                            }
                                        } else {
                                            $status = '<span class="label label-success">Dikembalikan</span>';
                                            $keterangan = $data['kondisi_buku_saat_dikembalikan'];
                                            
                                            // PERBAIKAN: Validasi data denda sebelum format
                                            if (!empty($data['denda']) && is_numeric($data['denda'])) {
                                                $denda = 'Rp ' . number_format($data['denda'], 0, ',', '.');
                                            } else {
                                                $denda = 'Rp 0';
                                            }
                                            
                                            // Untuk yang sudah dikembalikan, tampilkan tanggal aktual pengembalian
                                            $tgl_kembali = date('d-m-Y', strtotime(str_replace('-', '/', $data['tanggal_pengembalian'])));
                                        }
                                        
                                        // Format tanggal peminjaman
                                        try {
                                            $tgl_pinjam = date('d-m-Y', strtotime(str_replace('-', '/', $data['tanggal_peminjaman'])));
                                        } catch (Exception $e) {
                                            $tgl_pinjam = $data['tanggal_peminjaman'];
                                        }
                                        
                                        // Format nomor peminjaman
                                        $no_pinjam = sprintf("PJ%03d", $data['id_peminjaman']);
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $no_pinjam; ?></td>
                                            <td><?= htmlspecialchars($data['judul_buku']); ?></td>
                                            <td>
                                                <?php 
                                                // PERBAIKAN: Handle kategori yang tidak valid
                                                $kategori = $data['kategori_buku'];
                                                if (empty($kategori) || $kategori == '-- Harap pilih kategori buku --') {
                                                    echo '<span style="color: #999; font-style: italic;">Belum Dikategorikan</span>';
                                                } else {
                                                    echo htmlspecialchars($kategori);
                                                }
                                                ?>
                                            </td>
                                            <td><?= $tgl_pinjam; ?></td>
                                            <td><?= $tgl_kembali; ?></td>
                                            <td style="text-align: center;"><?= $status; ?></td>
                                            <td style="text-align: center;"><?= $keterangan; ?></td>
                                            <td style="text-align: center;"><?= $denda; ?></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; color: #999; font-style: italic; padding: 20px;">
                                            <i class="fa fa-info-circle"></i> Belum ada data peminjaman
                                        </td>
                                    </tr>
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

<!-- PASTIKAN JQUERY DIMUAT TERLEBIH DAHULU -->
<!-- Jika belum ada, tambahkan: -->
<!-- <script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script> -->

<!-- DataTables JavaScript -->
<script src="../../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
$(function () {
    $('#example1').DataTable({
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "pageLength": 10,
        "responsive": true
    });
});
</script>

<style>
/* Custom styling untuk konsistensi dengan desain */
.content-wrapper {
    background-color: #ecf0f5;
}

.box {
    border-top: 3px solid #3c8dbc;
}

.box-header {
    border-bottom: 1px solid #f4f4f4;
    color: #444;
    display: block;
    padding: 10px;
    position: relative;
}

.box-title {
    font-size: 18px;
    margin: 0;
    line-height: 1;
}

.table > thead > tr > th {
    background-color: #f4f4f4;
    font-weight: 600;
}

.label {
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
    padding: .2em .6em .3em;
    display: inline;
}

.label-warning {
    background-color: #f0ad4e;
}

.label-success {
    background-color: #5cb85c;
}

/* Custom breadcrumb styling */
.breadcrumb {
    background: rgba(255,255,255,0.85);
    border-radius: 3px;
    font-size: 12px;
    padding: 8px 15px;
    margin: 0 0 20px 0;
}

.breadcrumb > li + li:before {
    content: ">\00a0";
    padding: 0 5px;
    color: #ccc;
}

.breadcrumb > .active {
    color: #777;
}
</style>

<!-- Pesan jika ada notifikasi -->
<?php if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] != '') { ?>
<script>
    $(document).ready(function() {
        swal({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= $_SESSION['berhasil']; ?>'
        });
    });
</script>
<?php $_SESSION['berhasil'] = ''; } ?>

<?php if (isset($_SESSION['gagal']) && $_SESSION['gagal'] != '') { ?>
<script>
    $(document).ready(function() {
        swal({
            icon: 'error',
            title: 'Gagal',
            text: '<?= $_SESSION['gagal']; ?>'
        });
    });
</script>
<?php $_SESSION['gagal'] = ''; } ?>