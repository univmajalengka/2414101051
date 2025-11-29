// header shadow on scroll
const header = document.getElementById('siteHeader');
window.addEventListener('scroll', () => header.classList.toggle('scrolled', window.scrollY > 20));

// burger menu toggle
const burger = document.getElementById('burger');
const mainNav = document.getElementById('mainNav');
burger?.addEventListener('click', () => mainNav.classList.toggle('show'));

// smooth anchor scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', function(e){
    e.preventDefault();
    const t = document.querySelector(this.getAttribute('href'));
    if(!t) return;
    t.scrollIntoView({behavior:'smooth', block:'start'});
    if(mainNav.classList.contains('show')) mainNav.classList.remove('show');
  });
});

// Fade/Reveal with IntersectionObserver with stagger
const reveal = (selectors) => {
  const els = document.querySelectorAll(selectors);
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('visible');
        // add small stagger classes for children
        entry.target.querySelectorAll('*').forEach((c, i) => {
          if(i < 10) c.classList.add(`stagger-${Math.min(4, i+1)}`);
        });
        io.unobserve(entry.target);
      }
    });
  }, {threshold: 0.18});
  els.forEach(el => io.observe(el));
};

// apply reveals to different classes
reveal('.fade-up');
reveal('.fade-left');
reveal('.fade-right');

// HERO slider + parallax background
const slides = Array.from(document.querySelectorAll('.slide'));
let active = 0;
function showSlide(i){
  slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
}
showSlide(active);

document.getElementById('next')?.addEventListener('click', () => {
  active = (active + 1) % slides.length;
  showSlide(active);
});
document.getElementById('prev')?.addEventListener('click', () => {
  active = (active - 1 + slides.length) % slides.length;
  showSlide(active);
});
// autoplay
setInterval(() => { active = (active + 1) % slides.length; showSlide(active); }, 7000);

// parallax hero background move on scroll (subtle)
window.addEventListener('scroll', () => {
  const hero = document.querySelector('.hero-slider');
  if(!hero) return;
  const rect = hero.getBoundingClientRect();
  const winH = window.innerHeight;
  const pct = Math.min(1, Math.max(0, 1 - (rect.top / winH)));
  slides.forEach(s => {
    s.style.backgroundPosition = `center ${10 + pct * 12}%`;
  });
});

// Gallery video lightbox (if any .video-thumb added later)
document.querySelectorAll('.video-thumb').forEach(thumb => {
  thumb.addEventListener('click', () => {
    const url = thumb.dataset.youtube;
    if(!url) return;
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.inset = 0;
    overlay.style.background = 'rgba(0,0,0,0.88)';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.style.zIndex = 9999;

    const iframe = document.createElement('iframe');
    iframe.width = Math.min(window.innerWidth * 0.9, 1200);
    iframe.height = Math.min(window.innerHeight * 0.8, 720);
    iframe.src = url;
    iframe.frameBorder = 0;
    iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;

    const close = document.createElement('button');
    close.textContent = '✕';
    close.style.position = 'absolute';
    close.style.top = '18px';
    close.style.right = '22px';
    close.style.fontSize = '20px';
    close.style.background = 'transparent';
    close.style.color = '#fff';
    close.style.border = 'none';
    close.style.cursor = 'pointer';
    close.addEventListener('click', () => document.body.removeChild(overlay));

    overlay.appendChild(iframe);
    overlay.appendChild(close);
    document.body.appendChild(overlay);
  });
});

// demo form submit
document.getElementById('orderForm')?.addEventListener('submit', function(e){
  e.preventDefault();
  alert('Permintaan terkirim! Pengelola akan menghubungi Anda (demo).');
  this.reset();
});
