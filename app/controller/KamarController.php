<?php
require_once APP_PATH . '/dao/KamarDao.php';

class KamarController
{
    /**
     * Mengatur tampilan halaman utama daftar kamar.
     * Relasi: Memanggil fungsi getKamarPage() dari KamarDao.php untuk mengambil data sepotong-sepotong (pagination).
     */
    public function index()
    {
        $dao = new KamarDao();

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $kamarList = $dao->getKamarPage($limit, $offset);
        $totalData = $dao->countKamar();
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/kamar/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menampilkan form halaman kosong agar admin bisa mendaftarkan fisik kamar baru ke sistem.
     */
    public function create()
    {
        $contentView = APP_PATH . '/view/kamar/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menangkap data dari form "Tambah Kamar", merakitnya jadi objek Kamar, lalu menyuruh KamarDao.php (insertKamar) untuk menyimpannya ke MySQL.
     */
    public function store()
    {
        $nomor = $_POST['nomor_kamar'];
        $tipe = $_POST['tipe_kamar'];
        $harga = $_POST['harga_dasar'];

        $kamar = new Kamar(
            null,
            $nomor,
            $tipe,
            "Tersedia",
            $harga
        );

        $dao = new KamarDao();
        $dao->insertKamar($kamar);

        header("Location: /SobatKost/kamar");
        exit;
    }

    /**
     * Menampilkan halaman form edit. 
     * Relasi: Memanggil fungsi getKamarById() di KamarDao.php. 
     * Sistem juga memblokir admin jika mencoba mengedit harga kamar yang sedang berstatus 'Terisi'.
     */
    public function edit($id)
    {
        require_once APP_PATH . '/dao/KontrakDao.php';
        $dao = new KamarDao();
        $kamar = $dao->getKamarById($id);

        if (!$kamar) {
            echo "<h3>Data kamar tidak ditemukan</h3>";
            exit;
        }

        if ($kamar->getStatusKamar() === 'Terisi') {
            $_SESSION['error'] = 'Kamar yang sedang terisi tidak dapat diedit.';
            header('Location: ' . BASE_URL . '/kamar');
            exit;
        }

        $contentView = APP_PATH . '/view/kamar/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menangkap perubahan data dari form "Edit Kamar", lalu meneruskannya ke fungsi updateKamar() di KamarDao.php agar tersimpan di database.
     */
    public function update($id)
    {
        $kamar = new Kamar(
            $id,
            $_POST['nomor_kamar'],
            $_POST['tipe_kamar'],
            $_POST['status_kamar'],
            $_POST['harga_dasar']
        );

        $dao = new KamarDao();
        $dao->updateKamar($kamar);

        header("Location: /SobatKost/kamar");
        exit;
    }

    /**
     * Jalur khusus untuk sekadar mengubah status kamar (misalnya tiba-tiba AC rusak, admin mengubah status kamar jadi 'Perbaikan').
     */
    public function updateStatus($id)
    {
        if (isset($_POST['status_kamar'])) {
            $status = $_POST['status_kamar'];

            $dao = new KamarDao();
            $dao->updateStatusKamar($id, $status);
        }

        header("Location: /SobatKost/index.php?url=kamar");
        exit;
    }

    /**
     * Mengeksekusi penghapusan data kamar. 
     * Sistem akan menolak perintah hapus jika fungsi getKamarById() dari KamarDao mendeteksi kamar ini masih dipakai (Terisi).
     */
    public function delete($id)
    {
        require_once APP_PATH . '/dao/KontrakDao.php';
        $dao = new KamarDao();
        $kamar = $dao->getKamarById($id);

        if (!$kamar) {
            echo "Data tidak ditemukan";
            exit;
        }

        if ($kamar->getStatusKamar() === 'Terisi') {
            $_SESSION['error'] = 'Kamar yang sedang terisi tidak dapat dihapus.';
            header('Location: /SobatKost/index.php?url=kamar');
            exit;
        }

        try {
            $dao->deleteKamar($id);

        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Kamar tidak dapat dihapus karena masih digunakan pada data lain.';

        }

        header("Location: /SobatKost/index.php?url=kamar");
        exit;
    }
}