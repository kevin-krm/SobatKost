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
        $penggunaList = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil sebagai array biasa

        $contentView = APP_PATH . '/view/kontrak/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        $id_kamar = $_POST['id_kamar'];
        $mulai = $_POST['tanggal_mulai'];
        $selesai = $_POST['tanggal_selesai'];

        $link = PDOUtil::createConnection();
        $queryCek = "SELECT COUNT(*) as total FROM kontrak_sewa 
                 WHERE id_kamar = :id_kamar 
                 AND status_aktif != 0 
                 AND (
                    (:mulai BETWEEN tanggal_mulai AND tanggal_selesai) OR 
                    (:selesai BETWEEN tanggal_mulai AND tanggal_selesai) OR 
                    (tanggal_mulai BETWEEN :mulai1 AND :selesai1)
                 )";

        $stmtCek = $link->prepare($queryCek);
        $stmtCek->execute([
            ':id_kamar' => $id_kamar,
            ':mulai'    => $mulai,
            ':selesai'  => $selesai,
            ':mulai1'   => $mulai,
            ':selesai1' => $selesai
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
        $dao = new KontrakDao();
        $kontrak = $dao->getKontrakById($id);

        if (!$kontrak) {
            echo 'Data kontrak tidak ditemukan';
            exit;
        }

        $contentView = APP_PATH . '/view/kontrak/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        $kontrak = new Kontrak(
            $id,
            $_POST['id_pengguna'],
            $_POST['id_kamar'],
            $_POST['tanggal_mulai'],
            $_POST['tanggal_selesai'],
            $_POST['tipe_sewa'],
            $_POST['status_aktif'],
            null
        );

        $dao = new KontrakDao();
        $dao->updateKontrak($kontrak);

        header('Location: /SobatKost/kontrak');
        exit;
    }

    public function delete($id) {
        $dao = new KontrakDao();
        $dao->deleteKontrak($id);

        header('Location: /SobatKost/kontrak');
        exit;
    }
}
