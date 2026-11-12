<footer class="footer">
  <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2">
    <nav class="pull-left">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link" href="#">KAI Perlanaan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Help</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Licenses</a>
        </li>
      </ul>
    </nav>
    <div class="copyright">
      &copy; <span id="footer-year"></span> KAI Perlanaan. All rights reserved.
    </div>
    <div style="font-size: 13px; color: #888;">
      PT Kereta Api Indonesia (Persero)
    </div>
  </div>
</footer>
</div>
<!-- End Main Panel -->

<!-- Custom template -->
<div class="custom-template">
  <div class="title">Settings</div>
  <div class="custom-content">
    <div class="switcher">

      <div class="switch-block">
        <h4>Logo Header</h4>
        <div class="btnSwitch">
          <button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="blue"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
          <br />
          <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
          <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
        </div>
      </div>

      <!-- <div class="switch-block">
        <h4>Navbar Header</h4>
        <div class="btnSwitch">
          <button type="button" class="changeTopBarColor" data-color="dark"></button>
          <button type="button" class="changeTopBarColor" data-color="blue"></button>
          <button type="button" class="changeTopBarColor" data-color="purple"></button>
          <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
          <button type="button" class="changeTopBarColor" data-color="green"></button>
          <button type="button" class="changeTopBarColor" data-color="orange"></button>
          <button type="button" class="changeTopBarColor" data-color="red"></button>
          <button type="button" class="changeTopBarColor" data-color="white"></button>
          <br />
          <button type="button" class="changeTopBarColor" data-color="dark2"></button>
          <button type="button" class="changeTopBarColor" data-color="blue2"></button>
          <button type="button" class="changeTopBarColor" data-color="purple2"></button>
          <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
          <button type="button" class="changeTopBarColor" data-color="green2"></button>
          <button type="button" class="changeTopBarColor" data-color="orange2"></button>
          <button type="button" class="changeTopBarColor" data-color="red2"></button>
        </div>
      </div> -->

      <div class="switch-block">
        <h4>Sidebar</h4>
        <div class="btnSwitch">
          <button type="button" class="changeSideBarColor" data-color="white"></button>
          <button type="button" class="changeSideBarColor" data-color="dark"></button>
          <button type="button" class="changeSideBarColor" data-color="dark2"></button>
        </div>
      </div>

    </div>
  </div>
  <div class="custom-toggle">
    <i class="icon-settings"></i>
  </div>
</div>
<!-- End Custom template -->
</div>

<!--   Core JS Files   -->
<script src="<?php echo $base_url; ?>/assets/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/core/popper.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/core/bootstrap.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/chart.js/chart.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/jsvectormap/world.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/kaiadmin.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/setting-demo.js"></script>
<script src="<?php echo $base_url; ?>/assets/assets/js/demo.js"></script>

<script>
// =============================================
// 1. Tahun Footer — update otomatis
// =============================================
document.getElementById('footer-year').textContent = new Date().getFullYear();

// =============================================
// 2. Color Switcher — simpan ke localStorage
// =============================================
(function () {
  var KEYS = {
    logo: 'kai_logo_color',
    topbar: 'kai_topbar_color',
    sidebar: 'kai_sidebar_color'
  };

  var DEFAULTS = {
    logo: 'dark',
    topbar: 'white',
    sidebar: 'dark'
  };

  function markSelected(btnClass, color) {
    document.querySelectorAll('.' + btnClass).forEach(function (btn) {
      btn.classList.toggle('selected', btn.getAttribute('data-color') === color);
    });
  }

  function applyLogo(color) {
    var el = document.querySelector('.logo-header');
    if (el) el.setAttribute('data-background-color', color);
    markSelected('changeLogoHeaderColor', color);
    localStorage.setItem(KEYS.logo, color);
  }

  function applyTopBar(color) {
    var el = document.querySelector('.main-header');
    if (el) el.setAttribute('data-background-color', color);
    markSelected('changeTopBarColor', color);
    localStorage.setItem(KEYS.topbar, color);
  }

  function applySidebar(color) {
    var el = document.querySelector('.sidebar');
    if (el) el.setAttribute('data-background-color', color);
    markSelected('changeSideBarColor', color);
    localStorage.setItem(KEYS.sidebar, color);
  }

  function loadSaved() {
    applyLogo(localStorage.getItem(KEYS.logo) || DEFAULTS.logo);
    applyTopBar(localStorage.getItem(KEYS.topbar) || DEFAULTS.topbar);
    applySidebar(localStorage.getItem(KEYS.sidebar) || DEFAULTS.sidebar);
  }

  function bindButtons() {
    document.querySelectorAll('.changeLogoHeaderColor').forEach(function (btn) {
      btn.addEventListener('click', function () {
        applyLogo(this.getAttribute('data-color'));
      });
    });
    document.querySelectorAll('.changeTopBarColor').forEach(function (btn) {
      btn.addEventListener('click', function () {
        applyTopBar(this.getAttribute('data-color'));
      });
    });
    document.querySelectorAll('.changeSideBarColor').forEach(function (btn) {
      btn.addEventListener('click', function () {
        applySidebar(this.getAttribute('data-color'));
      });
    });
  }

  window.addEventListener('DOMContentLoaded', function () {
    bindButtons();
    setTimeout(loadSaved, 150);
  });
})();
</script>

</body>
</html>