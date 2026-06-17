<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Inventaris.php';

class InventarisDao {
    /**
     * Mengambil daftar inventaris sepotong-sepotong agar loading layar tidak berat.
     */
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

    /**
     * Menghitung total seluruh barang yang ada di kost.
     */
    public function countInventaris() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM inventaris_kamar")->fetchColumn();
    }

    /**
     * Mencari detail satu barang spesifik berdasarkan ID-nya.
     */
    public function getInventarisById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM inventaris_kamar WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Inventaris(
            $row['id_inventaris'],
            $row['id_kamar'],
            $row['nama_barang'],
            $row['kondisi_barang']
        );
    }

    /**
     * Menyimpan data barang baru menggunakan Stored Procedure MySQL (sp_insert_inventaris).
     */
    public function insertInventaris(Inventaris $inventaris) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_inventaris(:id_kamar, :nama, :kondisi)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id_kamar', $inventaris->getIdKamar());
        $stmt->bindValue(':nama', $inventaris->getNamaBarang());
        $stmt->bindValue(':kondisi', $inventaris->getKondisiBarang());
        $stmt->execute();
    }

    // FUNGSI UPDATE UNTUK FORM EDIT
    /**
     * Menyimpan hasil editan data barang kembali ke database.
     */
    public function updateInventaris(Inventaris $inventaris) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE inventaris_kamar SET id_kamar = :id_kamar, nama_barang = :nama, kondisi_barang = :kondisi WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $inventaris->getIdInventaris());
        $stmt->bindValue(':id_kamar', $inventaris->getIdKamar());
        $stmt->bindValue(':nama', $inventaris->getNamaBarang());
        $stmt->bindValue(':kondisi', $inventaris->getKondisiBarang());

        $stmt->execute();
    }

    /**
     * Mengubah status kondisi barang (Rusak/Baik) secara langsung tanpa mengubah data lain.
     */
    public function updateKondisiBarang($id, $kondisi) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE inventaris_kamar SET kondisi_barang = :kondisi WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':kondisi', $kondisi);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    /**
     * Menghapus barang dari catatan database selamanya.
     */
    public function deleteInventaris($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM inventaris_kamar WHERE id_inventaris = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>