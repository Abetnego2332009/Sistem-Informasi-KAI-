<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Jadwal Kereta - Admin ';
require '../templates/header.php';
require '../templates/sidebar.php';

$id = (int)$_GET['id'];

// Ambil data jadwal yang mau diedit
$query = mysqli_query($koneksi, "
    SELECT
        j.id,
        j.kereta_id,
        j.stasiun_asal_id,
        j.stasiun_tujuan_id,
        k.nama_ka,
        j.tipe,
        j.via,
        j.jam,
        j.hari_operasi,
        j.status
    FROM jadwal_kereta j
    JOIN kereta k       ON j.kereta_id         = k.id
    JOIN stasiun_rute a ON j.stasiun_asal_id   = a.id
    JOIN stasiun_rute t ON j.stasiun_tujuan_id = t.id
    WHERE j.id = $id
");
$data = mysqli_fetch_array($query);

// Ambil semua kereta untuk dropdown
$list_kereta = mysqli_query($koneksi, "SELECT id, nama_ka FROM kereta ORDER BY nama_ka ASC");

// Ambil semua stasiun untuk dropdown (disimpan ke array supaya bisa dipakai 2x)
$list_stasiun_q = mysqli_query($koneksi, "SELECT id, nama FROM stasiun_rute ORDER BY nama ASC");
$stasiun_arr = [];
while ($s = mysqli_fetch_assoc($list_stasiun_q)) {
    $stasiun_arr[] = $s;
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
                            <h4 class="card-title">Edit Jadwal Kereta</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_jadwal_kreta.php">

                                <!-- Hidden: id jadwal (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                                <div class="row">

                                    <!-- NAMA KERETA: dropdown dari tabel kereta, value = kereta_id -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kereta</label>
                                            <select name="kereta_id" class="form-control">
                                                <?php while ($k = mysqli_fetch_assoc($list_kereta)): ?>
                                                    <option value="<?= $k['id'] ?>"
                                                        <?= $k['id'] == $data['kereta_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($k['nama_ka']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- TIPE -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipe</label>
                                            <select name="tipe" class="form-control">
                                                <option value="keberangkatan" <?= $data['tipe'] == 'keberangkatan' ? 'selected' : '' ?>>Keberangkatan</option>
                                                <option value="kedatangan"    <?= $data['tipe'] == 'kedatangan'    ? 'selected' : '' ?>>Kedatangan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- STASIUN ASAL: dropdown dari tabel stasiun_rute, value = id -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stasiun Asal</label>
                                            <select name="stasiun_asal" class="form-control">
                                                <?php foreach ($stasiun_arr as $s): ?>
                                                    <option value="<?= $s['id'] ?>"
                                                        <?= $s['id'] == $data['stasiun_asal_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($s['nama']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- STASIUN TUJUAN: dropdown dari tabel stasiun_rute, value = id -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stasiun Tujuan</label>
                                            <select name="stasiun_tujuan" class="form-control">
                                                <?php foreach ($stasiun_arr as $s): ?>
                                                    <option value="<?= $s['id'] ?>"
                                                        <?= $s['id'] == $data['stasiun_tujuan_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($s['nama']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- VIA -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Via</label>
                                            <input type="text" name="via" class="form-control"
                                                value="<?= htmlspecialchars($data['via']); ?>">
                                        </div>
                                    </div>

                                    <!-- JAM -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jam</label>
                                            <input type="time" name="jam" class="form-control"
                                                value="<?= $data['jam']; ?>">
                                        </div>
                                    </div>

                                    <!-- HARI OPERASI -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Hari Operasi</label>
                                            <select name="hari_operasi" class="form-control">
                                                <option value="Setiap Hari"  <?= $data['hari_operasi'] == 'Setiap Hari'  ? 'selected' : '' ?>>Setiap Hari</option>
                                                <option value="Senin-Jumat"  <?= $data['hari_operasi'] == 'Senin-Jumat'  ? 'selected' : '' ?>>Senin - Jumat</option>
                                                <option value="Sabtu-Minggu" <?= $data['hari_operasi'] == 'Sabtu-Minggu' ? 'selected' : '' ?>>Sabtu - Minggu</option>
                                                <option value="Senin-Sabtu"  <?= $data['hari_operasi'] == 'Senin-Sabtu'  ? 'selected' : '' ?>>Senin - Sabtu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- STATUS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="Normal"     <?= $data['status'] == 'Normal'     ? 'selected' : '' ?>>Normal</option>
                                                <option value="Terlambat"  <?= $data['status'] == 'Terlambat'  ? 'selected' : '' ?>>Terlambat</option>
                                                <option value="Dibatalkan" <?= $data['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        Update Data
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