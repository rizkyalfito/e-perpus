<?php
session_start();
//------------------------------::::::::::::::::::::------------------------------\\
// Dibuat oleh FA Team di PT. Pacifica Raya Technology \\
//------------------------------::::::::::::::::::::------------------------------\\
include "../config/koneksi.php";

// Cek apakah file email_config.php ada sebelum include
if (file_exists("../config/email_config.php")) {
    include "../config/email_config.php";
} else {
    error_log("Email config file not found");
}

if ($_GET['aksi'] == "masuk") {
    // Kode login yang sudah ada...
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $data = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");
    $cek = mysqli_num_rows($data);

    if ($cek > 0) {
        $row = mysqli_fetch_assoc($data);

        if ($row['role'] == "Admin") {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['status'] = "Login";
            $_SESSION['level'] = "Admin";

            date_default_timezone_set('Asia/Jakarta');
            $id_user = $_SESSION['id_user'];
            $tanggal = date('d-m-Y');
            $jam = date('H:i:s');

            $query = "UPDATE user SET terakhir_login = '$tanggal ( $jam )' WHERE id_user = $id_user";
            mysqli_query($koneksi, $query);

            header("location: ../admin");
        } else if ($row['role'] == "Anggota") {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['level'] = "Anggota";
            $_SESSION['status'] = "Login";

            date_default_timezone_set('Asia/Jakarta');
            $id_user = $_SESSION['id_user'];
            $tanggal = date('d-m-Y');
            $jam = date('H:i:s');

            $query = "UPDATE user SET terakhir_login = '$tanggal ( $jam )' WHERE id_user = $id_user";
            mysqli_query($koneksi, $query);

            header("location: ../user");
        } else {
            $_SESSION['user_tidak_terdaftar'] = "Maaf, User tidak terdaftar pada database !!";
            header("location: ../masuk");
        }
    } else {
        $_SESSION['gagal_login'] = "Nama Pengguna atau Kata Sandi salah !!";
        header("location: ../masuk");
    }

} elseif ($_GET['aksi'] == "daftar") {
    // Kode registrasi yang sudah ada...
    $fullname = $_POST['funame'];
    $username = addslashes(strtolower($_POST['uname']));
    $username1 = str_replace(' ', '', $username);
    $password = $_POST['passw'];
    $kls = $_POST['kelas'];
    $jrs = $_POST['jurusan'];
    $kelas = $kls . $jrs;
    $alamat = $_POST['alamat'];
    $verif = "Tidak";
    $role = "Anggota";
    $join_date = date('d-m-Y');

    $query = mysqli_query($koneksi, "SELECT max(kode_user) as kodeTerakhir FROM user");
    $data = mysqli_fetch_array($query);
    $kodeTerakhir = $data['kodeTerakhir'];

    $urutan = (int) substr($kodeTerakhir, 3, 3);
    $urutan++;

    $huruf = "AP";
    $Anggota = $huruf . sprintf("%03s", $urutan);

    $sql = "INSERT INTO user(kode_user,nis,fullname,username,password,kelas,alamat,verif,role,join_date)
            VALUES('" . $Anggota . "','" . $nis . "','" . $fullname . "','" . $username1 . "','" . $password . "','" . $kelas . "','" . $alamat . "','" . $verif . "','" . $role . "','" . $join_date . "')";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        $_SESSION['berhasil'] = "Pendaftaran Berhasil !";
        header("location: ../masuk");
    } else {
        $_SESSION['gagal'] = "Pendaftaran Gagal !";
        header("location: ../masuk");
    }

} elseif ($_GET['aksi'] == "kirim_email_reset") {
    try {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        
        // Validasi input tidak boleh kosong
        if (empty($username) || empty($email)) {
            $_SESSION['gagal'] = "Username dan email harus diisi!";
            header("location: ../lupa-password-admin");
            exit();
        }
        
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['gagal'] = "Format email tidak valid!";
            header("location: ../lupa-password-admin");
            exit();
        }
        
        // Validasi admin dengan prepared statement untuk keamanan
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ? AND email = ? AND role = 'Admin'");
        if (!$stmt) {
            throw new Exception("Database prepare statement failed: " . mysqli_error($koneksi));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Generate token yang aman
            $token = bin2hex(random_bytes(32));
            
            // Hapus token lama yang belum digunakan untuk user ini
            $delete_stmt = mysqli_prepare($koneksi, "DELETE FROM password_reset_tokens WHERE username = ? AND used = 0");
            if ($delete_stmt) {
                mysqli_stmt_bind_param($delete_stmt, "s", $username);
                mysqli_stmt_execute($delete_stmt);
                mysqli_stmt_close($delete_stmt);
            }
            
            // Simpan token baru ke database
            $insert_stmt = mysqli_prepare($koneksi, "INSERT INTO password_reset_tokens (username, email, token, created_at) VALUES (?, ?, ?, NOW())");
            if (!$insert_stmt) {
                throw new Exception("Database prepare statement failed: " . mysqli_error($koneksi));
            }
            
            mysqli_stmt_bind_param($insert_stmt, "sss", $username, $email, $token);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                // Cek apakah fungsi sendResetPasswordEmail tersedia
                if (function_exists('sendResetPasswordEmail')) {
                    // Kirim email menggunakan fungsi dari email_config.php
                    if (sendResetPasswordEmail($email, $row['fullname'], $username, $token)) {
                        $_SESSION['berhasil'] = "Link reset password telah dikirim ke email $email. Silakan cek inbox atau folder spam Anda.";
                    } else {
                        $_SESSION['gagal'] = "Gagal mengirim email. Silakan coba lagi nanti atau hubungi administrator.";
                    }
                } else {
                    $_SESSION['gagal'] = "Sistem email belum dikonfigurasi dengan benar. Silakan hubungi administrator.";
                }
            } else {
                throw new Exception("Gagal menyimpan token reset password: " . mysqli_stmt_error($insert_stmt));
            }
            
            mysqli_stmt_close($insert_stmt);
        } else {
            $_SESSION['gagal'] = "Username atau email admin tidak ditemukan! Pastikan data yang Anda masukkan benar.";
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        error_log("Reset password error: " . $e->getMessage());
        $_SESSION['gagal'] = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
    }
    
    // Redirect ke halaman yang benar
    header("location: ../lupa-password-admin");
    exit();

} elseif ($_GET['aksi'] == "reset_password") {
    try {
        $token = htmlspecialchars($_POST['token']);
        $username = htmlspecialchars($_POST['username']);
        $new_password = htmlspecialchars($_POST['new_password']);
        $confirm_password = htmlspecialchars($_POST['confirm_password']);
        
        // Validasi input
        if (empty($token) || empty($username) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['gagal'] = "Semua field harus diisi!";
            header("location: ../reset_password_form.php?token=$token&username=$username");
            exit();
        }
        
        // Validasi password match
        if ($new_password !== $confirm_password) {
            $_SESSION['gagal'] = "Password baru dan konfirmasi password tidak cocok!";
            header("location: ../reset_password_form.php?token=$token&username=$username");
            exit();
        }
        
        // Validasi panjang password
        if (strlen($new_password) < 3) {
            $_SESSION['gagal'] = "Password minimal 3 karakter!";
            header("location: ../reset_password_form.php?token=$token&username=$username");
            exit();
        }
        
        // Validasi token dengan prepared statement
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM password_reset_tokens 
                                         WHERE token = ? 
                                         AND username = ? 
                                         AND used = 0 
                                         AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        if (!$stmt) {
            throw new Exception("Database prepare statement failed: " . mysqli_error($koneksi));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $token, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            // Hash password untuk keamanan (opsional, sesuaikan dengan sistem existing)
            // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // Atau gunakan password biasa sesuai sistem existing:
            $password_to_save = $new_password;
            
            // Update password admin
            $update_stmt = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE username = ? AND role = 'Admin'");
            if (!$update_stmt) {
                throw new Exception("Database prepare statement failed: " . mysqli_error($koneksi));
            }
            
            mysqli_stmt_bind_param($update_stmt, "ss", $password_to_save, $username);
            
            if (mysqli_stmt_execute($update_stmt)) {
                // Tandai token sudah digunakan
                $used_stmt = mysqli_prepare($koneksi, "UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
                if ($used_stmt) {
                    mysqli_stmt_bind_param($used_stmt, "s", $token);
                    mysqli_stmt_execute($used_stmt);
                    mysqli_stmt_close($used_stmt);
                }
                
                $_SESSION['berhasil'] = "Password admin berhasil direset! Silakan login dengan password baru.";
                header("location: ../masuk");
            } else {
                throw new Exception("Gagal update password: " . mysqli_stmt_error($update_stmt));
            }
            
            mysqli_stmt_close($update_stmt);
        } else {
            $_SESSION['gagal'] = "Token tidak valid atau sudah kadaluarsa! Link reset password hanya berlaku 1 jam.";
            header("location: ../lupa-password-admin");
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        error_log("Reset password error: " . $e->getMessage());
        $_SESSION['gagal'] = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
        
        // Redirect berdasarkan ketersediaan token
        if (!empty($token) && !empty($username)) {
            header("location: ../reset_password_form.php?token=$token&username=$username");
        } else {
            header("location: ../lupa-password-admin");
        }
    }
    exit();

} else {
    // Handle aksi yang tidak dikenali
    $_SESSION['gagal'] = "Aksi tidak valid!";
    header("location: ../masuk");
    exit();
}
?>