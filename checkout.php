<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong! Silakan belanja dulu.');location='produk.php';</script>";
    exit();
}

$isi_keranjang = $_SESSION['keranjang'];
$total_subtotal = 0;
$ongkos_kirim = 18000;
$checkout_items_html = "";

foreach ($isi_keranjang as $id_produk => $jumlah) {
    $result = mysqli_query($conn, "SELECT nama_produk, warna, harga FROM produk WHERE id_produk = '$id_produk'");
    if ($result && $data = mysqli_fetch_assoc($result)) {
        $subtotal = $data['harga'] * $jumlah;
        $total_subtotal += $subtotal;
        $checkout_items_html .= "
            <div class='checkout-item-detail'>
                <span>{$data['nama_produk']} ({$data['warna']}) x {$jumlah}</span>
                <span>Rp " . number_format($subtotal, 0, ',', '.') . "</span>
            </div>";
    }
}

$total_akhir = $total_subtotal + $ongkos_kirim;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $kecamatan = $_POST['kecamatan'];
    $desa = $_POST['desa'];
    $kodepos = $_POST['kodepos'];
    $alamat_lengkap = $_POST['alamat_lengkap'];
    $kurir = $_POST['kurir'];
    $pembayaran = $_POST['pembayaran'];
    $tanggal_pesan = date('Y-m-d H:i:s');

    $alamat = "$alamat_lengkap, $desa, $kecamatan, $kota, $provinsi, $kodepos";

    mysqli_query($conn, "
        INSERT INTO pesanan 
        (nama, telepon, alamat, kurir, metode_pembayaran, total_harga, ongkos_kirim, tanggal_pesanan, status_pesanan)
        VALUES 
        ('$nama', '$telepon', '$alamat', '$kurir', '$pembayaran', '$total_akhir', '$ongkos_kirim', '$tanggal_pesan', 'Baru')
    ");

    $id_pesanan = mysqli_insert_id($conn);

    foreach ($isi_keranjang as $id_produk => $jumlah) {
        $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'"));
        $harga = $produk['harga'];
        mysqli_query($conn, "
            INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_satuan)
            VALUES ('$id_pesanan','$id_produk','$jumlah','$harga')
        ");
    }

    unset($_SESSION['keranjang']);

    echo "<script>
        alert('Pesanan berhasil dibuat! Klik OK untuk melihat nota.');
        window.location.href='nota.php?id=$id_pesanan';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Landnic</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section id="checkout-page">
    <h2>Lengkapi Pesanan Anda</h2>

    <div class="checkout-grid">

        <div id="shipping-details">
            <form id="checkout-form" method="POST">
                <h3>Detail Kontak Penerima</h3>

                <label>Nama Lengkap*</label>
                <input type="text" name="nama" required>

                <label>No. Telepon(WhatsApp)*</label>
                <input type="tel" name="telepon" required>

                <h3>Detail Alamat Pengiriman</h3> 
                <label for="provinsi">Provinsi*</label> 
                <input type="text" id="provinsi" name="provinsi" placeholder="Contoh: Jawa Barat" required>

                <label for="kota">Kota/Kabupaten*</label> 
                <input type="text" id="kota" name="kota" placeholder="Contoh: Bandung" required>

                <label for="kecamatan">Kecamatan*</label> 
                <input type="text" id="kecamatan" name="kecamatan" placeholder="Contoh: Buah Batu" required>

                <label for="desa">Desa/Kelurahan*</label> 
                <input type="text" id="desa" name="desa" placeholder="Contoh: Cijawura" required>

                <label>Kode Pos*</label>
                <input type="text" name="kodepos" required>

                <label for="alamat_lengkap">Alamat Lengkap (Nama Jalan, No. Rumah, Patokan)*</label> 
                <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required></textarea>

                <h3>Opsi Pengiriman</h3>
                <label for="kurir">Pilih Kurir:</label> 
                <select id="kurir" name="kurir"> 
                    <option value="JNT">J&T Express (Rp 18.000)</option> 
                    <option value="JNE">JNE Reguler (Rp 18.000)</option> 
                    <option value="SICEPAT">SiCepat REG (Rp 18.000)</option> 
                </select> 

                <h3>Metode Pembayaran</h3>
                <div id="payment-options">
                    <label><input type="radio" name="pembayaran" value="BRI" required> Transfer Bank BRI</label>
                    <label><input type="radio" name="pembayaran" value="BCA"> Transfer Bank BCA</label>
                    <label><input type="radio" name="pembayaran" value="Mandiri"> Transfer Bank Mandiri</label>
                    <label><input type="radio" name="pembayaran" value="DANA"> E-Wallet DANA</label>
                    <label><input type="radio" name="pembayaran" value="Alfamart"> Bayar di Gerai **Alfamart**</label>
                    <label><input type="radio" name="pembayaran" value="COD"> COD (Bayar di Tempat)</label>
                </div>

                <button type="submit" class="btn">Konfirmasi & Lanjutkan</button>
            </form>
        </div>

        <div id="order-summary-box">
            <h3>Ringkasan Pesanan</h3>
            <div id="checkout-items">
                <?= $checkout_items_html; ?>
            </div>
            <div class="checkout-total">
                <p>Subtotal <span>Rp <?= number_format($total_subtotal, 0, ',', '.'); ?></span></p>
                <p>Ongkos Kirim <span>Rp <?= number_format($ongkos_kirim, 0, ',', '.'); ?></span></p>
                <p id="final-total-display">Total: Rp <?= number_format($total_akhir, 0, ',', '.'); ?></p>
            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const cartItems = JSON.parse(localStorage.getItem('landnic_cart')) || [];
    const ongkir = 18000;

    const itemsWithSubtotal = cartItems.map(item => ({
        ...item,
        subtotal: item.harga * item.kuantitas
    }));

    const subtotal = itemsWithSubtotal.reduce((sum, item) => sum + item.subtotal, 0);
    const totalAkhir = subtotal + ongkir;

    const dataNota = {
        idPesanan: 'ID' + Date.now(),
        tanggal: new Date().toLocaleString('id-ID'),
        pembeli: {
            nama: form.nama.value,
            telepon: form.telepon.value,
            alamat: `${form.alamat_lengkap.value}, ${form.desa.value}, ${form.kecamatan.value}, ${form.kota.value}, ${form.provinsi.value} (${form.kodepos.value})`
        },
        pengiriman: {
            kurir: form.kurir.value,
            ongkir: ongkir
        },
        pembayaran: form.pembayaran.value,
        items: itemsWithSubtotal,
        subtotal: subtotal,
        totalAkhir: totalAkhir
    };

    localStorage.setItem('landnic_nota', JSON.stringify(dataNota));
    localStorage.removeItem('landnic_cart');

    window.location.href = 'nota.php';
});

</body>
</html>
