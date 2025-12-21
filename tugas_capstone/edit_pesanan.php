<?php
include 'koneksi.php';

// Ambil ID
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id='$id'");
$row = mysqli_fetch_assoc($data);

// Update data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $tanggal_pesan = $_POST['tanggal_pesan'];
    $hari = $_POST['hari'];
    $peserta = $_POST['peserta'];
    $pelayanan = isset($_POST['pelayanan']) ? implode(', ', $_POST['pelayanan']) : '';
    $harga_paket = $_POST['harga_paket'];
    $total_tagihan = $_POST['total_tagihan'];

    mysqli_query($koneksi, "UPDATE pesanan SET 
        nama='$nama',
        telp='$telp',
        tanggal_pesan='$tanggal_pesan',
        hari='$hari',
        peserta='$peserta',
        pelayanan='$pelayanan',
        harga_paket='$harga_paket',
        total_tagihan='$total_tagihan'
        WHERE id='$id'");

    header("Location: modifikasi_pesanan.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function hitungTotal() {
            let hari = document.getElementById('hari').value;
            let peserta = document.getElementById('peserta').value;
            let layanan = document.querySelectorAll('input[name="pelayanan[]"]:checked');
            let harga = 0;
            layanan.forEach(l => harga += parseInt(l.value));
            document.getElementById('harga_paket').value = harga;
            document.getElementById('total_tagihan').value = hari * peserta * harga;
        }
    </script>
</head>
<body>

<h2>Edit Pesanan Paket Wisata</h2>
<form method="post">
    <label>Nama Pemesan</label>
    <input type="text" name="nama" value="<?= $row['nama']; ?>" required>

    <label>No HP</label>
    <input type="text" name="telp" value="<?= $row['telp']; ?>" required>

    <label>Tanggal Pesan</label>
    <input type="date" name="tanggal_pesan" value="<?= $row['tanggal_pesan']; ?>" required>

    <label>Hari</label>
    <input type="number" id="hari" name="hari" value="<?= $row['hari']; ?>" oninput="hitungTotal()" required>

    <label>Peserta</label>
    <input type="number" id="peserta" name="peserta" value="<?= $row['peserta']; ?>" oninput="hitungTotal()" required>

    <label>Pelayanan</label><br>
    <?php $p = $row['pelayanan']; ?>
    <input type="checkbox" name="pelayanan[]" value="1000000" <?= strpos($p,'1000000')!==false?'checked':''; ?> onclick="hitungTotal()"> Penginapan<br>
    <input type="checkbox" name="pelayanan[]" value="1200000" <?= strpos($p,'1200000')!==false?'checked':''; ?> onclick="hitungTotal()"> Transportasi<br>
    <input type="checkbox" name="pelayanan[]" value="500000" <?= strpos($p,'500000')!==false?'checked':''; ?> onclick="hitungTotal()"> Makan<br>

    <label>Harga Paket</label>
    <input type="number" id="harga_paket" name="harga_paket" value="<?= $row['harga_paket']; ?>" readonly>

    <label>Total Tagihan</label>
    <input type="number" id="total_tagihan" name="total_tagihan" value="<?= $row['total_tagihan']; ?>" readonly>

    <button type="submit" name="update" class="btn">Update</button>
</form>

</body>
</html>
