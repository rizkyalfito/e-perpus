<header class="main-header">
    <!-- Logo -->
    <a href="dashboard" class="logo" style="font-family: 'Quicksand', sans-serif">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <div style="width: 50px; margin: 0 auto;">
            <img src="../../assets/dist/img/mtsn1-luwu.png" class="img-circle" alt="User Image" style="width: 50px; height:auto">
        </div>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="../../assets/#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
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
                        <img src="<?= $foto_path; ?>" class="user-image" alt="User Image" style="object-fit: cover;">

                        <span class="hidden-xs"><?= $_SESSION['fullname']; ?>
                            <?php include "../../config/koneksi.php";
                            $id = $_SESSION['id_user'];
                            $query_verif = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id'");
                            $row = mysqli_fetch_array($query_verif);

                            if ($row['verif'] == "Iya") {
                                echo "<i class='fa fa-check-circle' title='Akun Terverifikasi' data-toggle='tooltip' data-placement='bottom'></i>";
                            } else {
                                //
                            }
                            ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $foto_path; ?>" class="img-circle" alt="User Image" style="object-fit: cover;">

                            <p>
                                <?= $_SESSION['fullname']; ?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
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
                        <img src="<?= $foto_path; ?>" class="user-image" alt="User Image" style="object-fit: cover;">

                        <span class="hidden-xs"><?= $_SESSION['fullname']; ?>
                            <?php include "../../config/koneksi.php";
                            $id = $_SESSION['id_user'];
                            $query_verif = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id'");
                            $row = mysqli_fetch_array($query_verif);

                            if ($row['verif'] == "Iya") {
                                echo "<i class='fa fa-check-circle' title='Akun Terverifikasi' data-toggle='tooltip' data-placement='bottom'></i>";
                            } else {
                                //
                            }
                            ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $foto_path; ?>" class="img-circle" alt="User Image" style="object-fit: cover;">

                            <p>
                                <?= $_SESSION['fullname']; ?>
                                <!-- -->
                                <?php
                                include "../../config/koneksi.php";

                                $id = $_SESSION['id_user'];
                                $query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id'");
                                while ($row = mysqli_fetch_array($query)) {
                                ?>
                                    <small>Tanggal Bergabung : <?= $row['join_date']; ?></small>
                                    <small>Terakhir Login : <?= $row['terakhir_login']; ?></small>
                                <?php
                                }
                                ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="#Logout" data-toggle="modal" data-target="#modalLogoutConfirm" class="btn btn-default btn-flat">Keluar</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<script>
    var refreshId = setInterval(function() {
        $('#badgePesan').load('./pages/function/Pesan.php?aksi=badgePesan');
    }, 500);
</script>
<script>
    var refreshId = setInterval(function() {
        $('#Pesan').load('./pages/function/Pesan.php?aksi=Pesan');
    }, 500);
</script>