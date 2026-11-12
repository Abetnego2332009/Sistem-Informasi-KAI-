
  // ── Scroll animation ──
  const faders = document.querySelectorAll('.fade-up');
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  faders.forEach(el => io.observe(el));

  // ── Back to top ──
  const btn = document.getElementById('backTop');
  window.addEventListener('scroll', () => btn.classList.toggle('show', window.scrollY > 400));
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  // ── Navbar active on scroll ──
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
  window.addEventListener('scroll', () => {
    let cur = '';
    sections.forEach(s => { if(window.scrollY >= s.offsetTop - 80) cur = s.id; });
    navLinks.forEach(l => {
      l.classList.toggle('active', l.getAttribute('href') === '#' + cur);
    });
  });

  // ── Filter Jadwal ──
  function filterJadwal() {
    const tujuan = document.getElementById('filterTujuan').value.toLowerCase();
    const hari   = document.getElementById('filterHari').value.toLowerCase();
    document.querySelectorAll('#tblKeberangkatan tr').forEach(row => {
      const t = (row.dataset.tujuan || '').toLowerCase();
      const h = (row.dataset.hari || '').toLowerCase();
      const matchT = !tujuan || t === tujuan;
      const matchH = !hari   || h === hari;
      row.style.display = matchT && matchH ? '' : 'none';
    });
  }
