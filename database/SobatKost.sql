DROP DATABASE IF EXISTS SobatKost;
CREATE DATABASE SobatKost;
USE SobatKost;

-- ----------------------------------------------------------
-- 1. PEMBUATAN TABEL (Primary Key String + Timestamps)
-- ----------------------------------------------------------

CREATE TABLE peran (
    id_peran INT PRIMARY KEY AUTO_INCREMENT,
    nama_peran VARCHAR(50) NOT NULL
);

CREATE TABLE pengguna (
    id_pengguna VARCHAR(15) PRIMARY KEY, -- Format: U-YYMM000
    id_peran INT,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    nomor_telepon VARCHAR(15),
    foto_ktp VARCHAR(255),
    status_aktif ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_peran) REFERENCES peran(id_peran)
);

CREATE TABLE kamar (
    id_kamar VARCHAR(10) PRIMARY KEY, -- Format: K-000
    nomor_kamar VARCHAR(10) NOT NULL,
    tipe_kamar VARCHAR(50),
    status_kamar ENUM('Tersedia', 'Terisi', 'Perbaikan') DEFAULT 'Tersedia',
    harga_dasar DECIMAL(12, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE inventaris_kamar (
    id_inventaris VARCHAR(10) PRIMARY KEY, -- Format: I-00000
    id_kamar VARCHAR(10),
    nama_barang VARCHAR(100),
    kondisi_barang VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);

CREATE TABLE kontrak_sewa (
    id_kontrak VARCHAR(15) PRIMARY KEY, -- Format: C-YYMMDD00
    id_pengguna VARCHAR(15),
    id_kamar VARCHAR(10),
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    tipe_sewa ENUM('Harian', 'Bulanan', 'Tahunan'),
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna),
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);

CREATE TABLE tagihan (
    id_tagihan VARCHAR(15) PRIMARY KEY, -- Format: T-YYMM000
    id_kontrak VARCHAR(15),
    total_biaya_sewa DECIMAL(12, 2),
    biaya_tambahan DECIMAL(12, 2),
    tanggal_jatuh_tempo DATE,
    status_tagihan ENUM('Belum Lunas', 'Lunas') DEFAULT 'Belum Lunas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kontrak) REFERENCES kontrak_sewa(id_kontrak)
);

CREATE TABLE pembayaran (
    id_pembayaran VARCHAR(15) PRIMARY KEY, -- Format: P-YYMMDD000
    id_tagihan VARCHAR(15),
    metode_pembayaran VARCHAR(50),
    bukti_pembayaran VARCHAR(255),
    tanggal_bayar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_verifikasi ENUM('Proses', 'Berhasil', 'Ditolak') DEFAULT 'Proses',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tagihan) REFERENCES tagihan(id_tagihan)
);

