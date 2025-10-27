<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran | Landnic</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section id="nota-page">
        <div id="nota-container">
            <h2>🎉 Pesanan Terkirim!</h2>
            <p class="sub-heading">Terima kasih atas pesanan Anda. Berikut adalah detail nota pembayaran Anda:</p>
            
            <div id="ringkasan-pembeli" class="nota-box">
                </div>

            <div id="ringkasan-pesanan" class="nota-box">
                <h3>Rincian Pesanan</h3>
                <div id="nota-items-list">
                    </div>
            </div>

            <div id="nota-total" class="nota-box total-box">
                </div>
            
            <div id="instruksi-pembayaran" class="payment-instruction-box">
                </div>
            
            <div class="call-to-action">
                <p>Kami akan segera menghubungi Anda melalui WhatsApp untuk konfirmasi dan petunjuk pembayaran.</p>
                
                <button onclick="window.print()" class="btn print-btn" style="background-color: #6c757d; margin-right: 15px;">Cetak Nota</button> 
                
                <a href="produk.php" class="btn">Lanjut Belanja</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/cart.js"></script> 
</body>
</html>