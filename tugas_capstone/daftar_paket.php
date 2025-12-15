<?php include 'koneksi.php'; ?>
<?php

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
    <title>Daftar Paket</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header"><h1>Daftar Paket Wisata</h1></div>
<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="daftar_paket.php">Daftar Paket Wisata</a>
    <a href="daftar_pesanan.php">Modifikasi Pesanan</a>
</div>

<div class="container">
    <div class="paket-grid">
        <?php foreach($paket as $id => $p): ?>
        <div class="card">
            <img src="<?php echo $p['gambar']; ?>" alt="">
            <div class="card-body">
                <h3><?php echo $p['nama']; ?></h3>
                <p class="small"><?php echo $p['deskripsi']; ?></p>
                <a class="btn" href="pemesanan.php?paket=<?php echo $id; ?>">Pilih Paket</a>
                <a class="small" style="margin-left:8px;" target="_blank" href="<?php echo $p['video']; ?>">Video</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
