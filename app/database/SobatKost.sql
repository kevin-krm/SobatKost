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
CREATE PROCEDURE sp_insert_pengguna(IN p_role INT, IN p_nama VARCHAR(100), IN p_email VARCHAR(100), IN p_pass VARCHAR(255))
BEGIN
    DECLARE v_prefix VARCHAR(15);
    DECLARE v_new_id VARCHAR(15);
    
    START TRANSACTION;
    SET v_prefix = CONCAT('U-', DATE_FORMAT(NOW(), '%y%m'));
    
    SELECT CONCAT(v_prefix, LPAD(COALESCE(MAX(SUBSTRING(id_pengguna, 7)), 0) + 1, 3, '0')) 
    INTO v_new_id 
    FROM pengguna 
    WHERE id_pengguna LIKE CONCAT(v_prefix, '%')
    FOR UPDATE;
    
    INSERT INTO pengguna (id_pengguna, id_peran, nama_lengkap, email, kata_sandi) 
    VALUES (v_new_id, p_role, p_nama, p_email, p_pass);
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

-- Pemanggilan Data via SP (Parameter kembali menggunakan format String aslinya)
CALL sp_insert_pengguna(1, 'Richard Vincentius', 'richard@sobatkost.com', '123');
CALL sp_insert_pengguna(3, 'Budi Santoso', 'budi@mail.com', '123');

CALL sp_insert_kamar('101', 'VIP', 2000000);
CALL sp_insert_kamar('102', 'Standard', 1500000);

CALL sp_insert_biaya('Kebersihan', 150000, 'Iuran Kebersihan Lingkungan');

CALL sp_insert_pengumuman('Kerja Bakti', 'Minggu pagi harap kumpul di depan kost.');

CALL sp_insert_aturan('Jam Malam', 'Tamu harap lapor sebelum jam 22.00 WIB.');

CALL sp_insert_inventaris('K-101', 'AC LG 1/2 PK', 'Bagus');