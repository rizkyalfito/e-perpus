<?php
// File: pages/function/get_buku_kurikulum.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['id_kurikulum']) || empty($_POST['id_kurikulum'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Kurikulum tidak ditemukan']);
    exit;
}

include "../../config/koneksi.php";

$id_kurikulum = mysqli_real_escape_string($koneksi, $_POST['id_kurikulum']);

$query = "SELECT bk.*, b.judul_buku, b.pengarang, b.penerbit_buku, b.tahun_terbit 
          FROM buku_kurikulum bk 
          JOIN buku b ON bk.id_buku = b.id_buku 
          WHERE bk.id_kurikulum = '$id_kurikulum' 
          ORDER BY b.judul_buku ASC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query error: ' . mysqli_error($koneksi)]);
    exit;
}

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    'status' => 'success', 
    'data' => $data,
    'total' => count($data)
]);
?>