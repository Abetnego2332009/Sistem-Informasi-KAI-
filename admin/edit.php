<?php
session_start();
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php';

$title = 'Edit Admin - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

$id    = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data admin tidak ditemukan!'); window.location.href='index.php';</script>";
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
                    <h3 class="fw-bold mb-3">Manajemen Admin</h3>
                    <h6 class="op-7 mb-2">Edit data akun admin</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Admin: <?= htmlspecialchars($data['nama']) ?></h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses.php" enctype="multipart/form-data">

                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['foto'] ?? '') ?>">

                                <div class="row">

                                    <!-- Nama -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" required
                                                value="<?= htmlspecialchars($data['nama']) ?>">
                                        </div>
                                    </div>

                                    <!-- Username (readonly) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($data['username']) ?>" readonly
                                                style="background:#f8f9fa;">
                                            <small class="text-muted">Username tidak bisa diubah.</small>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" required
                                                value="<?= htmlspecialchars($data['email']) ?>">
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select name="role" class="form-control" required>
                                                <option value="admin" <?= $data['role'] === 'admin'      ? 'selected' : '' ?>>Admin</option>
                                                <option value="superadmin" <?= $data['role'] === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Password baru (opsional) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password Baru <span class="text-muted">(opsional)</span></label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password"
                                                    class="form-control"
                                                    minlength="6"
                                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePass('password', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru <span class="text-muted">(opsional)</span></label>
                                            <div class="input-group">
                                                <input type="password" name="konfirmasi_password" id="konfirmasi"
                                                    class="form-control"
                                                    minlength="6"
                                                    placeholder="Ulangi password baru">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePass('konfirmasi', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <small id="passMsg" class="text-danger d-none">Password tidak cocok.</small>
                                        </div>
                                    </div>

                                    <!-- Foto Profil -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Foto Profil</label>
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <?php if (!empty($data['foto'])): ?>
                                                    <img id="previewImg" src="../uploads/admin/<?= htmlspecialchars($data['foto']) ?>" style="width:70px;height:70px;object-fit:cover;border-radius:50%;">
                                                <?php else: ?>
                                                    <div id="previewImg" style="width:70px;height:70px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-user fa-2x text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                                                    <small class="text-muted">Format JPG, PNG, WEBP. Maks 2MB.</small>
                                                </div>
                                            </div>
                                            <!-- TOMBOL HAPUS (selalu muncul) -->
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto" value="1">
                                                <label class="form-check-label text-danger" for="hapus_foto">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus foto profil
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="update" class="btn btn-primary"
                                        onclick="return validasiPassword()">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
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
</div>

<script>
    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function validasiPassword() {
        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('konfirmasi').value;
        const msg = document.getElementById('passMsg');
        // Validasi hanya jika password diisi
        if (p1 !== '' && p1 !== p2) {
            msg.classList.remove('d-none');
            return false;
        }
        msg.classList.add('d-none');
        return true;
    }

    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const img = document.getElementById('previewImg');
            reader.onload = e => {
                // Kalau sebelumnya div placeholder, ganti jadi img
                if (img.tagName === 'DIV') {
                    const newImg = document.createElement('img');
                    newImg.id = 'previewImg';
                    newImg.style.cssText = 'width:70px;height:70px;object-fit:cover;border-radius:50%;border:2px solid #dee2e6;';
                    img.replaceWith(newImg);
                    newImg.src = e.target.result;
                } else {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>