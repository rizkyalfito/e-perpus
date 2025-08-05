<?php
// Script to regenerate barcodes for all books in the system
// This ensures all barcodes follow the BK000### format

include "config/koneksi.php";

echo "Starting barcode regeneration for all books...\n";

// Get all books
$query = "SELECT id_buku, judul_buku, j_buku_baik, j_buku_rusak FROM buku";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error fetching books: " . mysqli_error($koneksi));
}

$total_books = mysqli_num_rows($result);
echo "Found $total_books books to process.\n\n";

$success_count = 0;
$error_count = 0;

while ($book = mysqli_fetch_assoc($result)) {
    $id_buku = $book['id_buku'];
    $judul = $book['judul_buku'];
    
    echo "Processing book ID $id_buku: $judul\n";
    
    // Begin transaction
    mysqli_autocommit($koneksi, FALSE);
    
    try {
        // Check if any units are currently borrowed
        $check_borrowed = "SELECT COUNT(*) as borrowed FROM buku_unit WHERE id_buku = $id_buku AND status = 'dipinjam'";
        $borrowed_result = mysqli_query($koneksi, $check_borrowed);
        $borrowed_data = mysqli_fetch_assoc($borrowed_result);
        
        if ($borrowed_data['borrowed'] > 0) {
            echo "  - Skipped: {$borrowed_data['borrowed']} units currently borrowed\n";
            mysqli_rollback($koneksi);
            $error_count++;
            continue;
        }
        
        // Delete existing units for this book
        $delete_units = "DELETE FROM buku_unit WHERE id_buku = $id_buku";
        if (!mysqli_query($koneksi, $delete_units)) {
            throw new Exception("Failed to delete existing units: " . mysqli_error($koneksi));
        }
        
        // Generate new barcodes
        $j_buku_baik = intval($book['j_buku_baik']);
        $j_buku_rusak = intval($book['j_buku_rusak']);
        
        $unit_index = 1;
        
        // Insert buku_unit untuk buku baik
        for ($i = 1; $i <= $j_buku_baik; $i++) {
            $barcode = 'BK' . str_pad($id_buku, 5, '0', STR_PAD_LEFT) . '-' . str_pad($unit_index, 3, '0', STR_PAD_LEFT);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'baik', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Failed to insert unit: " . mysqli_error($koneksi));
            }
            $unit_index++;
        }
        
        // Insert buku_unit untuk buku rusak
        for ($i = 1; $i <= $j_buku_rusak; $i++) {
            $barcode = 'BK' . str_pad($id_buku, 5, '0', STR_PAD_LEFT) . '-' . str_pad($unit_index, 3, '0', STR_PAD_LEFT);
            $insert_unit = "INSERT INTO buku_unit (id_buku, barcode, kondisi, status) VALUES ($id_buku, '$barcode', 'rusak', 'tersedia')";
            if (!mysqli_query($koneksi, $insert_unit)) {
                throw new Exception("Failed to insert unit: " . mysqli_error($koneksi));
            }
            $unit_index++;
        }
        
        // Commit transaction
        mysqli_commit($koneksi);
        
        $total_units = $j_buku_baik + $j_buku_rusak;
        echo "  - Success: Generated $total_units new barcodes\n";
        $success_count++;
        
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "  - Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
    
    // Reset autocommit
    mysqli_autocommit($koneksi, TRUE);
}

echo "\nBarcode regeneration completed!\n";
echo "Successfully processed: $success_count books\n";
echo "Errors encountered: $error_count books\n";
echo "Total books: $total_books\n";

mysqli_close($koneksi);
?>
