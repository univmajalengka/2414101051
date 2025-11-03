<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID Pesanan tidak ditemukan');location='produk.php';</script>";
    exit();
}

$id_pesanan = $_GET['id'];
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan'"));
if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan');location='produk.php';</script>";
    exit();
}

$detail = mysqli_query($koneksi, "SELECT dp.*, p.nama_produk, p.warna FROM detail_pesanan dp JOIN produk p ON dp.id_produk=p.id_produk WHERE dp.id_pesanan='$id_pesanan'");

$subtotal = 0;
while($item = mysqli_fetch_assoc($detail)) {
    $subtotal += $item['harga_satuan'] * $item['jumlah'];
}
$total_akhir = $subtotal + $pesanan['ongkos_kirim'];

$metode = $pesanan['metode_pembayaran'];
$instruksi = "";

switch($metode) {
    case 'Mandiri':
    case 'BCA':
    case 'BRI':
        $rekening = '';
        if ($metode == 'Mandiri') $rekening = '9991-2345-6789';
        else if ($metode == 'BCA') $rekening = '8880-1234-5678';
        else if ($metode == 'BRI') $rekening = '1122-3344-5566-7788';

        $instruksi = "
        <div class='instruksi'>
            <h4>Transfer Bank $metode</h4>
            <p><strong>Nama Bank:</strong> $metode</p>
            <p><strong>Nomor Rekening:</strong> $rekening (a.n. Landnic Hijab)</p>
            <p><strong>Jumlah Bayar:</strong> Rp ".number_format($total_akhir,0,',','.')."</p>
            <p class='warning-text'>⚠️ Penting: setelah transfer, kirim bukti pembayaran ke WhatsApp Landnic.</p>
        </div>";
        break;

    case 'DANA':
        $instruksi = "
        <div class='instruksi'>
            <h4>Transfer ke E-Wallet DANA</h4>
            <p><strong>Nama Akun:</strong> Landnic Official</p>
            <p><strong>Nomor HP/Akun DANA:</strong> 0812-3456-7890</p>
            <p><strong>Jumlah Bayar:</strong> Rp ".number_format($total_akhir,0,',','.')."</p>
            <p class='warning-text'>Mohon transfer sesuai nominal (tambahkan 3 digit terakhir ID Pesanan: ".substr($pesanan['id_pesanan'],-3).").</p>
        </div>";
        break;

    case 'Alfamart':
        $instruksi = "
        <div class='instruksi'>
            <h4>Bayar di Gerai Alfamart</h4>
            <p>Anda akan menerima kode pembayaran via WhatsApp Landnic dalam waktu 1x24 jam.</p>
            <p>Tunjukkan kode di kasir Alfamart dan bayar Rp ".number_format($total_akhir,0,',','.')."</p>
        </div>";
        break;

    case 'COD':
        $instruksi = "
        <div class='instruksi'>
            <h4>Pembayaran di Tempat (COD)</h4>
            <p>Pembayaran sebesar Rp ".number_format($total_akhir,0,',','.')." akan dilakukan kepada kurir J&T saat paket diterima.</p>
            <p class='warning-text'>Pastikan Anda menyiapkan uang tunai dan alamat sudah benar.</p>
        </div>";
        break;

    default:
        $instruksi = "<p>Metode pembayaran tidak dikenali. Silakan hubungi WhatsApp Landnic.</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>🎉 Pesanan Terkirim! | Landnic</title>
<style>
    body { font-family:'Poppins',sans-serif; background:#f8f8f8; margin:0; padding:20px; }
    .nota-box { background:#fff; padding:25px 30px; border-radius:16px; box-shadow:0 6px 20px rgba(0,0,0,0.08); margin-bottom:25px;}
    h2{font-size:1.8rem; color:#7E57C2; margin-bottom:20px; text-align:center;}
    h3{font-size:1.3rem; color:#6A1B9A; margin-bottom:12px;}
    h4{font-size:1.1rem; margin-bottom:8px; color:#6A1B9A;}
    p{margin:5px 0; font-size:0.95rem;}
    hr{border:none; border-top:1px solid #ddd; margin:18px 0;}
    .nota-item-row{display:flex; justify-content:space-between; margin:10px 0;}
    .grand-total{font-weight:bold; font-size:1.1rem; text-align:right;}
    .warning-text{color:#c00; font-size:0.9rem; margin-top:5px;}
    .total-box p span{float:right;}
    .call-to-action {display:flex; gap:15px; margin-top:25px; justify-content:center;}
    .btn{padding:12px 25px; border:none; cursor:pointer; border-radius:10px; font-weight:bold; transition:0.2s;}
    .print-btn{background:#7E57C2;color:#fff;box-shadow:0 3px 8px rgba(0,0,0,0.15);}
    .print-btn:hover{background:#6A1B9A;}
    .btn-secondary{background:#d1d5db;color:#333;}
    .btn-secondary:hover{background:#bcbcbc;}
@media print {
    .call-to-action, .instruksi {display:none;}
    body {padding:0;}
    #nota-page::after {
        content: "Terima kasih sudah belanja di Landnic. Pesanan Anda akan segera diproses.";
        display:block;
        text-align:center;
        font-size:0.95rem;
        margin-top:30px;
        color:#555;
    }
}
</style>
</head>
<body>

<section id="nota-page">
<h2>🎉 Pesanan Terkirim!</h2>

<div class="nota-box">
<h3>Detail Pengiriman</h3>
<p><strong>Nama:</strong> <?= $pesanan['nama'] ?></p>
<p><strong>Telepon:</strong> <?= $pesanan['telepon'] ?></p>
<p><strong>Alamat:</strong> <?= $pesanan['alamat'] ?></p>
<p><strong>Kurir:</strong> <?= $pesanan['kurir'] ?></p>
<p><strong>Tanggal Pesanan:</strong> <?= $pesanan['tanggal_pesanan'] ?></p>
<p><strong>ID Pesanan:</strong> <?= $pesanan['id_pesanan'] ?></p>
</div>

<div class="nota-box">
<h3>Rincian Pesanan</h3>
<?php
$detail = mysqli_query($conn, "SELECT dp.*, p.nama_produk, p.warna FROM detail_pesanan dp JOIN produk p ON dp.id_produk=p.id_produk WHERE dp.id_pesanan='$id_pesanan'");
$subtotal = 0;
while($item = mysqli_fetch_assoc($detail)) {
    $harga_item = $item['harga_satuan'] * $item['jumlah'];
    $subtotal += $harga_item;
    echo "<div class='nota-item-row'>
            <p><strong>{$item['nama_produk']}</strong> ({$item['warna']}) x {$item['jumlah']}</p>
            <p>Rp ".number_format($harga_item,0,',','.')."</p>
          </div>";
}
$total_akhir = $subtotal + $pesanan['ongkos_kirim'];
?>
<hr>
<p>Subtotal: <span>Rp <?= number_format($subtotal,0,',','.') ?></span></p>
<p>Ongkos Kirim: <span>Rp <?= number_format($pesanan['ongkos_kirim'],0,',','.') ?></span></p>
<p class="grand-total">Total: <span>Rp <?= number_format($total_akhir,0,',','.') ?></span></p>
</div>

<div class="nota-box total-box">
<h3>Metode Pembayaran</h3>
<p><?= $pesanan['metode_pembayaran'] ?></p>
<?= $instruksi ?>
</div>

<div class="call-to-action">
<button onclick="window.print()" class="btn print-btn">Cetak Nota</button>
<a href="produk.php" class="btn btn-secondary">Lanjut Belanja</a>
</div>

</section>

</body>
</html>
