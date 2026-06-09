// Navbar shrink on scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 60);
});

// Mobile menu toggle
document.getElementById('navToggle').addEventListener('click', () => {
  document.querySelector('.nav-links').classList.toggle('open');
});

// Speisekarte Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.menu-grid').forEach(g => g.classList.add('hidden'));

    btn.classList.add('active');
    document.getElementById('menu-' + btn.dataset.tab).classList.remove('hidden');
  });
});

// Scroll Reveal
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) e.target.classList.add('visible');
  });
}, { threshold: 0.15 });

document.querySelectorAll('.menu-card, .ueber-img, .ueber-text, .kontakt-item').forEach(el => {
  el.classList.add('reveal');
  observer.observe(el);
});
