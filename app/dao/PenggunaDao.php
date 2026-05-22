<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Pengguna.php';

class PenggunaDao {
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
                pr.nama_peran
              FROM pengguna p
              JOIN peran pr ON p.id_peran = pr.id_peran
              ORDER BY p.id_pengguna DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Pengguna');
    }

    public function insertPengguna(Pengguna $p)
    {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_pengguna(:role, :nama, :telp, :email, :pass, :foto)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':role', $p->getIdPeran());
        $stmt->bindValue(':nama', $p->getNamaLengkap());
        $stmt->bindValue(':telp', $p->getNomorTelepon());
        $stmt->bindValue(':email', $p->getEmail());
        $stmt->bindValue(':pass', $p->getPassword());
        $stmt->bindValue(':foto', $p->getFotoKtp());
        $stmt->execute();
        return $link->lastInsertId();
    }

    public function getPenggunaById($id)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT * FROM pengguna WHERE id_pengguna = :id";

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
            $row['foto_ktp']
        );
    }

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
            foto_ktp=:foto
            WHERE id_pengguna=:id";
        } else {
            $query = "UPDATE pengguna SET
            id_peran=:peran,
            nama_lengkap=:nama,
            nomor_telepon=:telp,
            email=:email,
            foto_ktp=:foto
            WHERE id_pengguna=:id";
        }

        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $p->getId());
        $stmt->bindValue(':peran', $p->getIdPeran());
        $stmt->bindValue(':nama', $p->getNamaLengkap());
        $stmt->bindValue(':telp', $p->getNomorTelepon());
        $stmt->bindValue(':email', $p->getEmail());
        $stmt->bindValue(':foto', $p->getFotoKtp());

        if ($p->getPassword()) {
            $stmt->bindValue(':pass', $p->getPassword());
        }

        $stmt->execute();
    }

    public function deletePengguna($id)
    {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM pengguna
                  WHERE id_pengguna = :id";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

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
                $row['nama_peran']
            );
        }
        return $result;
    }

    public function countPengguna()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM pengguna";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

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
}