CREATE TABLE komplain (
    id_komplain VARCHAR(15) PRIMARY KEY, -- Format: KPL-YYMMDD00
    id_pengguna VARCHAR(15),
    judul_masalah VARCHAR(100),
    deskripsi TEXT,
    status_komplain ENUM('Menunggu', 'Diproses', 'Selesai') DEFAULT 'Menunggu',
    tanggal_lapor TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

CREATE TABLE biaya_operasional (
    id_biaya VARCHAR(15) PRIMARY KEY, -- Format: B-YYMMDD000
    kategori_biaya ENUM('Listrik', 'Air', 'Kebersihan', 'Gaji Karyawan', 'Perbaikan', 'Lainnya'),
    jumlah_biaya DECIMAL(12, 2),
    tanggal_pengeluaran DATE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pengumuman (
    id_pengumuman VARCHAR(15) PRIMARY KEY, -- Format: A-YYMMDD000
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    tanggal_siar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE aturan_kost (
    id_aturan VARCHAR(10) PRIMARY KEY, -- Format: R-00000
    judul_aturan VARCHAR(100) NOT NULL,
    deskripsi_aturan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- 2. STORED PROCEDURES (Dengan Transaction & FOR UPDATE)
-- ----------------------------------------------------------

DELIMITER //

-- SP untuk Kamar
CREATE PROCEDURE sp_insert_kamar(IN p_no VARCHAR(10), IN p_tipe VARCHAR(50), IN p_harga DECIMAL(12,2))
BEGIN
    DECLARE v_id VARCHAR(15);
    START TRANSACTION;
    SET v_id = CONCAT('K-', LPAD(p_no, 3, '0'));
    
    INSERT INTO kamar (id_kamar, nomor_kamar, tipe_kamar, harga_dasar) 
    VALUES (v_id, p_no, p_tipe, p_harga);
    COMMIT;
END //

-- SP untuk Pengguna
CREATE PROCEDURE sp_insert_pengguna(
    IN p_role INT,
    IN p_nama VARCHAR(100),
    IN p_telp VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_pass VARCHAR(255),
    IN p_foto TEXT,
    IN p_status ENUM('aktif', 'nonaktif')
)
BEGIN
    DECLARE v_prefix VARCHAR(10);
    DECLARE v_new_id VARCHAR(15);
    DECLARE v_last INT;

    START TRANSACTION;

    SET v_prefix = CONCAT('U-', DATE_FORMAT(NOW(), '%y%m'));

    SELECT COALESCE(MAX(CAST(RIGHT(id_pengguna,3) AS UNSIGNED)), 0)
    INTO v_last
    FROM pengguna
    WHERE id_pengguna LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;

    SET v_new_id = CONCAT(v_prefix, LPAD(v_last + 1, 3, '0'));

    INSERT INTO pengguna (
        id_pengguna,
        id_peran,
        nama_lengkap,
        nomor_telepon,
        email,
        kata_sandi,
        foto_ktp,
        status_aktif
    ) VALUES (
        v_new_id,
        p_role,
        p_nama,
        p_telp,
        p_email,
        p_pass,
        p_foto,
        IFNULL(p_status, 'aktif')
    );

    COMMIT;
END //

-- SP untuk Kontrak
CREATE PROCEDURE sp_insert_kontrak(IN p_user VARCHAR(15), IN p_kamar VARCHAR(10), IN p_tipe VARCHAR(20))
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('C-', DATE_FORMAT(NOW(), '%y%m%d'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_kontrak, 9)), 0) + 1, 2, '0')) 
    INTO v_new_id 
    FROM kontrak_sewa 
    WHERE id_kontrak LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO kontrak_sewa (id_kontrak, id_pengguna, id_kamar, tanggal_mulai, tipe_sewa) 
    VALUES (v_new_id, p_user, p_kamar, CURDATE(), p_tipe);
    COMMIT;
END //

-- SP untuk Tagihan
CREATE PROCEDURE sp_insert_tagihan(IN p_kontrak VARCHAR(15), IN p_sewa DECIMAL(12,2), IN p_ekstra DECIMAL(12,2))
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('T-', DATE_FORMAT(NOW(), '%y%m'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_tagihan, 7)), 0) + 1, 3, '0')) 
    INTO v_new_id 
    FROM tagihan 
    WHERE id_tagihan LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO tagihan (id_tagihan, id_kontrak, total_biaya_sewa, biaya_tambahan, tanggal_jatuh_tempo) 
    VALUES (v_new_id, p_kontrak, p_sewa, p_ekstra, DATE_ADD(CURDATE(), INTERVAL 7 DAY));
    COMMIT;
END //

-- SP untuk Pembayaran
CREATE PROCEDURE sp_insert_pembayaran(IN p_tagihan VARCHAR(15), IN p_metode VARCHAR(50))
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('P-', DATE_FORMAT(NOW(), '%y%m%d'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_pembayaran, 9)), 0) + 1, 3, '0')) 
    INTO v_new_id 
    FROM pembayaran 
    WHERE id_pembayaran LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO pembayaran (id_pembayaran, id_tagihan, metode_pembayaran, status_verifikasi) 
    VALUES (v_new_id, p_tagihan, p_metode, 'Berhasil');
    COMMIT;
END //

-- SP untuk Biaya Operasional
CREATE PROCEDURE sp_insert_biaya(IN p_kat VARCHAR(50), IN p_jml DECIMAL(12,2), IN p_ket TEXT)
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('B-', DATE_FORMAT(NOW(), '%y%m%d'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_biaya, 9)), 0) + 1, 3, '0')) 
    INTO v_new_id 
    FROM biaya_operasional 
    WHERE id_biaya LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO biaya_operasional (id_biaya, kategori_biaya, jumlah_biaya, tanggal_pengeluaran, keterangan) 
    VALUES (v_new_id, p_kat, p_jml, CURDATE(), p_ket);
    COMMIT;
END //

