/* ===== 1. STORY FLOW REVEAL ANIMATION ===== */
document.addEventListener("DOMContentLoaded", () => {
  const blocks = document.querySelectorAll(".story-block");

  function reveal() {
    const trigger = window.innerHeight * 0.85;
    blocks.forEach(block => {
      const top = block.getBoundingClientRect().top;
      if (top < trigger) {
        block.classList.add("show");
      }
    });
  }

  window.addEventListener("scroll", reveal);
  reveal(); // Run once on load
});

/* ===== 2. AWARDS CARD STACK SLIDER ===== */
document.addEventListener("DOMContentLoaded", function () {
  const track = document.getElementById("slider"); // Targets your track ID="slider"
  if (!track) return;
  
  let slides = Array.from(track.querySelectorAll(".stackItem"));
  if (!slides.length) return;

  const cloneCount = 4;

  // Create Left Clones
  for (let i = slides.length - cloneCount; i < slides.length; i++) {
    const clone = slides[i].cloneNode(true);
    track.insertBefore(clone, track.firstChild);
  }

  // Create Right Clones
  for (let i = 0; i < cloneCount; i++) {
    const clone = slides[i].cloneNode(true);
    track.appendChild(clone);
  }

  // Re-fetch all elements including clones
  slides = Array.from(track.querySelectorAll(".stackItem"));
  let index = cloneCount;

  function updateSlider(animate = true) {
    const slideWidth = slides[0].offsetWidth + 30; // 30px account for margins
    const offset = (track.parentElement.offsetWidth / 2) - (slideWidth / 2);

    track.style.transition = animate ? "transform 0.8s ease" : "none";
    track.style.transform = `translateX(${offset - index * slideWidth}px)`;

    slides.forEach(s => s.classList.remove("active"));
    if (slides[index]) {
      slides[index].classList.add("active");
    }
  }

  function autoSlide() {
    index++;
    updateSlider(true);

    if (index >= slides.length - cloneCount) {
      setTimeout(() => {
        index = cloneCount;
        updateSlider(false);
      }, 800);
    }
  }

  // Set up button event click handlers for Awards Stack
  const prevBtn = document.querySelector(".stackBtn.left");
  const nextBtn = document.querySelector(".stackBtn.right");

  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      index++;
      updateSlider(true);
      if (index >= slides.length - cloneCount) {
        setTimeout(() => { index = cloneCount; updateSlider(false); }, 800);
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      index--;
      updateSlider(true);
      if (index < cloneCount) {
        setTimeout(() => { index = slides.length - cloneCount - 1; updateSlider(false); }, 800);
      }
    });
  }

  updateSlider(false);
  setInterval(autoSlide, 2000);

  window.addEventListener("resize", () => updateSlider(false));
});

/* ===== 3. FIXED REELS SLIDER ===== */
document.addEventListener("DOMContentLoaded", function () {
  const track = document.getElementById("reelsTrack");
  const slides = Array.from(document.querySelectorAll(".reelSlide"));
  const prevBtn = document.querySelector(".reelPrev");
  const nextBtn = document.querySelector(".reelNext");
  const dotsWrap = document.getElementById("reelsDots");
  const videos = Array.from(document.querySelectorAll(".reelVideo"));

  if (!track || !slides.length || !prevBtn || !nextBtn || !dotsWrap) {
    return;
  }

  let currentIndex = 0;

  function buildDots() {
    dotsWrap.innerHTML = "";
    slides.forEach((_, i) => {
      const dot = document.createElement("button");
      dot.className = "dot";
      dot.type = "button";
      if (i === 0) dot.classList.add("active");
      dot.addEventListener("click", () => {
        currentIndex = i;
        updateSlider();
      });
      dotsWrap.appendChild(dot);
    });
  }

  function pauseAllVideos(exceptIndex = null) {
    videos.forEach((video, i) => {
      if (i !== exceptIndex) {
        video.pause();
      }
    });
  }

  function updateSlider() {
    // Force item widths structurally to 100% via JS to overwrite layout collapses
    slides.forEach(slide => {
      slide.style.flex = "0 0 100%";
      slide.style.width = "100%";
    });

    // Animate view translations horizontally
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    
    // Manage Dot selection toggles
    const dots = dotsWrap.querySelectorAll(".dot");
    dots.forEach(dot => dot.classList.remove("active"));
    if (dots[currentIndex]) dots[currentIndex].classList.add("active");

    // Manage button availability transparency safely
    prevBtn.style.opacity = currentIndex === 0 ? "0.3" : "1";
    prevBtn.style.cursor = currentIndex === 0 ? "not-allowed" : "pointer";

    nextBtn.style.opacity = currentIndex >= slides.length - 1 ? "0.3" : "1";
    nextBtn.style.cursor = currentIndex >= slides.length - 1 ? "not-allowed" : "pointer";
  }

  nextBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentIndex < slides.length - 1) {
        currentIndex++;
        pauseAllVideos();
        updateSlider();
    }
  });

  prevBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentIndex > 0) {
        currentIndex--;
        pauseAllVideos();
        updateSlider();
    }
  });

  // Automatically pause background reels when another starts playing
  videos.forEach((video, index) => {
    video.addEventListener("play", () => {
      pauseAllVideos(index);
    });
  });

  window.addEventListener("resize", updateSlider);

  buildDots();
  updateSlider();
});