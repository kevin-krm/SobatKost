<?php
require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kontrak.php';

class KontrakDao {
    public function getAllKontrak() {
        $this->syncKontrakStatus();
        $link = PDOUtil::createConnection();

        $query = "SELECT k.*, p.nama_lengkap 
              FROM kontrak_sewa k
              JOIN pengguna p ON k.id_pengguna = p.id_pengguna
              ORDER BY k.created_at DESC";

        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $this->calculateStatus($row['tanggal_mulai'], $row['tanggal_selesai'], $row['status_aktif']);

            $obj = new Kontrak(
                $row['id_kontrak'],
                $row['id_pengguna'],
                $row['id_kamar'],
                $row['tanggal_mulai'],
                $row['tanggal_selesai'],
                $row['tipe_sewa'],
                $row['status_aktif'],
                $row['nama_lengkap']
            );
            $result[] = $obj;
        }
        return $result;
    }

    public function getKontrakById($id) {
        $link = PDOUtil::createConnection();

        $query = "SELECT k.*, p.nama_lengkap 
              FROM kontrak_sewa k
              JOIN pengguna p ON k.id_pengguna = p.id_pengguna
              WHERE k.id_kontrak = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $status = $this->calculateStatus($row['tanggal_mulai'], $row['tanggal_selesai'], $row['status_aktif']);

        return new Kontrak(
            $row['id_kontrak'],
            $row['id_pengguna'],
            $row['id_kamar'],
            $row['tanggal_mulai'],
            $row['tanggal_selesai'],
            $row['tipe_sewa'],
            $row['status_aktif'],
            $row['nama_lengkap']
        );
    }

    public function insertKontrak(Kontrak $kontrak) {
        $link = PDOUtil::createConnection();

        $queryCek = "SELECT status_kamar FROM kamar WHERE id_kamar = :id";
        $stmtCek = $link->prepare($queryCek);
        $stmtCek->execute([':id' => $kontrak->getIdKamar()]);
        $kamar = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($kamar['status_kamar'] == 'Perbaikan') {
            $_SESSION['error'] = 'Kamar sedang dalam perbaikan dan tidak dapat disewa!';
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        $checkQuery = "
        SELECT COUNT(*)
        FROM kontrak_sewa
        WHERE id_kamar = :kamar
        AND status_aktif = 1
        AND (
            tanggal_mulai <= :tanggal_selesai
            AND
            tanggal_selesai >= :tanggal_mulai
        )
    ";

        $checkStmt = $link->prepare($checkQuery);

        $checkStmt->bindValue(
            ':kamar',
            $kontrak->getIdKamar()
        );

        $checkStmt->bindValue(
            ':tanggal_mulai',
            $kontrak->getTanggalMulai()
        );

        $checkStmt->bindValue(
            ':tanggal_selesai',
            $kontrak->getTanggalSelesai()
        );

        $checkStmt->execute();

        $isBooked = $checkStmt->fetchColumn();

        if ($isBooked > 0) {
            $_SESSION['error'] =
                'Kamar sudah dibooking pada periode tersebut';

            header(
                'Location: /SobatKost/index.php?url=kontrak/create'
            );
            exit;
        }

        $query = "
        CALL sp_insert_kontrak(
            :user,
            :kamar,
            :tipe
        )
    ";

        $stmt = $link->prepare($query);

        $stmt->bindValue(
            ':user',
            $kontrak->getIdPengguna()
        );

        $stmt->bindValue(
            ':kamar',
            $kontrak->getIdKamar()
        );

        $stmt->bindValue(
            ':tipe',
            $kontrak->getTipeSewa()
        );

        $stmt->execute();
        $stmt->closeCursor();

        $lastQuery = "
        SELECT id_kontrak
        FROM kontrak_sewa
        ORDER BY created_at DESC
        LIMIT 1
    ";

        $lastStmt = $link->prepare($lastQuery);
        $lastStmt->execute();
        $idKontrak = $lastStmt->fetchColumn();
        $updateQuery = "
        UPDATE kontrak_sewa
        SET
            tanggal_mulai = :mulai,
            tanggal_selesai = :selesai,
            status_aktif = :status
        WHERE id_kontrak = :id
    ";

        $updateStmt = $link->prepare($updateQuery);
        $updateStmt->bindValue(
            ':mulai',
            $kontrak->getTanggalMulai()
        );

        $updateStmt->bindValue(
            ':selesai',
            $kontrak->getTanggalSelesai()
        );

        $updateStmt->bindValue(
            ':status',
            $kontrak->getStatusAktif()
        );

        $updateStmt->bindValue(
            ':id',
            $idKontrak
        );

        $updateStmt->execute();

        $_SESSION['success'] =
            'Kontrak berhasil ditambahkan';
    }

    public function updateKontrak(Kontrak $kontrak) {
        $link = PDOUtil::createConnection();

        $query = "UPDATE kontrak_sewa
                  SET
                    id_pengguna = :pengguna,
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

    public function deleteKontrak($id) {
        $link = PDOUtil::createConnection();

        $query = "DELETE FROM kontrak_sewa
                  WHERE id_kontrak = :id";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    private function calculateStatus($mulai, $selesai, $statusDB) {
        $today = date('Y-m-d');

        if ($statusDB == 0) return 0;
        if ($today > $selesai) {
            return 0;
        } elseif ($today >= $mulai && $today <= $selesai) {
            return 2;
        } else {
            return 1;
        }
    }

    public function syncKontrakStatus() {
        $link = PDOUtil::createConnection();

        $queryUpdateKontrak = "UPDATE kontrak_sewa 
          SET status_aktif = CASE 
              WHEN CURDATE() > tanggal_selesai THEN 0
              WHEN CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai THEN 2
              ELSE 1
          END
          WHERE status_aktif != 0 OR (CURDATE() > tanggal_selesai AND status_aktif = 1)";

        $link->query($queryUpdateKontrak);
        $link->query("UPDATE kamar SET status_kamar = 'Tersedia' WHERE status_kamar = 'Terisi'");

        $queryUpdateKamar = "UPDATE kamar 
                         SET status_kamar = 'Terisi' 
                         WHERE id_kamar IN (
                             SELECT id_kamar FROM kontrak_sewa WHERE status_aktif = 2
                         )";
        $link->query($queryUpdateKamar);
    }
}