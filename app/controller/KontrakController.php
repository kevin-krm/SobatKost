<?php
require_once APP_PATH . '/dao/KontrakDao.php';
require_once APP_PATH . '/model/Kontrak.php';

class KontrakController {
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

    public function store() {
        $id_kamar = $_POST['id_kamar'];
        $mulai = $_POST['tanggal_mulai'];
        $selesai = $_POST['tanggal_selesai'];

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
                    tanggal_mulai <= :selesai
                    AND tanggal_selesai >= :mulai
                 )";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->execute([
            ':id_kamar' => $id_kamar,
            ':mulai'    => $mulai,
            ':selesai'  => $selesai
        ]);

        $row = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar ini sudah terisi atau dibooking pada rentang tanggal tersebut!";
            header('Location: /SobatKost/index.php?url=kontrak/create');
            exit;
        }

        $today = date('Y-m-d');

        if ($today > $selesai) {
            $statusOtomatis = 0;
        } elseif ($today >= $mulai && $today <= $selesai) {
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
        $selesai = $_POST['tanggal_selesai'];

        $link = PDOUtil::createConnection();

        // ... (Sisa kode di bawahnya milik method update() biarkan tetap seperti semula)

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
                    tanggal_mulai <= :selesai
                    AND tanggal_selesai >= :mulai
                 )";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->execute([
            ':id_kamar' => $id_kamar,
            ':id'       => $id,
            ':mulai'    => $mulai,
            ':selesai'  => $selesai
        ]);

        $row = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Kamar ini sudah terisi atau dibooking pada rentang tanggal tersebut!";
            header('Location: /SobatKost/index.php?url=kontrak/edit&id=' . $id);
            exit;
        }

        // Tentukan status kontrak secara otomatis berdasarkan tanggal sewa
        $today = date('Y-m-d');
        if ($today > $selesai) {
            $statusOtomatis = 0; // Selesai
        } elseif ($today >= $mulai && $today <= $selesai) {
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
}