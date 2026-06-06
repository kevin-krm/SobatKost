<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/BiayaOperasional.php';

class BiayaOperasionalDao {
    public function getBiayaPage($limit, $offset, $filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM biaya_operasional";
        $query .= $this->buildDateFilter('tanggal_pengeluaran', $filterType);
        $query .= " ORDER BY tanggal_pengeluaran DESC LIMIT :limit OFFSET :offset";
        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new BiayaOperasional($row['id_biaya'], $row['kategori_biaya'], $row['jumlah_biaya'], $row['tanggal_pengeluaran'], $row['keterangan']);
        }
        return $result;
    }

    public function countBiaya($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM biaya_operasional";
        $query .= $this->buildDateFilter('tanggal_pengeluaran', $filterType);
        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getPemasukanPage($limit, $offset, $filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT p.id_pembayaran,
                         p.id_tagihan,
                         p.metode_pembayaran,
                         p.tanggal_bayar,
                         p.status_verifikasi,
                         t.total_biaya_sewa,
                         t.biaya_tambahan,
                         pg.nama_lengkap,
                         k.nomor_kamar
                  FROM pembayaran p
                  JOIN tagihan t ON p.id_tagihan = t.id_tagihan
                  LEFT JOIN kontrak_sewa ks ON t.id_kontrak = ks.id_kontrak
                  LEFT JOIN pengguna pg ON ks.id_pengguna = pg.id_pengguna
                  LEFT JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE p.status_verifikasi = 'Berhasil'";

        $query .= $this->buildDateFilter('p.tanggal_bayar', $filterType, true);
        $query .= " ORDER BY p.tanggal_bayar DESC LIMIT :limit OFFSET :offset";

        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPemasukan($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*)
                  FROM pembayaran p
                  WHERE p.status_verifikasi = 'Berhasil'";

        $query .= $this->buildDateFilter('p.tanggal_bayar', $filterType, true);
        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getRingkasanKeuangan($filterType = 'all', $filterValue = null) {
        $pemasukan = $this->getTotalPemasukan($filterType, $filterValue);
        $pengeluaran = $this->getTotalPengeluaran($filterType, $filterValue);

        return [
            'total_pemasukan' => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'total_profit' => $pemasukan - $pengeluaran
        ];
    }

    public function getTotalPemasukan($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COALESCE(SUM(t.total_biaya_sewa + t.biaya_tambahan), 0)
                  FROM pembayaran p
                  JOIN tagihan t ON p.id_tagihan = t.id_tagihan
                  WHERE p.status_verifikasi = 'Berhasil'";

        $query .= $this->buildDateFilter('p.tanggal_bayar', $filterType, true);
        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function getTotalPengeluaran($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COALESCE(SUM(jumlah_biaya), 0) FROM biaya_operasional";
        $query .= $this->buildDateFilter('tanggal_pengeluaran', $filterType);

        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
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

    private function buildDateFilter($column, $filterType, $hasWhere = false) {
        if ($filterType === 'month') {
            return ($hasWhere ? " AND " : " WHERE ") . "DATE_FORMAT($column, '%Y-%m') = :filter_value";
        }

        if ($filterType === 'year') {
            return ($hasWhere ? " AND " : " WHERE ") . "YEAR($column) = :filter_value";
        }

        return '';
    }

    private function bindDateFilter(PDOStatement $stmt, $filterType, $filterValue) {
        if ($filterType === 'month' || $filterType === 'year') {
            $stmt->bindValue(':filter_value', $filterValue);
        }
    }
}
?>
