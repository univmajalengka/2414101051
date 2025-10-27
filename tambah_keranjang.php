<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_produk = (int)$_GET['id'];
    $cek = mysqli_query($koneksi, "SELECT id_produk FROM produk WHERE id_produk=$id_produk");

    if (mysqli_num_rows($cek) > 0) {
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        if (isset($_SESSION['keranjang'][$id_produk])) {
            $_SESSION['keranjang'][$id_produk]++;
        } else {
            $_SESSION['keranjang'][$id_produk] = 1;
        }

        header("Location: keranjang.php");
        exit;
    } else {
        echo "Produk tidak ditemukan di database.";
    }
} else {
    echo "ID produk tidak dikirim.";
}
?>
