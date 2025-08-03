<?php
session_start();
include "../../../../config/koneksi.php";

if ($_GET['act'] == "tambah") {
    $judul_buku = $_POST['judulBuku'];
    $kategori_buku = $_POST['kategoriBuku'];
    $penerbit_buku = $_POST['penerbitBuku'];
    $pengarang = $_POST['pengarang'];
    $tahun_terbit = $_POST['tahunTerbit'];
    $isbn = $_POST['iSbn'];
    $j_buku_baik = intval($_POST['jumlahBukuBaik']);
    $j_buku_rusak = intval($_POST['jumlahBukuRusak']);

    // Begin transaction
    mysqli_autocommit($koneksi, FALSE);

    try {
        // Insert buku
        $sql = "INSERT INTO buku(judul_buku,kategori_buku,penerbit_buku,pengarang,tahun_terbit,isbn,j_buku_baik,j_buku_rusak)
            VALUES('" . $judul_buku . "','" . $kategori_buku . "','" . $penerbit_buku . "','" . $pengarang . "','" . $tahun_terbit . "','" . $isbn . "', '" . $j_buku_baik . "', '" . $j_buku_rusak . "')";
        if (!mysqli_query($koneksi, $sql)) {
            throw new Exception("Gagal menambahkan data buku");
        }

        $id_buku = mysqli_insert_id($koneksi);

        // Generate unique barcode for each unit and insert into buku_unit
        function generateBarcode($id_buku, $index) {
            return 'BK' . str_pad($id_buku, 5, '0', STR_PAD_LEFT) . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);
        }

        // Insert buku_unit for buku baik
        for ($i = 1; $i <= $j_buku_baik; $i++) {
            $barcode = generateBarcode($id_buku, $i);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'baik', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Gagal menambahkan unit buku baik");
            }
        }

        // Insert buku_unit for buku rusak
        for ($i = 1; $i <= $j_buku_rusak; $i++) {
            $barcode = generateBarcode($id_buku, $j_buku_baik + $i);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'rusak', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Gagal menambahkan unit buku rusak");
            }
        }

        // Commit transaction
        mysqli_commit($koneksi);

        $_SESSION['berhasil'] = "Data buku berhasil ditambahkan dengan unit barcode unik!";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = "Data buku gagal ditambahkan: " . $e->getMessage();
        header("location: " . $_SERVER['HTTP_REFERER']);
    }

    // Reset autocommit
    mysqli_autocommit($koneksi, TRUE);
} elseif ($_GET['act'] == "edit") {
    $id_buku = $_POST['id_buku'];
    $judul_buku = $_POST['judulBuku'];
    $kategori_buku = $_POST['kategoriBuku'];
    $penerbit_buku = $_POST['penerbitBuku'];
    $pengarang = $_POST['pengarang'];
    $tahun_terbit = $_POST['tahunTerbit'];
    $isbn = $_POST['iSbn'];
    $j_buku_baik = $_POST['jumlahBukuBaik'];
    $j_buku_rusak = $_POST['jumlahBukuRusak'];

    // PROCESS EDIT DATA
    $query = "UPDATE buku SET judul_buku = '$judul_buku', kategori_buku = '$kategori_buku', penerbit_buku = '$penerbit_buku', 
                pengarang = '$pengarang', tahun_terbit = '$tahun_terbit', isbn = '$isbn', j_buku_baik = '$j_buku_baik', j_buku_rusak = '$j_buku_rusak'";

    $query .= " WHERE id_buku = $id_buku";

    $sql = mysqli_query($koneksi, $query);
    if ($sql) {
        $_SESSION['berhasil'] = "Data buku berhasil diedit !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Data buku gagal diedit !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
} elseif ($_GET['act'] == "hapus") {
    $id_buku = $_GET['id'];

    $sql = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = '$id_buku'");

    if ($sql) {
        $_SESSION['berhasil'] = "Data buku berhasil di hapus !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Data buku gagal di hapus !";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
    // API endpoint untuk mengambil data units
    if (isset($_GET['act']) && $_GET['act'] == "get_units") {
    $id_buku = intval($_GET['id_buku']);
    
    header('Content-Type: application/json');
    
    // Query untuk mengambil semua units dari buku tertentu
    $query = "SELECT bu.*, b.judul_buku, b.pengarang, b.isbn 
              FROM buku_unit bu 
              JOIN buku b ON bu.id_buku = b.id_buku 
              WHERE bu.id_buku = $id_buku 
              ORDER BY bu.barcode ASC";
    
    $result = mysqli_query($koneksi, $query);
    
    if (!$result) {
        echo json_encode([
            'error' => 'Query failed: ' . mysqli_error($koneksi)
        ]);
        exit;
    }
    
    $units = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $units[] = [
            'id_buku_unit' => $row['id_buku_unit'],
            'id_buku' => $row['id_buku'],
            'barcode' => $row['barcode'],
            'kondisi' => $row['kondisi'],
            'status' => $row['status'],
            'judul_buku' => $row['judul_buku'],
            'pengarang' => $row['pengarang'],
            'isbn' => $row['isbn']
        ];
    }
    
    echo json_encode($units);
    exit;
}
}
