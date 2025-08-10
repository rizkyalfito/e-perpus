<?php
session_start();
include "../../../../config/koneksi.php";

// Handle AJAX requests untuk mencari anggota dan buku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Cari Anggota berdasarkan kode
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'cari_anggota') {
        $kode_anggota = mysqli_real_escape_string($koneksi, $_POST['kode_anggota']);
        
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE kode_user = '$kode_anggota' AND role = 'Anggota'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            
            // Cek apakah anggota memiliki peminjaman yang belum dikembalikan
            $cek_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM peminjaman WHERE nama_anggota = '".$data['fullname']."' AND kondisi_buku_saat_dikembalikan = ''");
            $pinjam_data = mysqli_fetch_assoc($cek_pinjam);
            
            if ($pinjam_data['jumlah'] >= 3) { // Maksimal 3 buku
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Anggota sudah meminjam maksimal 3 buku. Mohon kembalikan buku terlebih dahulu.',
                    'notification_type' => 'scan_error'
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'data' => $data,
                    'jumlah_pinjam' => $pinjam_data['jumlah'],
                    'message' => 'Data anggota berhasil ditemukan',
                    'notification_type' => 'scan_success'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anggota dengan kode tersebut tidak ditemukan',
                'notification_type' => 'scan_error'
            ]);
        }
        exit;
    }
    
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'cari_buku') {
        $barcode_input = mysqli_real_escape_string($koneksi, $_POST['isbn']);
        
        // Cari di buku_unit terlebih dahulu (barcode unit seperti BK00001-001)
        $query = mysqli_query($koneksi, "SELECT bu.*, b.judul_buku, b.kategori_buku, b.penerbit_buku, b.pengarang 
                                         FROM buku_unit bu 
                                         JOIN buku b ON bu.id_buku = b.id_buku 
                                         WHERE LOWER(bu.barcode) = LOWER('$barcode_input') AND bu.status = 'tersedia'");
        
        if (mysqli_num_rows($query) > 0) {
            // Jika ditemukan di buku_unit
            $data = mysqli_fetch_assoc($query);
            
            echo json_encode([
                'status' => 'success',
                'data' => $data,
                'search_type' => 'unit_barcode',
                'message' => 'Data buku berhasil ditemukan berdasarkan barcode unit',
                'notification_type' => 'scan_success'
            ]);
        } else {
            // Jika tidak ditemukan di buku_unit, cari berdasarkan ISBN di tabel buku
            $query2 = mysqli_query($koneksi, "SELECT b.*, bu.barcode, bu.kondisi, bu.status 
                                              FROM buku b 
                                              JOIN buku_unit bu ON b.id_buku = bu.id_buku 
                                              WHERE (b.isbn = '$barcode_input' OR b.barcode = '$barcode_input') 
                                              AND bu.status = 'tersedia' 
                                              LIMIT 1");
            
            if (mysqli_num_rows($query2) > 0) {
                // Jika ditemukan berdasarkan ISBN
                $data = mysqli_fetch_assoc($query2);
                
                // Format data sesuai dengan struktur yang diharapkan
                $formatted_data = [
                    'id_buku_unit' => $data['id_buku'],
                    'id_buku' => $data['id_buku'],
                    'barcode' => $data['barcode'], // barcode unit yang akan digunakan
                    'kondisi' => $data['kondisi'],
                    'status' => $data['status'],
                    'judul_buku' => $data['judul_buku'],
                    'kategori_buku' => $data['kategori_buku'],
                    'penerbit_buku' => $data['penerbit_buku'],
                    'pengarang' => $data['pengarang']
                ];
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $formatted_data,
                    'search_type' => 'isbn',
                    'message' => 'Data buku berhasil ditemukan berdasarkan ISBN, menggunakan unit tersedia: ' . $data['barcode'],
                    'notification_type' => 'scan_success'
                ]);
            } else {
                // Tidak ditemukan sama sekali
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Buku dengan barcode/ISBN tersebut tidak ditemukan atau semua unit sedang dipinjam',
                    'notification_type' => 'scan_error'
                ]);
            }
        }
        exit;
    }
    
    // New action: cari peminjaman by barcode buku
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'cari_peminjaman_by_barcode') {
        $barcode_buku = mysqli_real_escape_string($koneksi, $_POST['barcode_buku']);
        
        // Query peminjaman yang aktif (belum dikembalikan) dengan barcode buku yang sesuai
        $query = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE barcode_buku = '$barcode_buku' AND kondisi_buku_saat_dikembalikan = ''");
        
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        
        if (count($data) > 0) {
            echo json_encode([
                'status' => 'success',
                'data' => $data,
                'message' => 'Peminjaman aktif ditemukan untuk barcode buku tersebut',
                'notification_type' => 'scan_success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'data' => [],
                'message' => 'Tidak ditemukan peminjaman aktif untuk barcode buku tersebut',
                'notification_type' => 'scan_error'
            ]);
        }
        exit;
    }
}

