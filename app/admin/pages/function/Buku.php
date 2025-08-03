<?php
session_start();
include "../../../../config/koneksi.php";

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

// API endpoint untuk regenerate units per buku
if (isset($_GET['act']) && $_GET['act'] == "regenerate_units") {
    $id_buku = intval($_GET['id_buku']);
    
    header('Content-Type: application/json');
    
    // Begin transaction
    mysqli_autocommit($koneksi, FALSE);
    
    try {
        // Get book data
        $book_query = "SELECT * FROM buku WHERE id_buku = $id_buku";
        $book_result = mysqli_query($koneksi, $book_query);
        
        if (!$book_result || mysqli_num_rows($book_result) === 0) {
            throw new Exception("Buku tidak ditemukan");
        }
        
        $book_data = mysqli_fetch_assoc($book_result);
        $j_buku_baik = intval($book_data['j_buku_baik']);
        $j_buku_rusak = intval($book_data['j_buku_rusak']);
        
        // Delete existing units (only if not borrowed)
        $check_borrowed = "SELECT COUNT(*) as borrowed FROM buku_unit WHERE id_buku = $id_buku AND status = 'dipinjam'";
        $borrowed_result = mysqli_query($koneksi, $check_borrowed);
        $borrowed_data = mysqli_fetch_assoc($borrowed_result);
        
        if ($borrowed_data['borrowed'] > 0) {
            throw new Exception("Tidak dapat regenerate karena ada " . $borrowed_data['borrowed'] . " unit yang sedang dipinjam");
        }
        
        // Delete all existing units for this book
        $delete_units = "DELETE FROM buku_unit WHERE id_buku = $id_buku";
        if (!mysqli_query($koneksi, $delete_units)) {
            throw new Exception("Gagal menghapus units existing: " . mysqli_error($koneksi));
        }
        
        // Generate barcode function
        function generateBarcode($id_buku, $index) {
            return 'BK' . str_pad($id_buku, 5, '0', STR_PAD_LEFT) . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);
        }
        
        $unit_index = 1;
        
        // Insert buku_unit untuk buku baik
        for ($i = 1; $i <= $j_buku_baik; $i++) {
            $barcode = generateBarcode($id_buku, $unit_index);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'baik', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Gagal menambahkan unit buku baik: " . mysqli_error($koneksi));
            }
            $unit_index++;
        }
        
        // Insert buku_unit untuk buku rusak
        for ($i = 1; $i <= $j_buku_rusak; $i++) {
            $barcode = generateBarcode($id_buku, $unit_index);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'rusak', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Gagal menambahkan unit buku rusak: " . mysqli_error($koneksi));
            }
            $unit_index++;
        }
        
        // Commit transaction
        mysqli_commit($koneksi);
        
        echo json_encode([
            'success' => true,
            'message' => 'Units berhasil di-regenerate',
            'total_units' => $j_buku_baik + $j_buku_rusak
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($koneksi);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    
    // Reset autocommit
    mysqli_autocommit($koneksi, TRUE);
    exit;
}

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
    $j_buku_baik = intval($_POST['jumlahBukuBaik']);
    $j_buku_rusak = intval($_POST['jumlahBukuRusak']);

    // Begin transaction
    mysqli_autocommit($koneksi, FALSE);

    try {
        // Get current unit counts
        $current_query = mysqli_query($koneksi, "SELECT j_buku_baik, j_buku_rusak FROM buku WHERE id_buku = $id_buku");
        $current_data = mysqli_fetch_assoc($current_query);
        $current_baik = intval($current_data['j_buku_baik']);
        $current_rusak = intval($current_data['j_buku_rusak']);

        // Update buku data
        $query = "UPDATE buku SET judul_buku = '$judul_buku', kategori_buku = '$kategori_buku', penerbit_buku = '$penerbit_buku', 
                    pengarang = '$pengarang', tahun_terbit = '$tahun_terbit', isbn = '$isbn', j_buku_baik = '$j_buku_baik', j_buku_rusak = '$j_buku_rusak'
                  WHERE id_buku = $id_buku";

        if (!mysqli_query($koneksi, $query)) {
            throw new Exception("Gagal mengupdate data buku");
        }

        // Handle unit changes
        $total_current = $current_baik + $current_rusak;
        $total_new = $j_buku_baik + $j_buku_rusak;

        if ($total_new > $total_current) {
            // Add new units
            $units_to_add = $total_new - $total_current;
            
            // Get the highest existing unit number
            $max_query = mysqli_query($koneksi, "SELECT barcode FROM buku_unit WHERE id_buku = $id_buku ORDER BY barcode DESC LIMIT 1");
            $max_data = mysqli_fetch_assoc($max_query);
            $next_index = 1;
            
            if ($max_data) {
                // Extract number from barcode (format: BK00001-001)
                preg_match('/BK\d+-(\d+)/', $max_data['barcode'], $matches);
                $next_index = intval($matches[1]) + 1;
            }

            function generateBarcode($id_buku, $index) {
                return 'BK' . str_pad($id_buku, 5, '0', STR_PAD_LEFT) . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);
            }

            // Add new units (prioritize 'baik' condition)
            for ($i = 0; $i < $units_to_add; $i++) {
                $barcode = generateBarcode($id_buku, $next_index + $i);
                $kondisi = ($current_baik + $i < $j_buku_baik) ? 'baik' : 'rusak';
                
                $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', '$kondisi', 'tersedia')";
                if (!mysqli_query($koneksi, $insert_unit)) {
                    throw new Exception("Gagal menambahkan unit buku baru");
                }
            }
        } elseif ($total_new < $total_current) {
            // Remove excess units (only if they are 'tersedia')
            $units_to_remove = $total_current - $total_new;
            
            $remove_query = "DELETE FROM buku_unit 
                           WHERE id_buku = $id_buku 
                           AND status = 'tersedia' 
                           ORDER BY barcode DESC 
                           LIMIT $units_to_remove";
            
            if (!mysqli_query($koneksi, $remove_query)) {
                throw new Exception("Gagal menghapus unit buku berlebih");
            }
            
            // Check if we removed enough units
            $remaining_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku_unit WHERE id_buku = $id_buku");
            $remaining_data = mysqli_fetch_assoc($remaining_query);
            
            if ($remaining_data['total'] > $total_new) {
                throw new Exception("Tidak dapat mengurangi jumlah unit karena ada unit yang sedang dipinjam");
            }
        }

        // Update kondisi existing units based on new ratio
        if ($j_buku_baik != $current_baik || $j_buku_rusak != $current_rusak) {
            // Get all current units
            $units_query = mysqli_query($koneksi, "SELECT id_buku_unit FROM buku_unit WHERE id_buku = $id_buku ORDER BY barcode ASC");
            $unit_ids = [];
            while ($unit = mysqli_fetch_assoc($units_query)) {
                $unit_ids[] = $unit['id_buku_unit'];
            }

            // Update kondisi based on new counts
            for ($i = 0; $i < count($unit_ids); $i++) {
                $new_kondisi = ($i < $j_buku_baik) ? 'baik' : 'rusak';
                $update_kondisi = "UPDATE buku_unit SET kondisi = '$new_kondisi' WHERE id_buku_unit = " . $unit_ids[$i];
                mysqli_query($koneksi, $update_kondisi);
            }
        }

        // Commit transaction
        mysqli_commit($koneksi);

        $_SESSION['berhasil'] = "Data buku berhasil diedit!";
        header("location: " . $_SERVER['HTTP_REFERER']);

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = "Data buku gagal diedit: " . $e->getMessage();
        header("location: " . $_SERVER['HTTP_REFERER']);
    }

    // Reset autocommit
    mysqli_autocommit($koneksi, TRUE);

} elseif ($_GET['act'] == "hapus") {
    $id_buku = $_GET['id'];

    // Begin transaction
    mysqli_autocommit($koneksi, FALSE);

    try {
        // Check if any units are currently borrowed
        $check_borrowed = mysqli_query($koneksi, "SELECT COUNT(*) as borrowed FROM buku_unit WHERE id_buku = '$id_buku' AND status = 'dipinjam'");
        $borrowed_data = mysqli_fetch_assoc($check_borrowed);

        if ($borrowed_data['borrowed'] > 0) {
            throw new Exception("Tidak dapat menghapus buku karena masih ada unit yang sedang dipinjam");
        }

        // Delete all units first (foreign key constraint)
        $delete_units = "DELETE FROM buku_unit WHERE id_buku = '$id_buku'";
        if (!mysqli_query($koneksi, $delete_units)) {
            throw new Exception("Gagal menghapus unit buku");
        }

        // Delete book
        $delete_book = "DELETE FROM buku WHERE id_buku = '$id_buku'";
        if (!mysqli_query($koneksi, $delete_book)) {
            throw new Exception("Gagal menghapus data buku");
        }

        // Commit transaction
        mysqli_commit($koneksi);

        $_SESSION['berhasil'] = "Data buku berhasil dihapus!";
        header("location: " . $_SERVER['HTTP_REFERER']);

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($koneksi);
        $_SESSION['gagal'] = "Data buku gagal dihapus: " . $e->getMessage();
        header("location: " . $_SERVER['HTTP_REFERER']);
    }

    // Reset autocommit
    mysqli_autocommit($koneksi, TRUE);
}
?>