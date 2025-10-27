// assets/js/cart.js - KODE LENGKAP LANDNIC HIJAB (FINAL DAN BERSIH)

// MAP WARNA (Untuk konversi dari Hex ke Nama)
const colorMap = {
    '#f4cccc': 'Pink Muda',
    '#c9daf8': 'Biru Langit',
    '#d9ead3': 'Hijau Mint',
    '#ffe599': 'Kuning Lemon',
    '#b6d7a8': 'Hijau Sage',
    '#ead1dc': 'Ungu Lilac',
    '#cfe2f3': 'Biru Baby',
    '#f9cb9c': 'Peach',
};

// =======================================================
// FUNGSI UMUM KERANJANG & PRODUK
// =======================================================

/**
 * Fungsi pembantu untuk memformat angka menjadi Rupiah
 */
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
} 

/**
 * Fungsi untuk menghitung total kuantitas dan menampilkan di navbar.
 */
function updateCartCount() {
    const keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || [];
    let totalItems = 0;

    keranjang.forEach(item => { 
        totalItems += item.kuantitas;
    });

    const countElement = document.getElementById('cart-count');
    if (countElement) {
        countElement.textContent = totalItems;
        countElement.style.display = totalItems > 0 ? 'inline-block' : 'none'; 
    }
}

/**
 * Fungsi untuk menandai opsi warna yang sedang dipilih (visual)
 */
function pilihWarna(element) {
    let container = element.closest('.warna-opsi');
    let semuaWarna = container.querySelectorAll('.warna');
    
    semuaWarna.forEach(w => w.classList.remove('selected'));
    element.classList.add('selected');
}

/**
 * Fungsi yang dipanggil saat tombol "Tambah ke Keranjang" diklik.
 */
function tambahKeKeranjangDenganWarna(namaProduk, harga) {
    let cardId = `card-${namaProduk}`;
    let cardElement = document.getElementById(cardId);
    let warnaTerpilihElement = cardElement.querySelector('.warna.selected');

    if (!warnaTerpilihElement) {
        alert('Mohon pilih salah satu varian warna sebelum menambahkan ke keranjang!');
        return; 
    }
    
    let warnaHex = warnaTerpilihElement.getAttribute('data-color');
    keranjangLogika(namaProduk, harga, warnaHex);
}

/**
 * Logika utama untuk menyimpan atau memperbarui item di Local Storage.
 */
function keranjangLogika(namaProduk, harga, warnaHex) {
    const namaWarna = colorMap[warnaHex] || warnaHex; 

    let keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || [];
    let itemIndex = keranjang.findIndex(item => item.nama === namaProduk && item.warnaHex === warnaHex);
    let kuantitasBaru;

    if (itemIndex > -1) {
        keranjang[itemIndex].kuantitas += 1;
        kuantitasBaru = keranjang[itemIndex].kuantitas;
    } else {
        keranjang.push({
            nama: namaProduk,
            harga: harga,
            kuantitas: 1,
            warnaHex: warnaHex, 
            warnaNama: namaWarna 
        });
        kuantitasBaru = 1;
    }

    localStorage.setItem('landnic_keranjang', JSON.stringify(keranjang));
    
    updateCartCount(); 
    
    alert(`🛒 ${namaProduk} (${namaWarna}) berhasil ditambahkan ke keranjang! Total: ${kuantitasBaru} pcs.`);
}


// =======================================================
// FUNGSI HALAMAN KERANJANG (keranjang.php)
// =======================================================

/**
 * Fungsi untuk menghitung total harga dan menampilkan item di tabel.
 */
