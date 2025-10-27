<?php
include '../koneksi.php'; // Sertakan file koneksi database

// --- Bagian Logika: Mengambil Data Pesanan ---
$sql_pesanan = "SELECT 
                    id_pesanan, 
                    nama_pelanggan, 
                    tanggal_pesanan, 
                    total_harga, 
                    status_pesanan 
                FROM 
                    pesanan 
                ORDER BY 
                    tanggal_pesanan DESC"; // Sesuaikan nama tabel dan kolom Anda
                    
$result_pesanan = $conn->query($sql_pesanan);

$pesanan = [];
if ($result_pesanan && $result_pesanan->num_rows > 0) {
    while($row = $result_pesanan->fetch_assoc()) {
        $pesanan[] = $row;
    }
}

// --- Bagian Logika: Memproses Perubahan Status (Opsional) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id_pesanan_update = $_POST['id_pesanan'];
    $status_baru = $_POST['status_baru'];
    
    // Lindungi dari SQL Injection
    $id_pesanan_update = $conn->real_escape_string($id_pesanan_update);
    $status_baru = $conn->real_escape_string($status_baru);

    $sql_update = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id_pesanan = '$id_pesanan_update'";
    
    if ($conn->query($sql_update) === TRUE) {
        // Redirect untuk mencegah form resubmission dan memperbarui tampilan
        header("Location: kelola_pesanan.php?status=sukses");
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan Masuk</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Kelola Pesanan Masuk</h1>

<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <p style="color: green;">Status pesanan berhasil diperbarui!</p>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <p style="color: red;"><?= $error_message ?></p>
<?php endif; ?>


<?php if (!empty($pesanan)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesanan as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id_pesanan']) ?></td>
                <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                <td><?= htmlspecialchars($p['tanggal_pesanan']) ?></td>
                <td>Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <span style="font-weight: bold; color: 
                        <?php 
                            if ($p['status_pesanan'] == 'Baru') echo 'blue'; 
                            else if ($p['status_pesanan'] == 'Diproses') echo 'orange'; 
                            else if ($p['status_pesanan'] == 'Selesai') echo 'green'; 
                            else echo 'black'; 
                        ?>">
                        <?= htmlspecialchars($p['status_pesanan']) ?>
                    </span>
                </td>
                <td>
                    <form method="POST" action="kelola_pesanan.php" style="display: inline-block;">
                        <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan'] ?>">
                        <select name="status_baru" required>
                            <option value="">Ubah Status...</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                    
                    <a href="detail_pesanan.php?id=<?= $p['id_pesanan'] ?>">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada pesanan masuk saat ini.</p>
<?php endif; ?>

</body>
</html>