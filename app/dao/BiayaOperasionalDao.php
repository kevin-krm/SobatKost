<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/BiayaOperasional.php';

class BiayaOperasionalDao {
    /**
     * Mengambil data pengeluaran operasional yang difilter dan menggunakan metode pagination.
     */
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

    /**
     * Menghitung total jumlah baris data pengeluaran operasional setelah diterapkan filter.
     */
    public function countBiaya($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM biaya_operasional";
        $query .= $this->buildDateFilter('tanggal_pengeluaran', $filterType);
        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Mengambil data uang masuk (pemasukan tagihan lunas) untuk keperluan rekap keuangan.
     */
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

    /**
     * Menghitung total jumlah baris data transaksi uang masuk.
     */
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

    /**
     * Merangkum total pemasukan dan pengeluaran secara komprehensif untuk melihat sisa laba bersih.
     */
    public function getRingkasanKeuangan($filterType = 'all', $filterValue = null) {
        $pemasukan = $this->getTotalPemasukan($filterType, $filterValue);
        $pengeluaran = $this->getTotalPengeluaran($filterType, $filterValue);

        return [
            'total_pemasukan' => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'total_profit' => $pemasukan - $pengeluaran
        ];
    }

    /**
     * Menghitung nominal keseluruhan pemasukan uang dari pembayaran sewa kamar.
     */
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

    /**
     * Menghitung nominal keseluruhan biaya operasional bulanan yang telah dikeluarkan.
     */
    public function getTotalPengeluaran($filterType = 'all', $filterValue = null) {
        $link = PDOUtil::createConnection();
        $query = "SELECT COALESCE(SUM(jumlah_biaya), 0) FROM biaya_operasional";
        $query .= $this->buildDateFilter('tanggal_pengeluaran', $filterType);

        $stmt = $link->prepare($query);
        $this->bindDateFilter($stmt, $filterType, $filterValue);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    /**
     * Mencari detail satu pengeluaran operasional secara spesifik di database.
     */
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

    /**
     * Menyimpan data catatan pengeluaran uang baru ke dalam tabel operasional.
     */
    public function insertBiaya(BiayaOperasional $biaya) {
        $link = PDOUtil::createConnection();
        $query = "CALL sp_insert_biaya(:kat, :jml, :ket)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':kat', $biaya->getKategoriBiaya());
        $stmt->bindValue(':jml', $biaya->getJumlahBiaya());
        $stmt->bindValue(':ket', $biaya->getKeterangan());
        $stmt->execute();
    }

    /**
     * Memperbarui rincian data pengeluaran operasional yang sudah ada sebelumnya.
     */
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

    /**
     * Menghapus rekam jejak biaya pengeluaran operasional secara permanen.
     */
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
