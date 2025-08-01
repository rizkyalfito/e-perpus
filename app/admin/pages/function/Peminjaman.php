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
    
    // Cari Buku berdasarkan ISBN
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'cari_buku') {
        $isbn_input = mysqli_real_escape_string($koneksi, $_POST['isbn']);
        
        // Convert ISBN input to integer for database search (remove hyphens)
        $isbn_number = str_replace('-', '', $isbn_input);
        
        $query = mysqli_query($koneksi, "SELECT * FROM buku WHERE isbn = '$isbn_number'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            
            if ($data['j_buku_baik'] <= 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Buku tidak tersedia (stok habis)',
                    'notification_type' => 'scan_error'
                ]);
            } else {
                // Format ISBN for display (add hyphens)
                $data['isbn_display'] = $isbn_input;
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $data,
                    'message' => 'Data buku berhasil ditemukan',
                    'notification_type' => 'scan_success'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Buku dengan ISBN tersebut tidak ditemukan',
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
        $isbn = str_replace('-', '', $_POST['isbn']); // Remove hyphens for database
        
        // Return JSON response instead of redirect
        header('Content-Type: application/json');
        
        // Validasi input
        if (empty($nama_anggota) || empty($judul_buku) || empty($isbn)) {
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
        
        // Cek stok buku
        $cek_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE isbn = '$isbn'");
        if (mysqli_num_rows($cek_buku) == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        $data_buku = mysqli_fetch_assoc($cek_buku);
        if ($data_buku['j_buku_baik'] <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Stok buku tidak tersedia!',
                'notification_type' => 'form_error'
            ]);
            exit;
        }
        
        // Begin transaction
        mysqli_autocommit($koneksi, FALSE);
        
        try {
            // Insert peminjaman
            $sql_pinjam = "INSERT INTO peminjaman (nama_anggota, judul_buku, tanggal_peminjaman, tanggal_pengembalian, kondisi_buku_saat_dipinjam, kondisi_buku_saat_dikembalikan, denda) 
                          VALUES ('$nama_anggota', '$judul_buku', '$tanggal_pinjam', '$tanggal_kembali', '$kondisi_buku', '', '0')";
            
            if (!mysqli_query($koneksi, $sql_pinjam)) {
                throw new Exception("Gagal menyimpan data peminjaman");
            }
            
            // Update stok buku (kurangi 1)
            $sql_update_stok = "UPDATE buku SET j_buku_baik = j_buku_baik - 1 WHERE isbn = '$isbn'";
            
            if (!mysqli_query($koneksi, $sql_update_stok)) {
                throw new Exception("Gagal mengupdate stok buku");
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
        $tanggal_dikembalikan = date('Y-m-d');
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
            
            // Get ISBN dari judul buku untuk update stok
            $query_buku = mysqli_query($koneksi, "SELECT isbn FROM buku WHERE judul_buku = '".$data_pinjam['judul_buku']."'");
            $data_buku = mysqli_fetch_assoc($query_buku);
            
            if ($data_buku) {
                // Update stok buku
                if ($kondisi_buku_kembali === 'baik') {
                    $sql_update_stok = "UPDATE buku SET j_buku_baik = j_buku_baik + 1 WHERE isbn = '".$data_buku['isbn']."'";
                } else {
                    $sql_update_stok = "UPDATE buku SET j_buku_rusak = j_buku_rusak + 1 WHERE isbn = '".$data_buku['isbn']."'";
                }
                
                if (!mysqli_query($koneksi, $sql_update_stok)) {
                    throw new Exception("Gagal mengupdate stok buku");
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
            
            // Jika belum dikembalikan, kembalikan stok
            if (empty($data_pinjam['kondisi_buku_saat_dikembalikan'])) {
                $query_buku = mysqli_query($koneksi, "SELECT isbn FROM buku WHERE judul_buku = '".$data_pinjam['judul_buku']."'");
                $data_buku = mysqli_fetch_assoc($query_buku);
                
                if ($data_buku) {
                    $sql_update_stok = "UPDATE buku SET j_buku_baik = j_buku_baik + 1 WHERE isbn = '".$data_buku['isbn']."'";
                    if (!mysqli_query($koneksi, $sql_update_stok)) {
                        throw new Exception("Gagal mengembalikan stok buku");
                    }
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