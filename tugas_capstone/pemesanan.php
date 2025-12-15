<?php
include 'koneksi.php';

// harga layanan tetap
define('H_PENGINAPAN', 1000000);
define('H_TRANSPORT', 1200000);
define('H_SERVICE', 500000);

// paket (sama seperti index)
$paket_list = [
    1 => ['nama'=>'Farmhouse + Floating Market','harga_nama'=>'Farmhouse'],
    2 => ['nama'=>'The Lodge Maribaya Adventure','harga_nama'=>'Maribaya'],
    3 => ['nama'=>'Orchid Forest Cikole','harga_nama'=>'Orchid'],
    4 => ['nama'=>'Dusun Bambu Healing Trip','harga_nama'=>'Dusun']
];

$paket_id = isset($_GET['paket']) ? (int)$_GET['paket'] : 0;
$paket_nama = $paket_id && isset($paket_list[$paket_id]) ? $paket_list[$paket_id]['nama'] : '';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form Pemesanan Paket</title>
    <link rel="stylesheet" href="style.css">
    <script>
    // fungsi JS untuk validasi & hitung
    function cek() {
        const nama = document.getElementById('nama').value.trim();
        const telp = document.getElementById('telp').value.trim();
        const tgl = document.getElementById('tgl').value;
        const hari = parseInt(document.getElementById('hari').value) || 0;
        const peserta = parseInt(document.getElementById('peserta').value) || 0;

        if (!nama || !telp || !tgl || hari <= 0 || peserta <= 0) {
            alert('Semua field harus diisi dan hari/peserta > 0.');
            return false;
        }
        // pastikan ada pilihan layanan minimal 1
        const layanan = document.querySelectorAll('input[name="layanan[]"]:checked');
        if (layanan.length === 0) {
            if (!confirm('Anda belum memilih layanan (penginapan/transport/service). Lanjut saja?')) {
                return false;
            }
        }
        return true;
    }

    function hitung() {
        const hari = parseInt(document.getElementById('hari').value) || 0;
        const peserta = parseInt(document.getElementById('peserta').value) || 0;
        const cekboxes = document.querySelectorAll('input[name="layanan[]"]:checked');
        let hargaPaket = 0;
        cekboxes.forEach(cb => { hargaPaket += parseInt(cb.value); });

        const total = hari * peserta * hargaPaket;
        document.getElementById('harga_paket').value = hargaPaket;
        document.getElementById('total_tagihan').value = total;
    }

    function resetForm() {
        document.getElementById('formPesan').reset();
        document.getElementById('harga_paket').value = '';
        document.getElementById('total_tagihan').value = '';
    }
    </script>
</head>
<body>
<div class="header"><h1>Form Pemesanan</h1></div>
<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="daftar_paket.php">Daftar Paket Wisata</a>
    <a href="daftar_pesanan.php">Modifikasi Pesanan</a>
</div>

<div class="container">
    <h2>Pesan Paket: <?php echo htmlspecialchars($paket_nama ?: 'Pilih Paket'); ?></h2>
    <form id="formPesan" method="post" action="simpan_pesanan.php" onsubmit="return cek()">
        <input type="hidden" name="paket_id" value="<?php echo $paket_id; ?>">
        <div class="form-group">
            <label>Nama Pemesan</label>
            <input type="text" id="nama" name="nama">
        </div>
        <div class="form-group">
            <label>Nomor HP / Telp</label>
            <input type="text" id="telp" name="telp">
        </div>
        <div class="form-group">
            <label>Tanggal Pesan</label>
            <input type="date" id="tgl" name="tgl">
        </div>
        <div class="row">
            <div class="col form-group">
                <label>Waktu Pelaksanaan (jumlah hari)</label>
                <input type="number" id="hari" name="hari" min="1" value="1">
            </div>
            <div class="col form-group">
                <label>Jumlah Peserta</label>
                <input type="number" id="peserta" name="peserta" min="1" value="1">
            </div>
        </div>

        <div class="form-group">
            <label>Pelayanan Paket Perjalanan (centang yang disediakan)</label>
            <label><input type="checkbox" name="layanan[]" value="<?php echo H_PENGINAPAN; ?>"> Penginapan (Rp 1.000.000)</label>
            <label><input type="checkbox" name="layanan[]" value="<?php echo H_TRANSPORT; ?>"> Transportasi (Rp 1.200.000)</label>
            <label><input type="checkbox" name="layanan[]" value="<?php echo H_SERVICE; ?>"> Service / Makanan (Rp 500.000)</label>
        </div>

        <div class="row">
            <div class="col form-group">
                <label>Harga Paket Perjalanan (otomatis)</label>
                <input type="text" id="harga_paket" name="harga_paket" readonly>
            </div>
            <div class="col form-group">
                <label>Jumlah Tagihan (otomatis)</label>
                <input type="text" id="total_tagihan" name="total_tagihan" readonly>
            </div>
        </div>

        <div class="form-group">
            <button type="button" onclick="hitung()" class="btn">Hitung</button>
            <button type="submit" class="btn" style="background:#28a745;">Simpan</button>
            <button type="button" onclick="resetForm()" class="btn" style="background:#6c757d;">Reset</button>
        </div>
    </form>
</div>
</body>
</html>
