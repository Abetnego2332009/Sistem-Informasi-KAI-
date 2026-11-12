<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Harga Tiket - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;

// Ambil data harga tiket berdasarkan ID
$query = mysqli_query($koneksi, "
    SELECT * FROM harga_tiket WHERE id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan!");
}

// Ambil data stasiun
$query_stasiun = mysqli_query($koneksi, "
    SELECT id, kode, nama 
    FROM stasiun_rute 
    WHERE is_active = 1
");

// Ambil data kelas tiket
$query_kelas = mysqli_query($koneksi, "
    SELECT id, nama_kelas 
    FROM kelas_tiket
");
?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Edit Harga Tiket</h3>
                    <h6 class="op-7 mb-2">Informasi Harga Tiket Kereta Api</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Harga Tiket</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses.php">

                                <!-- Hidden: id jadwal (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                                <div class="row">

                                    
                                    <!-- Kelas -->
                                    <div class="col-md-6 mt-2">
                                        <label>Kelas Tiket</label>
                                        <select name="kelas_id" class="form-control" required>
                                            <?php while ($k = mysqli_fetch_array($query_kelas)): ?>
                                                <option value="<?= $k['id'] ?>"
                                                <?= ($k['id'] == $data['kelas_id']) ? 'selected' : '' ?>>
                                                <?= $k['nama_kelas'] ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Stasiun Asal -->
                                    <div class="col-md-6">
                                        <label>Stasiun Asal</label>
                                        <select name="stasiun_asal_id" class="form-control" required>
                                            <?php while ($s = mysqli_fetch_array($query_stasiun)): ?>
                                                <option value="<?= $s['id'] ?>"
                                                    <?= ($s['id'] == $data['stasiun_asal_id']) ? 'selected' : '' ?>>
                                                    <?= $s['kode'] ?> - <?= $s['nama'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <!-- Stasiun Tujuan -->
                                    <div class="col-md-6">
                                        <label>Stasiun Tujuan</label>
                                        <select name="stasiun_tujuan_id" class="form-control" required>
                                            <?php
                                            mysqli_data_seek($query_stasiun, 0); // reset
                                            while ($s = mysqli_fetch_array($query_stasiun)): ?>
                                                <option value="<?= $s['id'] ?>"
                                                    <?= ($s['id'] == $data['stasiun_tujuan_id']) ? 'selected' : '' ?>>
                                                    <?= $s['kode'] ?> - <?= $s['nama'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <!-- Harga -->
                                    <div class="col-md-6 mt-2">
                                        <label>Harga</label>
                                        <input type="number" name="harga" class="form-control"
                                            value="<?= $data['harga'] ?>" required>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mt-2">
                                        <label>Status</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" <?= $data['is_active'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= $data['is_active'] == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                                        </select>
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