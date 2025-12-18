function hitungTotal() {
    let hari = parseInt(document.getElementById('hari').value);
    let peserta = parseInt(document.getElementById('peserta').value);
    let layanan = document.querySelectorAll('input[name="pelayanan[]"]:checked');
    let error = document.getElementById('errorMsg');

    if (isNaN(hari) || isNaN(peserta) || layanan.length === 0) {
        error.style.display = 'block';
        return;
    }

    error.style.display = 'none';

    let hargaPaket = 0;
    layanan.forEach(item => {
        hargaPaket += parseInt(item.value);
    });

    document.getElementById('harga_paket').value = hargaPaket;
    document.getElementById('total_tagihan').value = hari * peserta * hargaPaket;
}

function resetForm() {
    document.getElementById('formPemesanan').reset();
    document.getElementById('harga_paket').value = '';
    document.getElementById('total_tagihan').value = '';
    document.getElementById('errorMsg').style.display = 'none';
}
