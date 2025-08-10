<?php
//------------------------------::::::::::::::::::::------------------------------\\
// Dibuat oleh FA Team di PT. Pacifica Raya Technology \\
//------------------------------::::::::::::::::::::------------------------------\\
// File ini untuk emergency reset password admin
// Hapus file ini setelah digunakan untuk keamanan

include "config/koneksi.php";

// Reset password admin menjadi 'admin123'
$query = "UPDATE user SET password = 'admin123' WHERE username = 'admin' AND role = 'Admin'";
$sql = mysqli_query($koneksi, $query);

if ($sql) {
    echo "<h2>Password admin berhasil direset!</h2>";
    echo "<p>Username: admin</p>";
    echo "<p>Password baru: admin123</p>";
    echo "<p><a href='masuk'>Klik di sini untuk login</a></p>";
    echo "<p style='color: red;'>HAPUS FILE INI SETELAH DIGUNAKAN!</p>";
} else {
    echo "<h2>Gagal reset password admin!</h2>";
    echo "<p>Error: " . mysqli_error($koneksi) . "</p>";
}
?>
