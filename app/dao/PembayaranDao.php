<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Pembayaran.php';

class PembayaranDao
{
    /**
     * Get semua pembayaran
     */
    public function getAllPembayaran()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pembayaran ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToPembayaran($row);
        }

        return $result;
    }

    /**
     * Get pembayaran berdasarkan ID
     */
    public function getPembayaranById($id_pembayaran)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pembayaran WHERE id_pembayaran = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id_pembayaran);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToPembayaran($row) : null;
    }

    /**
     * Get pembayaran berdasarkan ID Tagihan
     */
    public function getPembayaranByTagihanId($id_tagihan)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM pembayaran WHERE id_tagihan = :id_tagihan ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id_tagihan', $id_tagihan);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToPembayaran($row);
        }

        return $result;
    }

    /**
     * Get pembayaran dengan join detail
     */
    public function getPembayaranWithDetail($limit = 10, $offset = 0)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT p.*, t.total_biaya_sewa, t.status_tagihan, 
                         ks.tipe_sewa, pg.nama_lengkap, k.nomor_kamar
                  FROM pembayaran p
                  JOIN tagihan t ON p.id_tagihan = t.id_tagihan
                  JOIN kontrak_sewa ks ON t.id_kontrak = ks.id_kontrak
                  JOIN pengguna pg ON ks.id_pengguna = pg.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  ORDER BY p.created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $link->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToPembayaran($row);
        }

        return $result;
    }

    /**
     * Get pembayaran dengan paginasi
     */
    public function getPembayaranPage($limit = 10, $offset = 0)
    {
        return $this->getPembayaranWithDetail($limit, $offset);
    }

    /**
     * Get pembayaran berdasarkan status
     */
    public function getPembayaranByStatus($status, $limit = 10, $offset = 0)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT p.*, t.total_biaya_sewa, t.status_tagihan, 
                         ks.tipe_sewa, pg.nama_lengkap, k.nomor_kamar
                  FROM pembayaran p
                  JOIN tagihan t ON p.id_tagihan = t.id_tagihan
                  JOIN kontrak_sewa ks ON t.id_kontrak = ks.id_kontrak
                  JOIN pengguna pg ON ks.id_pengguna = pg.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE p.status_verifikasi = :status
                  ORDER BY p.created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $link->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToPembayaran($row);
        }

        return $result;
    }

    /**
     * Count pembayaran
     */
    public function countPembayaran()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) as total FROM pembayaran";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Count pembayaran by status
     */
    public function countPembayaranByStatus($status)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) as total FROM pembayaran WHERE status_verifikasi = :status";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Insert pembayaran
     */
    public function insertPembayaran(Pembayaran $pembayaran)
    {
        $link = PDOUtil::createConnection();

        $id = TagihanFactory::generatePembayaranId(rand(0, 999));
        $pembayaran->setIdPembayaran($id);

        $query = "INSERT INTO pembayaran 
                  (id_pembayaran, id_tagihan, metode_pembayaran, bukti_pembayaran, 
                   tanggal_bayar, status_verifikasi)
                  VALUES 
                  (:id, :id_tagihan, :metode, :bukti, :tanggal, :status)";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':id_tagihan', $pembayaran->getIdTagihan());
        $stmt->bindValue(':metode', $pembayaran->getMetodePembayaran());
        $stmt->bindValue(':bukti', $pembayaran->getBuktiPembayaran());
        $stmt->bindValue(':tanggal', $pembayaran->getTanggalBayar());
        $stmt->bindValue(':status', $pembayaran->getStatusVerifikasi());

        $stmt->execute();

        return $id;
    }

    /**
     * Update pembayaran
     */
    public function updatePembayaran(Pembayaran $pembayaran)
    {
        $link = PDOUtil::createConnection();

        $query = "UPDATE pembayaran 
                  SET id_tagihan = :id_tagihan,
                      metode_pembayaran = :metode,
                      bukti_pembayaran = :bukti,
                      tanggal_bayar = :tanggal,
                      status_verifikasi = :status,
                      updated_at = NOW()
                  WHERE id_pembayaran = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $pembayaran->getIdPembayaran());
        $stmt->bindValue(':id_tagihan', $pembayaran->getIdTagihan());
        $stmt->bindValue(':metode', $pembayaran->getMetodePembayaran());
        $stmt->bindValue(':bukti', $pembayaran->getBuktiPembayaran());
        $stmt->bindValue(':tanggal', $pembayaran->getTanggalBayar());
        $stmt->bindValue(':status', $pembayaran->getStatusVerifikasi());

        $stmt->execute();
    }

    /**
     * Update status pembayaran
     */
    public function updateStatusPembayaran($id_pembayaran, $status)
    {
        $link = PDOUtil::createConnection();

        $query = "UPDATE pembayaran 
                  SET status_verifikasi = :status, 
                      updated_at = NOW()
                  WHERE id_pembayaran = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id_pembayaran);
        $stmt->bindValue(':status', $status);

        $stmt->execute();

        // Jika pembayaran berhasil, update status tagihan
        if ($status === 'Berhasil') {
            $pembayaran = $this->getPembayaranById($id_pembayaran);
            $link = PDOUtil::createConnection();
            $query = "UPDATE tagihan SET status_tagihan = 'Lunas' WHERE id_tagihan = :id_tagihan";
            $stmt = $link->prepare($query);
            $stmt->bindValue(':id_tagihan', $pembayaran->getIdTagihan());
            $stmt->execute();
        }
    }

    /**
     * Delete pembayaran
     */
    public function deletePembayaran($id_pembayaran)
    {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM pembayaran WHERE id_pembayaran = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id_pembayaran);

        $stmt->execute();
    }

    // Map row database ke object Pembayaran
    private function mapRowToPembayaran($row)
    {
        $pembayaran = new Pembayaran(
            $row['id_pembayaran'],
            $row['id_tagihan'],
            $row['metode_pembayaran'],
            $row['bukti_pembayaran'],
            $row['tanggal_bayar'],
            $row['status_verifikasi'],
            $row['created_at'],
            $row['updated_at'],
                $row['nama_lengkap'] ?? null,
    $row['nomor_kamar'] ?? null
        );

        return $pembayaran;
    }

    public function getStatistik()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT 
                    COUNT(*) as total_pembayaran,
                    SUM(CASE WHEN status_verifikasi = 'Berhasil' THEN 1 ELSE 0 END) as total_berhasil,
                    SUM(CASE WHEN status_verifikasi = 'Proses' THEN 1 ELSE 0 END) as total_proses,
                    SUM(CASE WHEN status_verifikasi = 'Ditolak' THEN 1 ELSE 0 END) as total_ditolak
                  FROM pembayaran";
        
        $stmt = $link->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countByVerificationStatus()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT status_verifikasi, COUNT(*) as jumlah 
                  FROM pembayaran 
                  GROUP BY status_verifikasi";
        $stmt = $link->prepare($query);
        $stmt->execute();
        
        $result = [
            'Proses' => 0,
            'Berhasil' => 0,
            'Ditolak' => 0
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['status_verifikasi'];
            if (isset($result[$status])) {
                $result[$status] = (int)$row['jumlah'];
            }
        }
        return $result;
    }

    public function getRevenueCurrentMonth()
    {
        $link = PDOUtil::createConnection();
        $query = "
            SELECT SUM(t.total_biaya_sewa + t.biaya_tambahan) as total
            FROM pembayaran p
            JOIN tagihan t ON p.id_tagihan = t.id_tagihan
            WHERE p.status_verifikasi = 'Berhasil'
              AND MONTH(p.tanggal_bayar) = MONTH(CURRENT_DATE())
              AND YEAR(p.tanggal_bayar) = YEAR(CURRENT_DATE())
        ";
        $stmt = $link->prepare($query);
        $stmt->execute();
        return (float)($stmt->fetchColumn() ?? 0.0);
    }

    public function getAvailableYears()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT DISTINCT YEAR(tanggal_bayar) as tahun 
                  FROM pembayaran 
                  WHERE status_verifikasi = 'Berhasil'
                  ORDER BY tahun DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($years)) {
            $years = [date('Y')];
        }
        return $years;
    }

    public function getRevenueByMonthForYear($year)
    {
        $link = PDOUtil::createConnection();
        $query = "
            SELECT 
                DATE_FORMAT(p.tanggal_bayar, '%m') as bulan,
                SUM(t.total_biaya_sewa + t.biaya_tambahan) as total
            FROM pembayaran p
            JOIN tagihan t ON p.id_tagihan = t.id_tagihan
            WHERE p.status_verifikasi = 'Berhasil'
              AND YEAR(p.tanggal_bayar) = :year
            GROUP BY DATE_FORMAT(p.tanggal_bayar, '%m')
            ORDER BY bulan ASC
        ";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        
        $raw = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $raw[$row['bulan']] = (float)$row['total'];
        }
        
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = str_pad($m, 2, '0', STR_PAD_LEFT);
            $result[$key] = $raw[$key] ?? 0.0;
        }
        return $result;
    }

    public function countByVerificationStatusFiltered($filterType, $year = null, $month = null)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT status_verifikasi, COUNT(*) as jumlah FROM pembayaran";
        $where = [];
        $params = [];
        
        if ($filterType === 'year' && $year) {
            $where[] = "YEAR(tanggal_bayar) = :year";
            $params[':year'] = $year;
        } else if ($filterType === 'month' && $year && $month) {
            $where[] = "YEAR(tanggal_bayar) = :year AND MONTH(tanggal_bayar) = :month";
            $params[':year'] = $year;
            $params[':month'] = $month;
        }
        
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        $query .= " GROUP BY status_verifikasi";
        $stmt = $link->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        }
        $stmt->execute();
        
        $result = [
            'Proses' => 0,
            'Berhasil' => 0,
            'Ditolak' => 0
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['status_verifikasi'];
            if (isset($result[$status])) {
                $result[$status] = (int)$row['jumlah'];
            }
        }
        return $result;
    }
}
?>
