<?php
//------------------------------::::::::::::::::::::------------------------------\\
// Dibuat oleh FA Team di PT. Pacifica Raya Technology \\
//------------------------------::::::::::::::::::::------------------------------\\
$server = "localhost";
$username = "";
$password = "";
$database = "db_perpustakaan";

$koneksi = mysqli_connect("localhost","root","","db_perpustakaan");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}
