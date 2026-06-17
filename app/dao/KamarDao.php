<?php

require_once __DIR__ . '/PDOUtil.php';
require_once __DIR__ . '/../model/Kamar.php';

class KamarDao {

    /**
     * Mengambil semua daftar kamar dari database.
     * Logika: Mengecek langsung apakah hari ini ada kontrak sewa yang aktif (Tersambung ke tabel kontrak_sewa). Jika ada, otomatis statusnya berubah jadi 'Terisi'.
     */
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

    /**
     * Mencari data satu kamar spesifik berdasarkan ID-nya.
     * Relasi: Memanggil fungsi syncKontrakStatus() dari KontrakDao.php untuk memastikan status sewanya paling update (tidak basi) sebelum datanya ditampilkan.
     */
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

    /**
     * Menyimpan data kamar baru yang baru saja dibuat admin ke dalam database.
     * Menggunakan Stored Procedure (sp_insert_kamar) di MySQL agar proses simpan lebih aman.
     */
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

    /**
     * Menyimpan perubahan data kamar (misalnya admin habis mengedit harga sewa atau tipe kamar) kembali ke database.
     */
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

    /**
     * Jalan pintas khusus untuk mengubah status kamar (contoh: dari 'Tersedia' menjadi 'Perbaikan' karena atap bocor) tanpa mengubah data lainnya.
     */
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

    /**
     * Menghapus data kamar dari database selamanya berdasarkan ID.
     */
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

    /**
     * Sama seperti getAllKamar, tapi ini khusus untuk Pagination (membagi data kamar berhalaman-halaman) agar loading website tidak berat kalau jumlah kamarnya ratusan.
     */
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

    /**
     * Menghitung total fisik jumlah seluruh kamar yang ada di kost ini (baik yang kosong maupun isi).
     */
    public function countKamar() {
        $link = PDOUtil::createConnection();

        return $link
            ->query("SELECT COUNT(*) FROM kamar")
            ->fetchColumn();
    }

    /**
     * Menghitung berapa banyak kamar yang saat ini benar-benar sedang diisi oleh penyewa (aktif dikontrak).
     */
    public function countOccupiedRooms()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT COUNT(*) FROM kamar k WHERE EXISTS (SELECT 1 FROM kontrak_sewa ks WHERE ks.id_kamar = k.id_kamar AND ks.status_aktif = 1 AND CURDATE() BETWEEN ks.tanggal_mulai AND ks.tanggal_selesai)";
        return $link->query($query)->fetchColumn();
    }

    /**
     * Merekap dan menghitung jumlah kamar berdasarkan statusnya masing-masing (Tersedia vs Terisi vs Perbaikan).
     * Biasanya ini dipanggil oleh DashboardController.php untuk menampilkan grafik donat (pie chart) di halaman awal admin.
     */
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
