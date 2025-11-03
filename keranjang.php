<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_produk = (int)$_GET['id'];
    if (isset($_SESSION['keranjang'][$id_produk])) {
        if ($_GET['aksi'] == 'tambah') {
            $_SESSION['keranjang'][$id_produk]++;
        } elseif ($_GET['aksi'] == 'kurang') {
            $_SESSION['keranjang'][$id_produk]--;
            if ($_SESSION['keranjang'][$id_produk] <= 0) {
                unset($_SESSION['keranjang'][$id_produk]);
            }
        }
    }
    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    unset($_SESSION['keranjang'][$id_hapus]);
    $_SESSION['pesan_sukses'] = "Produk berhasil dihapus dari keranjang.";
    header("Location: keranjang.php");
    exit;
}

$isi_keranjang = $_SESSION['keranjang'];
$total_belanja = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Belanja | Landnic</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section id="keranjang-page" style="max-width: 1000px; margin: 50px auto; padding: 20px;">
  <h2 style="text-align:center;color:#875A8B;">Keranjang Belanja Anda</h2>

  <?php if (isset($_SESSION['pesan_sukses'])): ?>
      <div id="pesan" style="background-color:#dff0d8;color:#3c763d;padding:10px;border-radius:5px;margin:15px 0;text-align:center;">
          <?= $_SESSION['pesan_sukses']; ?>
      </div>
      <script>
        setTimeout(() => {
          const pesan = document.getElementById('pesan');
          if (pesan) pesan.style.display = 'none';
        }, 3000);
      </script>
      <?php unset($_SESSION['pesan_sukses']); ?>
  <?php endif; ?>

  <?php if (empty($isi_keranjang)): ?>
      <p style="text-align:center;border:1px dashed #ccc;padding:20px;font-size:1.1em;">
        Keranjang Anda masih kosong. <a href="produk.php" style="color:#875A8B;">Belanja sekarang</a>!
      </p>
  <?php else: ?>
      <table style="width:100%;border-collapse:collapse;margin-top:20px;">
        <thead style="background-color:#f9eef9;">
          <tr>
            <th style="padding:10px;">Produk</th>
            <th style="padding:10px;">Warna</th>
            <th style="padding:10px;text-align:center;">Harga Satuan</th>
            <th style="padding:10px;text-align:center;">Qty</th>
            <th style="padding:10px;text-align:right;">Subtotal</th>
            <th style="padding:10px;text-align:center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($isi_keranjang as $id_produk => $jumlah) {
              $result = mysqli_query($conn, "SELECT nama_produk, warna, harga FROM produk WHERE id_produk = $id_produk");
              if ($data = mysqli_fetch_assoc($result)) {
                  $harga = $data['harga'];
                  $subtotal = $harga * $jumlah;
                  $total_belanja += $subtotal;
                  echo "<tr style='border-bottom:1px solid #eee;'>
                          <td style='padding:10px;'>" . htmlspecialchars($data['nama_produk']) . "</td>
                          <td style='padding:10px;'>" . htmlspecialchars($data['warna']) . "</td>
                          <td style='padding:10px;text-align:center;'>Rp " . number_format($harga, 0, ',', '.') . "</td>
                          <td style='padding:10px;text-align:center;'>
                              <a href='keranjang.php?aksi=kurang&id=$id_produk' style='padding:4px 10px;background:#eee;border-radius:4px;text-decoration:none;'>–</a>
                              <span style='margin:0 8px;'>$jumlah</span>
                              <a href='keranjang.php?aksi=tambah&id=$id_produk' style='padding:4px 10px;background:#eee;border-radius:4px;text-decoration:none;'>+</a>
                          </td>
                          <td style='padding:10px;text-align:right;'>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                          <td style='padding:10px;text-align:center;'>
                              <a href='keranjang.php?hapus=$id_produk' style='color:red;text-decoration:none;'>Hapus</a>
                          </td>
                        </tr>";
              }
          }
          ?>
        </tbody>
        <tfoot>
          <tr style="border-top:2px solid #333;">
            <td colspan="4" style="text-align:right;font-weight:bold;">Total Belanja</td>
            <td colspan="2" style="text-align:right;font-weight:bold;color:#875A8B;">
              Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?>
            </td>
          </tr>
        </tfoot>
      </table>

      <div style="text-align:right;margin-top:30px;">
        <a href="checkout.php" style="background-color:#875A8B;color:white;padding:12px 25px;border-radius:5px;text-decoration:none;font-weight:bold;">
          Lanjut ke Pembayaran
        </a>
      </div>
  <?php endif; ?>

</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>
