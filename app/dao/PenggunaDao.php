<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Pengguna.php';

class PenggunaDao {
    /**
     * Mengambil data pengguna berdasarkan email untuk keperluan validasi kata sandi saat proses login.
     */
    public function login($email)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT
                p.*,
                pr.nama_peran
              FROM pengguna p
              JOIN peran pr ON p.id_peran = pr.id_peran
              WHERE p.email = :email
              LIMIT 1";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil seluruh data pengguna tanpa batasan halaman.
     */
    public function getAllPengguna() {
        $link = PDOUtil::createConnection();
        $query = "SELECT 
                p.id_pengguna,
                p.nama_lengkap,
                p.nomor_telepon,
                p.email,
                p.kata_sandi,
                p.created_at,
                p.foto_ktp,
                p.status_aktif,
                pr.nama_peran
              FROM pengguna p
              JOIN peran pr ON p.id_peran = pr.id_peran
              ORDER BY p.id_pengguna DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Pengguna');
    }

    /**
     * Menyimpan data akun pengguna baru ke dalam tabel pengguna di database.
     */
    public function insertPengguna(Pengguna $p)
    {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_pengguna(:role, :nama, :telp, :email, :pass, :foto, :status)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':role', $p->getIdPeran());
        $stmt->bindValue(':nama', $p->getNamaLengkap());
        $stmt->bindValue(':telp', $p->getNomorTelepon());
        $stmt->bindValue(':email', $p->getEmail());
        $stmt->bindValue(':pass', $p->getPassword());
        $stmt->bindValue(':foto', $p->getFotoKtp());
        $stmt->bindValue(':status', $p->getStatusAktif());
        $stmt->execute();
        return $link->lastInsertId();
    }

    /**
     * Mencari profil satu pengguna secara spesifik menggunakan ID pengguna.
     */
    public function getPenggunaById($id)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT p.*, pr.nama_peran 
                  FROM pengguna p
                  LEFT JOIN peran pr ON p.id_peran = pr.id_peran
                  WHERE p.id_pengguna = :id";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Pengguna(
            $row['id_pengguna'],
            $row['id_peran'],
            $row['nama_lengkap'],
            $row['nomor_telepon'],
            $row['email'],
            $row['kata_sandi'],
            $row['created_at'],
            $row['foto_ktp'],
            $row['status_aktif'] ?? 'aktif',
            $row['nama_peran']
        );
    }

    /**
     * Memperbarui informasi data profil pengguna yang sudah ada di database.
     */
    public function updatePengguna(Pengguna $p)
    {
        $link = PDOUtil::createConnection();

        if ($p->getPassword()) {
            $query = "UPDATE pengguna SET
            id_peran=:peran,
            nama_lengkap=:nama,
            nomor_telepon=:telp,
            email=:email,
            kata_sandi=:pass,
            foto_ktp=:foto,
            status_aktif=:status
            WHERE id_pengguna=:id";
        } else {
            $query = "UPDATE pengguna SET
            id_peran=:peran,
            nama_lengkap=:nama,
            nomor_telepon=:telp,
            email=:email,
            foto_ktp=:foto,
            status_aktif=:status
            WHERE id_pengguna=:id";
        }

        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $p->getId());
        $stmt->bindValue(':peran', $p->getIdPeran());
        $stmt->bindValue(':nama', $p->getNamaLengkap());
        $stmt->bindValue(':telp', $p->getNomorTelepon());
        $stmt->bindValue(':email', $p->getEmail());
        $stmt->bindValue(':foto', $p->getFotoKtp());
        $stmt->bindValue(':status', $p->getStatusAktif());

        if ($p->getPassword()) {
            $stmt->bindValue(':pass', $p->getPassword());
        }

        $stmt->execute();
    }

    /**
     * Menghapus data pengguna secara permanen dari tabel database.
     */
    public function deletePengguna($id)
    {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM pengguna
                  WHERE id_pengguna = :id";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * Mengambil daftar pengguna menggunakan pagination agar sistem memuat halaman lebih cepat.
     */
    public function getPenggunaPage($limit, $offset)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT
        p.id_pengguna,
        p.id_peran,
        p.nama_lengkap,
        p.nomor_telepon,
        p.email,
        p.kata_sandi,
        p.created_at,
        p.foto_ktp,
        p.status_aktif,
        pr.nama_peran
    FROM pengguna p
    JOIN peran pr ON p.id_peran = pr.id_peran
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
        $stmt->bindValue(':offset',$offset,PDO::PARAM_INT);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Pengguna(
                $row['id_pengguna'],
                $row['id_peran'],
                $row['nama_lengkap'],
                $row['nomor_telepon'],
                $row['email'],
                $row['kata_sandi'],
                $row['created_at'],
                $row['foto_ktp'],
                $row['status_aktif'] ?? 'aktif',
                $row['nama_peran']
            );
        }
        return $result;
    }

    /**
     * Menghitung total keseluruhan akun pengguna yang terdaftar di dalam sistem.
     */
    public function countPengguna()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM pengguna";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Memeriksa apakah suatu alamat email sudah terdaftar sebelumnya di database.
     */
    public function findByEmail($email)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pengguna
              WHERE email = :email
              LIMIT 1";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menghitung jumlah pengguna yang memiliki status penyewa aktif untuk ditampilkan di dashboard.
     */
    public function countPenyewaAktif()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM pengguna WHERE id_peran = 3 AND status_aktif = 'aktif'";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mengubah kata sandi pengguna di database, umumnya digunakan dalam proses lupa password.
     */
    public function updatePasswordByEmail($email, $passwordHash)
    {
        $link = PDOUtil::createConnection();
        $query = "UPDATE pengguna SET kata_sandi = :pass WHERE email = :email";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':pass', $passwordHash);
        $stmt->bindValue(':email', $email);
        return $stmt->execute();
    }
}