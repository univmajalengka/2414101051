<?php
session_start();
include '../koneksi.php'; 

// --- Proteksi login admin ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['admin_username'] ?? 'Administrator';

// --- Query data dashboard dengan pengecekan error ---
$total_produk = 0;
$pesanan_baru = 0;
$total_penjualan = 0;

if ($result = $conn->query("SELECT COUNT(*) AS total_produk FROM produk")) {
    $total_produk = $result->fetch_assoc()['total_produk'];
    $result->free();
}

if ($result = $conn->query("SELECT COUNT(*) AS pesanan_baru FROM pesanan WHERE status_pesanan='Baru'")) {
    $pesanan_baru = $result->fetch_assoc()['pesanan_baru'];
    $result->free();
}

if ($result = $conn->query("SELECT SUM(total_harga) AS total_penjualan FROM pesanan WHERE status_pesanan='Selesai'")) {
    $row = $result->fetch_assoc();
    $total_penjualan = $row['total_penjualan'] ?? 0;
    $result->free();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Landnic Hijab | Admin Dashboard</title>
<link rel="stylesheet" href="../assets/admin.css">
</head>
<body>

<div class="admin-dashboard">

    <div class="welcome-box">
        <h2>Selamat Datang, <?= htmlspecialchars($username); ?>!</h2>
        <p>Ini adalah halaman utama Dashboard Administrasi Landnic Hijab.</p>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Produk</h3>
            <p><?= $total_produk ?></p>
        </div>
        <div class="card">
            <h3>Pesanan Baru</h3>
            <p><?= $pesanan_baru ?></p>
        </div>
        <div class="card">
            <h3>Total Penjualan</h3>
            <p>Rp <?= number_format($total_penjualan,0,',','.') ?></p>
        </div>
    </div>

    <div class="admin-sidebar">
        <h4>Menu Cepat</h4>
        <ul>
            <li><a href="admin.php?page=dashboard">Dashboard</a></li>
            <li><a href="admin.php?page=produk">Kelola Produk</a></li>
            <li><a href="admin.php?page=pesanan">Kelola Pesanan</a></li>
            <li><a href="admin.php?page=logout">Logout</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <?php
        $page = $_GET['page'] ?? 'dashboard';
        switch ($page) {
            case 'produk': include 'kelola_produk.php'; break;
            case 'pesanan': include 'kelola_pesanan.php'; break;
            case 'logout':
                session_unset();
                session_destroy();
                header('Location: ../index.php');
                exit();
            case 'dashboard':
            default:
                echo ''; 
        }
        ?>
    </div>
    <div style="clear:both;"></div>

</div>

</body>
</html>

