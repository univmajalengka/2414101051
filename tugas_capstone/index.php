<?php
// index.php - Halaman Beranda
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Wisata UMKM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>Wisata UMKM Nusantara 🍃</h1>
</header>

<section class="hero-bg">
    <div class="hero-content">
        <h2>Jelajahi Keindahan Wisata Lokal🏝️</h2>
        <p>Temukan destinasi alam terbaik Indonesia dari gunung, pantai, hingga wisata alam pegunungan.
Nikmati perjalanan santai dengan pemandangan ikonik dan pengalaman lokal yang berkesan 📸
        </p>

        <div class="hero-menu">
            <a href="index.php" class="btn">Beranda</a>
            <a href="pemesanan.php" class="btn">Pemesanan</a>
            <a href="modifikasi_pesanan.php" class="btn">Data Pesanan</a>
        </div>
    </div>
</section>


<section class="paket">
    <h2>Daftar Paket Wisata</h2>

    <div class="paket-container">

        <!-- BROMO -->
        <div class="card">
            <img src="img/bromo.jpg" alt="Wisata Bromo">
            <h3>Wisata Bromo 🌄</h3>
            <p>
                Menikmati sunrise legendaris Gunung Bromo dengan suasana pegunungan yang sejuk dan menenangkan.
                <br><br>
                🏨 Rekomendasi penginapan hotel bintang ⭐⭐⭐  
                <br>
                🌄 View langsung pegunungan dan lautan pasir  
                <br>
                🚙 Akses jeep wisata kawasan Bromo
            </p>
            <a href="https://youtu.be/NcLvfuVVjFY" target="_blank" class="video">Lihat Video</a>
            <a href="pemesanan.php?paket=1" class="btn">Pesan Sekarang</a>
        </div>

        <!-- KARIMUNJAWA -->
        <div class="card">
            <img src="img/karimun.jpg" alt="Wisata Karimunjawa">
            <h3>Wisata Karimunjawa 🏝️</h3>
            <p>
                Pantai tropis dengan air laut jernih, cocok untuk healing dan liburan santai.
                <br><br>
                🏨 Penginapan resort / hotel bintang ⭐⭐⭐  
                <br>
                🤿 Snorkeling terumbu karang dan ikan warna-warni<br>
                🏖️ Pantai pasir putih & laut biru jernih<br>
            </p>
            <a href="https://youtu.be/2Uu0UOcpC4k" target="_blank" class="video">Lihat Video</a>
            <a href="pemesanan.php?paket=2" class="btn">Pesan Sekarang</a>
        </div>

        <!-- POSONG -->
        <div class="card">
            <img src="img/posong.jpg" alt="Wisata Posong">
            <h3>Wisata Taman Posong 🌄</h3>
            <p>
                Wisata alam pegunungan Temanggung dengan panorama Gunung Sindoro dan Sumbing.
                <br><br>
                🏨 Penginapan homestay & hotel bintang ⭐⭐⭐  
                <br>
                🌅 Spot sunrise & kabut pagi  
                <br>
                📸 Area foto alam terbuka
            </p>
            <a href="https://youtu.be/9-e5Wqq2Gmk" target="_blank" class="video">Lihat Video</a>
            <a href="pemesanan.php?paket=3" class="btn">Pesan Sekarang</a>
        </div>

        <!-- MELASTI -->
        <div class="card">
            <img src="img/melasti2.jpg" alt="Wisata Pantai Melasti">
            <h3>Wisata Pantai Melasti 🌊</h3>
            <p>
                Pantai eksotis di Bali dengan tebing megah dan air laut biru jernih.
                <br><br>
                🏨 Hotel bintang ⭐⭐⭐⭐ area Bali Selatan  
                <br>
                🌊 Pantai bersih dengan tebing ikonik  
                <br>
                🌅 Sunset view terbaik
            </p>
            <a href="https://youtu.be/p8vgiuRo7bA" target="_blank" class="video">Lihat Video</a>
            <a href="pemesanan.php?paket=4" class="btn">Pesan Sekarang</a>
        </div>

    </div>
</section>

<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> Wisata UMKM</p>
</footer>

</body>
</html>
