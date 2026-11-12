<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Jadwal Kereta - Admin ';
require '../templates/header.php';
require '../templates/sidebar.php';


$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');


?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">


            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Harga Tiket</h3>
                    <h6 class="op-7 mb-2">Informasi Harga Tiket Kereta Api</h6>
                </div>
            </div>

            <!-- TAMBAH Data  -->
            <div class="d-flex justify-content-between align-items-center my-4">

                <a href="tambah.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Tambah Harga Tiket
                </a>
            </div>

            <!-- Pencarian Data  -->
            <form method="GET" class="mb-3">
                <div class="d-flex gap-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari stasiun / kelas tiket..."
                        value="<?= $_GET['keyword'] ?? '' ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>


            <!-- TABEL HARGA TIKET -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Harga Tiket</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Kelas Tiket</th>
                                            <th>Stasiun Asal</th>
                                            <th>Stasiun Tujuan</th>
                                            <th>Harga</th>
                                            <th>status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "
                                                SELECT
                                                    h.id,
                                                    k.nama_kelas,
                                                    h.harga,
                                                    a.nama AS stasiun_asal,
                                                    t.nama AS stasiun_tujuan,
                                                    h.is_active
                                                FROM harga_tiket h
                                                JOIN kelas_tiket k ON h.kelas_id = k.id
                                                JOIN stasiun_rute a ON h.stasiun_asal_id = a.id
                                                JOIN stasiun_rute t ON h.stasiun_tujuan_id = t.id
                                                WHERE
                                                    a.nama LIKE '%$keyword%' OR
                                                    t.nama LIKE '%$keyword%' OR
                                                    k.nama_kelas LIKE '%$keyword%'
                                                ORDER BY k.nama_kelas ASC, h.harga ASC
                                                ";

                                        $result = mysqli_query($koneksi, $sql);
                                        if (mysqli_num_rows($result) == 0) {
                                            echo "<tr><td colspan='7' class='text-center'>Data tidak ditemukan</td></tr>";
                                        }

                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['nama_kelas'] ?></td>
                                                <td><?= $row['stasiun_asal'] ?></td>
                                                <td><?= $row['stasiun_tujuan'] ?></td>
                                                <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                <td>
                                                    <?php if ($row['is_active'] == 1): ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Tidak Aktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <!-- Tombol Edit -->
                                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus data ini?')">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </a>

                                                    </div>
                                                </td>


                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <?php require '../templates/footer.php' ?>