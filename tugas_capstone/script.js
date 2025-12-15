function cek() {
    if (
        document.getElementById("nama").value === "" ||
        document.getElementById("telp").value === "" ||
        document.getElementById("tgl").value === "" ||
        document.getElementById("hari").value === "" ||
        document.getElementById("peserta").value === ""
    ) {
        alert("Semua form harus diisi!");
        return false;
    }
}
