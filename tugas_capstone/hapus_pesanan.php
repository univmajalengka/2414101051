<?php
include 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = $id");
}
header("Location: daftar_pesanan.php");
?>
