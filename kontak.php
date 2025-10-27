<?php 
    // Pastikan menggunakan 'includes' di sini
    require_once __DIR__ . '/includes/header.php'; 
?>

<main id="kontak-main">
    <section id="contact-us" style="padding: 80px 40px; background-color: #fcf6f9;">
        <div class="contact-container" style="max-width: 800px; margin: 0 auto; text-align: center;">
            
            <h2 style="color: #8c5b89; font-size: 36px; margin-bottom: 20px; font-weight: 700;">Hubungi Kami</h2>
            <p style="color: #777; font-size: 1.1em; margin-bottom: 40px;">Punya pertanyaan tentang produk, pesanan, atau kolaborasi? Jangan ragu untuk menghubungi tim Landnic!</p>
            
            <div class="contact-info" style="display: flex; justify-content: space-around; margin-bottom: 60px;">
                <div style="flex: 1; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <h3 style="color: #ff5fa2; font-size: 24px; margin-bottom: 10px;">Chat Cepat (WA)</h3>
                    <p style="font-size: 1.1em; font-weight: 600;">+62 812-3456-7890</p>
                    <a href="https://wa.me/6281234567890" target="_blank" style="color: #8c5b89; text-decoration: none; font-weight: 500;">Kirim Pesan Sekarang &rarr;</a>
                </div>
                
                <div style="flex: 1; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-left: 20px;">
                    <h3 style="color: #ff5fa2; font-size: 24px; margin-bottom: 10px;">Email Bisnis</h3>
                    <p style="font-size: 1.1em; font-weight: 600;">halo@landnichijab.com</p>
                    <a href="mailto:halo@landnichijab.com" style="color: #8c5b89; text-decoration: none; font-weight: 500;">Kirim Email &rarr;</a>
                </div>
            </div>

            <h3 style="color: #8c5b89; font-size: 28px; margin-bottom: 30px;">Atau Isi Formulir di Bawah</h3>

            <form id="contact-form" action="#" method="POST" style="text-align: left;">
                <label for="nama" style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Anda:</label>
                <input type="text" id="nama" name="nama" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px;">
                
                <label for="email" style="display: block; margin-bottom: 5px; font-weight: 500;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px;">
                
                <label for="subjek" style="display: block; margin-bottom: 5px; font-weight: 500;">Subjek:</label>
                <input type="text" id="subjek" name="subjek" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px;">
                
                <label for="pesan" style="display: block; margin-bottom: 5px; font-weight: 500;">Pesan Anda:</label>
                <textarea id="pesan" name="pesan" rows="6" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 30px;"></textarea>
                
                <button type="submit" class="btn" style="width: 100%;">Kirim Pesan</button>
            </form>
            </div>
    </section>
</main>

<?php 
    // Pastikan menggunakan 'includes' di sini
    require_once __DIR__ . '/includes/footer.php'; 
?>