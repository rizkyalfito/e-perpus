<?php
session_start();

include "../../../../config/koneksi.php";
include "Peminjaman.php";
include "Pesan.php";

if ($_GET['aksi'] == "edit") {
    $id_user = $_POST['IdUser'];
    $nis = $_POST['Nis'];
    $fullname = $_POST['Fullname'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $kelas = $_POST['Kelas'];
    $alamat = $_POST['Alamat'];

    // Validasi kelas
    if (empty($_POST['Kelas']) || $_POST['Kelas'] == NULL) {
        $_SESSION['gagal'] = "Harap pilih kelas anda !";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Handle upload foto
    $foto_name = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        // Validasi tipe file
        if (!in_array($foto['type'], $allowed_types)) {
            $_SESSION['gagal'] = "Format foto tidak didukung. Gunakan JPG, PNG, atau GIF!";
            header("location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
        // Validasi ukuran file
        if ($foto['size'] > $max_size) {
            $_SESSION['gagal'] = "Ukuran foto terlalu besar. Maksimal 2MB!";
            header("location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
        // Generate nama file unik
        $file_extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $foto_name = 'user_' . $id_user . '_' . time() . '.' . $file_extension;
        $upload_path = '../../../../assets/img/users/' . $foto_name;
        
        // Pastikan folder exists
        if (!file_exists('../../../../assets/img/users/')) {
            mkdir('../../../../assets/img/users/', 0777, true);
        }
        
        // Upload file
        if (!move_uploaded_file($foto['tmp_name'], $upload_path)) {
            $_SESSION['gagal'] = "Gagal mengupload foto!";
            header("location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
        // Hapus foto lama jika bukan default
        $query_old = "SELECT foto FROM user WHERE id_user = '$id_user'";
        $result_old = mysqli_query($koneksi, $query_old);
        $old_data = mysqli_fetch_assoc($result_old);
        
        if ($old_data['foto'] && $old_data['foto'] != 'default-avatar.png' && file_exists('../../../../assets/img/users/' . $old_data['foto'])) {
            unlink('../../../../assets/img/users/' . $old_data['foto']);
        }
    }

    UpdateDataPeminjaman();
    UpdateDataPesan();

    // Build query update
    $query = "UPDATE user SET nis = '$nis', fullname = '$fullname', username = '$username', password = '$password', kelas = '$kelas', alamat = '$alamat'";
    
    // Tambahkan foto ke query jika ada upload
    if ($foto_name) {
        $query .= ", foto = '$foto_name'";
    }
    
    $query .= " WHERE id_user = '$id_user'";

    $sql = mysqli_query($koneksi, $query);

    if ($sql) {
        $_SESSION['berhasil'] = "Update profil berhasil !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Update profil gagal !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
}
?>