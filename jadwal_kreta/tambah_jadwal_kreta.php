<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Jadwal Kereta - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil data kereta untuk dropdown
$query_kereta = mysqli_query($koneksi, "SELECT id, nomor_ka, nama_ka FROM kereta WHERE is_active = 1 ORDER BY nomor_ka ASC");

// Ambil data stasiun untuk dropdown
$query_stasiun = mysqli_query($koneksi, "SELECT id, kode, nama FROM stasiun_rute WHERE is_active = 1 ORDER BY nama ASC");
$stasiun_list = [];
while ($s = mysqli_fetch_array($query_stasiun)) {
    $stasiun_list[] = $s;
}

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Jadwal Kereta</h3>
                    <h6 class="op-7 mb-2">Informasi Jadwal Keberangkatan Kereta Api</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Jadwal Kereta</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_jadwal_kreta.php">

                                <div class="row">

                                    <!-- Kereta -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kereta</label>
                                            <select name="kereta_id" class="form-control" required>
                                                <option value="">-- Pilih Kereta --</option>
                                                <?php while ($k = mysqli_fetch_array($query_kereta)): ?>
                                                    <option value="<?= $k['id'] ?>">
                                                        KA <?= htmlspecialchars($k['nomor_ka']) ?> - <?= htmlspecialchars($k['nama_ka']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Tipe -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipe</label>
                                            <select name="tipe" class="form-control" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="keberangkatan">Keberangkatan</option>
                                                <option value="kedatangan">Kedatangan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Stasiun Asal -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stasiun Asal</label>
                                            <select name="stasiun_asal_id" class="form-control" required>
                                                <option value="">-- Pilih Stasiun Asal --</option>
                                                <?php foreach ($stasiun_list as $s): ?>
                                                    <option value="<?= $s['id'] ?>">
                                                        <?= htmlspecialchars($s['kode']) ?> - <?= htmlspecialchars($s['nama']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Stasiun Tujuan -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stasiun Tujuan</label>
                                            <select name="stasiun_tujuan_id" class="form-control" required>
                                                <option value="">-- Pilih Stasiun Tujuan --</option>
                                                <?php foreach ($stasiun_list as $s): ?>
                                                    <option value="<?= $s['id'] ?>">
                                                        <?= htmlspecialchars($s['kode']) ?> - <?= htmlspecialchars($s['nama']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Via -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Via</label>
                                            <input type="text" name="via" class="form-control"
                                                placeholder="Contoh: Tebingtinggi, Kisaran">
                                        </div>
                                    </div>

                                    <!-- Jam -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jam</label>
                                            <input type="time" name="jam" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Hari Operasi -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Hari Operasi</label>
                                            <select name="hari_operasi" class="form-control" required>
                                                <option value="">-- Pilih Hari Operasi --</option>
                                                <option value="Setiap Hari">Setiap Hari</option>
                                                <option value="Senin-Jumat">Senin - Jumat</option>
                                                <option value="Sabtu-Minggu">Sabtu - Minggu</option>
                                                <option value="Senin-Sabtu">Senin - Sabtu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="Normal" selected>Normal</option>
                                                <option value="Terlambat">Terlambat</option>
                                                <option value="Dibatalkan">Dibatalkan</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        Simpan Data
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        Kembali
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