// Handle form submissions
if (isset($_GET['aksi'])) {
    
    if ($_GET['aksi'] == "tambah") {
        $nama_anggota = mysqli_real_escape_string($koneksi, $_POST['namaAnggota']);
        $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judulBuku']);
        $tanggal_pinjam = $_POST['tanggalPinjam'];
        $tanggal_kembali = $_POST['tanggalKembali'];
        $kondisi_buku = mysqli_real_escape_string($koneksi, $_POST['kondisiBuku']);
        $barcode = $_POST['isbn']; // now barcode per unit
        
        // Return JSON response instead of redirect
        header('Content-Type: application/json');
        
        // Validasi input
        if (empty($nama_anggota) || empty($judul_buku) || empty($barcode)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Semua field harus diisi!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        // Cek apakah anggota ada
        $cek_anggota = mysqli_query($koneksi, "SELECT * FROM user WHERE fullname = '$nama_anggota' AND role = 'Anggota'");
        if (mysqli_num_rows($cek_anggota) == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        // Cek batas peminjaman anggota
        $cek_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM peminjaman WHERE nama_anggota = '$nama_anggota' AND kondisi_buku_saat_dikembalikan = ''");
        $pinjam_data = mysqli_fetch_assoc($cek_pinjam);
        
        if ($pinjam_data['jumlah'] >= 3) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Melebihi batas maksimal peminjaman (3 buku per anggota)!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        // Cek stok buku_unit berdasarkan barcode
        $cek_buku_unit = mysqli_query($koneksi, "SELECT * FROM buku_unit WHERE barcode = '$barcode' AND status = 'tersedia'");
        if (mysqli_num_rows($cek_buku_unit) == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Buku dengan barcode tersebut tidak tersedia atau sedang dipinjam!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        $data_buku_unit = mysqli_fetch_assoc($cek_buku_unit);
        
        // Begin transaction
        mysqli_autocommit($koneksi, FALSE);
        
        try {
            // Cek apakah kolom barcode_buku exists di tabel peminjaman
            $check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM peminjaman LIKE 'barcode_buku'");
            $has_barcode_column = mysqli_num_rows($check_column) > 0;
            
            if ($has_barcode_column) {
                // Insert peminjaman dengan menyimpan barcode
                $sql_pinjam = "INSERT INTO peminjaman (nama_anggota, judul_buku, tanggal_peminjaman, tanggal_pengembalian, kondisi_buku_saat_dipinjam, kondisi_buku_saat_dikembalikan, denda, barcode_buku) 
                              VALUES ('$nama_anggota', '$judul_buku', '$tanggal_pinjam', '$tanggal_kembali', '$kondisi_buku', '', '0', '$barcode')";
            } else {
                // Insert peminjaman tanpa kolom barcode_buku
                $sql_pinjam = "INSERT INTO peminjaman (nama_anggota, judul_buku, tanggal_peminjaman, tanggal_pengembalian, kondisi_buku_saat_dipinjam, kondisi_buku_saat_dikembalikan, denda) 
                              VALUES ('$nama_anggota', '$judul_buku', '$tanggal_pinjam', '$tanggal_kembali', '$kondisi_buku', '', '0')";
            }
            
            if (!mysqli_query($koneksi, $sql_pinjam)) {
                throw new Exception("Gagal menyimpan data peminjaman");
            }
            
            // Update status buku_unit menjadi dipinjam
            $sql_update_unit = "UPDATE buku_unit SET status = 'dipinjam' WHERE barcode = '$barcode'";
            
            if (!mysqli_query($koneksi, $sql_update_unit)) {
                throw new Exception("Gagal mengupdate status buku unit");
            }
            
            // Commit transaction
            mysqli_commit($koneksi);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Peminjaman buku berhasil disimpan!',
                'notification_type' => 'form_success',
                'data' => [
                    'nama_anggota' => $nama_anggota,
                    'judul_buku' => $judul_buku,
                    'tanggal_pinjam' => $tanggal_pinjam,
                    'tanggal_kembali' => $tanggal_kembali
                ]
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($koneksi);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'notification_type' => 'form_error'
            ]);
        }
        
        // Reset autocommit
        mysqli_autocommit($koneksi, TRUE);
        exit;
    }
    
    elseif ($_GET['aksi'] == "kembali") {
        $id_peminjaman = $_POST['idPeminjaman'];
        $kondisi_buku_kembali = $_POST['kondisiBuku'];
        $denda = isset($_POST['denda']) ? $_POST['denda'] : 0;
        
        // Return JSON response instead of redirect
        header('Content-Type: application/json');
        
        // Begin transaction
        mysqli_autocommit($koneksi, FALSE);
        
        try {
            // Get data peminjaman
            $query_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
            $data_pinjam = mysqli_fetch_assoc($query_pinjam);
            
            if (!$data_pinjam) {
                throw new Exception("Data peminjaman tidak ditemukan");
            }
            
            // Update peminjaman
            $sql_update_pinjam = "UPDATE peminjaman SET 
                                    kondisi_buku_saat_dikembalikan = '$kondisi_buku_kembali',
                                    denda = '$denda'
                                  WHERE id_peminjaman = '$id_peminjaman'";
            
            if (!mysqli_query($koneksi, $sql_update_pinjam)) {
                throw new Exception("Gagal mengupdate data peminjaman");
            }
            
            // PERBAIKAN: Cek apakah kolom barcode_buku exists terlebih dahulu
            $check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM peminjaman LIKE 'barcode_buku'");
            $has_barcode_column = mysqli_num_rows($check_column) > 0;
            
            $barcode = null;
            if ($has_barcode_column && isset($data_pinjam['barcode_buku']) && !empty($data_pinjam['barcode_buku'])) {
                $barcode = $data_pinjam['barcode_buku'];
            } else {
                // Fallback: Cari barcode dari buku_unit yang statusnya dipinjam untuk buku ini
                $query_barcode = mysqli_query($koneksi, "SELECT bu.barcode FROM buku_unit bu 
                                                        JOIN buku b ON bu.id_buku = b.id_buku 
                                                        WHERE b.judul_buku = '".$data_pinjam['judul_buku']."' 
                                                        AND bu.status = 'dipinjam' 
                                                        LIMIT 1");
                if ($query_barcode && mysqli_num_rows($query_barcode) > 0) {
                    $barcode_data = mysqli_fetch_assoc($query_barcode);
                    $barcode = $barcode_data['barcode'];
                }
            }
            
            // Update status buku_unit jika barcode ditemukan
            if ($barcode) {
                $sql_update_unit = "UPDATE buku_unit SET status = 'tersedia' WHERE barcode = '$barcode'";
                if (!mysqli_query($koneksi, $sql_update_unit)) {
                    throw new Exception("Gagal mengupdate status buku unit");
                }
            }
            
            // Commit transaction
            mysqli_commit($koneksi);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Buku berhasil dikembalikan!',
                'notification_type' => 'form_success',
                'data' => [
                    'nama_anggota' => $data_pinjam['nama_anggota'],
                    'judul_buku' => $data_pinjam['judul_buku'],
                    'kondisi_kembali' => $kondisi_buku_kembali,
                    'denda' => $denda
                ]
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($koneksi);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'notification_type' => 'form_error'
            ]);
        }
        
        // Reset autocommit
        mysqli_autocommit($koneksi, TRUE);
        exit;
    }
    
    elseif ($_GET['aksi'] == "perpanjang") {
        $id_peminjaman = $_POST['idPeminjaman'];
        $tanggal_kembali_baru = $_POST['tanggalKembaliBaru'];
        
        header('Content-Type: application/json');
        
        $sql = "UPDATE peminjaman SET tanggal_pengembalian = '$tanggal_kembali_baru' WHERE id_peminjaman = '$id_peminjaman'";
        
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Peminjaman berhasil diperpanjang!',
                'notification_type' => 'form_success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperpanjang peminjaman!',
                'notification_type' => 'form_error'
            ]);
        }
        exit;
    }
    
    elseif ($_GET['aksi'] == "hapus") {
        $id_peminjaman = $_GET['id'];
        
        header('Content-Type: application/json');
        
        // Begin transaction
        mysqli_autocommit($koneksi, FALSE);
        
        try {
            // Get data peminjaman
            $query_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
            $data_pinjam = mysqli_fetch_assoc($query_pinjam);
            
            if (!$data_pinjam) {
                throw new Exception("Data peminjaman tidak ditemukan");
            }
            
            // Jika belum dikembalikan, kembalikan status buku unit
            if (empty($data_pinjam['kondisi_buku_saat_dikembalikan'])) {
                // Cek apakah kolom barcode_buku exists
                $check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM peminjaman LIKE 'barcode_buku'");
                $has_barcode_column = mysqli_num_rows($check_column) > 0;
                
                if ($has_barcode_column && isset($data_pinjam['barcode_buku']) && !empty($data_pinjam['barcode_buku'])) {
                    $sql_update_unit = "UPDATE buku_unit SET status = 'tersedia' WHERE barcode = '".$data_pinjam['barcode_buku']."'";
                    if (!mysqli_query($koneksi, $sql_update_unit)) {
                        throw new Exception("Gagal mengembalikan status buku unit");
                    }
                } else {
                    // Fallback: Cari dan update berdasarkan judul buku
                    $sql_update_unit = "UPDATE buku_unit bu 
                                       JOIN buku b ON bu.id_buku = b.id_buku 
                                       SET bu.status = 'tersedia' 
                                       WHERE b.judul_buku = '".$data_pinjam['judul_buku']."' 
                                       AND bu.status = 'dipinjam' 
                                       LIMIT 1";
                    mysqli_query($koneksi, $sql_update_unit); // Don't throw error if this fails
                }
            }
            
            // Delete peminjaman
            $sql_delete = "DELETE FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'";
            if (!mysqli_query($koneksi, $sql_delete)) {
                throw new Exception("Gagal menghapus data peminjaman");
            }
            
            // Commit transaction
            mysqli_commit($koneksi);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Data peminjaman berhasil dihapus!',
                'notification_type' => 'form_success'
            ]);
            
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($koneksi);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'notification_type' => 'form_error'
            ]);
        }
        
        // Reset autocommit
        mysqli_autocommit($koneksi, TRUE);
        exit;
    }
}

