<?php
include '../koneksi.php'; // koneksi database $koneksi

// --- Update Status via GET (dropdown) ---
if(isset($_GET['id']) && isset($_GET['status'])){
    $id_update = $_GET['id'];
    $status_baru = $_GET['status'];
    $conn->query("UPDATE pesanan SET status_pesanan='$status_baru' WHERE id_pesanan='$id_update'");
    header("Location: kelola_pesanan.php?status=sukses");
    exit;
}

// --- Detail Pesanan ---
if (isset($_GET['detail_id'])) {
    $id_pesanan = $_GET['detail_id'];

    $sql_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
    $result_pesanan = $conn->query($sql_pesanan);
    $pesanan = $result_pesanan->fetch_assoc();

    $sql_detail = "SELECT dp.*, p.nama_produk, p.warna 
                   FROM detail_pesanan dp 
                   JOIN produk p ON dp.id_produk = p.id_produk 
                   WHERE dp.id_pesanan = '$id_pesanan'";
    $detail = $conn->query($sql_detail);

    $conn->close();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Detail Pesanan #<?= $pesanan['id_pesanan'] ?> | Admin Landnic</title>
        <style>
            body { font-family: 'Poppins', sans-serif; margin:40px; background:#fafafa; }
            .box { background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:25px; }
            h1 { color:#8c5b89; }
            h2 { color:#6a1b9a; font-size:20px; margin-top:10px; }
            p { margin:5px 0; }

            table { width:100%; border-collapse:collapse; margin-top:15px; border-radius:8px; overflow:hidden; }
            th { background:#a9629bff; color:white; padding:12px; text-align:left; }
            td { border-bottom:1px solid rgba(0,0,0,0.1); padding:10px; }
            tr:hover { background:#f9f0fb; }

            .total { text-align:right; font-weight:bold; margin-top:10px; }

            a.back { background:#d1d5db; padding:8px 16px; border-radius:6px; display:inline-block; margin-top:20px; text-decoration:none; color:#333; transition:0.3s; }
            a.back:hover { background:#b0b7bd; }
        </style>
    </head>
    <body>

    <h1>Detail Pesanan #<?= $pesanan['id_pesanan'] ?></h1>

    <div class="box">
        <h2>Data Pembeli</h2>
        <p><strong>Nama:</strong> <?= $pesanan['nama'] ?></p>
        <p><strong>Telepon:</strong> <?= $pesanan['telepon'] ?></p>
        <p><strong>Alamat:</strong> <?= $pesanan['alamat'] ?></p>
        <p><strong>Kurir:</strong> <?= $pesanan['kurir'] ?></p>
        <p><strong>Tanggal Pesanan:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesanan'])) ?></p>
        <p><strong>Metode Pembayaran:</strong> <?= $pesanan['metode_pembayaran'] ?></p>
        <p><strong>Status:</strong> <?= $pesanan['status_pesanan'] ?></p>
    </div>

    <div class="box">
        <h2>Daftar Produk</h2>
        <table>
            <tr>
                <th>Nama Produk</th>
                <th>Warna</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $total = 0;
            while($row = $detail->fetch_assoc()):
                $subtotal = $row['harga_satuan'] * $row['jumlah'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['warna'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp<?= number_format($row['harga_satuan'],0,',','.') ?></td>
                <td>Rp<?= number_format($subtotal,0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p class="total">Total: Rp<?= number_format($total,0,',','.') ?></p>
    </div>

    <a href="kelola_pesanan.php" class="back">← Kembali ke Daftar Pesanan</a>

    </body>
    </html>
    <?php
    exit;
}

// --- Hapus Pesanan Selesai ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $hapus_id = $_POST['hapus_id'];
    $cek = $conn->query("SELECT status_pesanan FROM pesanan WHERE id_pesanan='$hapus_id'");
    $row = $cek->fetch_assoc();
    if($row['status_pesanan'] == 'Selesai'){
        $conn->query("DELETE FROM detail_pesanan WHERE id_pesanan='$hapus_id'");
        $conn->query("DELETE FROM pesanan WHERE id_pesanan='$hapus_id'");
        header("Location: kelola_pesanan.php?hapus=sukses");
        exit;
    } else {
        header("Location: kelola_pesanan.php?hapus=gagal");
        exit;
    }
}

// --- Daftar Pesanan ---
$result_pesanan = $conn->query("SELECT * FROM pesanan ORDER BY tanggal_pesanan DESC");
$pesanan = [];
if ($result_pesanan && $result_pesanan->num_rows > 0) {
    while($row = $result_pesanan->fetch_assoc()) {
        $pesanan[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan | Admin Landnic</title>
    <style>
        body { font-family: 'Poppins', sans-serif; margin:40px; background:#fafafa; }
        h1 { color:#8c5b89; margin-bottom:20px; }

        table { width:100%; border-collapse:collapse; border-radius:12px; overflow:hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); background:white; }
        th { background:#a9629bff; color:white; padding:12px; text-align:left; }
        td { border-bottom:1px solid rgba(0,0,0,0.1); padding:10px; vertical-align:middle; }
        tr:hover { background:#f9f0fb; }

        select { padding:6px 10px; border:1px solid #ccc; border-radius:6px; background:white; font-size:14px; cursor:pointer; }
        a.detail-link { display:inline-block; background:#1f8efa; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; transition:0.3s; }
        a.detail-link:hover { background:#0f6ad1; }
        button.hapus-btn { background:red; color:white; padding:6px 12px; border:none; border-radius:6px; cursor:pointer; }

        .status-baru { color:blue; font-weight:600; }
        .status-diproses { color:orange; font-weight:600; }
        .status-dikirim { color:purple; font-weight:600; }
        .status-selesai { color:green; font-weight:600; }
        .status-lain { color:gray; font-weight:600; }
    </style>
</head>
<body>

<h1>Kelola Pesanan Masuk</h1>

<?php if (isset($_GET['status']) && $_GET['status'] === 'sukses'): ?>
    <p style="color: green;">✅ Status pesanan berhasil diperbarui!</p>
<?php elseif(isset($_GET['hapus']) && $_GET['hapus']=='sukses'): ?>
    <p style="color: green;">✅ Pesanan berhasil dihapus!</p>
<?php elseif(isset($_GET['hapus']) && $_GET['hapus']=='gagal'): ?>
    <p style="color: red;">❌ Pesanan gagal dihapus (hanya pesanan selesai yang bisa dihapus)</p>
<?php endif; ?>

<?php if (!empty($pesanan)): ?>
<table>
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pesanan as $p): ?>
        <tr>
            <td><?= $p['id_pesanan'] ?></td>
            <td><?= $p['nama'] ?></td>
            <td><?= $p['telepon'] ?></td>
            <td><?= $p['tanggal_pesanan'] ?></td>
            <td>Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
            <td><?= $p['metode_pembayaran'] ?></td>
            <td class="<?php 
                if ($p['status_pesanan'] == 'Baru') echo 'status-baru';
                else if ($p['status_pesanan'] == 'Diproses') echo 'status-diproses';
                else if ($p['status_pesanan'] == 'Dikirim') echo 'status-dikirim';
                else if ($p['status_pesanan'] == 'Selesai') echo 'status-selesai';
                else echo 'status-lain';
            ?>">
                <?= $p['status_pesanan'] ?>
            </td>
            <td>
                <div style="display:flex; align-items:center; gap:5px;">
                    <!-- Dropdown update status -->
                    <select onchange="window.location.href='kelola_pesanan.php?id=<?= $p['id_pesanan'] ?>&status='+this.value;">
                        <option value="">Ubah status...</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Dikirim">Dikirim</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>

                    <!-- Detail -->
                    <a href="kelola_pesanan.php?detail_id=<?= $p['id_pesanan'] ?>" class="detail-link">Detail</a>

                    <!-- Hapus hanya Selesai -->
                    <?php if($p['status_pesanan'] == 'Selesai'): ?>
                        <form method="POST" action="kelola_pesanan.php" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                            <input type="hidden" name="hapus_id" value="<?= $p['id_pesanan'] ?>">
                            <button type="submit" class="hapus-btn">Hapus</button>
                        </form>
                    <?php endif; ?>
                </div>
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
