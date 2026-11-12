<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah FAQ - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Tambah FAQ</h3>
                    <h6 class="op-7 mb-2">Tambahkan Pertanyaan yang Sering Diajukan</h6>
                </div>
            </div>

            <!-- ALERT PESAN -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- FORM TAMBAH -->
            <div class="row">
                <div class="col-md-10">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Form Tambah FAQ</h4>
                        </div>
                        <div class="card-body">
                            <form action="proses_faq.php" method="POST">
                                <input type="hidden" name="aksi" value="tambah">

                                <!-- Pertanyaan -->
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label fw-semibold">
                                        Pertanyaan <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="pertanyaan"
                                        name="pertanyaan"
                                        rows="3"
                                        placeholder="Masukkan pertanyaan..."
                                        required><?= isset($_SESSION['old']['pertanyaan']) ? htmlspecialchars($_SESSION['old']['pertanyaan']) : '' ?></textarea>
                                    <?php unset($_SESSION['old']['pertanyaan']); ?>
                                </div>

                                <!-- Jawaban -->
                                <div class="mb-3">
                                    <label for="jawaban" class="form-label fw-semibold">
                                        Jawaban <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="jawaban"
                                        name="jawaban"
                                        rows="5"
                                        placeholder="Masukkan jawaban lengkap..."
                                        required><?= isset($_SESSION['old']['jawaban']) ? htmlspecialchars($_SESSION['old']['jawaban']) : '' ?></textarea>
                                    <?php unset($_SESSION['old']['jawaban']); ?>
                                </div>

                                <div class="row">
                                    <!-- Kategori -->
                                    <div class="col-md-4 mb-3">
                                        <label for="kategori" class="form-label fw-semibold">Kategori</label>
                                        <select class="form-select" id="kategori" name="kategori">
                                            <?php
                                            $kategoriList = ['Umum', 'Tiket', 'Bagasi', 'Jadwal', 'Fasilitas', 'Kontak'];
                                            $oldKategori  = isset($_SESSION['old']['kategori']) ? $_SESSION['old']['kategori'] : 'Umum';
                                            foreach ($kategoriList as $kat) {
                                                $selected = ($oldKategori == $kat) ? 'selected' : '';
                                                echo "<option value=\"$kat\" $selected>$kat</option>";
                                            }
                                            unset($_SESSION['old']['kategori']);
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Urutan -->
                                    <div class="col-md-4 mb-3">
                                        <label for="urutan" class="form-label fw-semibold">Urutan Tampil</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="urutan"
                                            name="urutan"
                                            min="0"
                                            max="127"
                                            value="<?= isset($_SESSION['old']['urutan']) ? (int)$_SESSION['old']['urutan'] : 0 ?>"
                                            placeholder="0">
                                        <?php unset($_SESSION['old']['urutan']); ?>
                                        <div class="form-text">Angka lebih kecil = tampil lebih atas.</div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="is_active" class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1" <?= (!isset($_SESSION['old']['is_active']) || $_SESSION['old']['is_active'] == 1) ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= (isset($_SESSION['old']['is_active']) && $_SESSION['old']['is_active'] == 0) ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                        <?php unset($_SESSION['old']['is_active']); ?>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan FAQ
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>
</div>
