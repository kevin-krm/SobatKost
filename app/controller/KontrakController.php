<?php
/**
 * Menyimpan data kesepakatan sewa. Nyawa penting sebelum tagihan bisa dicetak.
 */
require_once APP_PATH . '/dao/KontrakDao.php';
require_once APP_PATH . '/model/Kontrak.php';

class KontrakController {
    /**
     * Menampilkan halaman utama daftar perjanjian kontrak sewa.
     */
    public function index() {
        $dao = new KontrakDao();
        $limit = 10;
        $page = isset($_GET['page'])
            ? (int) $_GET['page']
            : 1;

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $totalData = count(
            $dao->getAllKontrak()
        );

        $totalPage = ceil(
            $totalData / $limit
        );

        $kontrakList = array_slice(
            $dao->getAllKontrak(),
            $offset,
            $limit
        );

        $contentView =
            APP_PATH . '/view/kontrak/index.php';

        require_once
            APP_PATH . '/view/index.php';
    }

    /**
     * Menampilkan form untuk membuat kontrak sewa baru.
     * Relasi: Memanggil data kamar dan pengguna untuk dipilih di form.
     */
    public function create() {
        require_once APP_PATH . '/dao/KamarDao.php';
        require_once APP_PATH . '/dao/KontrakDao.php';

        $kamarDao = new KamarDao();
        $kontrakDao = new KontrakDao();

        $kontrakDao->syncKontrakStatus();

        $kamarList = $kamarDao->getAllKamar();

        $link = PDOUtil::createConnection();
        $stmt = $link->query("SELECT id_pengguna, nama_lengkap FROM pengguna");
        $penggunaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $contentView = APP_PATH . '/view/kontrak/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menyimpan data kontrak sewa baru ke sistem.
     * Relasi: Fungsi ini akan otomatis memengaruhi status kamar (menjadi 'Terisi') di KamarDao.php.
     */
    public function store() {
        $id_kamar = $_POST['id_kamar'];
        $mulai = $_POST['tanggal_mulai'];
        $selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : null;
        $tipe_sewa = $_POST['tipe_sewa'];

        if ($tipe_sewa === 'Bulanan') {
            $day = date('d', strtotime($mulai));
            if ($day !== '01') {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['error'] = "Untuk tipe sewa Bulanan, tanggal mulai harus awal bulan (tanggal 1)!";
                header('Location: /SobatKost/index.php?url=kontrak/create');
                exit;
            }
        }

        $link = PDOUtil::createConnection();

        // 1. Cek status kamar (tidak boleh dalam Perbaikan)
        $queryKamar = "SELECT status_kamar FROM kamar WHERE id_kamar = :id_kamar";
        $stmtKamar = $link->prepare($queryKamar);
        $stmtKamar->execute([':id_kamar' => $id_kamar]);
        $kamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);

        if ($kamar && $kamar['status_kamar'] == 'Perbaikan') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar sedang dalam perbaikan dan tidak dapat disewa!";
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        // 2. Cek bentrok tanggal kontrak (booked)
        $queryCek = "SELECT COUNT(*) as total FROM kontrak_sewa 
                 WHERE id_kamar = :id_kamar 
                 AND status_aktif IN (1, 2) 
                 AND (
                    (tanggal_selesai IS NULL OR tanggal_selesai >= :mulai)
                    AND (:selesai_param IS NULL OR tanggal_mulai <= :selesai)
                 )";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->bindValue(':id_kamar', $id_kamar);
        $stmtCek->bindValue(':mulai', $mulai);
        $stmtCek->bindValue(':selesai', $selesai, $selesai === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmtCek->bindValue(':selesai_param', $selesai, $selesai === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmtCek->execute();

        $row = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar ini sudah terisi atau dibooking pada rentang tanggal tersebut!";
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        $today = date('Y-m-d');

        if ($selesai !== null && $today > $selesai) {
            $statusOtomatis = 0;
        } elseif ($today >= $mulai && ($selesai === null || $today <= $selesai)) {
            $statusOtomatis = 2;
        } else {
            $statusOtomatis = 1;
        }

        $kontrak = new Kontrak(
            null,
            $_POST['id_pengguna'],
            $id_kamar,
            $mulai,
            $selesai,
            $_POST['tipe_sewa'],
            $statusOtomatis,
            null
        );

        $dao = new KontrakDao();
        $dao->insertKontrak($kontrak);
        $dao->syncKontrakStatus();

        header('Location: /SobatKost/index.php?url=kontrak');
        exit;
    }

    /**
     * Menampilkan form untuk memperbarui data perjanjian sewa.
     */
    public function edit($id) {
        require_once APP_PATH . '/dao/KamarDao.php';
        require_once APP_PATH . '/dao/KontrakDao.php';
        $dao = new KontrakDao();
        $kontrak = $dao->getKontrakById($id);

        if (!$kontrak) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Data kontrak tidak ditemukan';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        if ($kontrak->getStatusAktif() == 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Kontrak yang sudah selesai tidak dapat diubah!';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }
        $kamarDao = new KamarDao();
        $kamarList = $kamarDao->getAllKamar();
        $contentView = APP_PATH . '/view/kontrak/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menyimpan hasil editan data kontrak kembali ke database via KontrakDao.php.
     */
    public function update($id) {
        require_once APP_PATH . '/dao/KontrakDao.php';
        $dao = new KontrakDao();
        $existing = $dao->getKontrakById($id);

        if (!$existing) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Data kontrak tidak ditemukan';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        if ($existing->getStatusAktif() == 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Kontrak yang sudah selesai tidak dapat diubah!';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        $mulai = $_POST['tanggal_mulai'];

        // 1. Jika kontrak AKTIF, tanggal mulai TIDAK BOLEH berbeda dari data asli di database
        if ($existing->getStatusAktif() == 2 && $mulai !== $existing->getTanggalMulai()) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Kontrak sedang berjalan! Anda tidak boleh mengubah tanggal mulai.';
            header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
            exit;
        }

        // 2. Jika tanggal diubah mundur sebelum tanggal awal kontrak asli
        if (strtotime($mulai) < strtotime($existing->getTanggalMulai())) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Tanggal mulai tidak boleh lebih awal dari tanggal kontrak semula!';
            header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
            exit;
        }

        $id_kamar = $_POST['id_kamar'];
        $selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : null;
        $tipe_sewa = $_POST['tipe_sewa'];

        if ($tipe_sewa === 'Bulanan') {
            $day = date('d', strtotime($mulai));
            if ($day !== '01') {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['error'] = "Untuk tipe sewa Bulanan, tanggal mulai harus awal bulan (tanggal 1)!";
                header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
                exit;
            }
        }

        $link = PDOUtil::createConnection();

        // 1. Cek status kamar baru (tidak boleh dalam Perbaikan)
        $queryKamar = "SELECT status_kamar FROM kamar WHERE id_kamar = :id_kamar";
        $stmtKamar = $link->prepare($queryKamar);
        $stmtKamar->execute([':id_kamar' => $id_kamar]);
        $kamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);

