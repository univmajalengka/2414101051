<?php
// admin/kelola_produk.php

// Panggil koneksi database
// Berdasarkan struktur folder, koneksi.php ada satu tingkat di atas folder admin
include '../koneksi.php'; 

// =======================================================
// 2. LOGIKA PESAN DAN AKSI (TAMBAH, EDIT, HAPUS)
// =======================================================
$message = '';
$action = $_GET['action'] ?? '';
// Gunakan id_produk
$id_produk = (int)($_GET['id_produk'] ?? 0); 

// Logika Hapus (DELETE)
if ($action === 'delete' && $id_produk > 0) {
    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);

    if ($stmt->execute()) {
        $message = '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">Produk ID ' . $id_produk . ' berhasil dihapus.</div>';
    } else {
        $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">Error saat menghapus produk: ' . $conn->error . '</div>';
    }
    $stmt->close();
    $action = ''; 
}

// Logika Tambah & Edit (INSERT & UPDATE) - Menggunakan Form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua field
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $warna = trim($_POST['warna'] ?? '');
    $harga = (float)($_POST['harga'] ?? 0.00); // Ambil sebagai float/decimal
    $foto = trim($_POST['foto'] ?? '');
    $stok = (int)($_POST['stok'] ?? 0);
    $post_id_produk = (int)($_POST['id_produk'] ?? 0); // ID Produk dari hidden field

    if (empty($nama_produk) || $harga <= 0) {
        $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">Nama produk dan Harga harus diisi dengan benar!</div>';
    } else {
        if ($post_id_produk > 0) {
            // Logika EDIT (UPDATE)
            $sql = "UPDATE produk SET nama_produk=?, deskripsi=?, warna=?, harga=?, foto=?, stok=? WHERE id_produk=?";
            $stmt = $conn->prepare($sql);
            // Tipe data: string, string, string, double, string, integer, integer (7 parameter)
            $stmt->bind_param("sssdsii", $nama_produk, $deskripsi, $warna, $harga, $foto, $stok, $post_id_produk);
            $pesan_sukses = "diperbarui";
        } else {
            // Logika TAMBAH (INSERT)
            $sql = "INSERT INTO produk (nama_produk, deskripsi, warna, harga, foto, stok) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // KODE FIX: "sssdsi" (6 Tipe Data) sesuai dengan 6 variabel.
            $stmt->bind_param("sssdsi", $nama_produk, $deskripsi, $warna, $harga, $foto, $stok);
            $pesan_sukses = "ditambahkan";
        }

        if ($stmt->execute()) {
            $message = '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">Produk berhasil **' . $pesan_sukses . '**.</div>';
            // Redirect ke halaman daftar setelah sukses
            header('Location: admin.php?page=produk');
            exit();
        } else {
            $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">Error database: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

// =======================================================
// 3. MENAMPILKAN FORM TAMBAH/EDIT JIKA DIPERLUKAN
// =======================================================

if ($action === 'add' || $action === 'edit') {
    $form_title = ($action === 'edit') ? 'Edit Produk' : 'Tambah Produk Baru';
    // Gunakan nama kolom baru untuk inisialisasi
    $product_data = ['id_produk' => 0, 'nama_produk' => '', 'deskripsi' => '', 'warna' => '', 'harga' => 0.00, 'foto' => '', 'stok' => 0];

    // Ambil data produk dari DB jika dalam mode edit
    if ($action === 'edit' && $id_produk > 0) {
        $result = $conn->query("SELECT * FROM produk WHERE id_produk = $id_produk");
        if ($result->num_rows > 0) {
            $product_data = $result->fetch_assoc();
        }
    }
    
    // Tampilan Form
    ?>
    
    <h2><?php echo $form_title; ?></h2>
    
    <a href="admin.php?page=produk" style="display: inline-block; margin-bottom: 15px;">&laquo; Kembali ke Daftar Produk</a>

    <?php echo $message; ?>

    <form action="admin.php?page=produk" method="POST" style="background-color: #f9f9f9; padding: 20px; border-radius: 8px;">
        <input type="hidden" name="id_produk" value="<?php echo $product_data['id_produk']; ?>">
        
        <label for="nama_produk" style="display: block; margin-top: 10px;">Nama Produk:</label>
        <input type="text" id="nama_produk" name="nama_produk" required value="<?php echo htmlspecialchars($product_data['nama_produk']); ?>"
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

        <label for="deskripsi" style="display: block; margin-top: 10px;">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" 
                  style="width: 100%; padding: 8px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px;"><?php echo htmlspecialchars($product_data['deskripsi']); ?></textarea>
        
        <label for="warna" style="display: block; margin-top: 10px;">Warna:</label>
        <input type="text" id="warna" name="warna" value="<?php echo htmlspecialchars($product_data['warna']); ?>"
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
        
        <label for="harga" style="display: block; margin-top: 10px;">Harga (Rp):</label>
        <input type="number" id="harga" name="harga" required min="0.01" step="0.01" value="<?php echo $product_data['harga']; ?>"
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
               
        <label for="stok" style="display: block; margin-top: 10px;">Stok:</label>
        <input type="number" id="stok" name="stok" required min="0" value="<?php echo $product_data['stok']; ?>"
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

        <label for="foto" style="display: block; margin-top: 10px;">Nama File Foto (e.g. gambar.jpg):</label>
        <input type="text" id="foto" name="foto" value="<?php echo htmlspecialchars($product_data['foto']); ?>"
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

        <button type="submit" 
                style="padding: 10px 15px; background-color: #875A8B; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Simpan Produk
        </button>
    </form>
    
    <?php
} 

// =======================================================
// 4. MENAMPILKAN DAFTAR PRODUK (SELECT / READ) - AESTHETIC STYLE
// =======================================================
if ($action === '') {
    ?>
    <h3>Kelola Produk</h3>
    <p>Di sini Anda dapat menambah, mengedit, atau menghapus produk.</p>

    <a href="admin.php?page=produk&action=add" 
       style="display: inline-block; padding: 10px 15px; background-color: #a9629bff; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px; font-weight: bold;">
        + Tambah Produk Baru
    </a>

    <?php echo $message; // Tampilkan pesan sukses/error setelah aksi ?>

    <h3>Daftar Produk</h3>
    
    <?php 
    // Kueri SELECT: Sekarang mengambil 'foto'
    $result = $conn->query("SELECT id_produk, nama_produk, warna, harga, stok, foto FROM produk ORDER BY id_produk DESC");
    
    if ($result && $result->num_rows > 0): 
    ?>
        <style>
            .product-table {
                width: 100%;
                border-collapse: collapse; 
                margin-top: 15px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Shadow untuk efek melayang */
                border-radius: 8px; /* Sudut membulat */
                overflow: hidden; /* Penting untuk sudut membulat */
            }
            .product-table th, .product-table td {
                padding: 12px 15px;
                text-align: left;
                border: 1px solid #e0e0e0; /* Border tipis */
            }
            .product-table thead th {
                background-color: #a9629bff; /* Warna ungu brand */
                color: white;
                font-weight: bold;
            }
            .product-table tbody tr:nth-child(even) {
                background-color: #f7f7f7; /* Warna selang-seling */
            }
            .product-table tbody tr:hover {
                background-color: #e8e8e8; /* Efek hover */
                transition: background-color 0.3s;
            }
            .action-link {
                text-decoration: none;
                padding: 5px 8px;
                border-radius: 3px;
                font-size: 0.9em;
            }
            .edit-link {
                color: #2a6496; 
                background-color: #e9f5ff;
            }
            .delete-link {
                color: #a94442; 
                background-color: #f2dede;
            }
        </style>

        <table class="product-table">
            <thead>
                <tr>
                    <th style="text-align: center;">ID</th>
                    <th>Nama Produk</th>
                    <th>Warna</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center;">Foto</th> <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $product['id_produk']; ?></td>
                    <td><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                    <td><?php echo htmlspecialchars($product['warna']); ?></td>
                    <td style="text-align: right;">Rp <?php echo number_format($product['harga'], 2, ',', '.'); ?></td>
                    <td style="text-align: center;"><?php echo $product['stok']; ?></td>
                    
                    <td style="text-align: center;">
                        <?php 
                        // Jika ingin menampilkan nama file-nya saja:
                        // echo htmlspecialchars($product['foto']); 
                        
                        // Untuk menampilkan gambarnya (asumsi gambar ada di ../assets/img/):
                        if (!empty($product['foto'])) {
                            echo '<img src="../assets/img/' . htmlspecialchars($product['foto']) . '" width="50" alt="Foto Produk">';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    
                    <td style="text-align: center;">
                        <a href="admin.php?page=produk&action=edit&id_produk=<?php echo $product['id_produk']; ?>" class="action-link edit-link">Edit</a> 
                        <a href="admin.php?page=produk&action=delete&id_produk=<?php echo $product['id_produk']; ?>" class="action-link delete-link" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada produk yang ditambahkan di database.</p>
    <?php endif; 
    
    $conn->close();
}
?>