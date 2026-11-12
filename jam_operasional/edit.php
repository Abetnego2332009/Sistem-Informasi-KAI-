<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Jam Operasional - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil id dari URL, pastikan bertipe integer
$id = (int)$_GET['id'];

// Ambil data jam operasional yang akan diedit berdasarkan id
$query = mysqli_query($koneksi, "
    SELECT id, layanan, jam_buka, jam_tutup, keterangan, is_active, urutan, admin_id
    FROM jam_operasional
    WHERE id = $id
");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan, kembali ke index
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Jam Operasional</h3>
                    <h6 class="op-7 mb-2">Kelola Jam Operasional Layanan Stasiun Perlanaan</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Jam Operasional</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses.php">

                                <!-- Hidden: id jam_operasional (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                                <div class="row">

                                    <!-- NAMA LAYANAN -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Layanan <span class="text-danger">*</span></label>
                                            <input type="text" name="layanan" class="form-control"
                                                value="<?= htmlspecialchars($data['layanan']) ?>" required>
                                        </div>
                                    </div>

                                    <!-- URUTAN -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Urutan Tampil <span class="text-danger">*</span></label>
                                            <input type="number" name="urutan" class="form-control"
                                                min="0" value="<?= $data['urutan'] ?>" required>
                                            <small class="text-muted">Angka kecil tampil lebih dulu</small>
                                        </div>
                                    </div>

                                    <!-- JAM BUKA -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jam Buka <span class="text-danger">*</span></label>
                                            <!-- substr dipakai untuk format HH:MM agar cocok dengan input type="time" -->
                                            <input type="time" name="jam_buka" class="form-control"
                                                value="<?= substr($data['jam_buka'], 0, 5) ?>" required>
                                        </div>
                                    </div>

                                    <!-- JAM TUTUP -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jam Tutup <span class="text-danger">*</span></label>
                                            <input type="time" name="jam_tutup" class="form-control"
                                                value="<?= substr($data['jam_tutup'], 0, 5) ?>" required>
                                        </div>
                                    </div>

                                    <!-- KETERANGAN -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <input type="text" name="keterangan" class="form-control"
                                                value="<?= htmlspecialchars($data['keterangan'] ?? '') ?>"
                                                placeholder="Contoh: Termasuk hari libur nasional">
                                        </div>
                                    </div>

                                    <!-- STATUS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="is_active" class="form-control" required>
                                                <option value="1" <?= $data['is_active'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                                <option value="0" <?= $data['is_active'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Data
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php'; ?>
</div>
