<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                // Query untuk mendapatkan foto user dari database
                $id_user = $_SESSION['id_user'];
                $query_foto = mysqli_query($koneksi, "SELECT foto FROM user WHERE id_user = '$id_user'");
                $data_foto = mysqli_fetch_assoc($query_foto);
                
                if (!empty($data_foto['foto']) && $data_foto['foto'] != 'default-avatar.png' && file_exists('../../assets/img/users/' . $data_foto['foto'])) {
                    $foto_path = '../../assets/img/users/' . $data_foto['foto'];
                } else {
                    $foto_path = '../../assets/dist/img/avatar5.png';
                }
                ?>
                <img src="<?= $foto_path; ?>" class="img-circle" alt="User Image" style="width: 45px; height: 45px; object-fit: cover;">
            </div>
            <div class="pull-left info">
                <p><?= $_SESSION['fullname']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN MENU</li>
            <li><a href="dashboard"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="peminjaman"><i class="fa fa-file"></i> <span>Data Peminjaman</span></a></li>
            <li><a href="data-buku"><i class="fa fa-file"></i> <span>Data Buku</span></a></li>
            <li><a href="profil-saya"><i class="fa fa-user"></i> <span>Cetak Kartu Anggota</span></a></li>
            <li class="header">LANJUTAN</li>
            <li><a href="#Logout" data-toggle="modal" data-target="#modalLogoutConfirm"><i class="fa fa-sign-out"></i> <span>Keluar</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<div class="modal fade" id="modalLogoutConfirm">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family: 'Quicksand', sans-serif; font-weight: bold;">Peringatan</h4>
            </div>
            <div class="modal-body">
                <span>Apa anda yakin ingin keluar dari Applikasi ? <br>
                    Anda harus login kembali jika ingin masuk Applikasi Perpustakaan</span>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Batal</button>
                <a href="keluar" class="btn btn-primary">Iya, Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
    var refreshId = setInterval(function() {
        $('#jumlahPesan').load('./pages/function/Pesan.php?aksi=jumlahPesan');
    }, 500);
</script>