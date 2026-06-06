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

    public function getTotalByKategori() {
        $link = PDOUtil::createConnection();
        $query = "SELECT kategori_biaya, SUM(jumlah_biaya) as total 
                  FROM biaya_operasional 
                  GROUP BY kategori_biaya";
        $stmt = $link->prepare($query);
        $stmt->execute();
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['kategori_biaya']] = (float)$row['total'];
        }
        return $result;
    }

    public function getTotalBiayaKeseluruhan() {
        $link = PDOUtil::createConnection();
        $query = "SELECT SUM(jumlah_biaya) as total FROM biaya_operasional";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return (float)($stmt->fetchColumn() ?? 0);
    }

    public function getTotalBiayaCurrentMonth() {
        $link = PDOUtil::createConnection();
        $query = "SELECT SUM(jumlah_biaya) FROM biaya_operasional 
                  WHERE MONTH(tanggal_pengeluaran) = MONTH(CURRENT_DATE()) 
                    AND YEAR(tanggal_pengeluaran) = YEAR(CURRENT_DATE())";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return (float)($stmt->fetchColumn() ?? 0.0);
    }

    public function getTotalByKategoriFiltered($filterType, $year = null, $month = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT kategori_biaya, SUM(jumlah_biaya) as total FROM biaya_operasional";
        $where = [];
        $params = [];
        
        if ($filterType === 'year' && $year) {
            $where[] = "YEAR(tanggal_pengeluaran) = :year";
            $params[':year'] = $year;
        } else if ($filterType === 'month' && $year && $month) {
            $where[] = "YEAR(tanggal_pengeluaran) = :year AND MONTH(tanggal_pengeluaran) = :month";
            $params[':year'] = $year;
            $params[':month'] = $month;
        }
        
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        $query .= " GROUP BY kategori_biaya";
        $stmt = $link->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        }
        $stmt->execute();
        
        $result = [
            'Listrik' => 0.0,
            'Air' => 0.0,
            'Kebersihan' => 0.0,
            'Gaji Karyawan' => 0.0,
            'Perbaikan' => 0.0,
            'Lainnya' => 0.0
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cat = $row['kategori_biaya'];
            if (array_key_exists($cat, $result)) {
                $result[$cat] = (float)$row['total'];
            }
        }
        return $result;
    }
}
?>
