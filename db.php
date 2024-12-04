<?php
$servername = "localhost"; // Biasanya 'localhost' jika menggunakan server lokal
$username = "root";        // Ganti dengan username MySQL kamu, default adalah 'root'
$password = "";            // Ganti dengan password MySQL kamu, jika ada
$dbname = "inventorychristo";  // Nama database yang digunakan

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
