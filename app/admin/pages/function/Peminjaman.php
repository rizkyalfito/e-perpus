<?php
// Peminjaman.php
session_start();
include "../../../config/koneksi.php";

header('Content-Type: application/json');

class TransaksiController {
    private $koneksi;
    
    public function __construct($connection) {
        $this->koneksi = $connection;
    }
    
    // Get anggota data by kode_user (barcode scan)
    public function getAnggotaByKode($kode_user) {
        try {
            $query = "SELECT * FROM user WHERE kode_user = ? AND role = 'Anggota' AND verif = 'Tidak'";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, "s", $kode_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                return [
                    'status' => 'success',
                    'data' => [
                        'kode_user' => $row['kode_user'],
                        'nama' => $row['fullname'],
                        'kelas' => $row['kelas'],
                        'nis' => $row['nis']
                    ]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Anggota tidak ditemukan atau belum diverifikasi'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    // Get buku data by ISBN (barcode scan)
    public function getBukuByISBN($isbn) {
        try {
            $query = "SELECT * FROM buku WHERE isbn = ?";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, "s", $isbn);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $stok_tersedia = (int)$row['j_buku_baik'];
                
                return [
                    'status' => 'success',
                    'data' => [
                        'isbn' => $row['isbn'],
                        'judul' => $row['judul_buku'],
                        'pengarang' => $row['pengarang'],
                        'penerbit' => $row['penerbit_buku'],
                        'kategori' => $row['kategori_buku'],
                        'stok_baik' => $row['j_buku_baik'],
                        'stok_rusak' => $row['j_buku_rusak'],
                        'stok_tersedia' => $stok_tersedia . ' buku tersedia',
                        'available' => $stok_tersedia > 0
                    ]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Buku dengan ISBN ' . $isbn . ' tidak ditemukan'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    // Check existing loan for return process
    public function checkPeminjaman($kode_anggota, $isbn) {
        try {
            $query = "SELECT p.*, u.fullname, b.judul_buku 
                     FROM peminjaman p 
                     JOIN user u ON p.nama_anggota = u.fullname 
                     JOIN buku b ON p.judul_buku = b.judul_buku 
                     WHERE u.kode_user = ? AND b.isbn = ? AND p.tanggal_pengembalian = ''";
            
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ss", $kode_anggota, $isbn);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // Calculate overdue status
                $tanggal_peminjaman = DateTime::createFromFormat('d-m-Y', $row['tanggal_peminjaman']);
                $batas_kembali = clone $tanggal_peminjaman;
                $batas_kembali->add(new DateInterval('P14D')); // 14 days loan period
                $today = new DateTime();
                
                $is_overdue = $today > $batas_kembali;
                $days_overdue = $is_overdue ? $today->diff($batas_kembali)->days : 0;
                
                return [
                    'status' => 'success',
                    'data' => [
                        'id_peminjaman' => $row['id_peminjaman'],
                        'tanggal_peminjaman' => $row['tanggal_peminjaman'],
                        'batas_kembali' => $batas_kembali->format('d-m-Y'),
                        'is_overdue' => $is_overdue,
                        'days_overdue' => $days_overdue,
                        'status' => $is_overdue ? 'Terlambat ' . $days_overdue . ' hari' : 'Belum Terlambat',
                        'kondisi_awal' => $row['kondisi_buku_saat_dipinjam']
                    ]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Tidak ada peminjaman aktif untuk kombinasi anggota dan buku ini'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    // Calculate denda based on conditions
    public function calculateDenda($kondisi_kembali, $days_overdue = 0) {
        $denda = 0;
        
        // Denda keterlambatan: Rp 1000 per hari
        if ($days_overdue > 0) {
            $denda += $days_overdue * 1000;
        }
        
        // Denda kondisi buku
        switch ($kondisi_kembali) {
            case 'Rusak':
                $denda += 20000;
                break;
            case 'Hilang':
                $denda += 50000;
                break;
        }
        
        return [
            'total' => $denda,
            'formatted' => $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : 'Tidak ada denda'
        ];
    }
    
    // Process peminjaman
    public function processPeminjaman($data) {
        try {
            // Validate anggota
            $anggota = $this->getAnggotaByKode($data['kodeAnggota']);
            if ($anggota['status'] !== 'success') {
                return $anggota;
            }
            
            // Validate buku
            $buku = $this->getBukuByISBN($data['isbnBuku']);
            if ($buku['status'] !== 'success') {
                return $buku;
            }
            
            if (!$buku['data']['available']) {
                return [
                    'status' => 'error',
                    'message' => 'Stok buku tidak tersedia'
                ];
            }
            
            // Insert peminjaman
            $query = "INSERT INTO peminjaman (nama_anggota, judul_buku, tanggal_peminjaman, tanggal_pengembalian, kondisi_buku_saat_dipinjam, kondisi_buku_saat_dikembalikan, denda) 
                     VALUES (?, ?, ?, '', ?, '', '')";
            
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ssss", 
                $anggota['data']['nama'],
                $buku['data']['judul'],
                $data['tanggalPeminjaman'],
                $data['kondisiBukuSaatDipinjam']
            );
            
            if (mysqli_stmt_execute($stmt)) {
                // Update stok buku
                $new_stok = (int)$buku['data']['stok_baik'] - 1;
                $update_query = "UPDATE buku SET j_buku_baik = ? WHERE isbn = ?";
                $update_stmt = mysqli_prepare($this->koneksi, $update_query);
                mysqli_stmt_bind_param($update_stmt, "is", $new_stok, $data['isbnBuku']);
                mysqli_stmt_execute($update_stmt);
                
                return [
                    'status' => 'success',
                    'message' => 'Peminjaman berhasil disimpan'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Gagal menyimpan peminjaman'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    // Process pengembalian
    public function processPengembalian($data) {
        try {
            // Check existing loan
            $loan = $this->checkPeminjaman($data['kodeAnggota'], $data['isbnBuku']);
            if ($loan['status'] !== 'success') {
                return $loan;
            }
            
            // Calculate denda
            $denda = $this->calculateDenda($data['kondisiBukuSaatDikembalikan'], $loan['data']['days_overdue']);
            
            // Update peminjaman record
            $query = "UPDATE peminjaman SET 
                     tanggal_pengembalian = ?, 
                     kondisi_buku_saat_dikembalikan = ?, 
                     denda = ? 
                     WHERE id_peminjaman = ?";
            
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, "sssi", 
                $data['tanggalPengembalian'],
                $data['kondisiBukuSaatDikembalikan'],
                $denda['formatted'],
                $loan['data']['id_peminjaman']
            );
            
            if (mysqli_stmt_execute($stmt)) {
                // Update stok buku
                $buku = $this->getBukuByISBN($data['isbnBuku']);
                if ($data['kondisiBukuSaatDikembalikan'] === 'Baik') {
                    $new_stok_baik = (int)$buku['data']['stok_baik'] + 1;
                    $update_query = "UPDATE buku SET j_buku_baik = ? WHERE isbn = ?";
                    $update_stmt = mysqli_prepare($this->koneksi, $update_query);
                    mysqli_stmt_bind_param($update_stmt, "is", $new_stok_baik, $data['isbnBuku']);
                } else {
                    $new_stok_rusak = (int)$buku['data']['stok_rusak'] + 1;
                    $update_query = "UPDATE buku SET j_buku_rusak = ? WHERE isbn = ?";
                    $update_stmt = mysqli_prepare($this->koneksi, $update_query);
                    mysqli_stmt_bind_param($update_stmt, "is", $new_stok_rusak, $data['isbnBuku']);
                }
                mysqli_stmt_execute($update_stmt);
                
                return [
                    'status' => 'success',
                    'message' => 'Pengembalian berhasil diproses',
                    'denda' => $denda
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Gagal memproses pengembalian'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    $controller = new TransaksiController($koneksi);
    
    switch ($_GET['action']) {
        case 'getAnggota':
            if (isset($_GET['kode'])) {
                echo json_encode($controller->getAnggotaByKode($_GET['kode']));
            }
            break;
            
        case 'getBuku':
            if (isset($_GET['isbn'])) {
                echo json_encode($controller->getBukuByISBN($_GET['isbn']));
            }
            break;
            
        case 'checkPeminjaman':
            if (isset($_GET['kode']) && isset($_GET['isbn'])) {
                $result = $controller->checkPeminjaman($_GET['kode'], $_GET['isbn']);
                echo json_encode($result);
            }
            break;
            
        case 'calculateDenda':
            if (isset($_GET['kondisi']) && isset($_GET['days'])) {
                $denda = $controller->calculateDenda($_GET['kondisi'], (int)$_GET['days']);
                echo json_encode(['status' => 'success', 'data' => $denda]);
            }
            break;
            
        case 'processPeminjaman':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'kodeAnggota' => $_POST['kodeAnggota'],
                    'isbnBuku' => $_POST['isbnBuku'],
                    'tanggalPeminjaman' => $_POST['tanggalPeminjaman'],
                    'kondisiBukuSaatDipinjam' => $_POST['kondisiBukuSaatDipinjam']
                ];
                echo json_encode($controller->processPeminjaman($data));
            }
            break;
            
        case 'processPengembalian':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'kodeAnggota' => $_POST['kodeAnggota'],
                    'isbnBuku' => $_POST['isbnBuku'],
                    'tanggalPengembalian' => $_POST['tanggalPengembalian'],
                    'kondisiBukuSaatDikembalikan' => $_POST['kondisiBukuSaatDikembalikan']
                ];
                echo json_encode($controller->processPengembalian($data));
            }
            break;
    }
}
?>