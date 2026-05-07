<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Inventaris.php';

class InventarisDao {
    public function getInventarisPage($limit, $offset) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM inventaris_kamar ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Inventaris($row['id_inventaris'], $row['id_kamar'], $row['nama_barang'], $row['kondisi_barang']);
        }
        return $result;
    }

    public function countInventaris() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM inventaris_kamar")->fetchColumn();
    }

    public function getInventarisById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM inventaris WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Inventaris(
            $row['id_inventaris'],
            $row['id_kamar'],
            $row['nama_barang'],
            $row['kondisi_barang'],
        );
    }

    public function insertInventaris(Inventaris $inventaris) {
        $link = PDOUtil::createConnection();
        // Menggunakan Stored Procedure sesuai kodemu sebelumnya
        $query = "CALL sp_insert_inventaris(:id_kamar, :nama, :kondisi)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id_kamar', $inventaris->getIdKamar());
        $stmt->bindValue(':nama', $inventaris->getNamaBarang());
        $stmt->bindValue(':kondisi', $inventaris->getKondisiBarang());
        $stmt->execute();
    }

    public function updateKondisiBarang($id, $kondisi) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE inventaris_kamar SET kondisi_barang = :kondisi WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':kondisi', $kondisi);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function deleteInventaris($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM inventaris_kamar WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>