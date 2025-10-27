<?php
include 'includes/header.php';
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Koleksi Hijab | Landnic</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<section id="produk">
  <h2>Koleksi Hijab Landnic</h2>
  <div class="produk-container">
    <?php
    $sql = "SELECT id_produk, nama_produk, deskripsi, warna, harga, foto, stok FROM produk WHERE stok > 0 ORDER BY id_produk DESC";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($p = mysqli_fetch_assoc($result)) {
        $id = (int)$p['id_produk'];
        $nama = htmlspecialchars($p['nama_produk']);
        $desc = htmlspecialchars($p['deskripsi']);
        $warna = htmlspecialchars($p['warna']);
        $harga = number_format($p['harga'], 0, ',', '.');
        $foto = htmlspecialchars($p['foto']);

        echo "
        <div class='produk-card'>
          <img src='assets/img/{$foto}' alt='{$nama}'>
          <h3>{$nama}</h3>
          <p><b>Deskripsi:</b> {$desc}</p>
          <p><b>Warna:</b> {$warna}</p>
          <p class='harga'><b>Rp {$harga}</b></p>
          <a href='tambah_keranjang.php?id={$id}' class='btn-tambah'>Tambah ke Keranjang</a>
        </div>
        ";
      }
    } else {
      echo "<p>Tidak ada produk tersedia.</p>";
    }
    ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<style>
.btn-tambah {
  display:inline-block;
  background:#875A8B;
  color:#fff;
  padding:8px 15px;
  border-radius:6px;
  text-decoration:none;
}
.btn-tambah:hover { background:#6b4470; }
</style>

</body>
</html>