        if ($kamar && $kamar['status_kamar'] == 'Perbaikan') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar sedang dalam perbaikan dan tidak dapat disewa!";
            header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
            exit;
        }

        // 2. Cek bentrok tanggal kontrak (kecuali kontrak ini sendiri)
        $queryCek = "SELECT COUNT(*) as total FROM kontrak_sewa 
                 WHERE id_kamar = :id_kamar 
                 AND id_kontrak != :id
                 AND status_aktif IN (1, 2) 
                 AND (
                    (tanggal_selesai IS NULL OR tanggal_selesai >= :mulai)
                    AND (:selesai_param IS NULL OR tanggal_mulai <= :selesai)
                 )";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->bindValue(':id_kamar', $id_kamar);
        $stmtCek->bindValue(':id', $id);
        $stmtCek->bindValue(':mulai', $mulai);
        $stmtCek->bindValue(':selesai', $selesai, $selesai === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmtCek->bindValue(':selesai_param', $selesai, $selesai === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmtCek->execute();

        $row = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar ini sudah terisi atau dibooking pada rentang tanggal tersebut!";
            header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
            exit;
        }

        // Tentukan status kontrak secara otomatis berdasarkan tanggal sewa
        $today = date('Y-m-d');
        if ($selesai !== null && $today > $selesai) {
            $statusOtomatis = 0; // Selesai
        } elseif ($today >= $mulai && ($selesai === null || $today <= $selesai)) {
            $statusOtomatis = 2; // Aktif
        } else {
            $statusOtomatis = 1; // Valid (Menunggu)
        }

        $kontrak = new Kontrak(
            $id,
            $_POST['id_pengguna'],
            $id_kamar,
            $mulai,
            $selesai,
            $_POST['tipe_sewa'],
            $statusOtomatis,
            null
        );

        $dao = new KontrakDao();
        $dao->updateKontrak($kontrak);
        $dao->syncKontrakStatus();

        header('Location: /SobatKost/index.php?url=kontrak');
        exit;
    }

    /**
     * Menghapus data rekam jejak kontrak dari sistem.
     */
    public function delete($id) {
        $dao = new KontrakDao();
        $kontrak = $dao->getKontrakById($id);

        // 1. Cek apakah data kontrak eksis di database
        if (!$kontrak) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Data kontrak tidak ditemukan';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        // 2. Proteksi: Hanya status 1 (Menunggu) yang boleh dihapus.
        if ($kontrak->getStatusAktif() != 1) {
            if (session_status() === PHP_SESSION_NONE) session_start();

            if ($kontrak->getStatusAktif() == 2) {
                $_SESSION['error'] = 'Kontrak yang sedang aktif tidak dapat dihapus!';
            } else {
                $_SESSION['error'] = 'Kontrak yang sudah selesai tidak dapat dihapus!';
            }

            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        // Jika statusnya bernilai 1 (Menunggu), proses hapus sukses dijalankan
        $dao->deleteKontrak($id);

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['success'] = 'Kontrak booking berhasil dihapus.';

        header('Location: /SobatKost/index.php?url=kontrak');
        exit;
    }

    /**
     * Menghentikan status aktif kontrak sewa sebelum batas waktunya secara paksa.
     */
    public function terminate($id) {
        require_once APP_PATH . '/dao/KontrakDao.php';
        $dao = new KontrakDao();
        $kontrak = $dao->getKontrakById($id);

        if (!$kontrak) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Data kontrak tidak ditemukan';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        if ($kontrak->getStatusAktif() != 2) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Hanya kontrak aktif yang dapat diakhiri!';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }

        // Retrieve termination date from POST
        $tanggalAkhir = $_POST['tanggal_selesai'] ?? null;
        if (!$tanggalAkhir) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Tanggal akhir kontrak tidak diberikan.';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }
        // Server-side validation
        $startDate = $kontrak->getTanggalMulai();
        $today = date('Y-m-d');
        if ($tanggalAkhir < $startDate) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Tanggal akhir tidak boleh kurang dari tanggal mulai.';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }
        if ($tanggalAkhir < $today) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Tanggal akhir tidak boleh kurang dari tanggal hari ini.';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }
        // Ensure tanggal akhir is last day of its month
        $lastDayOfMonth = date('Y-m-t', strtotime($tanggalAkhir));
        if ($tanggalAkhir !== $lastDayOfMonth) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = 'Tanggal akhir harus berada pada hari terakhir bulan yang dipilih.';
            header('Location: /SobatKost/index.php?url=kontrak');
            exit;
        }
        // Update contract termination with provided date
        $link = PDOUtil::createConnection();
        $query = "UPDATE kontrak_sewa \n SET tanggal_selesai = :tanggal_selesai \n WHERE id_kontrak = :id";
        $stmt = $link->prepare($query);
        $stmt->bindValue(':tanggal_selesai', $tanggalAkhir);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $dao->syncKontrakStatus();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['success'] = 'Tanggal akhir kontrak berhasil disimpan.';
        header('Location: /SobatKost/index.php?url=kontrak');
        exit;
    }
}