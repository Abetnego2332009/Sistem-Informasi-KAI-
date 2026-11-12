<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Berita - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil ID
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil data berita dari DB
$res  = mysqli_query($koneksi, "SELECT * FROM berita WHERE id = $id LIMIT 1");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    header('Location: index.php');
    exit;
}

// Pesan error dari proses
$error_map = [
    'format_foto' => 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.',
    'db'          => 'Gagal memperbarui data ke database. Silakan coba lagi.',
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
                    <h3 class="fw-bold mb-3">Edit Berita</h3>
                    <h6 class="op-7 mb-2">Perbarui data berita yang sudah ada</h6>
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
                            <h4 class="card-title">Form Edit Berita <span class="text-muted fs-6">#<?= $id ?></span></h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="proses_berita.php?aksi=edit" enctype="multipart/form-data">

                                <!-- Hidden ID -->
                                <input type="hidden" name="id" value="<?= $id ?>">

                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="judul" class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" id="judul" class="form-control"
                                        value="<?= htmlspecialchars($data['judul']) ?>" required>
                                    <div class="form-text text-muted">
                                        Slug saat ini: <code><?= htmlspecialchars($data['slug']) ?></code>
                                    </div>
                                </div>

                                <!-- Ringkasan -->
                                <div class="mb-3">
                                    <label for="ringkasan" class="form-label fw-semibold">Ringkasan</label>
                                    <textarea name="ringkasan" id="ringkasan" class="form-control" rows="2"><?= htmlspecialchars($data['ringkasan'] ?? '') ?></textarea>
                                </div>

                                <!-- Isi -->
                                <div class="mb-3">
                                    <label for="isi" class="form-label fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                                    <textarea name="isi" id="isi" class="form-control" rows="8" required><?= htmlspecialchars($data['isi']) ?></textarea>
                                </div>

                                <!-- Kategori & Tanggal -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                        <select name="kategori" id="kategori" class="form-select" required>
                                            <?php
                                            foreach (['Berita', 'Promo', 'Informasi', 'Kegiatan'] as $k):
                                                $sel = ($data['kategori'] === $k) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $k ?>" <?= $sel ?>><?= $k ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_tayang" class="form-label fw-semibold">Tanggal Tayang <span class="text-danger">*</span></label>
                                        <input type="date" name="tgl_tayang" id="tgl_tayang" class="form-control"
                                            value="<?= htmlspecialchars($data['tgl_tayang']) ?>" required>
                                    </div>
                                </div>

                                <!-- Foto -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Foto Berita</label>

                                    <?php if (!empty($data['foto'])): ?>
                                        <div class="mb-2">
                                            <img src="../uploads/berita/<?= htmlspecialchars($data['foto']) ?>"
                                                alt="Foto saat ini" class="rounded border"
                                                style="max-height:150px; object-fit:cover; display:block;">
                                            <div class="mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto">
                                                    <label class="form-check-label text-danger small" for="hapus_foto">
                                                        Hapus foto ini
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*"
                                        onchange="previewFoto(this)">
                                    <div class="form-text">Unggah foto baru untuk mengganti. Format: JPG, PNG, WEBP.</div>

                                    <div id="preview-container" class="mt-2" style="display:none;">
                                        <img id="foto-preview" src="#" alt="Preview"
                                            class="rounded border" style="max-height:150px; object-fit:cover;">
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            <?= $data['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="is_active">Aktifkan / Tayangkan Berita</label>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i> Update
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
                    <!-- <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Info Data</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted">ID</td>
                                    <td><strong>#<?= $data['id'] ?></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Views</td>
                                    <td><i class="fas fa-eye me-1 text-muted"></i><?= number_format($data['views']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dibuat</td>
                                    <td><?= date('d M Y H:i', strtotime($data['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Diperbarui</td>
                                    <td><?= date('d M Y H:i', strtotime($data['updated_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div> -->
                    <div class="card card-round mt-3">
                        <div class="card-header">
                            <h4 class="card-title">Keterangan Kategori</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><span class="badge bg-primary me-2">Berita</span> Berita umum seputar KAI</li>
                                <li class="mb-2"><span class="badge bg-success me-2">Promo</span> Promo & diskon tiket</li>
                                <li class="mb-2"><span class="badge bg-info text-dark me-2">Informasi</span> Informasi layanan</li>
                                <li class="mb-2"><span class="badge bg-warning text-dark me-2">Kegiatan</span> Event & kegiatan</li>
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
