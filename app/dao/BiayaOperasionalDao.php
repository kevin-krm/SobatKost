<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/BiayaOperasional.php';

class BiayaOperasionalDao {
    public function getBiayaPage($limit, $offset) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM biaya_operasional ORDER BY tanggal_pengeluaran DESC LIMIT :limit OFFSET :offset";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new BiayaOperasional($row['id_biaya'], $row['kategori_biaya'], $row['jumlah_biaya'], $row['tanggal_pengeluaran'], $row['keterangan']);
        }
        return $result;
    }

    public function countBiaya() {
        $link = PDOUtil::createConnection();
        return $link->query("SELECT COUNT(*) FROM biaya_operasional")->fetchColumn();
    }

    public function getBiayaById($id) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM biaya_operasional WHERE id_biaya = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new BiayaOperasional($row['id_biaya'], $row['kategori_biaya'], $row['jumlah_biaya'], $row['tanggal_pengeluaran'], $row['keterangan']);
    }

    public function insertBiaya(BiayaOperasional $biaya) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_biaya(:kat, :jml, :ket)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':kat', $biaya->getKategoriBiaya());
        $stmt->bindValue(':jml', $biaya->getJumlahBiaya());
        $stmt->bindValue(':ket', $biaya->getKeterangan());
        $stmt->execute();
    }

    public function updateBiaya(BiayaOperasional $biaya) {
        $link = PDOUtil::createConnection();
        $query = "UPDATE biaya_operasional SET kategori_biaya = :kat, jumlah_biaya = :jml, tanggal_pengeluaran = :tgl, keterangan = :ket WHERE id_biaya = :id";
        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $biaya->getIdBiaya());
        $stmt->bindValue(':kat', $biaya->getKategoriBiaya());
        $stmt->bindValue(':jml', $biaya->getJumlahBiaya());
        $stmt->bindValue(':tgl', $biaya->getTanggalPengeluaran());
        $stmt->bindValue(':ket', $biaya->getKeterangan());

        $stmt->execute();
    }

    public function deleteBiaya($id) {
        $link = PDOUtil::createConnection();
        $query = "DELETE FROM biaya_operasional WHERE id_biaya = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>
