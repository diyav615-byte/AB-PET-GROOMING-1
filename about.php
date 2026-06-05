<?php include "includes/header.php"; ?>

<link rel="stylesheet" href="assets/css/about-slider.css">

<section class="story-flow">

  <div class="container">

    <div class="story-block">
      <h2>Our Story</h2>
      <p>
        AB Pet Grooming is built with one goal — stress-free grooming.
        Every pet deserves gentle care, hygiene, and a calm environment.
      </p>
    </div>
    <div class="story-block">
      <h2>Our Goal</h2>
      <p>
        We aim to deliver premium grooming with consistency and trust.
        Every visit should feel safe, clean, and professional.
      </p>
    </div>

    <div class="story-block">
      <h2>Our Vision</h2>
      <p>
        We are creating a modern grooming experience where comfort meets
        premium quality and global standards.
      </p>
    </div>

  </div>
</section>

<script src="assets/js/about-slider.js"></script>
<section class="awardsStack">
  <div class="container">
    <div class="awardsHead">
      <h3>Awards & Journey</h3>
      <p>Championship moments, stage events, trophies, certificates and grooming highlights.</p>
    </div>

    <div class="stackWrap">
      <button class="stackBtn left" type="button">&#10094;</button>

      <div class="stackStage">
        <div class="stackTrack" id="slider">
          <div class="stackItem"><img src="assets/images/about/about.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about1.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about2.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about3.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about4.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about5.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about6.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about7.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about8.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about9.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about10.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about11.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about12.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about13.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about14.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about15.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about16.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about17.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about18.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about19.jpg" alt=""></div>
          <div class="stackItem"><img src="assets/images/about/about20.jpg" alt=""></div>
        </div>
      </div>

      <button class="stackBtn right" type="button">&#10095;</button>
    </div>

    <div class="stackDots" id="dots"></div>
  </div>
</section>

<section class="reelsSection">
  <div class="container">
    <div class="reelsHead">
      <h2>Watch Our Reels</h2>
      <p>Quick grooming highlights, transformations, happy pets and behind-the-scenes moments.</p>
    </div>

    <div class="reelsWrap">
      <button class="reelBtn reelPrev" type="button">&#10094;</button>

      <div class="reelsViewport">
        <div class="reelsTrack" id="reelsTrack">
          
          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel1.jpg">
                <source src="assets/videos/reels/reel1.mp4" type="video/mp4">
              </video>
            </div>
          </div>

          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel2.jpg">
                <source src="assets/videos/reels/reel2.mp4" type="video/mp4">
              </video>
            </div>
          </div>

          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel3.jpg">
                <source src="assets/videos/reels/reel3.mp4" type="video/mp4">
              </video>
            </div>
          </div>

          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel4.jpg">
                <source src="assets/videos/reels/reel4.mp4" type="video/mp4">
              </video>
            </div>
          </div>

          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel5.jpg">
                <source src="assets/videos/reels/reel5.mp4" type="video/mp4">
              </video>
            </div>
          </div>

          <div class="reelSlide">
            <div class="reelCard">
              <video class="reelVideo" controls playsinline preload="metadata" poster="assets/images/reels/reel6.jpg">
                <source src="assets/videos/reels/reel6.mp4" type="video/mp4">
              </video>
            </div>
          </div>


          

          

        </div>
      </div>

      <button class="reelBtn reelNext" type="button">&#10095;</button>
    </div>

    <div class="reelsDots" id="reelsDots"></div>
  </div>

  <div class="reelsInstagram">
  <a href="https://www.instagram.com/ab_pet_grooming_studio?igsh=ZnVnazFkdmJkdXho" target="_blank" class="instaBtn">
    View More on Instagram →
  </a>
</div>

</section>

<section class="review-section">

  <h2 class="review-title">What Our Happy Parents Say </h2>

  <div class="carousel">
    <div class="carousel-track">

      <?php
      include "config/db.php";

      $result = $conn->query("
        SELECT * FROM reviews
        WHERE status='approved'
        ORDER BY id DESC
      ");

      while($row = $result->fetch_assoc()){
      ?>

      <div class="review-card">
        <div class="stars">
          <?= str_repeat("⭐", $row['rating']) ?>
        </div>

        <p class="review-text">
          “<?= $row['message'] ?>”
        </p>

        <h4 class="review-name">
          — <?= $row['name'] ?>
        </h4>
      </div>

      <?php } ?>

    </div>
  </div>

</section>

<?php include "includes/footer.php"; ?>