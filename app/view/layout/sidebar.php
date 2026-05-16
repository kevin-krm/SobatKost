<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-house-heart-fill"></i>
        SobatKost
    </div>

    <div class="list-group list-group-flush">
        <a href="/SobatKost/" class="list-group-item list-group-item-action">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="/SobatKost/kamar" class="list-group-item list-group-item-action">
            <i class="bi bi-door-open me-2"></i> Manajemen Kamar
        </a>

        <a href="/SobatKost/inventaris" class="list-group-item list-group-item-action">
            <i class="bi bi-box-seam me-2"></i> Inventaris Kamar
        </a>

        <a href="/SobatKost/pengguna" class="list-group-item list-group-item-action">
            <i class="bi bi-people me-2"></i> Data Pengguna
        </a>

        <a href="/SobatKost/kontrak" class="list-group-item list-group-item-action">
            <i class="bi bi-file-earmark-text"></i> Kontrak Sewa
        </a>

        <a href="/SobatKost/tagihan" class="list-group-item list-group-item-action">
            <i class="bi bi-receipt me-2"></i> Tagihan
        </a>

        <a href="/SobatKost/komplain" class="list-group-item list-group-item-action">
            <i class="bi bi-tools me-2"></i> Tiket Komplain
        </a>

        <a href="/SobatKost/pengumuman" class="list-group-item list-group-item-action">
            <i class="bi bi-megaphone me-2"></i> Pengumuman
        </a>

        <a href="/SobatKost/aturan" class="list-group-item list-group-item-action">
            <i class="bi bi-journal-text me-2"></i> E-Rules / Aturan Kost
        </a>

        <a href="/SobatKost/keuangan" class="list-group-item list-group-item-action">
            <i class="bi bi-cash-coin me-2"></i> Laporan Keuangan
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="index.php?url=logout" class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div id="page-content-wrapper">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <h5 class="text-white mb-0">SobatKost Admin</h5>

            <div class="ms-auto d-flex align-items-center text-white">
                <span class="me-3 d-none d-md-inline">
                    Hello, <?= $_SESSION['user']['nama'] ?? 'Pengguna'; ?>
                </span>
                <i class="bi bi-person-circle fs-4"></i>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4 content-area ">