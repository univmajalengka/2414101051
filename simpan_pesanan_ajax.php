<?php include '../koneksi.php'; 

header('Content-Type: application/json');

// --- Cek Koneksi (Jika Gagal, PHP langsung merespons JSON Error) ---
if (!isset($conn) || $conn->connect_error) {
    $conn = null;
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal. Error: ' . ($conn->connect_error ?? 'Variabel koneksi tidak terdefinisi.')]);
    exit();
}

// --- Ambil dan Validasi Data ---
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!isset($data['action']) || $data['action'] !== 'simpan_pesanan' || empty($data['keranjang_items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap atau permintaan tidak valid.']);
    $conn->close();
    exit();
}

// --- Casting Data ---
$nama_pelanggan = $data['nama'] ?? '';
$telepon = $data['telepon'] ?? '';
$alamat_kirim = $data['alamat_lengkap'] ?? ''; 
$metode_pembayaran = $data['pembayaran'] ?? '';
$kurir = $data['kurir'] ?? '';

$ongkir = (double)($data['ongkir'] ?? 0); 
$total_akhir = (double)($data['total_akhir'] ?? 0); 

$items_keranjang = json_decode($data['keranjang_items'], true);

if (empty($items_keranjang) || $total_akhir <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong atau total harga tidak valid.']);
    $conn->close();
    exit();
}

$id_pelanggan = $_SESSION['user_id'] ?? 0; 
$status_pesanan = 'Baru'; 

// --- Mulai Transaksi ---
$conn->begin_transaction();

try {
    // Kolom di tabel pesanan yang Anda miliki (termasuk telepon, ongkos_kirim, metode_pembayaran, kurir)
    $stmt = $conn->prepare("INSERT INTO pesanan (
        id_pelanggan, nama_pelanggan, telepon, tanggal_pesanan, 
        total_harga, ongkos_kirim, status_pesanan, metode_pembayaran, alamat_kirim, kurir
    ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    
    // Total 10 parameter yang di-bind: i, s, s, d, d, s, s, s, s, s
    $stmt->bind_param("issddsssss", 
        $id_pelanggan,          
        $nama_pelanggan,        
        $telepon,               
        $total_akhir,           
        $ongkir,                
        $status_pesanan,        
        $metode_pembayaran,     
        $alamat_kirim,          
        $kurir                  
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan pesanan utama: " . $stmt->error);
    }
    
    $id_pesanan_db = $conn->insert_id;
    $stmt->close();

    // Kolom di tabel detail_pesanan yang Anda miliki
    $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (
        id_pesanan, id_produk, nama_produk, jumlah, harga
    ) VALUES (?, ?, ?, ?, ?)");

    foreach ($items_keranjang as $item) {
        $nama_produk_full = $item['nama'] . ' (' . $item['warnaNama'] . ')'; 
        
        $id_produk = (int)($item['id'] ?? 0); 
        $jumlah = (int)($item['kuantitas'] ?? 1);
        $harga_satuan = (double)($item['harga'] ?? 0); 
        
        if ($jumlah <= 0 || $harga_satuan <= 0) {
             throw new Exception("Data kuantitas atau harga salah untuk produk: " . $nama_produk_full);
        }

        // Binding: i, i, s, i, d
        $stmt_detail->bind_param("iisid", 
            $id_pesanan_db,         
            $id_produk,             
            $nama_produk_full,      
            $jumlah,                
            $harga_satuan           
        );

        if (!$stmt_detail->execute()) {
            throw new Exception("Gagal menyimpan detail produk (" . $nama_produk_full . "): " . $stmt_detail->error);
        }
    }
    
    $stmt_detail->close();
    
    $conn->commit();
    
    // --- Respons Sukses ---
    $id_pesanan_nota = 'LDN-' . $id_pesanan_db;
    
    echo json_encode(['status' => 'success', 'id_pesanan_baru' => $id_pesanan_nota]);
    
} catch (Exception $e) {
    if ($conn) {
        $conn->rollback();
    }
    // --- Respons Error ---
    echo json_encode(['status' => 'error', 'message' => "Kesalahan PHP/DB: " . $e->getMessage()]);
    
} finally {
    if ($conn) {
        $conn->close();
    }
}