function renderKeranjang() {
    const keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || [];
    const tbody = document.querySelector('#cart-table tbody');
    const totalDisplay = document.getElementById('total-harga-display');
    const msgKosong = document.getElementById('keranjang-kosong-msg');
    
    tbody.innerHTML = ''; 
    let grandTotal = 0;
    
    if (keranjang.length === 0) {
        msgKosong.style.display = 'block';
        if (document.getElementById('cart-table')) document.getElementById('cart-table').style.display = 'none';
        if (document.getElementById('cart-summary')) document.getElementById('cart-summary').style.display = 'none';
        totalDisplay.textContent = formatRupiah(0);
        return;
    }
    
    msgKosong.style.display = 'none';
    if (document.getElementById('cart-table')) document.getElementById('cart-table').style.display = 'table';
    if (document.getElementById('cart-summary')) document.getElementById('cart-summary').style.display = 'block';

    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.kuantitas;
        grandTotal += subtotal;
        
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${item.nama}</td>
            <td><span style="background:${item.warnaHex};" class="warna"></span> ${item.warnaNama}</td>
            <td>${formatRupiah(item.harga)}</td>
            <td>
                <input type="number" value="${item.kuantitas}" min="1" 
                    onchange="updateKuantitas(${index}, this.value)" style="width: 50px; text-align: center;">
            </td>
            <td>${formatRupiah(subtotal)}</td>
            <td>
                <button onclick="hapusItemKeranjang(${index})" style="background: none; border: none; color: #ff5fa2; cursor: pointer; font-weight: bold;">Hapus</button>
            </td>
        `;
    });

    totalDisplay.textContent = formatRupiah(grandTotal);
    updateCartCount();
}

function hapusItemKeranjang(index) {
    let keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || [];
    keranjang.splice(index, 1);
    localStorage.setItem('landnic_keranjang', JSON.stringify(keranjang));
    renderKeranjang();
}

function updateKuantitas(index, newQty) {
    let keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || [];
    const quantity = parseInt(newQty);
    
    if (quantity < 1 || isNaN(quantity)) {
        hapusItemKeranjang(index);
        return;
    }
    
    keranjang[index].kuantitas = quantity;
    localStorage.setItem('landnic_keranjang', JSON.stringify(keranjang));
    renderKeranjang();
}

function goToCheckout() {
    const keranjang = JSON.parse(localStorage.getItem('landnic_keranjang'));
    
    if (!keranjang || keranjang.length === 0) {
        alert("Keranjang Anda kosong! Tidak bisa checkout.");
        return;
    }
    window.location.href = 'checkout.php';
}

// =======================================================
// FUNGSI HALAMAN CHECKOUT (checkout.php)
// =======================================================

/**
 * Simulasi penghitungan Ongkir berdasarkan kurir dan alamat.
 */
function hitungOngkir() {
    const kurir = document.getElementById('kurir').value;
    const ongkirDisplay = document.getElementById('ongkir-display');
    const ongkirSummary = document.getElementById('ongkir-summary');
    let ongkir = 0;

    // SIMULASI HARGA ONGKIR
    if (kurir === 'JNT' || kurir === 'JNE') {
        ongkir = 18000;
    } else if (kurir === 'SICEPAT') {
        ongkir = 15000;
    } else {
        ongkir = 0;
    }
    
    // Tampilkan di dua tempat
    ongkirDisplay.textContent = formatRupiah(ongkir);
    ongkirSummary.textContent = formatRupiah(ongkir);
    
    // Panggil update ringkasan total setelah ongkir berubah
    renderCheckoutSummary(false); // Kirim flag false agar tidak memanggil hitungOngkir lagi
}

/**
 * Fungsi untuk menampilkan detail keranjang di halaman checkout.php
 * @param {boolean} initialLoad - Apakah ini panggilan pertama saat halaman dimuat
 */
function renderCheckoutSummary(initialLoad = true) {
    const keranjang = JSON.parse(localStorage.getItem('landnic_keranjang')) || []; 
    const summaryBox = document.getElementById('checkout-items');
    const subtotalDisplay = document.getElementById('subtotal-display');
    const ongkirSummary = document.getElementById('ongkir-summary');
    const finalTotalDisplay = document.getElementById('final-total-display');
    
    if (!summaryBox || !ongkirSummary) return; 
    
    summaryBox.innerHTML = '';
    let grandTotal = 0;
    
    if (keranjang.length === 0) {
        summaryBox.innerHTML = '<p>Keranjang kosong. Silakan kembali ke halaman produk.</p>';
        subtotalDisplay.textContent = formatRupiah(0);
        ongkirSummary.textContent = formatRupiah(0);
        finalTotalDisplay.textContent = formatRupiah(0);
        return;
    }

    keranjang.forEach(item => {
        const subtotal = item.harga * item.kuantitas;
        grandTotal += subtotal;
        
        summaryBox.innerHTML += `
            <div class="checkout-item-detail">
                <p><strong>${item.nama}</strong> (${item.warnaNama}) x ${item.kuantitas}</p>
                <p>${formatRupiah(subtotal)}</p>
            </div>
        `;
    });

    // Ambil nilai Ongkir yang sudah dihitung (Rp 0 jika belum ada)
    const ongkirText = ongkirSummary.textContent;
    const ongkirValue = parseInt(ongkirText.replace(/[^0-9]/g, '')) || 0; 

    // Update total akhir
    const totalAkhir = grandTotal + ongkirValue; 
    
    subtotalDisplay.textContent = formatRupiah(grandTotal);
    finalTotalDisplay.textContent = formatRupiah(totalAkhir);
    
    // Panggil hitungOngkir hanya pada saat pertama kali dimuat
    if (initialLoad && document.getElementById('kurir')) {
        hitungOngkir();
    }
}

/**
 * Fungsi yang dipanggil saat tombol submit di halaman checkout diklik.
 */
function submitOrder(event) {
    event.preventDefault(); 

    // Ambil data form
    const nama = document.getElementById('nama').value;
    const telepon = document.getElementById('telepon').value;
    const provinsi = document.getElementById('provinsi').value;
    const kota = document.getElementById('kota').value;
    const kecamatan = document.getElementById('kecamatan').value;
    const desa = document.getElementById('desa').value;
    const kodepos = document.getElementById('kodepos').value;
    const alamatLengkap = document.getElementById('alamat_lengkap').value;
    
    // Ambil data pengiriman & pembayaran
    const kurir = document.getElementById('kurir').value;
    const pembayaranElement = document.querySelector('input[name="pembayaran"]:checked');
    
    // Ambil nilai total
    const keranjang = JSON.parse(localStorage.getItem('landnic_keranjang'));
    const ongkirText = document.getElementById('ongkir-summary').textContent;
    const subtotalText = document.getElementById('subtotal-display').textContent;
    const totalAkhirText = document.getElementById('final-total-display').textContent;
    
    // Validasi dasar
    if (!keranjang || keranjang.length === 0) {
         alert("Keranjang kosong. Tidak ada yang bisa di pesan.");
         return;
    }
    if (!nama || !telepon || !provinsi || !pembayaranElement || !alamatLengkap || !kodepos) {
        alert("Mohon lengkapi semua detail pengiriman dan pilih metode pembayaran.");
        return;
    }

    const pembayaran = pembayaranElement.value; // Ambil nilai pembayaran setelah validasi
    
    // --- 1. SIMPAN DATA UNTUK NOTA ---
    const dataNota = {
        tanggal: new Date().toLocaleDateString('id-ID'),
        idPesanan: 'LDN-' + Date.now().toString().slice(-6), 
        
        pembeli: { 
            nama, telepon,
            alamat: `${alamatLengkap}, ${desa}, ${kecamatan}, ${kota}, ${provinsi} (${kodepos})` // Gabungkan
        },
        
        // Detail Pembayaran & Pengiriman
        pengiriman: { kurir, ongkir: ongkirText },
        pembayaran: pembayaran,

        items: keranjang, 
        subtotal: subtotalText,
        totalAkhir: totalAkhirText,
    };
    
    localStorage.setItem('landnic_nota', JSON.stringify(dataNota));
    
    // --- 2. HAPUS keranjang ---
    localStorage.removeItem('landnic_keranjang'); 

    // --- 3. Redirect ke halaman NOTA ---
    window.location.href = 'nota.php';
}

// =======================================================
// FUNGSI HALAMAN NOTA (nota.php)
// =======================================================

// GANTI TOTAL FUNGSI INI di assets/js/cart.js

/**
 * Fungsi untuk menampilkan nota pembayaran di halaman nota.php
 */
function renderNota() {
    const dataNota = JSON.parse(localStorage.getItem('landnic_nota'));
    const pembeliBox = document.getElementById('ringkasan-pembeli');
    const itemsList = document.getElementById('nota-items-list');
    const totalBox = document.getElementById('nota-total');
    const instruksiBox = document.getElementById('instruksi-pembayaran'); // Div untuk instruksi pembayaran
    
    if (!dataNota || !pembeliBox) {
        alert('Data nota tidak ditemukan. Silakan pesan ulang.');
        window.location.href = 'produk.php';
        return;
    }

    // 1. Tampilkan Detail Pembeli (TETAP SAMA)
    pembeliBox.innerHTML = `
        <h3>Detail Pengiriman</h3>
        <p><strong>Nama:</strong> ${dataNota.pembeli.nama}</p>
        <p><strong>Telepon:</strong> ${dataNota.pembeli.telepon}</p>
        <p><strong>Alamat Lengkap:</strong> ${dataNota.pembeli.alamat}</p>
        <p><strong>Kurir:</strong> ${dataNota.pengiriman.kurir}</p>
    `;

    // 2. Tampilkan Item Pesanan (TETAP SAMA)
    itemsList.innerHTML = '';
    dataNota.items.forEach(item => {
        const subtotal = item.harga * item.kuantitas;
        itemsList.innerHTML += `
            <div class="nota-item-row">
                <p><strong>${item.nama}</strong> (${item.warnaNama}) x ${item.kuantitas}</p>
                <p class="harga-item">${formatRupiah(subtotal)}</p>
            </div>
        `;
    });

    // 3. Tampilkan Total (TETAP SAMA)
    totalBox.innerHTML = `
        <p>Tanggal Pesanan: <span>${dataNota.tanggal}</span></p>
        <p>ID Pesanan: <span>${dataNota.idPesanan}</span></p>
        <hr>
        <p>Subtotal: <span>${dataNota.subtotal}</span></p>
        <p>Ongkos Kirim: <span>${dataNota.pengiriman.ongkir}</span></p>
        <hr>
        <p>Metode Pembayaran: <span class="pembayaran-method">${dataNota.pembayaran}</span></p>
        <p class="grand-total">Total Akhir: 
            <span class="total-harga-display">${dataNota.totalAkhir}</span>
        </p>
    `;
    
    // 4. Instruksi Pembayaran (LOGIKA BARU DAN LENGKAP)
    let detailPembayaran = "";
    const metode = dataNota.pembayaran;
    let nomorRekening = '1234-5678-90'; // Default
    let namaBank = '';

    if (metode.includes('Bank')) {
        // Logika untuk semua Bank (BRI, BCA, Mandiri)
        namaBank = metode.replace('Transfer Bank ', '');
        
        // Simulasikan nomor rekening yang berbeda (atau tetap satu jika Anda mau)
        if (namaBank === 'BRI') {
            nomorRekening = '1122-3344-5566-7788';
        } else if (namaBank === 'BCA') {
            nomorRekening = '8880-1234-5678';
        } else if (namaBank === 'Mandiri') {
            nomorRekening = '9991-2345-6789';
        }
        
        detailPembayaran = `
            <h4>Transfer Bank ${namaBank}</h4>
            <p><strong>Nama Bank:</strong> ${namaBank}</p>
            <p><strong>Nomor Rekening:</strong> ${nomorRekening} (a.n. Landnic Hijab)</p>
            <p><strong>Jumlah Bayar:</strong> ${dataNota.totalAkhir}</p>
            <p class="warning-text">Penting: Bukti transfer harap dikirimkan ke WhatsApp Landnic untuk konfirmasi.</p>
        `;

    } else if (metode === 'DANA') {
        detailPembayaran = `
            <h4>Transfer ke E-Wallet DANA</h4>
            <p><strong>Nama Akun:</strong> Landnic Official</p>
            <p><strong>Nomor HP/Akun DANA:</strong> 0812-3456-7890</p>
            <p><strong>Jumlah Bayar:</strong> ${dataNota.totalAkhir}</p>
            <p class="warning-text">Mohon transfer sesuai nominal di atas (termasuk kode unik jika ada, atau tambahkan 3 digit terakhir ID Pesanan Anda: ${dataNota.idPesanan.slice(-3)}).</p>
        `;
    } else if (metode === 'Alfamart') {
        detailPembayaran = `
            <h4>Bayar di Gerai Alfamart</h4>
            <p>Anda akan menerima **Kode Pembayaran** via WhatsApp Landnic dalam waktu 1x24 Jam.</p>
            <p>Tunjukkan kode tersebut di kasir Alfamart dan bayar **${dataNota.totalAkhir}**.</p>
        `;
    } else if (metode === 'COD') { 
        detailPembayaran = `
            <h4>Pembayaran di Tempat (COD)</h4>
            <p>Pembayaran sebesar **${dataNota.totalAkhir}** akan dilakukan secara tunai kepada kurir J&T saat paket diterima.</p>
            <p class="warning-text">Pastikan Anda menyiapkan uang tunai dan alamat Anda sudah benar. Kami akan konfirmasi via WhatsApp sebelum pengiriman.</p>
        `;
    } else {
        detailPembayaran = `<p>Metode pembayaran tidak dikenali. Silakan hubungi kami via WhatsApp.</p>`;
    }
    
    // Tampilkan di div instruksi
    if (instruksiBox) { 
        instruksiBox.innerHTML = detailPembayaran;
    }

    updateCartCount();
}

// =======================================================
// INITIATOR (Berjalan saat halaman dimuat)
// =======================================================
window.onload = function() {
    if (document.getElementById('keranjang-page')) {
        renderKeranjang();
    } else if (document.getElementById('checkout-page')) {
        renderCheckoutSummary();
    } else if (document.getElementById('nota-page')) {
        renderNota();
    }
    
    // PANGGIL DI SETIAP HALAMAN SAAT DIMUAT
    updateCartCount(); 
}

