<?php
include 'koneksi.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifikasi Pesanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header"><h1>Modifikasi Pesanan</h1></div>

<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="daftar_paket.php">Daftar Paket Wisata</a>
    <a href="daftar_pesanan.php">Modifikasi Pesanan</a>
</div>

<div class="container">
    <h2>Daftar Pesanan</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Hari</th>
                <th>Peserta</th>
                <th>Akomodasi</th>
                <th>Transportasi</th>
                <th>Service/Makanan</th>
                <th>Harga Paket</th>
                <th>Total Tagihan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $q = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");

            while ($r = mysqli_fetch_assoc($q)) {

                // Pecah layanan menjadi array angka
                $layanan = $r['pelayanan'] ? explode(", ", $r['pelayanan']) : [];

                // Deteksi Y/N
                $akomodasi = in_array("1000000", $layanan) ? "Y" : "N";
                $transport = in_array("1200000", $layanan) ? "Y" : "N";
                $service   = in_array("500000",  $layanan) ? "Y" : "N";

                echo "<tr>";
                echo "<td>".$r['id']."</td>";
                echo "<td>".htmlspecialchars($r['nama'])."</td>";
                echo "<td>".htmlspecialchars($r['telp'])."</td>";
                echo "<td>".$r['hari']."</td>";
                echo "<td>".$r['peserta']."</td>";

                // Y/N kolom baru
                echo "<td>".$akomodasi."</td>";
                echo "<td>".$transport."</td>";
                echo "<td>".$service."</td>";

                echo "<td>Rp ".number_format($r['harga_paket'])."</td>";
                echo "<td>Rp ".number_format($r['total_tagihan'])."</td>";

                echo "<td>
                    <a href='edit_pesanan.php?id=".$r['id']."' class='btn btn-blue'>Edit</a>
                    <a href='hapus_pesanan.php?id=".$r['id']."' class='btn btn-red' onclick=\"return confirm('Yakin hapus?')\">Delete</a>
                    </td>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>