-- SP untuk Inventaris
CREATE PROCEDURE sp_insert_inventaris(IN p_kamar VARCHAR(10), IN p_nama VARCHAR(100), IN p_kondisi VARCHAR(50))
BEGIN
    DECLARE v_new_id VARCHAR(10);
    
    START TRANSACTION;
    SELECT CONCAT('I-', LPAD(COALESCE(MAX(SUBSTRING(id_inventaris, 3)), 0) + 1, 5, '0')) 
    INTO v_new_id 
    FROM inventaris_kamar
    FOR UPDATE;
    
    INSERT INTO inventaris_kamar (id_inventaris, id_kamar, nama_barang, kondisi_barang) 
    VALUES (v_new_id, p_kamar, p_nama, p_kondisi);
    COMMIT;
END //

-- SP untuk Pengumuman
CREATE PROCEDURE sp_insert_pengumuman(IN p_judul VARCHAR(200), IN p_konten TEXT)
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('A-', DATE_FORMAT(NOW(), '%y%m%d'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_pengumuman, 9)), 0) + 1, 3, '0')) 
    INTO v_new_id 
    FROM pengumuman 
    WHERE id_pengumuman LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO pengumuman (id_pengumuman, judul, konten) 
    VALUES (v_new_id, p_judul, p_konten);
    COMMIT;
END //

-- SP untuk Aturan Kost
CREATE PROCEDURE sp_insert_aturan(IN p_judul VARCHAR(100), IN p_desk TEXT)
BEGIN
    DECLARE v_new_id VARCHAR(10);
    
    START TRANSACTION;
    SELECT CONCAT('R-', LPAD(COALESCE(MAX(SUBSTRING(id_aturan, 3)), 0) + 1, 5, '0')) 
    INTO v_new_id 
    FROM aturan_kost
    FOR UPDATE;
    
    INSERT INTO aturan_kost (id_aturan, judul_aturan, deskripsi_aturan) 
    VALUES (v_new_id, p_judul, p_desk);
    COMMIT;
END //

-- SP untuk Komplain
CREATE PROCEDURE sp_insert_komplain(IN p_user VARCHAR(15), IN p_judul VARCHAR(100), IN p_desk TEXT)
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);

    START TRANSACTION;
    -- Format: KPL-YYMMDD00
    SET v_prefix = CONCAT('KPL-', DATE_FORMAT(NOW(), '%y%m%d'));

    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_komplain, 11)), 0) + 1, 2, '0'))
    INTO v_new_id
    FROM komplain
    WHERE id_komplain LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;

    INSERT INTO komplain (id_komplain, id_pengguna, judul_masalah, deskripsi)
    VALUES (v_new_id, p_user, p_judul, p_desk);
    COMMIT;
END //

DELIMITER ;

-- ----------------------------------------------------------
-- 3. LOGIKA TAMBAHAN (Trigger & View)
-- ----------------------------------------------------------

DELIMITER //

-- Trigger 1: Otomatis 'Terisi' saat ada kontrak baru
CREATE TRIGGER trg_auto_status_terisi
AFTER INSERT ON kontrak_sewa
FOR EACH ROW
BEGIN
    UPDATE kamar SET status_kamar = 'Terisi' WHERE id_kamar = NEW.id_kamar;
END //

-- Trigger 2: Otomatis 'Tersedia' saat kontrak dinonaktifkan
CREATE TRIGGER trg_auto_status_tersedia
AFTER UPDATE ON kontrak_sewa
FOR EACH ROW
BEGIN
    IF NEW.status_aktif = FALSE AND OLD.status_aktif = TRUE THEN
        UPDATE kamar SET status_kamar = 'Tersedia' WHERE id_kamar = NEW.id_kamar;
    END IF;
END //

DELIMITER ;

CREATE VIEW v_dashboard_kamar AS
SELECT k.id_kamar, k.nomor_kamar, k.status_kamar, p.nama_lengkap AS penghuni
FROM kamar k 
LEFT JOIN kontrak_sewa ks ON k.id_kamar = ks.id_kamar AND ks.status_aktif = TRUE
LEFT JOIN pengguna p ON ks.id_pengguna = p.id_pengguna;

-- ----------------------------------------------------------
-- 4. DATA DUMMY
-- ----------------------------------------------------------

INSERT INTO peran (nama_peran) VALUES ('Owner'), ('Penjaga'), ('Penyewa');

