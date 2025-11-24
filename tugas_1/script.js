// SCROLL SHADOW
window.addEventListener("scroll", () => {
  const header = document.querySelector("header");
  header.classList.toggle("scrolled", window.scrollY > 10);
});

// BURGER MENU
const burger = document.querySelector(".burger");
const nav = document.querySelector("nav");

burger.addEventListener("click", () => {
  nav.classList.toggle("show");
});

// SMOOTH SCROLL
document.querySelectorAll("nav a").forEach(a => {
  a.addEventListener("click", e => {
    e.preventDefault();
    nav.classList.remove("show");
    document.querySelector(a.getAttribute("href"))
      .scrollIntoView({ behavior: "smooth" });
  });
});

// FADE-UP ANIMATION
const obs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.classList.add("visible");
    }
  });
}, { threshold: 0.2 });

document.querySelectorAll(".fade-up").forEach(el => obs.observe(el));
