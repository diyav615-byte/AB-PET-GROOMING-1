(function () {
  "use strict";

  document.addEventListener('DOMContentLoaded', () => {
    
    // ==========================================
    // 1. MOBILE DROPDOWN HAMBURGER LOGIC
    // ==========================================
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav');

    if (navToggle && navMenu) {
      // Toggle menu state on button click
      navToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        navToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
      });

      // Close menu when clicking any link inside it
      const navLinks = navMenu.querySelectorAll('a');
      navLinks.forEach(link => {
        link.addEventListener('click', () => {
          navToggle.classList.remove('active');
          navMenu.classList.remove('active');
        });
      });

      // Close menu panel automatically if user clicks anywhere outside
      document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
          navToggle.classList.remove('active');
          navMenu.classList.remove('active');
        }
      });
    }

    // ==========================================
    // 2. HERO SLIDER LOGIC (Index Page Only Safeguard)
    // ==========================================
    const heroBg = document.getElementById("heroBg");
    const heroImage = document.getElementById("heroImage");

    if (heroBg && heroImage) {
      const blob1 = document.getElementById("blob1");
      const blob2 = document.getElementById("blob2");
      const blob3 = document.getElementById("blob3");
      const heroBig = document.getElementById("heroBig");
      const heroSub = document.getElementById("heroSub");
      const heroDesc = document.getElementById("heroDesc");
      const navBookBtn = document.getElementById("navBookBtn");
      const heroBookBtn = document.getElementById("heroBookBtn");

      const slides = [
        {
          big: "pawsome",
          sub: "place for your pet",
          desc: "Premium grooming, safe boarding and pet care for dogs and cats. Clean, gentle and stress-free service.",
          image: "assets/images/hero/dog-hero.png",
          bg: "linear-gradient(110deg, #eadcff, #f9f6ff)",
          b1: "#d6c6ff",
          b2: "#ffe0ea",
          b3: "#fff4c8",
          btn: "#4a1fb8"
        },
        {
          big: "purrfect",
          sub: "place for your pet",
          desc: "Gentle grooming & loving care for cats too. Soft handling, safe products and premium comfort.",
          image: "assets/images/hero/cat-hero.png",
          bg: "linear-gradient(110deg, #ffd9e6, #fff7fb)",
          b1: "#ffc2d6",
          b2: "#d6c6ff",
          b3: "#fff4c8",
          btn: "#b32558"
        }
      ];

      let current = 0;

      function applySlide(i, animate = true) {
        const s = slides[i];

        if (animate) {
          heroImage.classList.remove("fade-in");
          heroImage.classList.add("fade-out");
        }

        heroBg.style.background = s.bg;
        if (blob1) blob1.style.background = s.b1;
        if (blob2) blob2.style.background = s.b2;
        if (blob3) blob3.style.background = s.b3;
        if (navBookBtn) navBookBtn.style.background = s.btn;
        if (heroBookBtn) heroBookBtn.style.background = s.btn;
        if (heroBig) heroBig.textContent = s.big;
        if (heroSub) heroSub.textContent = s.sub;
        if (heroDesc) heroDesc.textContent = s.desc;

        setTimeout(() => {
          heroImage.src = s.image;
          heroImage.classList.remove("fade-out");
          heroImage.classList.add("fade-in");
        }, animate ? 450 : 0);
      }

      applySlide(current, false);

      setInterval(() => {
        current = (current + 1) % slides.length;
        applySlide(current, true);
      }, 1000);
    }

  });
})();