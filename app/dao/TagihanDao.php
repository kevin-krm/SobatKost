<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Tagihan.php';

class TagihanDao
{
    /**
     * Get semua tagihan
     */
    public function getAllTagihan()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM tagihan ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToTagihan($row);
        }

        return $result;
    }

    /**
     * Get tagihan berdasarkan ID
     */
    public function getTagihanById($id_tagihan)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM tagihan WHERE id_tagihan = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id_tagihan);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToTagihan($row) : null;
    }

    /**
     * Get tagihan berdasarkan ID Kontrak
     */
    public function getTagihanByKontrakId($id_kontrak)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT * FROM tagihan WHERE id_kontrak = :id_kontrak ORDER BY created_at DESC";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id_kontrak', $id_kontrak);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToTagihan($row);
        }

        return $result;
    }

    /**
     * Get tagihan dengan join ke kontrak_sewa
     */
    public function getTagihanWithKontrak($limit = 10, $offset = 0)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT t.*, ks.tipe_sewa, p.nama_lengkap, k.nomor_kamar
                  FROM tagihan t
                  JOIN kontrak_sewa ks ON t.id_kontrak = ks.id_kontrak
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  ORDER BY t.created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $link->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToTagihan($row);
        }

        return $result;
    }

    /**
     * Get tagihan dengan paginasi
     */
    public function getTagihanPage($limit = 10, $offset = 0)
    {
        return $this->getTagihanWithKontrak($limit, $offset);
    }

    /**
     * Count semua tagihan
     */
    public function countTagihan()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) as total FROM tagihan";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Count tagihan belum lunas
     */
    public function countTagihanBelumLunas()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) as total FROM tagihan WHERE status_tagihan = 'Belum Lunas'";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Count tagihan overdue
     */
    public function countTagihanOverdue()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) as total FROM tagihan 
                  WHERE status_tagihan = 'Belum Lunas' 
                  AND tanggal_jatuh_tempo < NOW()";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Insert tagihan
     */
    public function insertTagihan(Tagihan $tagihan)
    {
        $link = PDOUtil::createConnection();

        $id = TagihanFactory::generateTagihanId(rand(0, 999));
        $tagihan->setIdTagihan($id);

        $query = "INSERT INTO tagihan 
                  (id_tagihan, id_kontrak, total_biaya_sewa, biaya_tambahan, 
                   tanggal_jatuh_tempo, status_tagihan)
                  VALUES 
                  (:id, :id_kontrak, :total_sewa, :biaya_tambahan, 
                   :jatuh_tempo, :status)";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':id_kontrak', $tagihan->getIdKontrak());
        $stmt->bindValue(':total_sewa', $tagihan->getTotalBiayaSewa());
        $stmt->bindValue(':biaya_tambahan', $tagihan->getBiayaTambahan());
        $stmt->bindValue(':jatuh_tempo', $tagihan->getTanggalJatuhTempo());
        $stmt->bindValue(':status', $tagihan->getStatusTagihan());

        $stmt->execute();

        return $id;
    }

    /**
     * Update tagihan
     */
    public function updateTagihan(Tagihan $tagihan)
    {
        $link = PDOUtil::createConnection();

        $query = "UPDATE tagihan 
                  SET total_biaya_sewa = :total_sewa,
                      biaya_tambahan = :biaya_tambahan,
                      tanggal_jatuh_tempo = :jatuh_tempo,
                      status_tagihan = :status,
                      updated_at = NOW()
                  WHERE id_tagihan = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $tagihan->getIdTagihan());
        $stmt->bindValue(':total_sewa', $tagihan->getTotalBiayaSewa());
        $stmt->bindValue(':biaya_tambahan', $tagihan->getBiayaTambahan());
        $stmt->bindValue(':jatuh_tempo', $tagihan->getTanggalJatuhTempo());
        $stmt->bindValue(':status', $tagihan->getStatusTagihan());

        $stmt->execute();
    }

    /**
     * Update status tagihan
     */
    public function updateStatusTagihan($id_tagihan, $status)
    {
        $link = PDOUtil::createConnection();

        $query = "UPDATE tagihan 
                  SET status_tagihan = :status, 
                      updated_at = NOW()
                  WHERE id_tagihan = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id_tagihan);
        $stmt->bindValue(':status', $status);

        $stmt->execute();
    }

    /**
     * Delete tagihan
     */
    public function deleteTagihan($id_tagihan)
    {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM tagihan WHERE id_tagihan = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id_tagihan);

        $stmt->execute();
    }

    /**
     * Map row database ke object Tagihan
     */
    private function mapRowToTagihan($row)
    {
        $tagihan = new Tagihan(
            $row['id_tagihan'],
            $row['id_kontrak'],
            $row['total_biaya_sewa'],
            $row['biaya_tambahan'],
            $row['tanggal_jatuh_tempo'],
            $row['status_tagihan'],
            $row['tipe_sewa'] ?? null,
            $row['created_at'],
            $row['updated_at']
        );

        return $tagihan;
    }

    /**
     * Get statistik tagihan
     */
    public function getStatistik()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT 
                    COUNT(*) as total_tagihan,
                    SUM(CASE WHEN status_tagihan = 'Lunas' THEN 1 ELSE 0 END) as total_lunas,
                    SUM(CASE WHEN status_tagihan = 'Belum Lunas' THEN 1 ELSE 0 END) as total_belum_lunas,
                    SUM(CASE WHEN status_tagihan = 'Belum Lunas' AND tanggal_jatuh_tempo < NOW() THEN 1 ELSE 0 END) as total_overdue,
                    SUM(CASE WHEN status_tagihan = 'Lunas' THEN total_biaya_sewa + biaya_tambahan ELSE 0 END) as total_penerimaan
                  FROM tagihan";
        
        $stmt = $link->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
