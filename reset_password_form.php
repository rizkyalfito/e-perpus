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
    <title>Reset Password Admin | <?= $row1['nama_app']; ?></title>
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
            
            <h3 class="text-center" style="margin-top: 0; margin-bottom: 20px;">Reset Password Admin</h3>
            
            <?php
            // Validasi token
            if (isset($_GET['token']) && isset($_GET['username'])) {
                $token = mysqli_real_escape_string($koneksi, $_GET['token']);
                $username = mysqli_real_escape_string($koneksi, $_GET['username']);
                
                // Cek token valid
                $cek_token = mysqli_query($koneksi, "SELECT * FROM password_reset_tokens 
                                                    WHERE token = '$token' 
                                                    AND username = '$username' 
                                                    AND used = 0 
                                                    AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
                
                if (mysqli_num_rows($cek_token) > 0) {
                    $data_token = mysqli_fetch_assoc($cek_token);
                    ?>
                    <form name="formResetPassword" action="function/Process.php?aksi=reset_password" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <input type="hidden" name="username" value="<?= $username ?>">
                        
                        <div class="form-group has-feedback">
                            <label>Password Baru</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Masukkan password baru" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        
                        <div class="form-group has-feedback">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasi password baru" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
                            </div>
                        </div>
                    </form>
                    <?php
                } else {
                    echo '<div class="alert alert-danger text-center">
                            <strong>Token tidak valid atau sudah kadaluarsa!</strong><br>
                            Silakan kembali ke halaman <a href="lupa_password_email.php">lupa password</a>
                          </div>';
                }
            } else {
                echo '<div class="alert alert-danger text-center">
                        <strong>Parameter tidak valid!</strong><br>
                        Silakan kembali ke halaman <a href="lupa_password_email.php">lupa password</a>
                      </div>';
            }
            ?>

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
        function validateForm() {
            var newPassword = document.forms["formResetPassword"]["new_password"].value;
            var confirmPassword = document.forms["formResetPassword"]["confirm_password"].value;
            
            if (newPassword != confirmPassword) {
                toastr.error("Password baru dan konfirmasi password tidak cocok!");
                return false;
            }
            
            if (newPassword.length < 3) {
                toastr.error("Password minimal 3 karakter!");
                return false;
            }
        }
    </script>
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
