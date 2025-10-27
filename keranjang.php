<?php
// keranjang.php
session_start(); // ⬅️ Wajib di baris pertama
include 'koneksi.php'; // Hubungkan database

// --- 1. AMBIL DATA KERANJANG DARI SESSION ---
if (!isset($_SESSION['keranjang'])) {
 $_SESSION['keranjang'] = [];
}
$isi_keranjang = $_SESSION['keranjang'];
$total_belanja = 0; 

// ⚠️ BARIS INI KITA HAPUS/NONAKTIFKAN: $conn = $koneksi;
// Kita akan langsung menggunakan $koneksi dari koneksi.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Landnic</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section id="keranjang-page" style="max-width: 1000px; margin: 50px auto; padding: 20px;">
        <h2>Keranjang Belanja Anda</h2>

        <div id="keranjang-container">
            <?php if (empty($isi_keranjang)): ?>
                
                <p id="keranjang-kosong-msg" style="font-size: 1.1em; padding: 20px; border: 1px dashed #ccc; text-align: center;">
                    Keranjang Anda masih kosong. Ayo, <a href="produk.php" style="color: #875A8B;">belanja hijab cantik</a>!
                </p>

            <?php else: ?>
                
                <table id="cart-table" style="width: 100%; border-collapse: collapse; margin-bottom: 30px; text-align: left;">
                    <thead>
                        <tr style="background-color: #f4f4f4;">
                            <th style="padding: 10px;">Produk</th>
                            <th style="padding: 10px;">Warna</th>
                            <th style="padding: 10px; text-align: center;">Harga Satuan</th>
                            <th style="padding: 10px; text-align: center;">Kuantitas</th>
                            <th style="padding: 10px; text-align: right;">Subtotal</th>
                            <th style="padding: 10px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <?php 
                    foreach ($isi_keranjang as $id_produk => $jumlah):
                        
                        // SANITASI ID PRODUK (PENTING UNTUK KEAMANAN DAN KONSISTENSI)
                        $id_produk = (int)$id_produk;

                        // QUERY DATABASE UNTUK MENGAMBIL DETAIL PRODUK
                        $q = "SELECT nama_produk, warna, harga FROM produk WHERE id_produk = $id_produk";
                        $result = mysqli_query($koneksi, $q); // ⬅️ PERUBAHAN: Gunakan $koneksi
                        
                        // Periksa hasil query
                        if ($result && $data = mysqli_fetch_assoc($result)):
                            $subtotal = $data['harga'] * $jumlah;
                            $total_belanja += $subtotal;
                    ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                            <td style="padding: 10px;"><?php echo htmlspecialchars($data['warna']); ?></td>
                            <td style="padding: 10px; text-align: center;">Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                            <td style="padding: 10px; text-align: center;">
                                <?php echo $jumlah; ?> 
                            </td>
                            <td style="padding: 10px; text-align: right;">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            <td style="padding: 10px; text-align: center;">
                                <a href="hapus_keranjang.php?id=<?php echo $id_produk; ?>" style="color: red; text-decoration: none;">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        // Jika produk ditemukan di session tetapi TIDAK di database
                        elseif (is_array($isi_keranjang) && array_key_exists($id_produk, $isi_keranjang)): 
                            // Ini hanya untuk debugging, bisa dihapus setelah masalah teratasi
                            // echo "<tr style='color: red;'><td colspan='6'>Produk ID $id_produk gagal dimuat (cek DB/query).</td></tr>";
                            unset($_SESSION['keranjang'][$id_produk]); // Hapus item yang invalid
                        endif;
                    endforeach; 
                    ?>
                    </tbody>
                    <tfoot style="border-top: 2px solid #333;">
                        <tr>
                            <td colspan="4" style="padding: 10px; font-weight: bold; text-align: right;">Total Belanja</td>
                            <td colspan="2" style="padding: 10px; font-weight: bold; text-align: right;">
                                <span id="total-harga-display" style="color: #875A8B; display: block;">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                            </td>
                        </tr>
                    </tfoot> 
                </table>
                
                <div id="cart-summary" style="float: right; text-align: right; padding-top: 15px;">
                    <a href="checkout.php" class="btn checkout-btn" style="background-color: #875A8B; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                        Lanjut ke Pembayaran
                    </a>
                </div>
                
                <div style="clear: both;"></div>
            <?php endif; ?>

        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
</body>
</html>