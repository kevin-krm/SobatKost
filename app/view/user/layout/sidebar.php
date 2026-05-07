<!-- SIDEBAR -->
<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-house-heart-fill"></i>
        SobatKost
    </div>

    <div class="list-group list-group-flush">
        <a href="/SobatKost/user" class="list-group-item list-group-item-action">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="/SobatKost/user/tagihan" class="list-group-item list-group-item-action">
            <i class="bi bi-receipt me-2"></i> Tagihan
        </a>

        <a href="/SobatKost/user/komplain" class="list-group-item list-group-item-action">
            <i class="bi bi-tools me-2"></i> Tiket Komplain
        </a>

        <a href="/SobatKost/user/pengumuman" class="list-group-item list-group-item-action">
            <i class="bi bi-megaphone me-2"></i> Pengumuman
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="/SobatKost/logout" class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- CONTENT AREA -->
<div id="page-content-wrapper">
    <!-- HEADER-->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow-sm">
        <div class="container-fluid">

            <!-- TOGGLE -->
            <button class="btn btn-light me-3" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <h5 class="text-white mb-0">
                SobatKost
            </h5>

            <div class="ms-auto d-flex align-items-center text-white">
                <!-- NOTIFIKASI -->
                <div class="dropdown me-3">
                    <button
                            class="btn  position-relative text-white"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                    >
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </button>

                    <!-- DROPDOWN -->
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 320px;">
                        <li class="dropdown-header fw-bold py-3 px-3 border-bottom">
                            Notifikasi
                        </li>

                        <li class="text-center py-5 px-3">
                            <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">
                                Belum ada notifikasi
                            </p>
                        </li>
                    </ul>
                </div>

                <!-- USER -->
                <span class="me-3 d-none d-md-inline">
                    Halo, User
                </span>
                <i class="bi bi-person-circle fs-4"></i>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4 content-area ">