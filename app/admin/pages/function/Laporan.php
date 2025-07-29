<?php
include "../../../../config/koneksi.php";

// Function untuk format tanggal Indonesia
function formatTanggalIndonesia($tanggal) {
    $bulan = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    );
    
    $tanggal_array = explode('-', $tanggal);
    if (count($tanggal_array) == 3) {
        return $tanggal_array[2] . ' ' . $bulan[(int)$tanggal_array[1]] . ' ' . $tanggal_array[0];
    }
    return $tanggal;
}

// Function untuk hitung denda
function hitungDenda($tanggal_kembali, $tanggal_dikembalikan = null) {
    if (empty($tanggal_dikembalikan)) return 0;
    
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

// Function untuk header laporan
function headerLaporan($judul, $filter = '') {
    global $koneksi;
    
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<meta charset='utf-8'>";
    echo "<title>$judul</title>";
    echo "<link rel='stylesheet' href='../../../../assets/bower_components/bootstrap/dist/css/bootstrap.min.css'>";
    echo "<link rel='icon' type='icon' href='../../../../assets/dist/img/logo_app.png'>";
    echo "<style>";
    echo "body { font-family: 'Quicksand', sans-serif; margin: 20px; }";
    echo ".header { margin-bottom: 30px; }";
    echo ".logo-left { float: left; height: 80px; width: 80px; }";
    echo ".logo-right { float: right; height: 80px; width: 80px; }";
    echo ".header-text { text-align: center; margin: 0 100px; }";
    echo ".clear { clear: both; }";
    echo "table { width: 100%; margin-top: 20px; }";
    echo "th { background-color: #f5f5f5; }";
    echo ".text-right { text-align: right; }";
    echo ".text-center { text-align: center; }";
    echo ".footer { margin-top: 30px; text-align: center; }";
    echo ".section-title { margin-top: 40px; margin-bottom: 20px; font-size: 16px; font-weight: bold; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px; }";
    echo ".summary-box { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #3c8dbc; }";
    echo ".highlight-row { background-color: #e8f4fd !important; }";
    echo "@media print { .no-print { display: none; } .page-break { page-break-before: always; } }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    
    // Header dengan logo
    echo "<div class='header'>";
    echo "<img src='../../../../assets/dist/img/logo_app.png' class='logo-left'>";
    echo "<img src='../../../../assets/dist/img/mtsn1-luwu.png' class='logo-right'>";
    echo "<div class='header-text'>";
    echo "<h3 style='margin: 0; font-weight: bold;'>SISTEM INFORMASI PERPUSTAKAAN</h3>";
    
    // Ambil data identitas
    $sql_identitas = mysqli_query($koneksi, "SELECT * FROM identitas LIMIT 1");
    if ($row_identitas = mysqli_fetch_assoc($sql_identitas)) {
        echo "<p style='margin: 5px 0; font-size: 12px;'>" . $row_identitas['alamat_app'] . "</p>";
        echo "<p style='margin: 5px 0; font-size: 12px;'>Email: " . $row_identitas['email_app'] . " | Telp: " . $row_identitas['nomor_hp'] . "</p>";
    }
    echo "</div>";
    echo "<div class='clear'></div>";
    echo "</div>";
    
    echo "<hr style='border: 2px solid #000;'>";
    echo "<h4 style='text-align: center; margin: 20px 0;'>$judul</h4>";
    if (!empty($filter)) {
        echo "<p style='text-align: center; margin: 10px 0; font-style: italic;'>$filter</p>";
    }
}

// Function untuk footer laporan
function footerLaporan() {
    echo "<div class='footer no-print' style='margin-top: 40px;'>";
    echo "<button onclick='window.print()' class='btn btn-primary'>Cetak Laporan</button> ";
    echo "<button onclick='window.close()' class='btn btn-default'>Tutup</button>";
    echo "</div>";
    
    echo "<div class='footer' style='margin-top: 50px; display: none;' id='print-footer'>";
    echo "<div style='float: right; width: 200px; text-align: center;'>";
    echo "<p>Bekasi, " . date('d F Y') . "</p>";
    echo "<p>Petugas Perpustakaan</p>";
    echo "<br><br><br>";
    echo "<p>________________________</p>";
    echo "</div>";
    echo "<div class='clear'></div>";
    echo "</div>";
    
    echo "<script>";
    echo "window.addEventListener('beforeprint', function() {";
    echo "  document.getElementById('print-footer').style.display = 'block';";
    echo "});";
    echo "window.addEventListener('afterprint', function() {";
    echo "  document.getElementById('print-footer').style.display = 'none';";
    echo "});";
    echo "</script>";
    
    echo "</body>";
    echo "</html>";
}

if (isset($_GET['aksi'])) {
    
    if ($_GET['aksi'] == "rentang_tanggal") {
        $tanggal_mulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
        $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
        $jenis_laporan = isset($_POST['jenis_laporan']) ? $_POST['jenis_laporan'] : 'gabungan';
        
        $filter_text = "Periode: " . formatTanggalIndonesia($tanggal_mulai) . " s/d " . formatTanggalIndonesia($tanggal_selesai);
        
        if ($jenis_laporan == 'gabungan') {
            // Laporan Gabungan
            headerLaporan("LAPORAN PERPUSTAKAAN PERIODE TANGGAL", $filter_text);
            
            // Summary Box - Query statistik yang diperbaiki
            echo "<div class='summary-box'>";
            echo "<h5 style='margin: 0 0 10px 0;'><i class='fa fa-info-circle'></i> Ringkasan Periode</h5>";
            
            // Hitung statistik peminjaman
            $sql_peminjaman = mysqli_query($koneksi, "
                SELECT COUNT(*) as total_peminjaman
                FROM peminjaman 
                WHERE tanggal_peminjaman BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
            ");
            $stats_peminjaman = mysqli_fetch_assoc($sql_peminjaman);
            $total_peminjaman = $stats_peminjaman['total_peminjaman'];
            
            // Hitung statistik pengembalian
            $sql_pengembalian = mysqli_query($koneksi, "
                SELECT COUNT(*) as total_pengembalian, SUM(CAST(denda AS UNSIGNED)) as total_denda
                FROM peminjaman 
                WHERE kondisi_buku_saat_dikembalikan != '' 
                AND kondisi_buku_saat_dikembalikan IS NOT NULL
                AND DATE(tanggal_pengembalian) BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
            ");
            $stats_pengembalian = mysqli_fetch_assoc($sql_pengembalian);
            $total_pengembalian = $stats_pengembalian['total_pengembalian'];
            $total_denda = $stats_pengembalian['total_denda'] ? $stats_pengembalian['total_denda'] : 0;
            
            echo "<div class='row'>";
            echo "<div class='col-md-3'><strong>Total Peminjaman:</strong> " . $total_peminjaman . " buku</div>";
            echo "<div class='col-md-3'><strong>Total Pengembalian:</strong> " . $total_pengembalian . " buku</div>";
            echo "<div class='col-md-3'><strong>Total Denda:</strong> Rp " . number_format($total_denda, 0, ',', '.') . "</div>";
            echo "<div class='col-md-3'><strong>Total Aktivitas:</strong> " . ($total_peminjaman + $total_pengembalian) . "</div>";
            echo "</div>";
            echo "</div>";
            
            // Tabel Gabungan
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr class='highlight-row'>";
            echo "<th width='4%'>No</th>";
            echo "<th width='8%'>Jenis</th>";
            echo "<th width='10%'>Tanggal</th>";
            echo "<th width='18%'>Nama Anggota</th>";
            echo "<th width='20%'>Judul Buku</th>";
            echo "<th width='12%'>Tgl Peminjaman</th>";
            echo "<th width='12%'>Tgl Jatuh Tempo</th>";
            echo "<th width='8%'>Kondisi</th>";
            echo "<th width='8%'>Denda</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $no = 1;
            $total_peminjaman_periode = 0;
            $total_pengembalian_periode = 0;
            $total_denda_periode = 0;
            
            // Query untuk mendapatkan semua aktivitas dalam rentang tanggal
            $sql_aktivitas = mysqli_query($koneksi, "
                (SELECT 
                    'PEMINJAMAN' as jenis,
                    tanggal_peminjaman as tanggal_aktivitas,
                    nama_anggota,
                    judul_buku,
                    tanggal_peminjaman,
                    tanggal_pengembalian,
                    kondisi_buku_saat_dipinjam as kondisi,
                    '0' as denda,
                    kondisi_buku_saat_dikembalikan
                FROM peminjaman 
                WHERE tanggal_peminjaman BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
                
                UNION ALL
                
                (SELECT 
                    'PENGEMBALIAN' as jenis,
                    DATE(tanggal_pengembalian) as tanggal_aktivitas,
                    nama_anggota,
                    judul_buku,
                    tanggal_peminjaman,
                    tanggal_pengembalian,
                    kondisi_buku_saat_dikembalikan as kondisi,
                    denda,
                    kondisi_buku_saat_dikembalikan
                FROM peminjaman 
                WHERE kondisi_buku_saat_dikembalikan != '' 
                AND kondisi_buku_saat_dikembalikan IS NOT NULL
                AND DATE(tanggal_pengembalian) BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
                
                ORDER BY tanggal_aktivitas ASC, jenis ASC
            ");
            
            while ($row = mysqli_fetch_assoc($sql_aktivitas)) {
                $bg_color = '';
                if ($row['jenis'] == 'PEMINJAMAN') {
                    $bg_color = 'style="background-color: #e8f5e8;"';
                    $total_peminjaman_periode++;
                } else {
                    $bg_color = 'style="background-color: #fff2e8;"';
                    $total_pengembalian_periode++;
                    $total_denda_periode += (int)$row['denda'];
                }
                
                echo "<tr $bg_color>";
                echo "<td>" . $no++ . "</td>";
                echo "<td><strong>" . $row['jenis'] . "</strong></td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_aktivitas']) . "</td>";
                echo "<td>" . $row['nama_anggota'] . "</td>";
                echo "<td>" . $row['judul_buku'] . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_peminjaman']) . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_pengembalian']) . "</td>";
                echo "<td>" . $row['kondisi'] . "</td>";
                echo "<td class='text-right'>";
                if ($row['jenis'] == 'PENGEMBALIAN' && (int)$row['denda'] > 0) {
                    echo "Rp " . number_format((int)$row['denda'], 0, ',', '.');
                } else {
                    echo "-";
                }
                echo "</td>";
                echo "</tr>";
            }
            
            if ($no == 1) {
                echo "<tr><td colspan='9' class='text-center'>Tidak ada aktivitas dalam rentang tanggal tersebut</td></tr>";
            } else {
                // Row total
                echo "<tr class='highlight-row' style='font-weight: bold;'>";
                echo "<td colspan='6' class='text-right'>TOTAL:</td>";
                echo "<td class='text-center'>$total_peminjaman_periode Pinjam | $total_pengembalian_periode Kembali</td>";
                echo "<td>-</td>";
                echo "<td class='text-right'>Rp " . number_format($total_denda_periode, 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
        } else {
            // Laporan Terpisah
            headerLaporan("LAPORAN PERPUSTAKAAN PERIODE TANGGAL", $filter_text);
            
            // Section Peminjaman
            echo "<div class='section-title'>📚 DATA PEMINJAMAN</div>";
            
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th width='5%'>No</th>";
            echo "<th width='20%'>Nama Anggota</th>";
            echo "<th width='25%'>Judul Buku</th>";
            echo "<th width='15%'>Tanggal Peminjaman</th>";
            echo "<th width='15%'>Tanggal Jatuh Tempo</th>";
            echo "<th width='10%'>Kondisi Buku</th>";
            echo "<th width='10%'>Status</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $no = 1;
            $total_pinjam = 0;
            $sql_pinjam = mysqli_query($koneksi, "
                SELECT * FROM peminjaman 
                WHERE tanggal_peminjaman BETWEEN '$tanggal_mulai' AND '$tanggal_selesai' 
                ORDER BY tanggal_peminjaman ASC
            ");
            
            while ($row = mysqli_fetch_assoc($sql_pinjam)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nama_anggota'] . "</td>";
                echo "<td>" . $row['judul_buku'] . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_peminjaman']) . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_pengembalian']) . "</td>";
                echo "<td>" . $row['kondisi_buku_saat_dipinjam'] . "</td>";
                
                if (empty($row['kondisi_buku_saat_dikembalikan'])) {
                    echo "<td><span style='color: red;'>Belum Dikembalikan</span></td>";
                } else {
                    echo "<td><span style='color: green;'>Sudah Dikembalikan</span></td>";
                }
                echo "</tr>";
                $total_pinjam++;
            }
            
            if ($total_pinjam == 0) {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada data peminjaman dalam periode tersebut</td></tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
            echo "<div style='margin-bottom: 20px;'><strong>Total Peminjaman: $total_pinjam buku</strong></div>";
            
            // Section Pengembalian
            echo "<div class='section-title page-break'>🔄 DATA PENGEMBALIAN</div>";
            
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th width='5%'>No</th>";
            echo "<th width='20%'>Nama Anggota</th>";
            echo "<th width='25%'>Judul Buku</th>";
            echo "<th width='12%'>Tgl Peminjaman</th>";
            echo "<th width='12%'>Tgl Pengembalian</th>";
            echo "<th width='10%'>Kondisi Pinjam</th>";
            echo "<th width='10%'>Kondisi Kembali</th>";
            echo "<th width='6%'>Denda</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $no = 1;
            $total_kembali = 0;
            $total_denda = 0;
            
            $sql_kembali = mysqli_query($koneksi, "
                SELECT * FROM peminjaman 
                WHERE kondisi_buku_saat_dikembalikan != '' 
                AND kondisi_buku_saat_dikembalikan IS NOT NULL
                AND DATE(tanggal_pengembalian) BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
                ORDER BY tanggal_pengembalian ASC
            ");
            
            while ($row = mysqli_fetch_assoc($sql_kembali)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nama_anggota'] . "</td>";
                echo "<td>" . $row['judul_buku'] . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_peminjaman']) . "</td>";
                echo "<td>" . formatTanggalIndonesia($row['tanggal_pengembalian']) . "</td>";
                echo "<td>" . $row['kondisi_buku_saat_dipinjam'] . "</td>";
                echo "<td>" . $row['kondisi_buku_saat_dikembalikan'] . "</td>";
                echo "<td class='text-right'>Rp " . number_format((int)$row['denda'], 0, ',', '.') . "</td>";
                echo "</tr>";
                
                $total_kembali++;
                $total_denda += (int)$row['denda'];
            }
            
            if ($total_kembali == 0) {
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data pengembalian dalam periode tersebut</td></tr>";
            } else {
                echo "<tr style='background-color: #f9f9f9; font-weight: bold;'>";
                echo "<td colspan='7' class='text-right'>Total Denda:</td>";
                echo "<td class='text-right'>Rp " . number_format($total_denda, 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
            echo "<div style='margin-top: 20px;'>";
            echo "<strong>Total Pengembalian: $total_kembali buku</strong><br>";
            echo "<strong>Total Denda: Rp " . number_format($total_denda, 0, ',', '.') . "</strong>";
            echo "</div>";
        }
        
        footerLaporan();
        
    } elseif ($_GET['aksi'] == "nama_anggota") {
        $nama_anggota = mysqli_real_escape_string($koneksi, $_POST['nama_anggota']);
        
        headerLaporan("LAPORAN PEMINJAMAN PER ANGGOTA", "Nama Anggota: $nama_anggota");
        
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th width='5%'>No</th>";
        echo "<th width='25%'>Judul Buku</th>";
        echo "<th width='12%'>Tanggal Peminjaman</th>";
        echo "<th width='12%'>Tanggal Pengembalian</th>";
        echo "<th width='10%'>Kondisi Pinjam</th>";
        echo "<th width='10%'>Kondisi Kembali</th>";
        echo "<th width='8%'>Status</th>";
        echo "<th width='8%'>Denda</th>";
        echo "<th width='10%'>Keterangan</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $no = 1;
        $total_pinjam = 0;
        $total_kembali = 0;
        $total_denda = 0;
        $belum_kembali = 0;
        
        $sql = mysqli_query($koneksi, "SELECT p.*, u.kode_user 
                                      FROM peminjaman p 
                                      LEFT JOIN user u ON p.nama_anggota = u.fullname 
                                      WHERE p.nama_anggota = '$nama_anggota' 
                                      ORDER BY p.tanggal_peminjaman DESC");
        
        while ($row = mysqli_fetch_assoc($sql)) {
            $status_kembali = !empty($row['kondisi_buku_saat_dikembalikan']);
            $denda = 0;
            $keterangan = '';
            
            if ($status_kembali) {
                $denda = (int)$row['denda'];
                $total_kembali++;
            } else {
                $belum_kembali++;
                // Cek apakah terlambat
                $today = new DateTime();
                $due_date = new DateTime($row['tanggal_pengembalian']);
                if ($today > $due_date) {
                    $diff = $today->diff($due_date);
                    $keterangan = "Terlambat " . $diff->days . " hari";
                    $denda = $diff->days * 1000; // Denda potensial
                }
            }
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['judul_buku'] . "</td>";
            echo "<td>" . formatTanggalIndonesia($row['tanggal_peminjaman']) . "</td>";
            echo "<td>" . formatTanggalIndonesia($row['tanggal_pengembalian']) . "</td>";
            echo "<td>" . $row['kondisi_buku_saat_dipinjam'] . "</td>";
            echo "<td>" . ($status_kembali ? $row['kondisi_buku_saat_dikembalikan'] : '-') . "</td>";
            echo "<td>" . ($status_kembali ? '<span style="color: green;">Kembali</span>' : '<span style="color: red;">Pinjam</span>') . "</td>";
            echo "<td class='text-right'>Rp " . number_format($denda, 0, ',', '.') . "</td>";
            echo "<td>" . $keterangan . "</td>";
            echo "</tr>";
            
            $total_pinjam++;
            $total_denda += ($status_kembali ? $denda : 0);
        }
        
        if ($total_pinjam == 0) {
            echo "<tr><td colspan='9' class='text-center'>Tidak ada data peminjaman untuk anggota tersebut</td></tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        
        echo "<div style='margin-top: 20px;'>";
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<strong>Ringkasan:</strong><br>";
        echo "• Total Peminjaman: $total_pinjam buku<br>";
        echo "• Sudah Dikembalikan: $total_kembali buku<br>";
        echo "• Belum Dikembalikan: $belum_kembali buku<br>";
        echo "• Total Denda: Rp " . number_format($total_denda, 0, ',', '.') . "";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        footerLaporan();
        
    } elseif ($_GET['aksi'] == "semua_peminjaman") {
        headerLaporan("LAPORAN SEMUA PEMINJAMAN BUKU", "Data Terkini Semua Peminjaman");
        
        // Hitung statistik real dari database
        $sql_stats_all = mysqli_query($koneksi, "
            SELECT 
                COUNT(*) as total_semua_peminjaman,
                SUM(CASE WHEN kondisi_buku_saat_dikembalikan != '' AND kondisi_buku_saat_dikembalikan IS NOT NULL THEN 1 ELSE 0 END) as total_sudah_kembali,
                SUM(CASE WHEN kondisi_buku_saat_dikembalikan = '' OR kondisi_buku_saat_dikembalikan IS NULL THEN 1 ELSE 0 END) as total_belum_kembali,
                SUM(CAST(denda AS UNSIGNED)) as total_denda_keseluruhan
            FROM peminjaman
        ");
        $stats_all = mysqli_fetch_assoc($sql_stats_all);
        
        // Summary box dengan data real
        echo "<div class='summary-box'>";
        echo "<h5 style='margin: 0 0 10px 0;'><i class='fa fa-chart-bar'></i> Statistik Keseluruhan</h5>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'><strong>Total Peminjaman:</strong> " . $stats_all['total_semua_peminjaman'] . " buku</div>";
        echo "<div class='col-md-3'><strong>Sudah Dikembalikan:</strong> " . $stats_all['total_sudah_kembali'] . " buku</div>";
        echo "<div class='col-md-3'><strong>Belum Dikembalikan:</strong> " . $stats_all['total_belum_kembali'] . " buku</div>";
        echo "<div class='col-md-3'><strong>Total Denda:</strong> Rp " . number_format($stats_all['total_denda_keseluruhan'], 0, ',', '.') . "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th width='4%'>No</th>";
        echo "<th width='18%'>Nama Anggota</th>";
        echo "<th width='22%'>Judul Buku</th>";
        echo "<th width='10%'>Tgl Pinjam</th>";
        echo "<th width='10%'>Tgl Kembali</th>";
        echo "<th width='8%'>Kondisi Pinjam</th>";
        echo "<th width='8%'>Kondisi Kembali</th>";
        echo "<th width='8%'>Status</th>";
        echo "<th width='6%'>Denda</th>";
        echo "<th width='6%'>Ket.</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $no = 1;
        $total_semua = 0;
        $total_kembali = 0;
        $total_denda = 0;
        
        $sql = mysqli_query($koneksi, "SELECT p.*, u.kode_user 
                                      FROM peminjaman p 
                                      LEFT JOIN user u ON p.nama_anggota = u.fullname 
                                      ORDER BY p.tanggal_peminjaman DESC");
        
        while ($row = mysqli_fetch_assoc($sql)) {
            $status_kembali = !empty($row['kondisi_buku_saat_dikembalikan']);
            $denda = (int)$row['denda'];
            $keterangan = '';
            
            if (!$status_kembali) {
                // Cek keterlambatan untuk yang belum kembali
                $today = new DateTime();
                $due_date = new DateTime($row['tanggal_pengembalian']);
                if ($today > $due_date) {
                    $diff = $today->diff($due_date);
                    $keterangan = "T-" . $diff->days;
                }
            }
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['nama_anggota'] . "</td>";
            echo "<td>" . $row['judul_buku'] . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal_peminjaman'])) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal_pengembalian'])) . "</td>";
            echo "<td>" . $row['kondisi_buku_saat_dipinjam'] . "</td>";
            echo "<td>" . ($status_kembali ? $row['kondisi_buku_saat_dikembalikan'] : '-') . "</td>";
            echo "<td>" . ($status_kembali ? '<span style="color: green; font-size: 11px;">Kembali</span>' : '<span style="color: red; font-size: 11px;">Pinjam</span>') . "</td>";
            echo "<td class='text-right' style='font-size: 11px;'>Rp " . number_format($denda, 0, ',', '.') . "</td>";
            echo "<td style='font-size: 11px;'>" . $keterangan . "</td>";
            echo "</tr>";
            
            $total_semua++;
            if ($status_kembali) $total_kembali++;
            $total_denda += $denda;
        }
        
        if ($total_semua == 0) {
            echo "<tr><td colspan='10' class='text-center'>Tidak ada data peminjaman dalam database</td></tr>";
        } else {
            // Row total dengan data real
            echo "<tr class='highlight-row' style='font-weight: bold;'>";
            echo "<td colspan='7' class='text-right'>TOTAL:</td>";
            echo "<td class='text-center'>$total_kembali / $total_semua</td>";
            echo "<td class='text-right' style='font-size: 11px;'>Rp " . number_format($total_denda, 0, ',', '.') . "</td>";
            echo "<td>-</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        
        echo "<div style='margin-top: 20px;'>";
        echo "<strong>Ringkasan: Total $total_semua peminjaman | Sudah Kembali: $total_kembali | Belum Kembali: " . ($total_semua - $total_kembali) . " | Total Denda: Rp " . number_format($total_denda, 0, ',', '.') . "</strong>";
        echo "</div>";
        
        footerLaporan();
    }
}
?>