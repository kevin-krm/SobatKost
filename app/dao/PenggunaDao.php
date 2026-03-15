<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Pengguna.php';

class PenggunaDao {
    public function getAllPengguna() {
        $link = PDOUtil::createConnection();
        $query = "SELECT p.id_pengguna,p.nama_lengkap,p.nomor_telepon,p.email,p.kata_sandi,p.created_at,p.foto_ktp,pr.nama_peran
    FROM pengguna p 
    JOIN peran pr ON p.id_peran = pr.id_peran
    ORDER BY p.id_pengguna DESC
    ";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Pengguna');
    }

    public function insertPengguna($peran, $nama, $email, $password) {
        $link = PDOUtil::createConnection();
        try {
            $query = "CALL sp_insert_pengguna(:role, :nama, :email, :pass)";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':role', $peran, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();
            PDOUtil::closeConnection();
            return true;
        } catch (PDOException $e) {
            PDOUtil::closeConnection();
            die("Gagal menambah pengguna: " . $e->getMessage());
        }
    }

    public function getPenggunaById($id)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pengguna WHERE id_pengguna = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePengguna($id, $peran, $nama, $email, $password)
    {
        $link = PDOUtil::createConnection();
        if (!empty($password)) {
            $query = "UPDATE pengguna 
                  SET id_peran=:peran, nama_lengkap=:nama, email=:email, kata_sandi=:pass
                  WHERE id_pengguna=:id";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':pass', $password);
        } else {
            $query = "UPDATE pengguna 
                  SET id_peran=:peran, nama_lengkap=:nama, email=:email
                  WHERE id_pengguna=:id";
            $stmt = $link->prepare($query);
        }
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':peran', $peran);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    public function deletePengguna($id)
    {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM pengguna WHERE id_pengguna=:id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getPenggunaPage($limit,$offset)
    {
        $link = PDOUtil::createConnection();
        $query = "
    SELECT 
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
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset
    ";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
        $stmt->bindValue(':offset',$offset,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS,'Pengguna');
    }

    public function countPengguna()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM pengguna";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>