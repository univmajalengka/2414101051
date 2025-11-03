<?php
session_start();
unset($_SESSION['keranjang']);
unset($_SESSION['pesan_status']);

header("Location: produk.php");
exit;
?>