<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Jadwal Kereta - Admin ';
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
                    <h3 class="fw-bold mb-3">Dashboard Jadwal Kereta</h3>
                    <h6 class="op-7 mb-2">Informasi Jadwal Keberangkatan Kereta Api</h6>
                </div>
            </div>

            <!-- TAMBAH Data  -->
            <div class="d-flex justify-content-between align-items-center my-4">
                
                <a href="tambah_jadwal_kreta.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Tambah Jadwal
                </a>
            </div>

            <!-- TABEL JADWAL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Jadwal Kereta</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Kereta</th>
                                            <th>Tipe</th>
                                            <th>Asal → Tujuan</th>
                                            <th>Via</th>
                                            <th>Jam</th>
                                            <th>Hari</th>
                                            <th>Status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "
                                                SELECT
                                                    j.id,
                                                    k.nomor_ka,
                                                    k.nama_ka,
                                                    k.jenis,
                                                    j.tipe,
                                                    a.nama  AS stasiun_asal,
                                                    t.nama  AS stasiun_tujuan,
                                                    j.via,
                                                    j.jam,
                                                    j.hari_operasi,
                                                    j.status,
                                                    j.keterlambatan_menit,
                                                    j.keterangan,
                                                    j.is_active
                                                FROM jadwal_kereta j
                                                JOIN kereta       k ON j.kereta_id          = k.id
                                                JOIN stasiun_rute a ON j.stasiun_asal_id    = a.id
                                                JOIN stasiun_rute t ON j.stasiun_tujuan_id  = t.id
                                                ORDER BY j.tipe ASC, j.jam ASC
                                            ";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['nama_ka'] ?></td>
                                                <td><?= $row['tipe'] ?></td>
                                                <td><?= $row['stasiun_asal'] ?> → <?= $row['stasiun_tujuan'] ?></td>
                                                <td><?= $row['via'] ?></td>
                                                <td><?= $row['jam'] ?></td>
                                                <td><?= $row['hari_operasi'] ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'Normal'): ?>
                                                        <span class="badge bg-success">Normal</span>
                                                    <?php elseif ($row['status'] == 'Terlambat'): ?>
                                                        <span class="badge bg-warning">Terlambat</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Batal</span>
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
                                                        <a href="proses_jadwal_kreta.php?id=<?= $row['id'] ?>"
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