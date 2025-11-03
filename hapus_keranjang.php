<?php
session_start();
if (isset($_GET['id'])) {
    $id_produk = (int)$_GET['id'];
    if (isset($_SESSION['keranjang'][$id_produk])) {
        unset($_SESSION['keranjang'][$id_produk]);
        $_SESSION['pesan_status'] = "Item berhasil dihapus dari keranjang.";
    }
} 
header("Location: keranjang.php");
exit;
?>