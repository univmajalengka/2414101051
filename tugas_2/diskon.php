<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Diskon - Pink Pastel</title>
    <link rel="stylesheet" href="style.css">

    <!-- ICON -->
    <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons.js"></script>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <ion-icon name="pricetags-outline"></ion-icon>
            <span>Diskon</span>
        </div>
    </nav>

    <div class="container">
        <h2 class="judul">💗 Perhitungan Diskon 💗</h2>

        <form method="POST" class="form-box">
            <label>Total Belanja (Rp)</label>
            <input type="number" name="totalBelanja" required placeholder="Masukkan nominal belanja...">

            <button type="submit" name="hitung">
                <ion-icon name="calculator-outline"></ion-icon> Hitung Diskon
            </button>
        </form>

        <?php
        function hitungDiskon($totalBelanja) {
            if ($totalBelanja >= 100000) {
                return 0.10 * $totalBelanja;
            } elseif ($totalBelanja >= 50000) {
                return 0.05 * $totalBelanja;
            } else {
                return 0;
            }
        }

        if (isset($_POST['hitung'])) {
            $total = $_POST['totalBelanja'];
            $diskon = hitungDiskon($total);
            $bayar = $total - $diskon;

            echo "
            <div class='struk'>
                <h3><ion-icon name='receipt-outline'></ion-icon> Hasil Perhitungan</h3>
                <p>Total Belanja: <span>Rp " . number_format($total,0,',','.') . "</span></p>
                <p>Diskon: <span>Rp " . number_format($diskon,0,',','.') . "</span></p>
                <p class='total-bayar'>Total Bayar: <b>Rp " . number_format($bayar,0,',','.') . "</b></p>
            </div>
            ";
        }
        ?>

    </div>

    <!-- FOOTER -->
    <footer>
        <p>💗 HAPPY SHOPPING 💗</p>
    </footer>

</body>
</html>
