<?php
// index.php
include 'koneksi.php';

$paket = [
    1 => [
        'nama' => 'Farmhouse Lembang',
        'deskripsi' => 'Nuansa Eropa dengan rumah Hobbit dan peternakan domba lucu.',
        'gambar' => 'img/farm.jpg',
        'video' => 'https://youtu.be/T1JFssmTEpo?si=MaPUKhymOYiG8H3N'
    ],
    2 => [
        'nama' => 'The Lodge Maribaya',
        'deskripsi' => 'Wisata alam hutan pinus dengan wahana foto Sky Tree yang viral.',
        'gambar' => 'img/maribaya.jpg',
        'video' => 'https://youtu.be/zavr1wp9YJE?si=RKmP5qgulXw2pElA'
    ],
    3 => [
        'nama' => 'Orchid Forest Cikole',
        'deskripsi' => 'Taman anggrek terluas di tengah hutan pinus yang sejuk.',
        'gambar' => 'img/cikole.jpg',
        'video' => 'https://youtu.be/XRPawnnj5sc?si=tr17iyP3nt1PyxBC'
    ],
    4 => [
        'nama' => 'Dusun Bambu',
        'deskripsi' => 'Pusat kuliner dan rekreasi danau dengan perahu sampan.',
        'gambar' => 'img/bambu.jpg',
        'video' => 'https://youtu.be/zEX4WLMj2-4?si=y75N8nUpUaKXuEbC'
    ]
];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Beranda - Paket Wisata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>Selamat Datang di Desa Wisata Lembang</h1>
    </div>

    <div class="nav">
        <a href="index.php">Beranda</a>
        <a href="daftar_paket.php">Daftar Paket Wisata</a>
        <a href="daftar_pesanan.php">Modifikasi Pesanan</a>
    </div>

    <div class="container">
        <h2>Rekomendasi Paket Wisata UMKM - Lembang</h2>
        <p class="small">Klik "Pesan Sekarang" untuk langsung menuju form pemesanan paket yang dipilih.</p>

        <div class="paket-grid">
            <?php foreach($paket as $id => $p): ?>
            <div class="card">
                <img src="<?php echo $p['gambar']; ?>" alt="<?php echo $p['nama']; ?>">
                <div class="card-body">
                    <h3><?php echo $p['nama']; ?></h3>
                    <p class="small"><?php echo $p['deskripsi']; ?></p>
                    <a href="pemesanan.php?paket=<?php echo $id; ?>" class="btn btn-blue">Pesan Sekarang</a>
                    <a href="<?php echo $p['video']; ?>" target="_blank" class="link-video">Lihat Video</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</body>
</html>
