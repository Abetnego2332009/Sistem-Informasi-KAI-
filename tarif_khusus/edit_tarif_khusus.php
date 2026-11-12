<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

// Ambil data berdasarkan id
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id  = intval($_GET['id']);
$sql = "SELECT * FROM tarif_khusus WHERE id = $id LIMIT 1";
$res = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

$title = 'Edit Tarif Khusus - Admin';
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
                    <h3 class="fw-bold mb-3">Edit Tarif Khusus</h3>
                    <h6 class="op-7 mb-2">Ubah Data Tarif Diskon Penumpang Khusus</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Form Edit Tarif Khusus</h4>
                        </div>
                        <div class="card-body">
                            <form action="proses_tarif_khusus.php" method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                <!-- Ikon Emoji -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ikon Emoji</label>
                                    <input type="text" name="ikon_emoji" class="form-control"
                                        value="<?= htmlspecialchars($row['ikon_emoji'] ?? '') ?>"
                                        maxlength="10">
                                    <small class="text-muted">Copy-paste emoji yang sesuai kategori</small>
                                </div>

                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                    <input type="text" name="kategori" class="form-control"
                                        value="<?= htmlspecialchars($row['kategori']) ?>" required>
                                </div>

                                <!-- Persen Diskon -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Persen Diskon (%) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="persen_diskon" class="form-control"
                                            value="<?= $row['persen_diskon'] ?>"
                                            min="1" max="100" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>

                                <!-- Syarat -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Syarat</label>
                                    <textarea name="syarat" class="form-control" rows="2"><?= htmlspecialchars($row['syarat'] ?? '') ?></textarea>
                                </div>

                                <!-- Keterangan -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control"
                                        value="<?= htmlspecialchars($row['keterangan'] ?? '') ?>">
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            name="is_active" id="is_active"
                                            <?= $row['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="d-flex gap-2">
                                    <button type="submit" name="update" class="btn btn-warning">
                                        <i class="bi bi-save me-1"></i> Update
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Batal
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Emoji -->
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Referensi Emoji</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted" style="font-size:.85rem;">Copy emoji di bawah ini ke kolom Ikon Emoji:</p>
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td style="font-size:1.3rem;">👴</td><td>Lansia</td></tr>
                                    <tr><td style="font-size:1.3rem;">👶</td><td>Anak-anak</td></tr>
                                    <tr><td style="font-size:1.3rem;">♿</td><td>Disabilitas</td></tr>
                                    <tr><td style="font-size:1.3rem;">🎖️</td><td>TNI / Polri</td></tr>
                                    <tr><td style="font-size:1.3rem;">🎓</td><td>Mahasiswa</td></tr>
                                    <tr><td style="font-size:1.3rem;">🏥</td><td>Pasien / Medis</td></tr>
                                    <tr><td style="font-size:1.3rem;">👨‍👩‍👧</td><td>Keluarga</td></tr>
                                    <tr><td style="font-size:1.3rem;">🌟</td><td>Loyalitas</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>