// Function untuk hitung denda
function hitungDenda($tanggal_kembali, $tanggal_dikembalikan = null) {
    $tanggal_dikembalikan = $tanggal_dikembalikan ?: date('Y-m-d');
    
    $date1 = new DateTime($tanggal_kembali);
    $date2 = new DateTime($tanggal_dikembalikan);
    
    if ($date2 > $date1) {
        $diff = $date2->diff($date1);
        $hari_terlambat = $diff->days;
        $denda_per_hari = 1000; // Rp 1.000 per hari
        return $hari_terlambat * $denda_per_hari;
    }
    
    return 0;
}

// Function untuk get data peminjaman (untuk API atau AJAX)
if (isset($_GET['get_data']) && $_GET['get_data'] === 'peminjaman') {
    // Query dengan JOIN untuk mendapatkan kode anggota
    $query = "SELECT p.*, u.kode_user as kode_anggota 
              FROM peminjaman p 
              LEFT JOIN user u ON p.nama_anggota = u.fullname 
              WHERE u.role = 'Anggota' 
              ORDER BY p.tanggal_peminjaman DESC";
    
    $result = mysqli_query($koneksi, $query);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Hitung denda jika belum dikembalikan
        if (empty($row['kondisi_buku_saat_dikembalikan'])) {
            $row['denda_calculated'] = hitungDenda($row['tanggal_pengembalian']);
        } else {
            $row['denda_calculated'] = $row['denda'];
        }
        
        $data[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>