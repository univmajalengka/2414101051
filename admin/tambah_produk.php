<?php
// admin/tambah_produk.php
// File ini di-include oleh admin/admin.php, sehingga $koneksi sudah tersedia.
include '../koneksi.php';
// Cek apakah formulir telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dan terapkan keamanan (mysqli_real_escape_string)
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $warna = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $harga = $_POST['harga']; // Angka
    $stok = $_POST['stok'];
    
    // UPLOAD GAMBAR
    $foto_nama = $_FILES['gambar']['name']; // NAMA INPUT FILE DISESUAIKAN DENGAN FORM DI BAWAH
    $foto_tmp = $_FILES['gambar']['tmp_name'];
    
    $status_upload = true;
    $foto_baru = '';

    // Proses upload foto
    if (!empty($foto_nama)) {
        $ekstensi = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $foto_baru = uniqid('prod_') . '.' . $ekstensi;
        
        // Folder tujuan: '../img/produk/' 
        $folder_tujuan = '../img/produk/' . $foto_baru;
        
        if (!move_uploaded_file($foto_tmp, $folder_tujuan)) {
            $status_upload = false;
            $error_msg = "Gagal mengupload foto! Pastikan folder '../img/produk/' sudah dibuat dan memiliki izin tulis.";
        }
    } else {
        $error_msg = "Foto produk harus diupload!";
        $status_upload = false;
    }

    if ($status_upload) {
        // PERBAIKAN: Menggunakan $koneksi (prosedural)
        $query = "INSERT INTO produk (nama_produk, deskripsi, warna, harga, foto, stok) 
                  VALUES ('$nama', '$deskripsi', '$warna', '$harga', '$foto_baru', '$stok')";

        if (mysqli_query($koneksi, $query)) {
            // Redirect kembali ke halaman daftar produk
            header('Location: admin.php?page=produk&status=success');
            exit();
        } else {
            $error_msg = "Error saat menyimpan data ke database: " . mysqli_error($koneksi);
        }
    }
}
?>

<h3>Tambah Produk Baru</h3>

<?php if (isset($error_msg)): ?>
    <div style="color: red; margin-bottom: 15px;"><?php echo $error_msg; ?></div>
<?php endif; ?>

<form action="admin.php?page=tambah_produk" method="POST" enctype="multipart/form-data" class="form-admin">
    
    <label for="nama_produk">Nama Produk:</label>
    <input type="text" id="nama_produk" name="nama_produk" required><br><br>

    <label for="harga">Harga (Rp):</label>
    <input type="number" step="1" id="harga" name="harga" required><br><br> <label for="warna">Warna Produk:</label>
    <input type="text" id="warna" name="warna" required><br><br>

    <label for="stok">Stok:</label>
    <input type="number" id="stok" name="stok" required><br><br>
    
    <label for="deskripsi">Deskripsi:</label>
    <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea><br><br>

    <label for="gambar">Gambar Produk:</label>
    <input type="file" id="gambar" name="gambar" required><br><br> 
    
    <button type="submit" name="submit">Simpan Produk</button>
    <a href="admin.php?page=produk">Batal</a>
</form>