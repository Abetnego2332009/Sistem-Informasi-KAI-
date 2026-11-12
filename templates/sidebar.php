<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">

    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="<?= $base_url ?>/dashboard.php" class="logo">
        <img
          src="<?php echo $base_url; ?>/assets/assets/img/gambarKai.png"
          alt="navbar brand"
          class="navbar-brand img-responsive"
          height="50" />
        <span style="color:#fff;font-weight:700;font-size:13px;vertical-align:middle;margin-left:6px;">
          KAI <span style="font-weight:400;opacity:.8;">Perlanaan</span>
        </span>
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
    <!-- End Logo Header -->

    <!-- Admin Info -->

    <!-- End Admin Info -->

  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">

        <!-- ===================== DASHBOARD ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Dashboard</h4>
        </li>

        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
          <a href="<?= $base_url ?>/dashboard.php">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>


        <!-- <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'statistik_pengunjung.php' ? 'active' : ''; ?>">
          <a href="<?= $base_url ?>/statistik_pengunjung.php">
            <i class="fas fa-chart-bar"></i>
            <p>Statistik Pengunjung</p>
          </a>
        </li> -->

        <!-- ===================== MANAJEMEN STASIUN ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Manajemen Stasiun</h4>
        </li>

        <li class="nav-item <?= $base_url?>/profil_stasiun">
          <a href="<?= $base_url?>/profil_stasiun">
            <i class="fas fa-building"></i>
            <p>Profil Stasiun</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/jam_operasional">
          <a href="<?= $base_url ?>/jam_operasional">
            <i class="fas fa-clock"></i>
            <p>Jam Operasional</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/fasilitas">
          <a href="<?= $base_url ?>/fasilitas">
            <i class="fas fa-concierge-bell"></i>
            <p>Fasilitas</p>
          </a>
        </li>

        <!-- ===================== PERJALANAN KERETA ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Perjalanan Kereta</h4>
        </li>

        <li class="nav-item <?= $base_url ?>/kereta">
          <a href="<?= $base_url  ?>/kereta">
            <i class="fas fa-train"></i>
            <p>Daftar Kereta</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/rute">
          <a href="<?= $base_url  ?>/rute">
            <i class="fas fa-route"></i>
            <p>Stasiun & Rute Kereta</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/jadwal_kreta">
          <a href="<?= $base_url  ?>/jadwal_kreta">
            <i class="fas fa-calendar-alt"></i>
            <p>Jadwal Kereta</p>
          </a>
        </li>

        <!-- ===================== TIKET & TARIF ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Tiket &amp; Tarif</h4>
        </li>

        <!-- <li class="nav-item <?= $base_url ?>/kelas_tiket">
          <a href="<?= $base_url  ?>/kelas_tiket">
            <i class="fas fa-layer-group"></i>
            <p>Kelas Tiket</p>
          </a>
        </li> -->

        <li class="nav-item <?= $base_url ?>/harga_tiket">
          <a href="<?= $base_url  ?>/harga_tiket">
            <i class="fas fa-ticket-alt"></i>
            <p>Harga Tiket</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/tarif_khusus">
          <a href="<?= $base_url  ?>/tarif_khusus">
            <i class="fas fa-tags"></i>
            <p>Tarif Khusus</p>
          </a>
        </li>

        <!-- ===================== INFORMASI & KONTEN ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Informasi &amp; Konten</h4>
        </li>

        <li class="nav-item <?= $base_url ?>/pengumuman">
          <a href="<?= $base_url  ?>/pengumuman">
            <i class="fas fa-bullhorn"></i>
            <p>Pengumuman</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/berita">
          <a href="<?= $base_url  ?>/berita">
            <i class="fas fa-newspaper"></i>
            <p>Berita &amp; Promo</p>
          </a>
        </li>

        <li class="nav-item <?= $base_url ?>/faq">
          <a href="<?= $base_url  ?>/faq">
            <i class="fas fa-question-circle"></i>
            <p>FAQ</p>
          </a>
        </li>

        <!-- ===================== PENGATURAN ===================== -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Pengaturan</h4>
        </li>

        <li class="nav-item <?= $base_url ?>/pengaturan_sistem">
          <a href="<?= $base_url  ?>/pengaturan_sistem">
            <i class="fas fa-cogs"></i>
            <p>Pengaturan Sistem</p>
          </a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin'): ?>
          <li class="nav-item <?= $base_url ?>/admin">
            <a href="<?= $base_url  ?>/admin">
              <i class="fas fa-user-shield"></i>
              <p>Manajemen Admin</p>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->