<?php
include_once 'dao/PenghuniDao.php';

class PenghuniController
{
    private PenghuniDao $penghuniDao;

    public function __construct()
    {
        $this->penghuniDao = new PenghuniDao();
    }

    public function create()
    {
        include_once('view/penghuni/Create.php');
    }

    // Lanjutkan dengan CRUD yang tersisa (Berisi Logika Program)
}
