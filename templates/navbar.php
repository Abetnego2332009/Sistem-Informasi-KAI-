<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tentukan base_url (sesuaikan jika perlu, biasanya sudah didefinisikan di file yang include navbar)
if (!isset($base_url)) $base_url = '';

// Path relatif dari navbar.php (ada di folder templates/)
$path_upload = __DIR__ . '/../uploads/admin/' . ($_SESSION['foto'] ?? '');
$foto_url = '';

// Cek apakah session foto ada dan file benar-benar ada
if (!empty($_SESSION['foto']) && file_exists($path_upload)) {
    $foto_url = $base_url . '/uploads/admin/' . htmlspecialchars($_SESSION['foto']);
} else {
    $foto_url = $base_url . '/assets/assets/img/defauld.png';
}
?>
<!-- Navbar -->
<div class="main-header">
  <div class="main-header-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="index.php" class="logo">
        <img src="<?= $foto_url ?>"
          alt="navbar brand"
          class="navbar-brand"
          height="20" />
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
  </div>
  <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
        <li class="nav-item topbar-user dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
            <div class="avatar-sm">
              <img src="<?= $foto_url ?>"
                alt="..."
                class="avatar-img rounded-circle" />
            </div>
            <span class="profile-username">
              <span class="op-7">Hi,</span>
              <span class="fw-bold"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    <img src="<?= $foto_url ?>"
                      alt="image profile"
                      class="avatar-img rounded" />
                  </div>
                  <div class="u-text">
                    <h4><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                    <a href="<?= $base_url ?>/profile" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                  </div>
                </div>
              </li>
              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= $base_url ?>/logout.php">Logout</a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</div>