<?php
session_start();
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php';

$login_id = intval($_SESSION['id'] ?? 0);

// Ambil data admin yang sedang login (fresh dari DB)
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $login_id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

$title = 'Edit Profil - Admin';
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
                    <h3 class="fw-bold mb-3">Edit Profil</h3>
                    <h6 class="op-7 mb-2">Perbarui informasi akun Anda sendiri</h6>
                </div>
            </div>

            <!-- ALERT ERROR jika ada -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php
                    $err = [
                        'email_exists'  => 'Email sudah digunakan akun lain.',
                        'pass_mismatch' => 'Konfirmasi password tidak cocok.',
                        'gagal'         => 'Gagal memperbarui profil, coba lagi.',
                    ];
                    echo $err[$_GET['error']] ?? 'Terjadi kesalahan.';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card card-round shadow-sm">
                        <div class="card-header">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-edit me-2"></i>Edit Profil Saya
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <form method="POST" action="proses_profile.php" enctype="multipart/form-data">

                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['foto'] ?? '') ?>">

                                <!-- Foto Preview di atas -->
                                <div class="text-center mb-4">
                                    <?php if (!empty($data['foto'])): ?>
                                        <img id="previewImg"
                                            src="../uploads/admin/<?= htmlspecialchars($data['foto']) ?>"
                                            class="rounded-circle border"
                                            style="width:90px;height:90px;object-fit:cover;border-width:3px!important;">
                                    <?php else: ?>
                                        <div id="previewImg"
                                            class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;border-width:3px!important;">
                                            <i class="fas fa-user fa-3x text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <p class="text-muted small mt-2 mb-0">Foto Profil Saat Ini</p>
                                </div>

                                <div class="row">

                                    <!-- Nama -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" required
                                                value="<?= htmlspecialchars($data['nama']) ?>">
                                        </div>
                                    </div>

                                    <!-- Username (readonly - tidak bisa diubah) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($data['username']) ?>"
                                                readonly style="background:#f8f9fa;">
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

                                    <!-- Role (readonly - tidak bisa ubah role sendiri) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="text" class="form-control"
                                                value="<?= $data['role'] === 'superadmin' ? 'Superadmin' : 'Admin' ?>"
                                                readonly style="background:#f8f9fa;">
                                            <small class="text-muted">Role hanya bisa diubah oleh superadmin.</small>
                                        </div>
                                    </div>

                                    <!-- Password Baru (opsional) -->
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
                                            <label>Ganti Foto Profil</label>
                                            <input type="file" name="foto" class="form-control"
                                                accept="image/jpeg,image/png,image/webp"
                                                onchange="previewFoto(this)">
                                            <small class="text-muted">
                                                Format: JPG, PNG, WEBP. Maks. 2MB.
                                                Kosongkan jika tidak ingin mengganti foto.
                                            </small>
                                        </div>

                                        <?php if (!empty($data['foto'])): ?>
                                            <!-- Tampilkan checkbox hapus HANYA jika ada foto -->
                                            <div class="form-group mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto" value="1">
                                                    <label class="form-check-label text-danger" for="hapus_foto">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus foto profil
                                                    </label>
                                                </div>
                                                <small class="text-muted">Centang jika ingin menghapus foto (tanpa mengganti dengan foto baru).</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <button type="submit" name="update_profile" class="btn btn-primary"
                                        onclick="return validasiPassword()">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                    <a href="<?= $base_url ?>/profile" class="btn btn-secondary">
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
                if (img.tagName === 'DIV') {
                    const newImg = document.createElement('img');
                    newImg.id = 'previewImg';
                    newImg.className = 'rounded-circle border';
                    newImg.style.cssText = 'width:90px;height:90px;object-fit:cover;border-width:3px!important;';
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