<?php
// koneksi.php
$host = "localhost";
$user = "root";
$password = ""; 
$database = "landnichijab_db"; 

// Pastikan variabel koneksi dinamai $koneksi
$koneksi = mysqli_connect($host, $user, $password, $database); 

if (mysqli_connect_errno()) {
    // Jika koneksi gagal, script akan berhenti di sini.
    echo "Koneksi database gagal: " . mysqli_connect_error();
    die(); 
}
// Variabel $koneksi akan digunakan di file lain.
?>