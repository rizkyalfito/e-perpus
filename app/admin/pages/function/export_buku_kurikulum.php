<?php
session_start();
include "../../../../config/koneksi.php";
require_once '../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!isset($_GET['id_kurikulum']) || empty($_GET['id_kurikulum'])) {
    die('ID Kurikulum tidak ditemukan');
}

$id_kurikulum = mysqli_real_escape_string($koneksi, $_GET['id_kurikulum']);
$action = isset($_GET['action']) ? $_GET['action'] : 'download';

// Get curriculum details
$kurikulum_query = mysqli_query($koneksi, "SELECT * FROM kurikulum WHERE id_kurikulum = '$id_kurikulum'");
$kurikulum = mysqli_fetch_assoc($kurikulum_query);

if (!$kurikulum) {
    die('Kurikulum tidak ditemukan');
}

// Get books for this curriculum
$query = "SELECT bk.*, b.judul_buku, b.pengarang, b.penerbit_buku, b.tahun_terbit, b.isbn, b.kategori_buku 
          FROM buku_kurikulum bk 
          JOIN buku b ON bk.id_buku = b.id_buku 
          WHERE bk.id_kurikulum = '$id_kurikulum' 
          ORDER BY b.judul_buku ASC";

$result = mysqli_query($koneksi, $query);
$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

if ($action == 'preview') {
    // For preview, we'll create a simple HTML table
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Preview Daftar Buku Kurikulum</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            body { padding: 20px; }
            .table { margin-top: 20px; }
            .header-info { margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header-info">
                <h2>Preview Daftar Buku Kurikulum</h2>
                <p><strong>Nama Kurikulum:</strong> <?= htmlspecialchars($kurikulum['nama_kurikulum']) ?></p>
                <p><strong>Kode Kurikulum:</strong> <?= htmlspecialchars($kurikulum['kode_kurikulum']) ?></p>
                <p><strong>Total Buku:</strong> <?= count($books) ?></p>
            </div>
            
            <?php if (empty($books)): ?>
                <div class="alert alert-warning">Tidak ada buku dalam kurikulum ini.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun Terbit</th>
                                <th>ISBN</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $index => $book): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($book['judul_buku']) ?></td>
                                <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                <td><?= htmlspecialchars($book['penerbit_buku']) ?></td>
                                <td><?= htmlspecialchars($book['tahun_terbit']) ?></td>
                                <td><?= htmlspecialchars($book['isbn']) ?></td>
                                <td><?= htmlspecialchars($book['kategori_buku']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <a href="export_buku_kurikulum.php?id_kurikulum=<?= $id_kurikulum ?>&action=download" class="btn btn-success">
                    <i class="fa fa-download"></i> Download Excel
                </a>
                <button onclick="window.close()" class="btn btn-default">Tutup</button>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('E-Perpus System')
        ->setLastModifiedBy('E-Perpus System')
        ->setTitle('Daftar Buku Kurikulum - ' . $kurikulum['nama_kurikulum'])
        ->setSubject('Daftar Buku Kurikulum')
        ->setDescription('Daftar buku dalam kurikulum ' . $kurikulum['nama_kurikulum']);

    // Header information - dimulai dari baris 1
    $sheet->setCellValue('A1', 'DAFTAR BUKU KURIKULUM');
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A2', 'Nama Kurikulum: ' . $kurikulum['nama_kurikulum']);
    $sheet->mergeCells('A2:G2');
    $sheet->getStyle('A2')->getFont()->setBold(true);

    $sheet->setCellValue('A3', 'Kode Kurikulum: ' . $kurikulum['kode_kurikulum']);
    $sheet->mergeCells('A3:G3');

    $sheet->setCellValue('A4', 'Total Buku: ' . count($books));
    $sheet->mergeCells('A4:G4');

    // Baris kosong
    $sheet->setCellValue('A5', '');

    // Headers untuk tabel - baris 6
    $headers = [
        'A6' => 'No',
        'B6' => 'Judul Buku',
        'C6' => 'Pengarang',
        'D6' => 'Penerbit',
        'E6' => 'Tahun Terbit',
        'F6' => 'ISBN',
        'G6' => 'Kategori'
    ];

    foreach ($headers as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }

    // Style headers
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4472C4']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ];

    $sheet->getStyle('A6:G6')->applyFromArray($headerStyle);

    // Add data buku dimulai dari baris 7
    $rowNum = 7;
    foreach ($books as $index => $book) {
        $sheet->setCellValue('A' . $rowNum, $index + 1);
        $sheet->setCellValue('B' . $rowNum, $book['judul_buku'] ?? '');
        $sheet->setCellValue('C' . $rowNum, $book['pengarang'] ?? '');
        $sheet->setCellValue('D' . $rowNum, $book['penerbit_buku'] ?? '');
        $sheet->setCellValue('E' . $rowNum, $book['tahun_terbit'] ?? '');
        $sheet->setCellValue('F' . $rowNum, $book['isbn'] ?? '');
        $sheet->setCellValue('G' . $rowNum, $book['kategori_buku'] ?? '');
        $rowNum++;
    }

    // Jika tidak ada buku, tampilkan pesan
    if (empty($books)) {
        $sheet->setCellValue('A7', 'Tidak ada buku dalam kurikulum ini');
        $sheet->mergeCells('A7:G7');
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7')->getFont()->setItalic(true);
        $rowNum = 8;
    }

    // Auto-size columns
    foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Add borders untuk tabel data
    if (!empty($books)) {
        $lastRow = $rowNum - 1;
        $sheet->getStyle('A6:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    } else {
        $sheet->getStyle('A6:G7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    // Set minimum column widths
    $sheet->getColumnDimension('A')->setWidth(5);   // No
    $sheet->getColumnDimension('B')->setWidth(30);  // Judul Buku
    $sheet->getColumnDimension('C')->setWidth(20);  // Pengarang
    $sheet->getColumnDimension('D')->setWidth(20);  // Penerbit
    $sheet->getColumnDimension('E')->setWidth(12);  // Tahun Terbit
    $sheet->getColumnDimension('F')->setWidth(15);  // ISBN
    $sheet->getColumnDimension('G')->setWidth(15);  // Kategori

    // Set row heights
    $sheet->getRowDimension(1)->setRowHeight(25);
    $sheet->getRowDimension(6)->setRowHeight(20);

    // Download Excel file
    $filename = 'Daftar_Buku_' . str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $kurikulum['nama_kurikulum']) . '_' . date('Y-m-d') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>