-- Pemanggilan Data via SP (Dengan password terenkripsi '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa' yang merupakan hash dari '123')
CALL sp_insert_pengguna(1, 'Richard Vincentius', '081234567890', 'richard@sobatkost.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_richard.jpg', 'aktif');
CALL sp_insert_pengguna(2, 'Agus Setiawan', '081234567891', 'agus@sobatkost.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_agus.jpg', 'aktif');
CALL sp_insert_pengguna(3, 'Budi Santoso', '081234567892', 'budi@mail.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_budi.jpg', 'aktif');
CALL sp_insert_pengguna(3, 'Siti Rahma', '081234567893', 'siti@mail.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_siti.jpg', 'aktif');
CALL sp_insert_pengguna(3, 'Joko Widodo', '081234567894', 'joko@mail.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_joko.jpg', 'aktif');
CALL sp_insert_pengguna(3, 'Dewi Lestari', '081234567895', 'dewi@mail.com', '$2y$10$VXFHOxYNAPMyt6MyQj44f..Gp8YlKK93oAP4u.ECP64OkPNj3gtoa', 'ktp_dewi.jpg', 'aktif');

-- Menyimpan referensi ID Pengguna secara dinamis
SET @owner_id = (SELECT id_pengguna FROM pengguna WHERE email = 'richard@sobatkost.com');
SET @penjaga_id = (SELECT id_pengguna FROM pengguna WHERE email = 'agus@sobatkost.com');
SET @budi_id = (SELECT id_pengguna FROM pengguna WHERE email = 'budi@mail.com');
SET @siti_id = (SELECT id_pengguna FROM pengguna WHERE email = 'siti@mail.com');
SET @joko_id = (SELECT id_pengguna FROM pengguna WHERE email = 'joko@mail.com');
SET @dewi_id = (SELECT id_pengguna FROM pengguna WHERE email = 'dewi@mail.com');

-- Tambah Kamar
CALL sp_insert_kamar('101', 'VIP', 2000000);
CALL sp_insert_kamar('102', 'Standard', 1500000);
CALL sp_insert_kamar('103', 'Standard', 1500000);
CALL sp_insert_kamar('104', 'VIP', 2200000);
CALL sp_insert_kamar('105', 'Deluxe', 1800000);
CALL sp_insert_kamar('201', 'Standard', 1400000);
CALL sp_insert_kamar('202', 'Deluxe', 1750000);
CALL sp_insert_kamar('203', 'Standard', 1400000);
CALL sp_insert_kamar('204', 'VIP', 2100000);
CALL sp_insert_kamar('205', 'Standard', 1400000);

-- Tambah Inventaris Kamar
CALL sp_insert_inventaris('K-101', 'AC LG 1/2 PK', 'Bagus');
CALL sp_insert_inventaris('K-101', 'Tempat Tidur Queen Size', 'Bagus');
CALL sp_insert_inventaris('K-101', 'Lemari Pakaian Kayu', 'Bagus');
CALL sp_insert_inventaris('K-101', 'Meja Belajar & Kursi', 'Bagus');

CALL sp_insert_inventaris('K-102', 'Tempat Tidur Single', 'Bagus');
CALL sp_insert_inventaris('K-102', 'Kipas Angin Miyako', 'Bagus');
CALL sp_insert_inventaris('K-102', 'Lemari Pakaian Plastik', 'Bagus');

CALL sp_insert_inventaris('K-103', 'Tempat Tidur Single', 'Bagus');
CALL sp_insert_inventaris('K-103', 'Kipas Angin Miyako', 'Bagus');
CALL sp_insert_inventaris('K-103', 'Lemari Pakaian Plastik', 'Cukup');

CALL sp_insert_inventaris('K-104', 'AC Sharp 1/2 PK', 'Bagus');
CALL sp_insert_inventaris('K-104', 'Tempat Tidur Queen Size', 'Bagus');
CALL sp_insert_inventaris('K-104', 'Televisi LED 32 Inch', 'Bagus');

CALL sp_insert_inventaris('K-105', 'AC LG 1/2 PK', 'Bagus');
CALL sp_insert_inventaris('K-105', 'Tempat Tidur Single', 'Bagus');
CALL sp_insert_inventaris('K-105', 'Lemari Pakaian Kayu', 'Bagus');

