<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "arkdb";
$port = 3309;

// Koneksi ke database
$conn = mysqli_connect($host, $user,$pass,$db, $port);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
