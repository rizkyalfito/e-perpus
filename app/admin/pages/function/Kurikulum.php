<?php
session_start();
include "../../../../config/koneksi.php";

if ($_GET['act'] == "tambah") {
    $kode_kurikulum = $_POST['kodeKurikulum'];
    $nama_kurikulum = $_POST['namaKurikulum'];

    $sql = "INSERT INTO kurikulum(kode_kurikulum,nama_kurikulum)VALUES('$kode_kurikulum','$nama_kurikulum')";
    $sql .= mysqli_query($koneksi, $sql);

    if ($sql) {
        $_SESSION['berhasil'] = "Kurikulum berhasil ditambahkan !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Kurikulum gagal ditambahkan !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
} elseif ($_GET['act'] == "edit") {
    $id_kurikulum = $_POST['idKurikulum'];
    $nama_kurikulum = $_POST['namaKurikulum'];

    $query = "UPDATE kurikulum SET nama_kurikulum = '$nama_kurikulum'";
    $query .= "WHERE id_kurikulum = '$id_kurikulum'";

    $sql = mysqli_query($koneksi, $query);

    if ($sql) {
        $_SESSION['berhasil'] = "Kurikulum berhasil diedit !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Kurikulum gagal diedit !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
} elseif ($_GET['act'] == "hapus") {
    $id_kurikulum = $_GET['id'];

    $sql = mysqli_query($koneksi, "DELETE FROM kurikulum WHERE id_kurikulum = '$id_kurikulum'");

    if ($sql) {
        $_SESSION['berhasil'] = "Kurikulum berhasil dihapus !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Kurikulum gagal dihapus !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
} elseif ($_GET['act'] == "tambah_buku") {
    $id_buku = $_POST['idBuku'];
    $id_kurikulum = $_POST['idKurikulum'];

    // Cek apakah buku sudah ada di kurikulum ini
    $check_query = "SELECT * FROM buku_kurikulum WHERE id_buku = '$id_buku' AND id_kurikulum = '$id_kurikulum'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['gagal'] = "Buku sudah ada di kurikulum ini !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql = "INSERT INTO buku_kurikulum(id_buku,id_kurikulum)VALUES('$id_buku','$id_kurikulum')";
        $sql .= mysqli_query($koneksi, $sql);

        if ($sql) {
            $_SESSION['berhasil'] = "Buku berhasil ditambahkan ke kurikulum !";
            header("location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['gagal'] = "Buku gagal ditambahkan ke kurikulum !";
            header("location: " . $_SERVER['HTTP_REFERER']);
        }
    }
} elseif ($_GET['act'] == "hapus_buku") {
    $id_buku_kurikulum = $_GET['id'];

    $sql = mysqli_query($koneksi, "DELETE FROM buku_kurikulum WHERE id_buku_kurikulum = '$id_buku_kurikulum'");

    if ($sql) {
        $_SESSION['berhasil'] = "Buku berhasil dihapus dari kurikulum !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Buku gagal dihapus dari kurikulum !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
}
?>
