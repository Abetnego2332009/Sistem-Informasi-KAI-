<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin


$title = 'Edit FAQ - Admin';

// ── Ambil ID & validasi ──────────────────────────────────────────────────────
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// ── Ambil data FAQ dari DB ───────────────────────────────────────────────────
$sql    = "SELECT * FROM faq WHERE id = $id LIMIT 1";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['error'] = 'Data FAQ tidak ditemukan.';
    header('Location: index.php');
    exit;
}

$faq = mysqli_fetch_assoc($result);

// Jika ada old input dari redirect (gagal validasi), prioritaskan old input
$pertanyaan = isset($_SESSION['old']['pertanyaan']) ? $_SESSION['old']['pertanyaan'] : $faq['pertanyaan'];
$jawaban    = isset($_SESSION['old']['jawaban'])    ? $_SESSION['old']['jawaban']    : $faq['jawaban'];
$kategori   = isset($_SESSION['old']['kategori'])   ? $_SESSION['old']['kategori']   : $faq['kategori'];
$urutan     = isset($_SESSION['old']['urutan'])     ? $_SESSION['old']['urutan']     : $faq['urutan'];
$is_active  = isset($_SESSION['old']['is_active'])  ? $_SESSION['old']['is_active']  : $faq['is_active'];
unset($_SESSION['old']);

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
                    <h3 class="fw-bold mb-3">Edit FAQ</h3>
                    <h6 class="op-7 mb-2">Ubah Pertanyaan yang Sering Diajukan</h6>
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

            <!-- FORM EDIT -->
            <div class="row">
                <div class="col-md-10">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Form Edit FAQ <span class="text-muted fs-6">#<?= $id ?></span></h4>
                        </div>
                        <div class="card-body">
                            <form action="proses_faq.php" method="POST">
                                <input type="hidden" name="aksi" value="edit">
                                <input type="hidden" name="id"   value="<?= $id ?>">

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
                                        required><?= htmlspecialchars($pertanyaan) ?></textarea>
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
                                        required><?= htmlspecialchars($jawaban) ?></textarea>
                                </div>

                                <div class="row">
                                    <!-- Kategori -->
                                    <div class="col-md-4 mb-3">
                                        <label for="kategori" class="form-label fw-semibold">Kategori</label>
                                        <select class="form-select" id="kategori" name="kategori">
                                            <?php
                                            $kategoriList = ['Umum', 'Tiket', 'Bagasi', 'Jadwal', 'Fasilitas', 'Kontak'];
                                            foreach ($kategoriList as $kat) {
                                                $selected = ($kategori == $kat) ? 'selected' : '';
                                                echo "<option value=\"$kat\" $selected>$kat</option>";
                                            }
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
                                            value="<?= (int)$urutan ?>">
                                        <div class="form-text">Angka lebih kecil = tampil lebih atas.</div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="is_active" class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1" <?= ($is_active == 1) ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= ($is_active == 0) ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i> Update FAQ
                                    </button>
                                    <a href="<?= $base_url ?>/faq" class="btn btn-secondary">
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