-- Tambah Kontrak Sewa
-- Kontrak Budi di Kamar 101 (Bulanan, Aktif)
CALL sp_insert_kontrak(@budi_id, 'K-101', 'Bulanan');
SET @budi_kontrak_id = (SELECT id_kontrak FROM kontrak_sewa WHERE id_pengguna = @budi_id ORDER BY created_at DESC LIMIT 1);
UPDATE kontrak_sewa 
SET tanggal_mulai = DATE_SUB(CURDATE(), INTERVAL 1 MONTH), 
    tanggal_selesai = DATE_ADD(CURDATE(), INTERVAL 11 MONTH),
    status_aktif = 2 
WHERE id_kontrak = @budi_kontrak_id;

-- Kontrak Siti di Kamar 102 (Bulanan, Aktif)
CALL sp_insert_kontrak(@siti_id, 'K-102', 'Bulanan');
SET @siti_kontrak_id = (SELECT id_kontrak FROM kontrak_sewa WHERE id_pengguna = @siti_id ORDER BY created_at DESC LIMIT 1);
UPDATE kontrak_sewa 
SET tanggal_mulai = DATE_SUB(CURDATE(), INTERVAL 15 DAY), 
    tanggal_selesai = DATE_ADD(CURDATE(), INTERVAL 15 DAY),
    status_aktif = 2 
WHERE id_kontrak = @siti_kontrak_id;

-- Kontrak Joko di Kamar 105 (Tahunan, Aktif)
CALL sp_insert_kontrak(@joko_id, 'K-105', 'Tahunan');
SET @joko_kontrak_id = (SELECT id_kontrak FROM kontrak_sewa WHERE id_pengguna = @joko_id ORDER BY created_at DESC LIMIT 1);
UPDATE kontrak_sewa 
SET tanggal_mulai = DATE_SUB(CURDATE(), INTERVAL 3 MONTH), 
    tanggal_selesai = DATE_ADD(CURDATE(), INTERVAL 9 MONTH),
    status_aktif = 2 
WHERE id_kontrak = @joko_kontrak_id;

-- Kontrak Expired (Penyewa Budi di Kamar 103, sudah selesai/tidak aktif)
CALL sp_insert_kontrak(@budi_id, 'K-103', 'Bulanan');
SET @expired_kontrak_id = (SELECT id_kontrak FROM kontrak_sewa WHERE id_pengguna = @budi_id ORDER BY created_at DESC LIMIT 1);
UPDATE kontrak_sewa 
SET tanggal_mulai = DATE_SUB(CURDATE(), INTERVAL 3 MONTH), 
    tanggal_selesai = DATE_SUB(CURDATE(), INTERVAL 1 MONTH),
    status_aktif = 0 
WHERE id_kontrak = @expired_kontrak_id;

-- Tambah Tagihan & Pembayaran (Data Dummy Realistis untuk Presentasi)

-- 1. JOKO (Pembayaran Tahunan di Desember 2025, agar tidak merusak skala grafik 2026)
CALL sp_insert_tagihan(@joko_kontrak_id, 21600000, 0);
SET @tagihan_joko = (SELECT id_tagihan FROM tagihan WHERE id_kontrak = @joko_kontrak_id ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2025-12-10', status_tagihan = 'Lunas', created_at = '2025-12-01 08:00:00', updated_at = '2025-12-05 10:00:00' WHERE id_tagihan = @tagihan_joko;

CALL sp_insert_pembayaran(@tagihan_joko, 'Transfer Bank');
SET @pembayaran_joko = (SELECT id_pembayaran FROM pembayaran WHERE id_tagihan = @tagihan_joko ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2025-12-05 10:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_joko_jan.jpg', created_at = '2025-12-05 10:00:00', updated_at = '2025-12-05 10:00:00' WHERE id_pembayaran = @pembayaran_joko;

-- 2. HISTORI JANUARI 2026
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-01-05', status_tagihan = 'Lunas', created_at = '2026-01-01 08:00:00', updated_at = '2026-01-02 09:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-01-02 09:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_budi_jan.jpg', created_at = '2026-01-02 09:00:00', updated_at = '2026-01-02 09:00:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-01-10', status_tagihan = 'Lunas', created_at = '2026-01-01 08:00:00', updated_at = '2026-01-08 11:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'E-Wallet (OVO)');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-01-08 11:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_siti_jan.jpg', created_at = '2026-01-08 11:00:00', updated_at = '2026-01-08 11:00:00' WHERE id_pembayaran = @pembayaran;

-- 3. HISTORI FEBRUARI 2026
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-02-05', status_tagihan = 'Lunas', created_at = '2026-02-01 08:00:00', updated_at = '2026-02-03 09:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-02-03 09:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_budi_feb.jpg', created_at = '2026-02-03 09:00:00', updated_at = '2026-02-03 09:00:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-02-10', status_tagihan = 'Lunas', created_at = '2026-02-01 08:00:00', updated_at = '2026-02-09 11:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'E-Wallet (OVO)');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-02-09 11:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_siti_feb.jpg', created_at = '2026-02-09 11:00:00', updated_at = '2026-02-09 11:00:00' WHERE id_pembayaran = @pembayaran;

