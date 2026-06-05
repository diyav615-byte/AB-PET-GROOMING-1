document.addEventListener("DOMContentLoaded", () => {

  const blocks = document.querySelectorAll(".story-block");

  function reveal(){
    const trigger = window.innerHeight * 0.85;

    blocks.forEach(block => {
      const top = block.getBoundingClientRect().top;

      if(top < trigger){
        block.classList.add("show");
      }
    });
  }

  window.addEventListener("scroll", reveal);

  // run once on load
  reveal();

});

document.addEventListener("DOMContentLoaded", function () {

  const track = document.querySelector(".stackTrack");
  let slides = Array.from(document.querySelectorAll(".stackItem"));

  const cloneCount = 4;

  // LEFT CLONE
  for (let i = slides.length - cloneCount; i < slides.length; i++) {
    const clone = slides[i].cloneNode(true);
    track.insertBefore(clone, track.firstChild);
  }

  // RIGHT CLONE
  for (let i = 0; i < cloneCount; i++) {
    const clone = slides[i].cloneNode(true);
    track.appendChild(clone);
  }

  slides = Array.from(document.querySelectorAll(".stackItem"));

  let index = cloneCount;

  function updateSlider(animate = true) {
    const slideWidth = slides[0].offsetWidth + 30;
    const offset = (track.parentElement.offsetWidth / 2) - (slideWidth / 2);

    track.style.transition = animate ? "transform 0.8s ease" : "none";
    track.style.transform = `translateX(${offset - index * slideWidth}px)`;

    slides.forEach(s => s.classList.remove("active"));
    slides[index].classList.add("active");
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

  updateSlider(false);
  setInterval(autoSlide, 4000);
});


function scrollLeft(){
  document.getElementById("testimonialContainer").scrollBy({
    left: -300,
    behavior: "smooth"
  });
}

function scrollRight(){
  document.getElementById("testimonialContainer").scrollBy({
    left: 300,
    behavior: "smooth"
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const track = document.getElementById("reelsTrack");
  const slides = Array.from(document.querySelectorAll(".reelSlide"));
  const prevBtn = document.querySelector(".reelPrev");
  const nextBtn = document.querySelector(".reelNext");
  const dotsWrap = document.getElementById("reelsDots");
  const videos = Array.from(document.querySelectorAll(".reelVideo"));

  if (!track || !slides.length || !prevBtn || !nextBtn || !dotsWrap) return;

  let currentIndex = 0;

  function getVisibleCount() {
    if (window.innerWidth <= 640) return 1;
    if (window.innerWidth <= 980) return 2;
    return 3;
  }

  function buildDots() {
    dotsWrap.innerHTML = "";
    slides.forEach((_, i) => {
      const dot = document.createElement("button");
      dot.className = "dot";
      dot.type = "button";
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
    const visibleCount = getVisibleCount();
    const slideWidth = 100 / visibleCount;

    slides.forEach(slide => {
      slide.classList.remove("is-center", "is-side");
      slide.style.flex = `0 0 ${slideWidth}%`;
    });

    const offset = currentIndex * slideWidth;
    track.style.transform = `translateX(-${offset}%)`;

    const dots = dotsWrap.querySelectorAll(".dot");
    dots.forEach(dot => dot.classList.remove("active"));
    if (dots[currentIndex]) dots[currentIndex].classList.add("active");

    if (visibleCount === 3) {
      slides.forEach((slide, i) => {
        if (i === currentIndex) {
          slide.classList.add("is-center");
        } else if (
          i === (currentIndex - 1 + slides.length) % slides.length ||
          i === (currentIndex + 1) % slides.length
        ) {
          slide.classList.add("is-side");
        }
      });
    }

    if (visibleCount === 2) {
      slides.forEach((slide, i) => {
        if (i === currentIndex || i === (currentIndex + 1) % slides.length) {
          slide.classList.add("is-side");
        }
      });
    }
  }

  nextBtn.addEventListener("click", () => {
    pauseAllVideos();
    if (currentIndex < slides.length - 1) {
      currentIndex++;
      updateSlider();
    }
  });

  prevBtn.addEventListener("click", () => {
    pauseAllVideos();
    if (currentIndex > 0) {
      currentIndex--;
      updateSlider();
    }
  });

  videos.forEach((video, index) => {
    video.addEventListener("play", () => {
      pauseAllVideos(index);
    });
  });

  window.addEventListener("resize", updateSlider);

  buildDots();
  updateSlider();
});

