<?php
session_start();

// Pastikan file koneksi ada dan benar
include 'koneksi.php';

// Cek apakah variabel koneksi ada dan valid
if (!isset($koneksi) || !$koneksi) {
    // Jika koneksi di file koneksi.php bernama $conn, buat alias ke $koneksi
    if (isset($conn)) {
        $koneksi = $conn;
    } else {
        die("Koneksi database tidak ditemukan atau gagal.");
    }
}

if (isset($_GET['id'])) {
    $id_produk = (int)$_GET['id'];

    // Pastikan koneksi digunakan dengan benar
    $cek = mysqli_query($koneksi, "SELECT nama_produk, warna FROM produk WHERE id_produk = $id_produk");

    if ($cek && mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_assoc($cek);

        $nama_produk = htmlspecialchars($data['nama_produk']) . ' (' . htmlspecialchars($data['warna']) . ')';
        $pesan = "Produk {$nama_produk} berhasil ditambahkan ke keranjang.";

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        if (isset($_SESSION['keranjang'][$id_produk])) {
            $_SESSION['keranjang'][$id_produk]++;
        } else {
            $_SESSION['keranjang'][$id_produk] = 1;
        }

        $pesan_js_aman = json_encode($pesan);
        echo "<script>
                alert($pesan_js_aman);
                window.location.href = 'produk.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Produk tidak ditemukan.');
                window.location.href = 'produk.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID produk tidak valid.');
            window.location.href = 'produk.php';
          </script>";
}
?>
