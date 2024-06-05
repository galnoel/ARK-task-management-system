<?php
// Read environment variables from .env file
$env = parse_ini_file('.env');

// Set database configuration
$host = $env['DB_HOST'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$db = $env['DB_NAME'];
$port = $env['DB_PORT'];

// Koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
