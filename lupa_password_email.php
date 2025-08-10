<?php
session_start();
//------------------------------::::::::::::::::::::------------------------------\\
// Dibuat oleh FA Team di PT. Pacifica Raya Technology \\
//------------------------------::::::::::::::::::::------------------------------\\
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    include "config/koneksi.php";

    $sql = mysqli_query($koneksi, "SELECT * FROM identitas");
    $row1 = mysqli_fetch_assoc($sql);
    ?>
    <title>Lupa Password | <?= $row1['nama_app']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
    <!-- Icon -->
    <link rel="icon" type="icon" href="assets/dist/img/logo_app.png">
    <!-- Custom -->
    <link rel="stylesheet" href="assets/dist/css/custom.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/dist/css/toastr.min.css">
</head>

<body class="hold-transition login-page" style="font-family: 'Quicksand', sans-serif;">
    <div class="login-box">
        <div class="login-logo">
            <a href="masuk"><b><?= $row1['nama_app']; ?></b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body" style="border-radius: 10px;">
            <img src="assets/dist/img/logo_app.png" height="80px" width="80px" style="display: block; margin-left: auto; margin-right: auto; margin-top: -12px; margin-bottom: 5px;">
            
            <h3 class="text-center" style="margin-top: 0; margin-bottom: 20px;">Lupa Password Admin</h3>
            <p class="text-center" style="margin-bottom: 20px;">Masukkan username dan email admin untuk menerima link reset password</p>
            
            <form name="formLupaPassword" action="./function/Process.php?aksi=kirim_email_reset" method="POST" enctype="multipart/form-data">
                <div class="form-group has-feedback">
                    <label>Username Admin</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username admin" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <label>Email Admin</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email admin" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Kirim Link Reset Password</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center" style="margin-top: 20px;">
                <a href="masuk" class="text-center">Kembali ke Login</a>
            </div>

            <p style="text-align: center; font-size: 13px; margin-top: 20px;">Hak Cipta &copy; <?= date('Y'); ?> .<?= $row1['nama_app']; ?> by FA Team.</p>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Sweet Alert -->
    <script src="assets/dist/js/sweetalert.min.js"></script>
    <!-- Toastr -->
    <script src="assets/dist/js/toastr.min.js"></script>
    <script>
        <?php
        if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] <> '') {
            echo "swal({
                icon: 'success',
                title: 'Berhasil',
                text: '$_SESSION[berhasil]',
                buttons: false,
                timer: 3000
              })";
        }
        $_SESSION['berhasil'] = '';
        ?>
    </script>
    <script>
        <?php
        if (isset($_SESSION['gagal']) && $_SESSION['gagal'] <> '') {
            echo "swal({
                icon: 'error',
                title: 'Peringatan',
                text: '$_SESSION[gagal]',
                buttons: false,
                timer: 3000
              })";
        }
        $_SESSION['gagal'] = '';
        ?>
    </script>
</body>

</html>
