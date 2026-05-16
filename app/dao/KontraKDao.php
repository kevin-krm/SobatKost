<?php
require_once __DIR__ . '/PDOUtil.php';

class Kontrak
{
    private $id_kontrak;
    private $id_pengguna;
    private $id_kamar;
    private $tanggal_mulai;
    private $tanggal_selesai;
    private $tipe_sewa;
    private $status_aktif;
    private $created_at;
    private $updated_at;
    private $nama_penyewa;
    private $nomor_kamar;
    private $harga_dasar;
    private $nama_peran;

    public function __construct(
        $id_kontrak,
        $id_pengguna,
        $id_kamar,
        $tanggal_mulai,
        $tanggal_selesai,
        $tipe_sewa,
        $status_aktif = true,
        $created_at = null,
        $updated_at = null,
        $nama_penyewa = null,
        $nomor_kamar = null,
        $harga_dasar = null,
        $nama_peran = null
    ) {
        $this->id_kontrak = $id_kontrak;
        $this->id_pengguna = $id_pengguna;
        $this->id_kamar = $id_kamar;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->tipe_sewa = $tipe_sewa;
        $this->status_aktif = $status_aktif;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->nama_penyewa = $nama_penyewa;
        $this->nomor_kamar = $nomor_kamar;
        $this->harga_dasar = $harga_dasar;
        $this->nama_peran = $nama_peran;
    }

    public function getIdKontrak() { return $this->id_kontrak; }
    public function getIdPengguna() { return $this->id_pengguna; }
    public function getIdKamar() { return $this->id_kamar; }
    public function getTanggalMulai() { return $this->tanggal_mulai; }
    public function getTanggalSelesai() { return $this->tanggal_selesai; }
    public function getTipeSewa() { return $this->tipe_sewa; }
    public function getStatusAktif() { return $this->status_aktif; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function getNamaPenyewa() { return $this->nama_penyewa; }
    public function getNomorKamar() { return $this->nomor_kamar; }
    public function getHargaDasar() { return $this->harga_dasar; }
}

class KontraKDao
{
    public function getAllKontrak()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT ks.*, p.nama_lengkap, k.nomor_kamar, k.harga_dasar
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

    public function getKontrakById($id_kontrak)
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT ks.*, p.nama_lengkap, k.nomor_kamar, k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.id_kontrak = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id_kontrak);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToKontrak($row) : null;
    }

    public function getKontrakAktif()
    {
        $link = PDOUtil::createConnection();
        $query = "SELECT ks.*, p.nama_lengkap, k.nomor_kamar, k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.status_aktif = TRUE
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
        $query = "SELECT ks.*, p.nama_lengkap, k.nomor_kamar, k.harga_dasar
                  FROM kontrak_sewa ks
                  JOIN pengguna p ON ks.id_pengguna = p.id_pengguna
                  JOIN kamar k ON ks.id_kamar = k.id_kamar
                  WHERE ks.id_pengguna = :id_pengguna
                  ORDER BY ks.tanggal_mulai DESC";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToKontrak($row);
        }

        return $result;
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
            $row['created_at'],
            $row['updated_at'],
            $row['nama_lengkap'],
            $row['nomor_kamar'],
            $row['harga_dasar']
        );
    }
}
?>
