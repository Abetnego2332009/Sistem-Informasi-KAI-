<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Pengumuman - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul      = trim($_POST['judul']);
    $isi        = trim($_POST['isi']);
    $tipe       = $_POST['tipe'];
    $prioritas  = $_POST['prioritas'];
    $tgl_mulai  = $_POST['tgl_mulai'];
    $tgl_selesai = !empty($_POST['tgl_selesai']) ? "'" . mysqli_real_escape_string($koneksi, $_POST['tgl_selesai']) . "'" : "NULL";
    $is_active  = isset($_POST['is_active']) ? 1 : 0;
    $admin_id   = $_SESSION['admin_id'] ?? 1;

    $judul     = mysqli_real_escape_string($koneksi, $judul);
    $isi       = mysqli_real_escape_string($koneksi, $isi);
    $tgl_mulai = mysqli_real_escape_string($koneksi, $tgl_mulai);

    $sql = "INSERT INTO pengumuman (judul, isi, tipe, prioritas, tgl_mulai, tgl_selesai, is_active, admin_id)
            VALUES ('$judul', '$isi', '$tipe', '$prioritas', '$tgl_mulai', $tgl_selesai, $is_active, $admin_id)";

   if (mysqli_affected_rows($koneksi) >= 0) {
        echo "<script>
                alert('Data berhasil diupdate!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diupdate!');
                window.location.href = 'edit.php?id=$id';
              </script>";
    }

}

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Tambah Pengumuman</h3>
                    <h6 class="op-7 mb-2">Buat pengumuman baru untuk layanan Stasiun Perlanaan</h6>
                </div>
            </div>

            <!-- ALERT ERROR -->
            <?php if (!empty($error)): ?>
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
                            <h4 class="card-title">Form Tambah Pengumuman</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">

                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="judul" class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" id="judul" class="form-control"
                                        placeholder="Masukkan judul pengumuman..."
                                        value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                                </div>

                                <!-- Isi -->
                                <div class="mb-3">
                                    <label for="isi" class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                                    <textarea name="isi" id="isi" class="form-control" rows="5"
                                        placeholder="Tulis isi pengumuman di sini..." required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                                </div>

                                <!-- Tipe & Prioritas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipe" class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                                        <select name="tipe" id="tipe" class="form-select" required>
                                            <option value="">-- Pilih Tipe --</option>
                                            <?php
                                            $tipe_list = ['Umum', 'Gangguan', 'Keterlambatan', 'Darurat'];
                                            foreach ($tipe_list as $t):
                                                $sel = (($_POST['tipe'] ?? '') === $t) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $t ?>" <?= $sel ?>><?= $t ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="prioritas" class="form-label fw-semibold">Prioritas <span class="text-danger">*</span></label>
                                        <select name="prioritas" id="prioritas" class="form-select" required>
                                            <option value="">-- Pilih Prioritas --</option>
                                            <?php
                                            $prioritas_list = ['Rendah', 'Sedang', 'Tinggi'];
                                            foreach ($prioritas_list as $p):
                                                $sel = (($_POST['prioritas'] ?? '') === $p) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $p ?>" <?= $sel ?>><?= $p ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_mulai" class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                                            value="<?= htmlspecialchars($_POST['tgl_mulai'] ?? date('Y-m-d')) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                                        <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                            value="<?= htmlspecialchars($_POST['tgl_selesai'] ?? '') ?>">
                                        <div class="form-text">Kosongkan jika tidak ada batas waktu.</div>
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            <?= (!isset($_POST['is_active']) || $_POST['is_active']) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="is_active">Aktifkan Pengumuman</label>
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
                            <h4 class="card-title">Keterangan Tipe</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><span class="badge bg-info text-dark me-2">Umum</span> Informasi umum layanan</li>
                                <li class="mb-2"><span class="badge bg-orange text-dark me-2">Gangguan</span> Gangguan operasional</li>
                                <li class="mb-2"><span class="badge bg-warning text-dark me-2">Keterlambatan</span> Keterlambatan kereta</li>
                                <li class="mb-2"><span class="badge bg-danger me-2">Darurat</span> Kondisi darurat mendesak</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-round mt-3">
                        <div class="card-header">
                            <h4 class="card-title">Keterangan Prioritas</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><span class="badge bg-danger me-2">Tinggi</span> Tampil paling atas</li>
                                <li class="mb-2"><span class="badge bg-warning text-dark me-2">Sedang</span> Tampil normal</li>
                                <li class="mb-2"><span class="badge bg-secondary me-2">Rendah</span> Tampil paling bawah</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>

</div>
