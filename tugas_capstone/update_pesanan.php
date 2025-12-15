<?php
include 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$telp = mysqli_real_escape_string($koneksi, $_POST['telp'] ?? '');
$hari = (int)($_POST['hari'] ?? 0);
$peserta = (int)($_POST['peserta'] ?? 0);
$layanan_arr = $_POST['layanan'] ?? [];

$harga_paket = 0;
if (is_array($layanan_arr)) {
    foreach($layanan_arr as $l) $harga_paket += (int)$l;
}
$total = $hari * $peserta * $harga_paket;
$layanan_str = is_array($layanan_arr) ? implode(", ", $layanan_arr) : '';

$sql = "UPDATE pesanan SET
        nama = '". $nama ."',
        telp = '". $telp ."',
        hari = ". $hari .",
        peserta = ". $peserta .",
        pelayanan = '". mysqli_real_escape_string($koneksi, $layanan_str) ."',
        harga_paket = ". (int)$harga_paket .",
        total_tagihan = ". (int)$total ."
        WHERE id = ". $id;

if (mysqli_query($koneksi, $sql)) {
    header("Location: daftar_pesanan.php");
} else {
    echo "Error update: " . mysqli_error($koneksi);
}
?>