-- 4. HISTORI MARET 2026
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-03-05', status_tagihan = 'Lunas', created_at = '2026-03-01 08:00:00', updated_at = '2026-03-02 09:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-03-02 09:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_budi_mar.jpg', created_at = '2026-03-02 09:00:00', updated_at = '2026-03-02 09:00:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-03-10', status_tagihan = 'Lunas', created_at = '2026-03-01 08:00:00', updated_at = '2026-03-08 11:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'E-Wallet (OVO)');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-03-08 11:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_siti_mar.jpg', created_at = '2026-03-08 11:00:00', updated_at = '2026-03-08 11:00:00' WHERE id_pembayaran = @pembayaran;

-- 5. HISTORI APRIL 2026
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 50000);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-04-05', status_tagihan = 'Lunas', created_at = '2026-04-01 08:00:00', updated_at = '2026-04-07 14:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-04-07 14:00:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_budi_apr.jpg', created_at = '2026-04-07 14:00:00', updated_at = '2026-04-07 14:00:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-04-10', status_tagihan = 'Lunas', created_at = '2026-04-01 08:00:00', updated_at = '2026-04-09 10:30:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'E-Wallet (OVO)');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-04-09 10:30:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_siti_apr.jpg', created_at = '2026-04-09 10:30:00', updated_at = '2026-04-09 10:30:00' WHERE id_pembayaran = @pembayaran;

-- 6. HISTORI MEI 2026
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-05-05', status_tagihan = 'Lunas', created_at = '2026-05-01 08:00:00', updated_at = '2026-05-03 08:15:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-05-03 08:15:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_budi_mei.jpg', created_at = '2026-05-03 08:15:00', updated_at = '2026-05-03 08:15:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-05-10', status_tagihan = 'Lunas', created_at = '2026-05-01 08:00:00', updated_at = '2026-05-09 16:45:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'E-Wallet (GoPay)');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-05-09 16:45:00', status_verifikasi = 'Berhasil', bukti_pembayaran = 'bukti_siti_mei.jpg', created_at = '2026-05-09 16:45:00', updated_at = '2026-05-09 16:45:00' WHERE id_pembayaran = @pembayaran;

-- 7. BULAN JUNI 2026 (Bulan Berjalan / Real-time Status)
CALL sp_insert_tagihan(@budi_kontrak_id, 2000000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-06-05', status_tagihan = 'Belum Lunas', created_at = '2026-06-01 08:00:00', updated_at = '2026-06-04 19:00:00' WHERE id_tagihan = @tagihan;
CALL sp_insert_pembayaran(@tagihan, 'Transfer Bank');
SET @pembayaran = (SELECT id_pembayaran FROM pembayaran ORDER BY created_at DESC LIMIT 1);
UPDATE pembayaran SET tanggal_bayar = '2026-06-04 19:00:00', status_verifikasi = 'Proses', bukti_pembayaran = 'bukti_budi_jun_pending.jpg', created_at = '2026-06-04 19:00:00', updated_at = '2026-06-04 19:00:00' WHERE id_pembayaran = @pembayaran;

CALL sp_insert_tagihan(@siti_kontrak_id, 1500000, 0);
SET @tagihan = (SELECT id_tagihan FROM tagihan ORDER BY created_at DESC LIMIT 1);
UPDATE tagihan SET tanggal_jatuh_tempo = '2026-06-10', status_tagihan = 'Belum Lunas', created_at = '2026-06-01 08:00:00', updated_at = '2026-06-01 08:00:00' WHERE id_tagihan = @tagihan;