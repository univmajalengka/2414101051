<?php
include 'koneksi.php';

define('H_PENGINAPAN', 1000000);
define('H_TRANSPORT', 1200000);
define('H_SERVICE', 500000);

$nama = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$telp = mysqli_real_escape_string($koneksi, $_POST['telp'] ?? '');
$tgl = $_POST['tgl'] ?? '';
$hari = (int)($_POST['hari'] ?? 0);
$peserta = (int)($_POST['peserta'] ?? 0);
$paket_id = (int)($_POST['paket_id'] ?? 0);

$layanan_arr = $_POST['layanan'] ?? [];
// pastikan nilai numeric
$harga_paket = 0;
if (is_array($layanan_arr) && count($layanan_arr) > 0) {
    foreach($layanan_arr as $l) {
        $harga_paket += (int)$l;
    }
}
$total = $hari * $peserta * $harga_paket;
$layanan_str = is_array($layanan_arr) ? implode(", ", $layanan_arr) : '';

// validasi minimal
if (empty($nama) || empty($telp) || empty($tgl) || $hari <= 0 || $peserta <= 0) {
    echo "<script>alert('Data tidak lengkap. Pastikan semua field terisi.'); window.history.back();</script>";
    exit;
}

// simpan
$sql = "INSERT INTO pesanan (nama, telp, tanggal_pesan, hari, peserta, pelayanan, harga_paket, total_tagihan, paket_id)
        VALUES (
            '". $nama ."',
            '". $telp ."',
            '". $tgl ."',
            ". $hari .",
            ". $peserta .",
            '". mysqli_real_escape_string($koneksi, $layanan_str) ."',
            ". (int)$harga_paket .",
            ". (int)$total .",
            ". $paket_id ."
        )";

if (mysqli_query($koneksi, $sql)) {
    header("Location: daftar_pesanan.php");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
