<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Pengumuman.php';

class PengumumanDao {

    public function showAllPengumuman() {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pengumuman ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Pengumuman();
            $p->setIdPengumuman($row['id_pengumuman']);
            $p->setJudul($row['judul']);
            $p->setKonten($row['konten']);
            $p->setTanggalSiar($row['tanggal_siar']);
            $p->setCreatedAt($row['created_at']);
            $p->setUpdatedAt($row['updated_at']);

            $result[] = $p;
        }

        $link = null;
        return $result;
    }

    public function addPengumuman(Pengumuman $pengumuman) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_pengumuman(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $pengumuman->getJudul());
        $stmt->bindValue(2, $pengumuman->getKonten());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    public function getPengumumanById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pengumuman WHERE id_pengumuman = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $p = new Pengumuman();
        $p->setIdPengumuman($row['id_pengumuman']);
        $p->setJudul($row['judul']);
        $p->setKonten($row['konten']);
        $p->setTanggalSiar($row['tanggal_siar']);
        $p->setCreatedAt($row['created_at']);
        $p->setUpdatedAt($row['updated_at']);

        $link = null;
        return $p;
    }

    public function updatePengumuman(Pengumuman $p) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE pengumuman SET judul = :judul, konten = :konten WHERE id_pengumuman = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':judul', $p->getJudul());
        $stmt->bindValue(':konten', $p->getKonten());
        $stmt->bindValue(':id', $p->getIdPengumuman());
        $result = $stmt->execute();
        $link = null;
        return $result;
    }

    public function deletePengumuman($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM pengumuman WHERE id_pengumuman = ?";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $result = $stmt->execute();
        $link = null;
        return $result;
    }
}
?>