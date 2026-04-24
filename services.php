<?php include "includes/header.php"; ?>
<style>
/* ===== SERVICES PAGE FULL STYLE ===== */
.services-page{
  background: linear-gradient(180deg, #f7f2fc 0%, #ffffff 100%);
  min-height: 100vh;
}

.svc-hero{
  position: relative;
  padding: 130px 0 110px;
  overflow: hidden;
  border-radius: 0 0 32px 32px;
  background: linear-gradient(135deg, #7158a6, #7158a6);
}

.svc-hero::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    radial-gradient(circle at 20% 20%, rgba(255,255,255,0.18), transparent 30%),
    radial-gradient(circle at 80% 30%, rgba(255,255,255,0.12), transparent 30%);
  pointer-events:none;
}

.svc-hero-inner{
  position: relative;
  z-index: 2;
  text-align: center;
  color: #fff;
}

.svc-hero h1{
  font-size: 64px;
  font-weight: 900;
  margin-bottom: 14px;
  line-height: 1.1;
}

.svc-hero p{
  font-size: 20px;
  max-width: 780px;
  margin: 0 auto;
  opacity: 0.96;
  line-height: 1.7;
}

.svc-wrap{
  padding: 50px 0 90px;
}

.svc-toggle{
  position: relative;
  max-width: 540px;
  margin: 0 auto 34px;
  background: #fff;
  border-radius: 22px;
  padding: 10px;
  display: flex;
  gap: 10px;
  box-shadow: 0 16px 45px rgba(68, 35, 106, 0.10);
  border: 1px solid rgba(113,88,166,0.10);
}

.svc-indicator{
  position: absolute;
  top: 10px;
  left: 10px;
  height: calc(100% - 20px);
  width: calc(50% - 15px);
  border-radius: 16px;
  background: linear-gradient(135deg, #7158a6, #7158a6);
  transition: all 0.35s ease;
  z-index: 1;
}

.svc-tab{
  position: relative;
  z-index: 2;
  flex: 1;
  border: none;
  background: transparent;
  padding: 16px 18px;
  border-radius: 16px;
  font-size: 20px;
  font-weight: 800;
  color: #44305f;
  cursor: pointer;
  transition: color 0.25s ease;
}

.svc-tab.active{
  color: #fff;
}

.svc-panel{
  display: none;
}

.svc-panel.active{
  display: block;
}

.svc-section-title{
  font-size: 34px;
  font-weight: 900;
  color: #2b154d;
  margin: 0 0 22px;
}

.svc-grid-3{
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 30px;
}

/* FIX GRID ISSUE */
.svc-grid-2{
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 22px;
}

/* FULL WIDTH CARD */
.svc-grid-2 .svc-mini-card:last-child{
  grid-column: 1 / -1;
}

/* FIX OVERFLOW */
body, .services-page{
  overflow-x: hidden;
}

/* CENTER BOOK BUTTON */
.book-center{
  text-align: center;
  margin: 40px 0;
}

.book-center a{
  padding: 14px 28px;
  font-size: 18px;
  border-radius: 30px;
  background: #7158a6;
  color: #fff;
  font-weight: 700;
  text-decoration: none;
}

.svc-stack{
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.svc-card{
  background: #fff;
  border-radius: 24px;
  padding: 26px;
  border: 1px solid rgba(113,88,166,0.10);
  box-shadow: 0 16px 45px rgba(68, 35, 106, 0.08);
  height: 100%;
}

.svc-card-featured{
  background: linear-gradient(135deg, #8a6bc4, #7158a6);
  color: #fff;
}

.svc-card h3{
  font-size: 22px;
  font-weight: 900;
  margin-bottom: 16px;
  color: #2a1443;
}

.svc-card-featured h3{
  color: #fff;
}

.svc-list{
  list-style: none;
  padding: 0;
  margin: 0 0 18px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.svc-list li{
  color: #5e5570;
  font-weight: 600;
  line-height: 1.6;
}

.svc-card-featured .svc-list li{
  color: rgba(255,255,255,0.94);
}

.svc-price-table{
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 6px;
}

.svc-row{
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  font-size: 16px;
  color: #554a66;
  font-weight: 700;
}

.svc-row b{
  color: #23153b;
  font-size: 22px;
}

.svc-card-featured .svc-row{
  color: rgba(255,255,255,0.92);
}

.svc-card-featured .svc-row b{
  color: #fff;
}

.book-btn{
  display: inline-block;
  margin-top: 18px;
  padding: 12px 22px;
  border-radius: 999px;
  background: #7158a6;
  color: #fff;
  text-decoration: none;
  font-weight: 800;
  box-shadow: 0 12px 24px rgba(113,88,166,0.20);
  transition: 0.25s ease;
}

.book-btn:hover{
  transform: translateY(-2px);
  background: #5b4588;
}

.svc-card-featured .book-btn{
  background: #fff;
  color: #7158a6;
}

.svc-card-featured .book-btn:hover{
  background: #f7f1fc;
}

.svc-mini-card{
  background: #fff;
  border-radius: 24px;
  padding: 24px 26px;
  border: 1px solid rgba(113,88,166,0.10);
  box-shadow: 0 16px 45px rgba(68, 35, 106, 0.08);
}

.svc-mini-card h3{
  font-size: 22px;
  font-weight: 900;
  color: #2a1443;
  margin-bottom: 14px;
}

.svc-mini-card ul{
  list-style: none;
  padding: 0;
  margin: 0;
}

.svc-mini-card li{
  display: flex;
  justify-content: space-between;
  gap: 16px;
  padding: 8px 0;
  color: #554a66;
  font-weight: 600;
  line-height: 1.5;
}

/* ===== FULL WIDTH PURPLE BANNER FIX ===== */
.svc-banner{
  width: 100%;
  margin-top: 30px;
  border-radius: 28px;
  padding: 30px 32px;
  background: linear-gradient(135deg, #7158a6, #7158a6);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  box-shadow: 0 18px 50px rgba(113,88,166,0.18);
  grid-column: 1 / -1;
}

.svc-banner h3{
  color: #fff;
  margin: 0 0 8px;
  font-size: 26px;
  font-weight: 900;
}

.svc-banner p{
  color: rgba(255,255,255,0.95);
  margin: 0;
  max-width: 740px;
  line-height: 1.7;
}

.svc-banner-btn{
  display: inline-block;
  white-space: nowrap;
  padding: 14px 22px;
  border-radius: 999px;
  background: #fff;
  color: #4f3d78;
  text-decoration: none;
  font-weight: 800;
  transition: 0.25s ease;
}

/* ===== ⭐ FINAL FIX (BOOK BUTTON CENTER) ===== */
.svc-card .book-btn,
.svc-card-featured .book-btn{
  display: block;
  width: fit-content;
  margin: 18px auto 0;
}

@media (max-width: 1100px){
  .svc-grid-3{
    grid-template-columns: 1fr;
  }

  .svc-grid-2{
    grid-template-columns: 1fr;
  }
}

@media (max-width: 900px){
  .svc-banner{
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 768px){
  .svc-hero{
    padding: 95px 0 80px;
  }

  .svc-hero h1{
    font-size: 42px;
  }

  .svc-hero p{
    font-size: 16px;
  }

  .svc-tab{
    font-size: 17px;
    padding: 14px 10px;
  }

  .svc-section-title{
    font-size: 28px;
  }
}

/* ===== FIX: BOOK BUTTON CENTER & BIG ===== */
.book-center{
  text-align: center;
  margin-top: 30px;
}

.book-center a{
  padding: 12px 28px;
  font-size: 16px;
  border-radius: 30px;
  background: #7158a6;
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  display: inline-block;
}

/* ===== FIX: GRID OVERFLOW ISSUE ===== */
.svc-grid-2{
  display: grid;
  grid-template-columns: 1.3fr 1fr;
  gap: 22px;
  margin-bottom: 24px;
  align-items: start; /* IMPORTANT FIX */
}

/* ===== FIX: STACK FULL WIDTH ===== */
.svc-grid-2 > .svc-mini-card:last-child{
  grid-column: 1 / -1;
}

</style>

<section class="services-page">

  <section class="svc-hero">
    <div class="container svc-hero-inner">
      <h1>Our Grooming Services</h1>
      <p>Premium care for your furry family members — because they deserve the best.</p>
    </div>
  </section>

  <div class="svc-wrap">
    <div class="container">

      <div class="svc-toggle">
        <div class="svc-indicator"></div>
        <button class="svc-tab active" data-target="dogsPanel">Dogs</button>
        <button class="svc-tab" data-target="catsPanel">Cats</button>
      </div>

      <!-- DOGS PANEL -->
      <div class="svc-panel active" id="dogsPanel">
        <h2 class="svc-section-title">Dogs</h2>

        <div class="svc-grid-3">
          <div class="svc-card">
            <h3>Essentials Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Fragnance</li>
             
            </ul>
            
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹750</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹950</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹1200</b></div>
            </div>
           
          </div>

          <div class="svc-card svc-card-featured">
            <h3>Classic Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Ear Cleaning</li>
              <li>Nail Clipping and Grinding</li>
              <li>Hair Cut</li>
              <li>Sanitary Trimming </li>
            </ul>
           
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹1000</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹1200</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹1650</b></div>
            </div>
         
          </div>

          <div class="svc-card">
            <h3>AB’s Special Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Ear Cleaning</li>
              <li>Nail Clipping and Grinding</li>
              <li>Hair Cut</li>
              <li>Sanitary Trimming </li>
            </ul>
           
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹2000</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹2250</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹2700</b></div>
            </div>
           
          </div>
        </div>

        </div> <!-- svc-grid-2 -->

<div style="text-align:center; margin-top:20px;">
  <a href="book-appointment.php" style="
     padding:12px 26px;
     font-size:16px;
     border-radius:25px;
     background:#7158a6;
     color:#fff;
     text-decoration:none;
     font-weight:700;">
     Book Appointment
  </a>
</div>

<div class="svc-banner">
  <div>
    <h3>Pet Boarding Services</h3>
    <p>Going on a trip? Leave your furry friend in safe, loving hands. Comfortable stays with daily care and playtime.</p>
  </div>
  <a href="boarding.php" class="svc-banner-btn">Explore Boarding →</a>
</div>

</div> <!-- ✅ ONLY ONE closing for dogsPanel -->

        

     <div class="svc-panel" id="catsPanel">

  <h2 class="svc-section-title">Cats</h2>

  <!-- TOP 3 CARDS -->
  <div class="svc-grid-3">

    <div class="svc-card">
      <h3>Essentials Package</h3>
      <ul class="svc-list">
        <li>Bath</li>
        <li>Blow Dry</li>
      </ul>

      <div class="svc-price-table">
        <div class="svc-row"><span>Adult</span><b>₹800</b></div>
        <div class="svc-row"><span>Kitten</span><b>₹500</b></div>
      </div>
    </div>

    <div class="svc-card svc-card-featured">
      <h3>Classic Package</h3>
      <ul class="svc-list">
        <li>Bath</li>
        <li>Blow Dry</li>
        <li>Nail Clipping and Grinding</li>
        <li>Ear Cleaning</li>
        <li>Sanitary Trimming</li>
      </ul>

      <div class="svc-price-table">
        <div class="svc-row"><span>Adult</span><b>₹1000</b></div>
        <div class="svc-row"><span>Kitten</span><b>₹700</b></div>
      </div>
    </div>

    <div class="svc-card">
      <h3>AB’s Special Package</h3>
      <ul class="svc-list">
        <li>Bath</li>
        <li>Blow Dry</li>
        <li>Ear Cleaning</li>
        <li>Nail Clipping and Grinding</li>
        <li>Hair Cut</li>
        <li>Sanitary Trimming</li>
      </ul>

      <div class="svc-price-table">
        <div class="svc-row"><span>Adult</span><b>₹1650</b></div>
        <div class="svc-row"><span>Kitten</span><b>₹1000</b></div>
      </div>
    </div>

  </div>


  <!-- BOTTOM GRID -->
  <div class="svc-grid-2">

    <!-- LEFT -->
    <div class="svc-mini-card">
      <h3>Cat Add-On Services</h3>
      <ul>
        <li><span>Nail Clipping and Grinding</span><strong>₹200</strong></li>
        <li><span>Ear Cleaning</span><strong>₹150</strong></li>
        <li><span>De-shedding</span><strong>₹200</strong></li>
        <li><span>Sanitary Trim</span><strong>₹350</strong></li>
        <li><span>Medicated Bath</span><strong>₹300</strong></li>
      </ul>
    </div>

    <!-- RIGHT -->
    <div class="svc-mini-card">
      <h3>Only Haircut</h3>
      <ul>
        <li><span>Adult</span><strong>₹1000</strong></li>
        <li><span>Kitten</span><strong>₹600</strong></li>
      </ul>
      <p class="note">Cats above six months will be considered as adult</p>
    </div>

    <!-- FULL WIDTH -->
    <div class="svc-mini-card" style="grid-column: 1 / -1;">
      <h3>Puppies (Younger than 6 months)</h3>
      <ul>
        <li><span>Bath & Blow Dry</span><strong>₹350</strong></li>
        <li><span>Bath + Blow Dry + Ear Cleaning + Nail Clipping</span><strong>₹500</strong></li>
        <li><span>Hair Cut</span><strong>₹500</strong></li>
      </ul>
    </div>

  </div>


  <!-- BOOK BUTTON -->
  <div class="book-center">
    <a href="book-appointment.php">Book Appointment</a>
  </div>


  <!-- BANNER -->
  <div class="svc-banner">
    <div>
      <h3>Pet Boarding Services</h3>
      <p>Going on a trip? Leave your furry friend in safe, loving hands.</p>
    </div>
    <a href="boarding.php" class="svc-banner-btn">Explore Boarding →</a>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".svc-tab");
  const panels = document.querySelectorAll(".svc-panel");
  const indicator = document.querySelector(".svc-indicator");

  function activateTab(targetId, clickedTab) {
    tabs.forEach(tab => tab.classList.remove("active"));
    panels.forEach(panel => panel.classList.remove("active"));

    clickedTab.classList.add("active");
    const activePanel = document.getElementById(targetId);
    if (activePanel) {
      activePanel.classList.add("active");
    }

    if (indicator) {
      const tabRect = clickedTab.getBoundingClientRect();
      const parentRect = clickedTab.parentElement.getBoundingClientRect();
      indicator.style.width = tabRect.width + "px";
      indicator.style.left = (tabRect.left - parentRect.left) + "px";
    }
  }

  tabs.forEach(tab => {
    tab.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      activateTab(targetId, this);
    });
  });

  const activeTab = document.querySelector(".svc-tab.active") || tabs[0];
  if (activeTab) {
    activateTab(activeTab.getAttribute("data-target"), activeTab);
  }
});
</script>


<?php include "includes/footer.php"; ?>
<section class="services-page">

  <section class="svc-hero">
    <div class="container svc-hero-inner">
      <h1>Our Grooming Services</h1>
      <p>Premium care for your furry family members — because they deserve the best.</p>
    </div>
  </section>

  <div class="svc-wrap">
    <div class="container">

      <div class="svc-toggle">
        <div class="svc-indicator"></div>
        <button class="svc-tab active" data-target="dogsPanel">Dogs</button>
        <button class="svc-tab" data-target="catsPanel">Cats</button>
      </div>

      <!-- DOGS PANEL -->
      <div class="svc-panel active" id="dogsPanel">
        <h2 class="svc-section-title">Dogs</h2>

        <div class="svc-grid-3">
          <div class="svc-card">
            <h3>Essentials Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Fragnance</li>
             
            </ul>
            
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹750</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹950</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹1200</b></div>
            </div>
           
          </div>

          <div class="svc-card svc-card-featured">
            <h3>Classic Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Ear Cleaning</li>
              <li>Nail Clipping and Grinding</li>
              <li>Hair Cut</li>
              <li>Sanitary Trimming </li>
            </ul>
           
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹1000</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹1200</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹1650</b></div>
            </div>
         
          </div>

          <div class="svc-card">
            <h3>AB’s Special Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Ear Cleaning</li>
              <li>Nail Clipping and Grinding</li>
              <li>Hair Cut</li>
              <li>Sanitary Trimming </li>
            </ul>
           
            <div class="svc-price-table">
              <div class="svc-row"><span>Small Breed</span><b>₹2000</b></div>
              <div class="svc-row"><span>Medium Breed</span><b>₹2250</b></div>
              <div class="svc-row"><span>Large Breed</span><b>₹2700</b></div>
            </div>
           
          </div>
        </div>

        <div class="svc-grid-2">
          <div class="svc-mini-card">
            <h3>Add-On Services</h3>
            <ul>
              <li><span>Ear Cleaning</span><strong>₹150</strong></li>
              <li><span>Nail Clipping & Grinding</span><strong>₹200</strong></li>
              <li><span>Sanitary Trimming</span><strong>₹350</strong></li>
              <li><span>Teeth Brushing</span><strong>₹150</strong></li>
              <li><span>Face Trimming</span><strong>₹300</strong></li>
              <li><span>Medicated Bath</span><strong>₹350</strong></li>
              <li><span>Deshedding</span><strong>₹500</strong></li>
            </ul>
          </div>

          <div class="svc-stack">
            <div class="svc-mini-card">
              <h3>Haircut</h3>
              <ul>
                <li><span>Small Breed</span><strong>₹1000</strong></li>
                <li><span>Large Breed</span><strong>₹1200</strong></li>
                <li><span>Giant Breed</span><strong>₹2000</strong></li>
              </ul>
            </div>

            <div class="svc-mini-card">
              <h3>Puppies (Younger than 6 months)</h3>
              <ul>
                <li><span>Bath & Blow Dry</span><strong>₹350</strong></li>
                <li><span>Bath + Blow Dry + Ear Cleaning + Nail Clipping & Grinding</span><strong>₹500</strong></li>
                <li><span>Hair Cut</span><strong>₹500</strong></li>
              </ul>
            </div>
          </div>
        </div>

         
          </div>
      <div style="text-align:center; margin-top:20px;">
  <a href="book-appointment.php" style="
    padding:8px 16px;
    font-size:14px;
    border-radius:20px;
    background:#7158a6;
    color:#fff;
    text-decoration:none;
    font-weight:600;
  ">
    Book Now
  </a>
</div>

        <div class="svc-banner">
          <div>
            <h3>Pet Boarding Services</h3>
            <p>Going on a trip? Leave your furry friend in safe, loving hands. Comfortable stays with daily care and playtime.</p>
          </div>
          <a href="boarding.php" class="svc-banner-btn">Explore Boarding →</a>
        </div>
      </div>

       

      <!-- CATS PANEL -->
      <div class="svc-panel" id="catsPanel">
        <h2 class="svc-section-title">Cats</h2>

        <div class="svc-grid-3">
          <div class="svc-card">
            <h3>Essentials Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
            </ul>
          
            <div class="svc-price-table">
              <div class="svc-row"><span>Adult</span><b>₹800</b></div>
              <div class="svc-row"><span>Kitten</span><b>₹500</b></div>
            </div>
          
          </div>

          <div class="svc-card svc-card-featured">
            <h3>Classic Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Nail Clipping and Grinding</li>
              <li>Ear Cleaning</li>
              <li>Sanitary Trimming</li>
            </ul>
            
            <div class="svc-price-table">
              <div class="svc-row"><span>Adult</span><b>₹1000</b></div>
              <div class="svc-row"><span>Kitten</span><b>₹700</b></div>
            </div>
            
          </div>

          <div class="svc-card">
            <h3>AB’s Special Package</h3>
            <ul class="svc-list">
              <li>Bath</li>
              <li>Blow Dry</li>
              <li>Ear Cleaning</li>
              <li>Nail Clipping and Grinding</li>
              <li>Hair Cut</li>
              <li>Sanitary Trimming</li>
            </ul>
           
            <div class="svc-price-table">
              <div class="svc-row"><span>Adult</span><b>₹1650</b></div>
              <div class="svc-row"><span>Kitten</span><b>₹1000</b></div>
            </div>
            
          </div>
        </div>

        <div class="svc-grid-2">
          <div class="svc-mini-card">
            <h3>Cat Add-On Services</h3>
            <ul>
              <li><span>Nail Clipping and Grinding</span><strong>₹200</strong></li>
              <li><span>Ear Cleaning</span><strong>₹150</strong></li>
              <li><span>De-shedding</span><strong>₹200</strong></li>
              <li><span>Sanitary Trim</span><strong>₹350</strong></li>
              <li><span>Medicated Bath</span><strong>₹300</strong></li>
            </ul>
          </div>

          <div class="svc-stack">
            <div class="svc-mini-card">
              <h3>Only Haircut</h3>
              <ul>
                <li><span>Adult</span><strong>₹1000</strong></li>
                <li><span>Kitten</span><strong>₹600</strong></li>
                <p class="note">Cats above six months will consider as an adult</p>
</ul>
</div>
  </div>

   </div>
      <div style="text-align:center; margin-top:20px;">
  <a href="book-appointment.php" style="
    padding:8px 16px;
    font-size:14px;
    border-radius:20px;
    background:#7158a6;
    color:#fff;
    text-decoration:none;
    font-weight:600;
  ">
    Book Now
  </a>
</div>

  <div class="svc-banner">
    <div>
      <h3>Pet Boarding Services</h3>
      <p>Going on a trip? Leave your furry friend in safe, loving hands. Comfortable stays with daily care and playtime.</p>
    </div>
    <a href="boarding.php" class="svc-banner-btn">Explore Boarding →</a>
  </div>
</div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".svc-tab");
  const panels = document.querySelectorAll(".svc-panel");
  const indicator = document.querySelector(".svc-indicator");

  function activateTab(targetId, clickedTab) {
    tabs.forEach(tab => tab.classList.remove("active"));
    panels.forEach(panel => panel.classList.remove("active"));

    clickedTab.classList.add("active");
    const activePanel = document.getElementById(targetId);
    if (activePanel) {
      activePanel.classList.add("active");
    }

    if (indicator) {
      const tabRect = clickedTab.getBoundingClientRect();
      const parentRect = clickedTab.parentElement.getBoundingClientRect();
      indicator.style.width = tabRect.width + "px";
      indicator.style.left = (tabRect.left - parentRect.left) + "px";
    }
  }

  tabs.forEach(tab => {
    tab.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      activateTab(targetId, this);
    });
  });

  const activeTab = document.querySelector(".svc-tab.active") || tabs[0];
  if (activeTab) {
    activateTab(activeTab.getAttribute("data-target"), activeTab);
  }
});
</script>


<?php include "includes/footer.php"; ?>