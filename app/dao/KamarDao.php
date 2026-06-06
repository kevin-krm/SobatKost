<?php

require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kamar.php';

class KamarDao {

    public function getAllKamar() {

        $link = PDOUtil::createConnection();

        $query = "
            SELECT
                k.id_kamar,
                k.nomor_kamar,
                k.tipe_kamar,
                k.harga_dasar,
                CASE
                    WHEN k.status_kamar = 'Perbaikan'
                    THEN 'Perbaikan'

                    WHEN EXISTS (
                        SELECT 1
                        FROM kontrak_sewa ks
                        WHERE ks.id_kamar = k.id_kamar
                        AND ks.status_aktif = 1
                        AND CURDATE()
                            BETWEEN ks.tanggal_mulai
                            AND ks.tanggal_selesai
                    )
                    THEN 'Terisi'
                    ELSE k.status_kamar
                END AS status_real
            FROM kamar k
            ORDER BY k.id_kamar DESC
        ";

        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Kamar(
                $row['id_kamar'],
                $row['nomor_kamar'],
                $row['tipe_kamar'],
                $row['status_real'],
                $row['harga_dasar']
            );
        }

        return $result;
    }

    public function getKamarById($id) {
        $kontrakDao = new KontrakDao();
        $kontrakDao->syncKontrakStatus();

        $link = PDOUtil::createConnection();

        $query = "
            SELECT
                k.id_kamar,
                k.nomor_kamar,
                k.tipe_kamar,
                k.harga_dasar,

                CASE

                    WHEN k.status_kamar = 'Perbaikan'
                    THEN 'Perbaikan'

                    WHEN EXISTS (
                        SELECT 1
                        FROM kontrak_sewa ks
                        WHERE ks.id_kamar = k.id_kamar
                        AND ks.status_aktif = 1
                        AND CURDATE()
                            BETWEEN ks.tanggal_mulai
                            AND ks.tanggal_selesai
                    )

                    THEN 'Terisi'

                    ELSE k.status_kamar

                END AS status_real

            FROM kamar k

            WHERE k.id_kamar = :id
        ";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Kamar(
            $row['id_kamar'],
            $row['nomor_kamar'],
            $row['tipe_kamar'],
            $row['status_real'],
            $row['harga_dasar']
        );
    }

    public function insertKamar(Kamar $kamar) {
        $link = PDOUtil::createConnection();

        $query = "
            CALL sp_insert_kamar(
                :no,
                :tipe,
                :harga
            )
        ";

        $stmt = $link->prepare($query);

        $stmt->bindValue(':no', $kamar->getNomorKamar());
        $stmt->bindValue(':tipe', $kamar->getTipeKamar());
        $stmt->bindValue(':harga', $kamar->getHargaDasar());

        $stmt->execute();
    }

    public function updateKamar(Kamar $kamar) {
        $link = PDOUtil::createConnection();

        $query = "
            UPDATE kamar
            SET
                nomor_kamar = :no,
                tipe_kamar = :tipe,
                status_kamar = :status,
                harga_dasar = :harga
            WHERE id_kamar = :id
        ";

        $stmt = $link->prepare($query);

        $stmt->bindValue(':id', $kamar->getId());
        $stmt->bindValue(':no', $kamar->getNomorKamar());
        $stmt->bindValue(':tipe', $kamar->getTipeKamar());
        $stmt->bindValue(':status', $kamar->getStatusKamar());
        $stmt->bindValue(':harga', $kamar->getHargaDasar());

        $stmt->execute();
    }

    public function updateStatusKamar($id, $status) {
        $link = PDOUtil::createConnection();

        $query = "
            UPDATE kamar
            SET status_kamar = :status
            WHERE id_kamar = :id
        ";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    public function deleteKamar($id) {
        $link = PDOUtil::createConnection();

        $query = "
            DELETE FROM kamar
            WHERE id_kamar = :id
        ";

        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getKamarPage($limit, $offset) {
        $link = PDOUtil::createConnection();

        $query = "
            SELECT
                k.id_kamar,
                k.nomor_kamar,
                k.tipe_kamar,
                k.harga_dasar,

                CASE

                    WHEN k.status_kamar = 'Perbaikan'
                    THEN 'Perbaikan'

                    WHEN EXISTS (
                        SELECT 1
                        FROM kontrak_sewa ks
                        WHERE ks.id_kamar = k.id_kamar
                        AND ks.status_aktif = 1
                        AND CURDATE()
                            BETWEEN ks.tanggal_mulai
                            AND ks.tanggal_selesai
                    )

                    THEN 'Terisi'

                    ELSE k.status_kamar

                END AS status_real

            FROM kamar k

            ORDER BY k.created_at DESC

            LIMIT :limit OFFSET :offset
        ";

        $stmt = $link->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Kamar(
                $row['id_kamar'],
                $row['nomor_kamar'],
                $row['tipe_kamar'],
                $row['status_real'],
                $row['harga_dasar']
            );
        }

        return $result;
    }

    public function countKamar() {
        $link = PDOUtil::createConnection();

        return $link
            ->query("SELECT COUNT(*) FROM kamar")
            ->fetchColumn();
    }

    public function countOccupiedRooms()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM kamar k WHERE EXISTS (SELECT 1 FROM kontrak_sewa ks WHERE ks.id_kamar = k.id_kamar AND ks.status_aktif = 1 AND CURDATE() BETWEEN ks.tanggal_mulai AND ks.tanggal_selesai)";
        return $link->query($query)->fetchColumn();
    }

    public function countRoomsByStatus()
    {
        $link = PDOUtil::createConnection();
        $query = "
            SELECT 
                CASE
                    WHEN k.status_kamar = 'Perbaikan' THEN 'Perbaikan'
                    WHEN EXISTS (
                        SELECT 1
                        FROM kontrak_sewa ks
                        WHERE ks.id_kamar = k.id_kamar
                        AND ks.status_aktif = 1
                        AND CURDATE() BETWEEN ks.tanggal_mulai AND ks.tanggal_selesai
                    ) THEN 'Terisi'
                    ELSE k.status_kamar
                END AS status_real,
                COUNT(*) as jumlah
            FROM kamar k
            GROUP BY status_real
        ";
        $stmt = $link->prepare($query);
        $stmt->execute();

        $result = [
            'Tersedia' => 0,
            'Terisi' => 0,
            'Perbaikan' => 0
        ];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['status_real'];
            $result[$status] = (int)$row['jumlah'];
        }
        return $result;
    }
}
