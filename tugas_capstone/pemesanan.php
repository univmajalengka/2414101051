<?php
// pemesanan.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan Paket Wisata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>Form Pemesanan Paket Wisata</h1>
</header>

<section class="form-container">
    <form id="formPemesanan" action="proses_simpan.php" method="post">

        <label>Nama Pemesan</label>
        <input type="text" name="nama" required>

        <label>Nomor HP / Telp</label>
        <input type="text" name="telp" required>

        <label>Tanggal Pesan</label>
        <input type="date" name="tanggal_pesan" required>

        <label>Waktu Pelaksanaan Perjalanan (Hari)</label>
        <input type="number" id="hari" name="hari" required>

        <label>Pelayanan Paket Perjalanan</label>

        <div class="checkbox">
            <input type="checkbox" name="pelayanan[]" value="1000000">
            Penginapan (Rp 1.000.000)
        </div>

        <div class="checkbox">
            <input type="checkbox" name="pelayanan[]" value="1200000">
            Transportasi (Rp 1.200.000)
        </div>

        <div class="checkbox">
            <input type="checkbox" name="pelayanan[]" value="500000">
            Servis / Makan (Rp 500.000)
        </div>

        <label>Jumlah Peserta</label>
        <input type="number" id="peserta" name="peserta" required>

        <label>Harga Paket Perjalanan</label>
        <input type="number" id="harga_paket" name="harga_paket" readonly>

        <label>Jumlah Tagihan</label>
        <input type="number" id="total_tagihan" name="total_tagihan" readonly>

        <p id="errorMsg" style="color:red; display:none; margin-top:10px;">
            Semua data wajib diisi sebelum menghitung!
        </p>

        <div class="btn-group">
            <button type="submit" class="btn">Simpan</button>
            <button type="button" class="btn" onclick="hitungTotal()">Hitung</button>
            <button type="button" class="btn danger" onclick="resetForm()">Reset</button>
        </div>

    </form>
</section>

<script src="script.js"></script>
</body>
</html>
