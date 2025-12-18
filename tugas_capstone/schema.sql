-- schema.sql
-- Database dan tabel untuk Aplikasi Pemesanan Paket Wisata

CREATE DATABASE IF NOT EXISTS wisata;
USE wisata;

CREATE TABLE IF NOT EXISTS pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(20) NOT NULL,
    tanggal_pesan DATE NOT NULL,
    hari INT NOT NULL,
    peserta INT NOT NULL,
    pelayanan TEXT NOT NULL,
    harga_paket BIGINT NOT NULL,
    total_tagihan BIGINT NOT NULL
);
