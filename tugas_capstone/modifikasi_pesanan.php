<?php
include 'koneksi.php';

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id='$id'");
    header("Location: modifikasi_pesanan.php");
}

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function konfirmasi() {
            return confirm('Yakin ingin menghapus data ini?');
        }
    </script>
</head>
<body>

<header class="header">
    <h1>Daftar Pesanan Paket Wisata</h1>
    <a href="index.php" class="btn">Kembali</a>
</header>

<table class="table">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Telp</th>
        <th>Tanggal</th>
        <th>Hari</th>
        <th>Peserta</th>
        <th>Pelayanan</th>
        <th>Harga Paket</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['nama']; ?></td>
        <td><?= $row['telp']; ?></td>
        <td><?= $row['tanggal_pesan']; ?></td>
        <td><?= $row['hari']; ?></td>
        <td><?= $row['peserta']; ?></td>
        <td><?= $row['pelayanan']; ?></td>
        <td>Rp <?= number_format($row['harga_paket']); ?></td>
        <td>Rp <?= number_format($row['total_tagihan']); ?></td>
        <td>
            <a href="edit_pesanan.php?id=<?= $row['id']; ?>" class="btn">Edit</a>
            <a href="?hapus=<?= $row['id']; ?>" onclick="return konfirmasi()" class="btn danger">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>