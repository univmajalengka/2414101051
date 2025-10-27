<?php
// admin/admin.php
session_start();

// =======================================================
// OTENTIKASI: Jika admin belum login, alihkan ke login.php
// =======================================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // login.php ada di folder yang sama
    exit();
}

$username = $_SESSION['admin_username'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landnic Hijab | Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css"> 
    <style>
        /* Gaya dasar untuk Dashboard Admin */
        body {
            background-color: #f4f4f9;
        }
        .admin-dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding-top: 85px; 
            min-height: 80vh;
        }
        .admin-sidebar {
            width: 250px;
            float: left;
            padding-right: 20px;
            border-right: 1px solid #eee;
        }
        .admin-content {
            margin-left: 270px;
        }
        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }
        .admin-sidebar ul li a {
            display: block;
            padding: 10px 0;
            text-decoration: none;
            color: #875A8B;
            font-weight: 600;
        }
        .admin-sidebar ul li a:hover {
            color: #ff5fa2;
        }
    </style>
</head>
<body>


<div class="admin-dashboard">
    <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>Ini adalah halaman utama Dashboard Administrasi Landnic Hijab.</p>
    
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
        
        // Router sederhana untuk konten
        switch ($page) {
            case 'produk':
                include 'kelola_produk.php'; 
                break;
            case 'pesanan':
                include 'kelola_pesanan.php'; 
                break;
            case 'logout':
                // Logout logic
                session_unset();
                session_destroy();
                // Harap diperhatikan: Arahkan ke index.php di folder utama
                header('Location: ../index.php'); 
                exit();
            case 'dashboard':
            default:
                echo '<h3>Statistik Ringkas</h3><p>Total Produk: 12</p><p>Pesanan Baru: 3</p><p>Total Penjualan: Rp 1.500.000</p>';
                break;
        }
        ?>
    </div>
    <div style="clear: both;"></div>
</div>



</body>
</html>