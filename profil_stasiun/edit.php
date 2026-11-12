<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Profil Stasiun - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

$id = (int)$_GET['id'];

// Ambil data profil stasiun
$query   = mysqli_query($koneksi, "SELECT * FROM info_stasiun WHERE id = $id");
$stasiun = mysqli_fetch_assoc($query);

if (!$stasiun) {
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
                    <h3 class="fw-bold mb-3">Profil Stasiun</h3>
                    <h6 class="op-7 mb-2">Edit Informasi & Detail Data Stasiun</h6>
                </div>
            </div>

            <form method="POST" action="proses_profil_stasiun.php" enctype="multipart/form-data">

                <input type="hidden" name="id"    value="<?= $stasiun['id'] ?>">
                <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($stasiun['foto'] ?? '') ?>">

                <!-- IDENTITAS STASIUN -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Identitas Stasiun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Stasiun <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_stasiun" class="form-control" required
                                        value="<?= htmlspecialchars($stasiun['nama_stasiun']) ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kode Stasiun <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_stasiun" class="form-control" required
                                        maxlength="10"
                                        value="<?= htmlspecialchars($stasiun['kode_stasiun']) ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kelas Stasiun <span class="text-danger">*</span></label>
                                    <select name="kelas_stasiun" class="form-control" required>
                                        <?php foreach (['Besar', 'Madya', 'Kecil', 'Halte'] as $kls): ?>
                                            <option value="<?= $kls ?>"
                                                <?= $stasiun['kelas_stasiun'] == $kls ? 'selected' : '' ?>>
                                                <?= $kls ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Divisi Regional</label>
                                    <input type="text" name="divre" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['divre'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Jalur KA</label>
                                    <input type="number" name="jumlah_jalur" class="form-control" min="1"
                                        value="<?= $stasiun['jumlah_jalur'] ?? 2 ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ketinggian (mdpl)</label>
                                    <input type="number" name="ketinggian_mdpl" class="form-control"
                                        value="<?= $stasiun['ketinggian_mdpl'] ?? '' ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ALAMAT -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Alamat Stasiun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($stasiun['alamat']) ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Desa / Kelurahan</label>
                                    <input type="text" name="desa" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['desa'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <input type="text" name="kecamatan" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['kecamatan'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kabupaten / Kota</label>
                                    <input type="text" name="kabupaten" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['kabupaten'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <input type="text" name="provinsi" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['provinsi'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kode Pos</label>
                                    <input type="text" name="kode_pos" class="form-control" maxlength="10"
                                        value="<?= htmlspecialchars($stasiun['kode_pos'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control"
                                        placeholder="Contoh: 3.3700000"
                                        value="<?= $stasiun['latitude'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control"
                                        placeholder="Contoh: 99.0300000"
                                        value="<?= $stasiun['longitude'] ?? '' ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- KONTAK -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-phone-alt me-2 text-success"></i>Informasi Kontak</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    <input type="text" name="telepon" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['telepon'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?= htmlspecialchars($stasiun['email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" class="form-control"
                                        placeholder="https://"
                                        value="<?= htmlspecialchars($stasiun['website'] ?? '') ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- DESKRIPSI & SEJARAH -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-align-left me-2 text-info"></i>Deskripsi & Sejarah</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Deskripsi Stasiun</label>
                                    <textarea name="deskripsi" class="form-control" rows="5"
                                        placeholder="Deskripsi singkat mengenai stasiun..."><?= htmlspecialchars($stasiun['deskripsi'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sejarah Stasiun</label>
                                    <textarea name="sejarah" class="form-control" rows="5"
                                        placeholder="Sejarah singkat mengenai stasiun..."><?= htmlspecialchars($stasiun['sejarah'] ?? '') ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- FOTO -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-image me-2 text-secondary"></i>Foto Stasiun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">

                            <?php if (!empty($stasiun['foto'])): ?>
                                <div class="col-md-3 mb-3">
                                    <label class="d-block mb-1 text-muted">Foto Saat Ini</label>
                                    <img src="../uploads/stasiun/<?= htmlspecialchars($stasiun['foto']) ?>"
                                        class="img-fluid rounded border"
                                        alt="Foto Stasiun"
                                        style="max-height:150px; object-fit:cover;">
                                </div>
                            <?php endif; ?>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Upload Foto Baru</label>
                                    <input type="file" name="foto" class="form-control"
                                        accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Format: JPG, PNG, WEBP. Maks. 2MB.
                                        <?= !empty($stasiun['foto']) ? 'Kosongkan jika tidak ingin mengganti foto.' : '' ?>
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex gap-2 mb-5">
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

    <?php require '../templates/footer.php'; ?>
</div>