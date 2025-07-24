<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Tambah Pengembalian Buku
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
            <li class="active">Tambah Pengembalian</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Formulir Tambah Pengembalian Buku</h3>
                    </div>
                    <!-- /.box-header -->
                    <form action="function/Peminjaman.php?aksi=pengembalian" method="POST">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Judul Buku</label>
                                <select class="form-control" name="judulBuku" required>
                                    <option selected disabled>-- Pilih Buku --</option>
                                    <?php
                                    include "../../config/koneksi.php";
                                    $sql = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE tanggal_pengembalian = ''");
                                    while ($data = mysqli_fetch_array($sql)) {
                                    ?>
                                        <option value="<?= $data['judul_buku']; ?>"><?= $data['judul_buku']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengembalian</label>
                                <input type="text" class="form-control" name="tanggalPengembalian" value="<?= date('d-m-Y'); ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi Buku Saat Dikembalikan</label>
                                <select class="form-control" name="kondisiBukuSaatDikembalikan" required>
                                    <option selected disabled>-- Pilih Kondisi Buku --</option>
                                    <option value="Baik">Baik ( Tidak terkena denda )</option>
                                    <option value="Rusak">Rusak ( Denda 20.000 )</option>
                                    <option value="Hilang">Hilang ( Denda 50.000 )</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
