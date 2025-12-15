<?php
include 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: daftar_pesanan.php"); exit; }

$res = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id = $id");
$data = mysqli_fetch_assoc($res);
if (!$data) { echo "Pesanan tidak ditemukan."; exit; }

$layanan_terpilih = $data['pelayanan'] ? explode(", ", $data['pelayanan']) : [];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header"><h1>Edit Pesanan #<?php echo $data['id']; ?></h1></div>
<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="daftar_paket.php">Daftar Paket Wisata</a>
    <a href="daftar_pesanan.php">Modifikasi Pesanan</a>
</div>

<div class="container">
    <form action="update_pesanan.php" method="post">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>">
        </div>
        <div class="form-group">
            <label>Nomor HP/Telp</label>
            <input type="text" name="telp" value="<?php echo htmlspecialchars($data['telp']); ?>">
        </div>
        <div class="row">
            <div class="col form-group">
                <label>Jumlah Hari</label>
                <input type="number" name="hari" value="<?php echo $data['hari']; ?>">
            </div>
            <div class="col form-group">
                <label>Jumlah Peserta</label>
                <input type="number" name="peserta" value="<?php echo $data['peserta']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Akomodasi / Pelayanan</label>
            <label><input type="checkbox" name="layanan[]" value="1000000" <?php if(in_array('1000000',$layanan_terpilih)) echo 'checked'; ?>> Penginapan (Rp 1.000.000)</label>
            <label><input type="checkbox" name="layanan[]" value="1200000" <?php if(in_array('1200000',$layanan_terpilih)) echo 'checked'; ?>> Transportasi (Rp 1.200.000)</label>
            <label><input type="checkbox" name="layanan[]" value="500000" <?php if(in_array('500000',$layanan_terpilih)) echo 'checked'; ?>> Service/Makanan (Rp 500.000)</label>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Harga Paket</label>
                <input type="text" name="harga_paket" value="<?php echo $data['harga_paket']; ?>" readonly>
            </div>
            <div class="col form-group">
                <label>Total Tagihan</label>
                <input type="text" name="total_tagihan" value="<?php echo $data['total_tagihan']; ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn" style="background:#28a745;">Update</button>
            <a class="btn" href="daftar_pesanan.php" style="background:#6c757d;">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
