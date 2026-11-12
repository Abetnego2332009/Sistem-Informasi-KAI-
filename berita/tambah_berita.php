<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Berita - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Pesan error dari proses
$error_map = [
    'format_foto' => 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.',
    'db'          => 'Gagal menyimpan data ke database. Silakan coba lagi.',
];
$error = isset($_GET['error']) ? ($error_map[$_GET['error']] ?? 'Terjadi kesalahan.') : null;

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Tambah Berita</h3>
                    <h6 class="op-7 mb-2">Buat berita, promo, atau informasi baru untuk Stasiun Perlanaan</h6>
                </div>
            </div>

            <!-- ALERT ERROR -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Form Tambah Berita</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="proses_berita.php?aksi=tambah" enctype="multipart/form-data">

                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="judul" class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" id="judul" class="form-control"
                                        placeholder="Masukkan judul berita..." required>
                                    <div class="form-text">Slug URL akan dibuat otomatis dari judul.</div>
                                </div>

                                <!-- Ringkasan -->
                                <div class="mb-3">
                                    <label for="ringkasan" class="form-label fw-semibold">Ringkasan</label>
                                    <textarea name="ringkasan" id="ringkasan" class="form-control" rows="2"
                                        placeholder="Ringkasan singkat berita (opsional)..."></textarea>
                                </div>

                                <!-- Isi -->
                                <div class="mb-3">
                                    <label for="isi" class="form-label fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                                    <textarea name="isi" id="isi" class="form-control" rows="8"
                                        placeholder="Tulis isi lengkap berita di sini..." required></textarea>
                                </div>

                                <!-- Kategori & Tanggal Tayang -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                        <select name="kategori" id="kategori" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Berita">Berita</option>
                                            <option value="Promo">Promo</option>
                                            <option value="Informasi">Informasi</option>
                                            <option value="Kegiatan">Kegiatan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_tayang" class="form-label fw-semibold">Tanggal Tayang <span class="text-danger">*</span></label>
                                        <input type="date" name="tgl_tayang" id="tgl_tayang" class="form-control"
                                            value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>

                                <!-- Upload Foto -->
                                <div class="mb-3">
                                    <label for="foto" class="form-label fw-semibold">Foto Berita</label>
                                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*"
                                        onchange="previewFoto(this)">
                                    <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB. (Opsional)</div>
                                    <div id="preview-container" class="mt-2" style="display:none;">
                                        <img id="foto-preview" src="#" alt="Preview"
                                            class="rounded border" style="max-height:150px; object-fit:cover;">
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        <label class="form-check-label fw-semibold" for="is_active">Aktifkan / Tayangkan Berita</label>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- INFO SAMPING -->
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Keterangan Kategori</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><span class="badge bg-primary me-2">Berita</span> Berita umum seputar KAI & stasiun</li>
                                <li class="mb-2"><span class="badge bg-success me-2">Promo</span> Promo & diskon tiket</li>
                                <li class="mb-2"><span class="badge bg-info text-dark me-2">Informasi</span> Informasi layanan & fasilitas</li>
                                <li class="mb-2"><span class="badge bg-warning text-dark me-2">Kegiatan</span> Kegiatan & event stasiun</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-round mt-3">
                        <div class="card-header">
                            <h4 class="card-title">Tips Penulisan</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 small text-muted">
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Judul singkat & informatif</li>
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Ringkasan maks 1–2 kalimat</li>
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Isi bisa menggunakan tag HTML</li>
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Foto landscape lebih optimal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>

</div>

<script>
function previewFoto(input) {
    const container = document.getElementById('preview-container');
    const preview   = document.getElementById('foto-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}
</script>
