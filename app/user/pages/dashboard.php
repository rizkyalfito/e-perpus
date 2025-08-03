<?php
// File: pages/content.php untuk dashboard user - VERSI YANG DIPERBAIKI
// Cek apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../config/koneksi.php";

// Cek apakah session tersedia dan valid
if (!isset($_SESSION['fullname']) || empty($_SESSION['fullname'])) {
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Dashboard</h1>
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

// Ambil data user yang sedang login
$fullname = $_SESSION['fullname'];

// Query dengan error handling - PERBAIKI untuk menangani kategori yang tidak valid
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
    $total_peminjaman = 0;
    $buku_dipinjam = 0;
    $buku_dikembalikan = 0;
} else {
    // Hitung statistik - DIPERBAIKI SESUAI DENGAN LOGIKA ADMIN
    $total_peminjaman = mysqli_num_rows($sql);
    
    // PERBAIKAN: Gunakan kondisi_buku_saat_dikembalikan seperti di admin
    $sql_dipinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname' AND kondisi_buku_saat_dikembalikan = ''");
    $buku_dipinjam = $sql_dipinjam ? mysqli_num_rows($sql_dipinjam) : 0;
    $buku_dikembalikan = $total_peminjaman - $buku_dipinjam;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Dashboard
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
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Welcome Box -->
        <div class="row">
            <div class="col-md-12">
                <div class="box" style=" border-radius: 0;">
                    <div class="box-body" style="text-align: center; padding: 30px;">
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: bold; margin-bottom: 5px;">
                            Haii
                        </h2>
                        <h3 style="font-family: 'Quicksand', sans-serif; font-weight: bold; margin-bottom: 15px; color: #3c8dbc;">
                            <?= htmlspecialchars($fullname); ?>
                        </h3>
                        <p style="font-size: 16px; margin-bottom: 5px;">
                            Selamat datang di Sistem Informasi Perpustakaan
                        </p>
                        <p style="font-size: 16px; font-weight: bold;">
                            MTs Negeri 1 Luwu
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= $total_peminjaman; ?></h3>
                        <p>Total Peminjaman</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= $buku_dipinjam; ?></h3>
                        <p>Sedang Dipinjam</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bookmark"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= $buku_dikembalikan; ?></h3>
                        <p>Sudah Dikembalikan</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Laporan Table -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="border: 2px solid #000; border-radius: 0;">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold; font-size: 18px;">
                            Data Laporan
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive" style="padding: 0;">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <thead style="background-color: #f4f4f4;">
                                <tr>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">No</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">No. Pinjam</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Judul Buku</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Kategori</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Tgl Pinjam</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Tgl Kembali</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Status</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Keterangan</th>
                                    <th style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 12px;">Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (isset($sql_error)) {
                                    // Tampilkan error jika ada masalah dengan query
                                    ?>
                                    <tr>
                                        <td colspan="9" style="border: 1px solid #000; text-align: center; color: #d9534f; padding: 20px;">
                                            <i class="fa fa-exclamation-triangle"></i> Error: <?= htmlspecialchars($sql_error); ?>
                                        </td>
                                    </tr>
                                    <?php
                                } elseif ($sql && mysqli_num_rows($sql) > 0) {
                                    // Reset pointer jika diperlukan
                                    mysqli_data_seek($sql, 0);
                                    while ($data = mysqli_fetch_array($sql)) {
                                        // PERBAIKAN: Tentukan status berdasarkan kondisi_buku_saat_dikembalikan seperti di admin
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
                                            
                                            // PERBAIKAN: Validasi data denda sebelum number_format
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
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $no++; ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $no_pinjam; ?></td>
                                            <td style="border: 1px solid #000; padding: 10px;"><?= htmlspecialchars($data['judul_buku']); ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;">
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
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $tgl_pinjam; ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $tgl_kembali; ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $status; ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $keterangan; ?></td>
                                            <td style="border: 1px solid #000; text-align: center; padding: 10px;"><?= $denda; ?></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="9" style="border: 1px solid #000; text-align: center; color: #999; font-style: italic; padding: 20px;">
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

<style>
/* Custom styling untuk dashboard yang mengikuti tema AdminLTE yang sudah ada */
.small-box {
    border-radius: 2px;
    position: relative;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.small-box > .inner {
    padding: 10px;
}

.small-box h3 {
    font-size: 38px;
    font-weight: bold;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}

.small-box p {
    font-size: 15px;
}

.small-box .icon {
    -webkit-transition: all .3s linear;
    -o-transition: all .3s linear;
    transition: all .3s linear;
    position: absolute;
    top: -10px;
    right: 10px;
    z-index: 0;
    font-size: 90px;
    color: rgba(0,0,0,0.15);
}

/* Background colors untuk small boxes */
.bg-aqua {
    background-color: #00c0ef !important;
    color: #fff !important;
}

.bg-yellow {
    background-color: #f39c12 !important;
    color: #fff !important;
}

.bg-green {
    background-color: #00a65a !important;
    color: #fff !important;
}

/* Label styling sesuai AdminLTE yang sudah ada */
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

/* Box styling yang konsisten dengan tema yang sudah ada */
.box {
    position: relative;
    background: #ffffff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.box.box-primary {
    border-top-color: #3c8dbc;
}

.box.box-info {
    border-top-color: #00c0ef;
}

.box.box-danger {
    border-top-color: #dd4b39;
}

.box.box-warning {
    border-top-color: #f39c12;
}

.box.box-success {
    border-top-color: #00a65a;
}

.box-header {
    color: #444;
    display: block;
    padding: 10px;
    position: relative;
}

.box-header.with-border {
    border-bottom: 1px solid #f4f4f4;
}

.box-title {
    font-size: 18px;
    margin: 0;
    line-height: 1;
}

.box-body {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    padding: 10px;
}

/* Custom untuk welcome box */
.welcome-box h2, .welcome-box h3 {
    color: #444;
}

.welcome-box p {
    color: #666;
}
</style>

<!-- Script untuk notifikasi menggunakan Toastr yang sudah ada -->
<?php if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] != '') { ?>
<script>
    $(document).ready(function() {
        toastr.success('<?= $_SESSION['berhasil']; ?>');
    });
</script>
<?php $_SESSION['berhasil'] = ''; } ?>

<?php if (isset($_SESSION['gagal']) && $_SESSION['gagal'] != '') { ?>
<script>
    $(document).ready(function() {
        toastr.error('<?= $_SESSION['gagal']; ?>');
    });
</script>
<?php $_SESSION['gagal'] = ''; } ?>