<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kontrak.php';

class KontrakDao
{
    public function getAllKontrak()
    {
        $this->syncKontrakStatus();
        $link = PDOUtil::createConnection();

        $query = "SELECT ks.*, 
                         p.nama_lengkap, 
                         k.nomor_kamar, 
                         k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  ORDER BY ks.created_at DESC";

        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToKontrak($row);
        }

        return $result;
    }

    public function getKontrakById($id)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT ks.*, 
                         p.nama_lengkap, 
                         k.nomor_kamar, 
                         k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.id_kontrak = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToKontrak($row) : null;
    }

    public function getKontrakAktif()
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT ks.*, 
                         p.nama_lengkap, 
                         k.nomor_kamar, 
                         k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.status_aktif = 2
                  ORDER BY ks.tanggal_mulai DESC";

        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToKontrak($row);
        }

        return $result;
    }

    public function getKontrakByPengguna($id_pengguna)
    {
        $link = PDOUtil::createConnection();

        $query = "SELECT ks.*, 
                         p.nama_lengkap, 
                         k.nomor_kamar, 
                         k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.id_pengguna = :id_pengguna
                  ORDER BY ks.tanggal_mulai DESC";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id_pengguna', $id_pengguna);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToKontrak($row);
        }

        return $result;
    }

    public function insertKontrak(Kontrak $kontrak)
    {
        $link = PDOUtil::createConnection();

        // cek status kamar
        $queryCek = "SELECT status_kamar 
                     FROM kamar 
                     WHERE id_kamar = :id";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->execute([
            ':id' => $kontrak->getIdKamar()
        ]);

        $kamar = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($kamar['status_kamar'] == 'Perbaikan') {
            $_SESSION['error'] = 'Kamar sedang dalam perbaikan dan tidak dapat disewa!';
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        // cek bentrok tanggal kontrak
        $checkQuery = "SELECT COUNT(*)
                       FROM kontrak_sewa
                       WHERE id_kamar = :kamar
                       AND status_aktif IN (1,2)
                       AND (
                            tanggal_mulai <= :tanggal_selesai
                            AND tanggal_selesai >= :tanggal_mulai
                       )";

        $checkStmt = $link->prepare($checkQuery);
        $checkStmt->bindValue(':kamar', $kontrak->getIdKamar());
        $checkStmt->bindValue(':tanggal_mulai', $kontrak->getTanggalMulai());
        $checkStmt->bindValue(':tanggal_selesai', $kontrak->getTanggalSelesai());
        $checkStmt->execute();

        $isBooked = $checkStmt->fetchColumn();

        if ($isBooked > 0) {
            $_SESSION['error'] = 'Kamar sudah dibooking pada periode tersebut';
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        // insert via stored procedure
        $query = "CALL sp_insert_kontrak(
                    :user,
                    :kamar,
                    :tipe
                  )";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':user', $kontrak->getIdPengguna());
        $stmt->bindValue(':kamar', $kontrak->getIdKamar());
        $stmt->bindValue(':tipe', $kontrak->getTipeSewa());
        $stmt->execute();
        $stmt->closeCursor();

        // ambil id terakhir
        $lastQuery = "SELECT id_kontrak
                      FROM kontrak_sewa
                      ORDER BY created_at DESC
                      LIMIT 1";

        $lastStmt = $link->prepare($lastQuery);
        $lastStmt->execute();
        $idKontrak = $lastStmt->fetchColumn();

        // update detail tanggal
        $updateQuery = "UPDATE kontrak_sewa
                        SET tanggal_mulai = :mulai,
                            tanggal_selesai = :selesai,
                            status_aktif = :status
                        WHERE id_kontrak = :id";

        $updateStmt = $link->prepare($updateQuery);
        $updateStmt->bindValue(':mulai', $kontrak->getTanggalMulai());
        $updateStmt->bindValue(':selesai', $kontrak->getTanggalSelesai());
        $updateStmt->bindValue(':status', $kontrak->getStatusAktif());
        $updateStmt->bindValue(':id', $idKontrak);
        $updateStmt->execute();

        $_SESSION['success'] = 'Kontrak berhasil ditambahkan';
    }

    public function updateKontrak(Kontrak $kontrak)
    {
        $link = PDOUtil::createConnection();

        $query = "UPDATE kontrak_sewa
                  SET id_pengguna = :pengguna,
                      id_kamar = :kamar,
                      tanggal_mulai = :mulai,
                      tanggal_selesai = :selesai,
                      tipe_sewa = :tipe,
                      status_aktif = :status
                  WHERE id_kontrak = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $kontrak->getIdKontrak());
        $stmt->bindValue(':pengguna', $kontrak->getIdPengguna());
        $stmt->bindValue(':kamar', $kontrak->getIdKamar());
        $stmt->bindValue(':mulai', $kontrak->getTanggalMulai());
        $stmt->bindValue(':selesai', $kontrak->getTanggalSelesai());
        $stmt->bindValue(':tipe', $kontrak->getTipeSewa());
        $stmt->bindValue(':status', $kontrak->getStatusAktif());
        $stmt->execute();
    }

    public function deleteKontrak($id)
    {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM kontrak_sewa
                  WHERE id_kontrak = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function syncKontrakStatus()
    {
        $link = PDOUtil::createConnection();

        $queryUpdateKontrak = "UPDATE kontrak_sewa
                               SET status_aktif = CASE
                                   WHEN CURDATE() > tanggal_selesai THEN 0
                                   WHEN CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai THEN 2
                                   ELSE 1
                               END";

        $link->query($queryUpdateKontrak);

        $link->query("UPDATE kamar 
                      SET status_kamar = 'Tersedia' 
                      WHERE status_kamar = 'Terisi'");

        $queryUpdateKamar = "UPDATE kamar
                             SET status_kamar = 'Terisi'
                             WHERE id_kamar IN (
                                 SELECT id_kamar
                                 FROM kontrak_sewa
                                 WHERE status_aktif = 2
                             )";

        $link->query($queryUpdateKamar);
    }

    private function mapRowToKontrak($row)
    {
        return new Kontrak(
            $row['id_kontrak'],
            $row['id_pengguna'],
            $row['id_kamar'],
            $row['tanggal_mulai'],
            $row['tanggal_selesai'],
            $row['tipe_sewa'],
            $row['status_aktif'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null,
            $row['nama_lengkap'] ?? null,
            $row['nomor_kamar'] ?? null,
            $row['harga_dasar'] ?? null
        );
    }
}