<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Landnic</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section id="checkout-page">
        <h2>Lengkapi Pesanan Anda</h2>
        <div class="checkout-grid">
            
            <div id="shipping-details">
                <form id="checkout-form" onsubmit="submitOrder(event)">
                    
                    <h3>Detail Kontak Penerima</h3>
                    <label for="nama">Nama Lengkap*</label>
                    <input type="text" id="nama" name="nama" required>
                    
                    <label for="telepon">Nomor Telepon (WhatsApp)*</label>
                    <input type="tel" id="telepon" name="telepon" required>
                    
                    <h3>Detail Alamat Pengiriman</h3>
                    <label for="provinsi">Provinsi*</label>
                    <input type="text" id="provinsi" name="provinsi" placeholder="Contoh: Jawa Barat" required>

                    <label for="kota">Kota/Kabupaten*</label>
                    <input type="text" id="kota" name="kota" placeholder="Contoh: Bandung" required>

                    <label for="kecamatan">Kecamatan*</label>
                    <input type="text" id="kecamatan" name="kecamatan" placeholder="Contoh: Buah Batu" required>

                    <label for="desa">Desa/Kelurahan*</label>
                    <input type="text" id="desa" name="desa" placeholder="Contoh: Cijawura" required>

                    <label for="kodepos">Kode Pos*</label>
                    <input type="text" id="kodepos" name="kodepos" required>

                    <label for="alamat_lengkap">Alamat Lengkap (Nama Jalan, No. Rumah, Patokan)*</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required></textarea>

                    <h3>Opsi Pengiriman</h3>
                    <label for="kurir">Pilih Kurir:</label>
                    <select id="kurir" onchange="hitungOngkir()">
                        <option value="JNT">J&T Express</option>
                        <option value="JNE">JNE Reguler</option>
                        <option value="SICEPAT">SiCepat REG</option>
                    </select>
                    <p class="small-info">Ongkir ke alamat Anda: <span id="ongkir-display">Rp 0</span></p>

                    <h3>Metode Pembayaran</h3>
                    <div id="payment-options">
                        <label>
                            <input type="radio" name="pembayaran" value="Transfer Bank BRI" required>
                            Transfer Bank **BRI**
                        </label>
                        <label>
                            <input type="radio" name="pembayaran" value="Transfer Bank BCA" required>
                            Transfer Bank **BCA**
                        </label>
                        <label>
                            <input type="radio" name="pembayaran" value="Transfer Bank Mandiri" required>
                            Transfer Bank **Mandiri**
                        </label>

                        <label>
                            <input type="radio" name="pembayaran" value="DANA" required>
                            E-Wallet **DANA**
                        </label>
                        <label>
                            <input type="radio" name="pembayaran" value="Alfamart" required>
                            Bayar di Gerai **Alfamart**
                        </label>
                        
                        <label>
                            <input type="radio" name="pembayaran" value="COD" required>
                            **COD** (Bayar di Tempat)
                        </label>
                    </div>
                    
                    <button type="submit" class="btn">Konfirmasi & Lanjutkan ke Pembayaran</button>
                </form>
            </div>

            <div id="order-summary-box">
                <h3>Ringkasan Pesanan</h3>
                <div id="checkout-items">
                    </div>

                <div class="checkout-total">
                    <p>Subtotal: <span id="subtotal-display"></span></p>
                    <p>Ongkos Kirim: <span id="ongkir-summary">Rp 0</span></p>
                    <hr>
                    <p>Total Akhir: <span id="final-total-display"></span></p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/cart.js"></script> 
</body>
</html>
<?php include '../koneksi.php';