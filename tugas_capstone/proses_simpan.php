<?php
// proses_simpan.php
include 'koneksi.php';

// Ambil data dari form
$nama = $_POST['nama'];
$telp = $_POST['telp'];
$tanggal_pesan = $_POST['tanggal_pesan'];
$hari = $_POST['hari'];
$peserta = $_POST['peserta'];
$pelayanan = isset($_POST['pelayanan']) ? implode(', ', $_POST['pelayanan']) : '';
$harga_paket = $_POST['harga_paket'];
$total_tagihan = $_POST['total_tagihan'];

// Query simpan data
$sql = "INSERT INTO pesanan 
        (nama, telp, tanggal_pesan, hari, peserta, pelayanan, harga_paket, total_tagihan)
        VALUES 
        ('$nama', '$telp', '$tanggal_pesan', '$hari', '$peserta', '$pelayanan', '$harga_paket', '$total_tagihan')";

if (mysqli_query($koneksi, $sql)) {
    header("Location: modifikasi_pesanan.php");
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>