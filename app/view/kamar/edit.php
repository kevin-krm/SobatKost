<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Kamar
                </h4>

                <a href="/SobatKost/index.php?url=kamar" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Edit Kamar</strong>
                </div>

                <div class="card-body">

                    <form
                            method="POST"
                            action="/SobatKost/index.php?url=kamar/update&id=<?= $kamar['id_kamar']; ?>"
                    >

                        <div class="mb-3">
                            <label class="form-label">ID Kamar</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($kamar['id_kamar']); ?>"
                                    readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Kamar</label>
                            <input
                                    type="text"
                                    name="nomor_kamar"
                                    class="form-control"
                                    value="<?= htmlspecialchars($kamar['nomor_kamar']); ?>"
                                    required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Kamar</label>
                            <select
                                    name="tipe_kamar"
                                    class="form-select"
                                    required
                            >
                                <option
                                        value="AC"
                                        <?= ($kamar['tipe_kamar'] == 'AC') ? 'selected' : ''; ?>
                                >
                                    AC
                                </option>

                                <option
                                        value="Standard"
                                        <?= ($kamar['tipe_kamar'] == 'Standard') ? 'selected' : ''; ?>
                                >
                                    Standard
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select
                                    name="status_kamar"
                                    class="form-select"
                                    required
                            >
                                <option
                                        value="Tersedia"
                                        <?= ($kamar['status_kamar'] == 'Tersedia') ? 'selected' : ''; ?>
                                >
                                    Tersedia
                                </option>

                                <option
                                        value="Terisi"
                                        <?= ($kamar['status_kamar'] == 'Terisi') ? 'selected' : ''; ?>
                                >
                                    Terisi
                                </option>

                                <option
                                        value="Perbaikan"
                                        <?= ($kamar['status_kamar'] == 'Perbaikan') ? 'selected' : ''; ?>
                                >
                                    Perbaikan
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Dasar</label>
                            <input
                                    type="number"
                                    name="harga_dasar"
                                    class="form-control"
                                    value="<?= htmlspecialchars($kamar['harga_dasar']); ?>"
                                    required
                            >
                        </div>

                        <div class="d-grid gap-2">
                            <button
                                    type="submit"
                                    class="btn btn-primary"
                            >
                                <i class="bi bi-save"></i>
                                Update Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>