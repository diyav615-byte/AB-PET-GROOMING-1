<?php
include 'config/db.php';
?>
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
  grid-template-columns: repeat(auto-fit,minmax(320px,1fr));
  gap: 24px;
  margin-bottom: 30px;
}

body, .services-page{
  overflow-x: hidden;
}

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

.svc-card{
  background: linear-gradient(135deg,#6d4bb5,#8d6ad9);
  border-radius: 24px;
  padding: 26px;
  border: 1px solid rgba(255,255,255,0.10);
  box-shadow: 0 16px 45px rgba(68, 35, 106, 0.18);
  height: 100%;
  color:#fff;
  transition:0.35s ease;
}

.svc-card:hover{
  transform:translateY(-6px);
  box-shadow:0 20px 50px rgba(68,35,106,0.28);
}

.svc-card h3{
  font-size: 22px;
  font-weight: 900;
  margin-bottom: 16px;
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
  color:rgba(255,255,255,0.92);
  font-weight:600;
  line-height:1.6;
}

.svc-price-table{
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 6px;
}

.svc-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  font-size:16px;
  color:rgba(255,255,255,0.92);
  font-weight:700;
}
.svc-row b{
  color: #fff;
  font-size: 22px;
}

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
}

@media (max-width:768px){

  .svc-hero h1{
    font-size:42px;
  }

  .svc-grid-3{
    grid-template-columns:1fr;
  }

  .svc-banner{
    flex-direction:column;
    align-items:flex-start;
  }

  
}
</style>

<section class="services-page">

  <!-- HERO -->
  <section class="svc-hero">
    <div class="container svc-hero-inner">
      <h1>Our Grooming Services</h1>
      <p>Premium care for your furry family members — because they deserve the best.</p>
    </div>
  </section>

  <div class="svc-wrap">
    <div class="container">

      <!-- TOGGLE -->
      <div class="svc-toggle">
        <div class="svc-indicator"></div>

        <button class="svc-tab active" data-target="dogsPanel">
          Dogs
        </button>

        <button class="svc-tab" data-target="catsPanel">
          Cats
        </button>
      </div>

      <!-- ================= DOGS ================= -->
      <div class="svc-panel active" id="dogsPanel">

        <h2 class="svc-section-title">Dogs</h2>

        <div class="svc-grid-3">

        <?php
        $dogs = mysqli_query($conn,"
        SELECT * FROM service_cards
        WHERE category='dog'
        ORDER BY id ASC
        ");

        while($card = mysqli_fetch_assoc($dogs)):
        ?>

        <div class="svc-card">

          <h3><?php echo $card['title']; ?></h3>

          <!-- ITEMS -->
          <ul class="svc-list">

          <?php
          $items = mysqli_query($conn,"
          SELECT * FROM service_card_items
          WHERE service_id=".$card['id']."
          AND type='item'
          ");

          while($i = mysqli_fetch_assoc($items)):
          ?>

          <li>
            <?php echo $i['name']; ?>

            <?php if($i['price']){ ?>
            - ₹<?php echo $i['price']; ?>
            <?php } ?>
          </li>

          <?php endwhile; ?>

          </ul>

          <!-- BREEDS -->
          <div class="svc-price-table">

          <?php
          $breeds = mysqli_query($conn,"
          SELECT * FROM service_card_items
          WHERE service_id=".$card['id']."
          AND type='breed'
          ");

          while($b = mysqli_fetch_assoc($breeds)):
          ?>

          <div class="svc-row">
            <span><?php echo $b['name']; ?></span>

            <b>
              <?php if($b['price']){ ?>
              ₹<?php echo $b['price']; ?>
              <?php } ?>
            </b>
          </div>

          <?php endwhile; ?>

          </div>

        </div>

        <?php endwhile; ?>

        </div>
      </div>

      <!-- ================= CATS ================= -->
      <div class="svc-panel" id="catsPanel">

        <h2 class="svc-section-title">Cats</h2>

        <div class="svc-grid-3">

        <?php
        $cats = mysqli_query($conn,"
        SELECT * FROM service_cards
        WHERE category='cat'
        ORDER BY id ASC
        ");

        while($card = mysqli_fetch_assoc($cats)):
        ?>

        <div class="svc-card">

          <h3><?php echo $card['title']; ?></h3>

          <!-- ITEMS -->
          <ul class="svc-list">

          <?php
          $items = mysqli_query($conn,"
          SELECT * FROM service_card_items
          WHERE service_id=".$card['id']."
          AND type='item'
          ");

          while($i = mysqli_fetch_assoc($items)):
          ?>

          <li>
            <?php echo $i['name']; ?>

            <?php if($i['price']){ ?>
            - ₹<?php echo $i['price']; ?>
            <?php } ?>
          </li>

          <?php endwhile; ?>

          </ul>

          <!-- BREEDS -->
          <div class="svc-price-table">

          <?php
          $breeds = mysqli_query($conn,"
          SELECT * FROM service_card_items
          WHERE service_id=".$card['id']."
          AND type='breed'
          ");

          while($b = mysqli_fetch_assoc($breeds)):
          ?>

          <div class="svc-row">
            <span><?php echo $b['name']; ?></span>

            <b>
              <?php if($b['price']){ ?>
              ₹<?php echo $b['price']; ?>
              <?php } ?>
            </b>
          </div>

          <?php endwhile; ?>

          </div>

        </div>

        <?php endwhile; ?>

        </div>
      </div>

      <!-- COMMON -->
      <div class="book-center">
        <a href="book-appointment.php" class="book-btn">
          Book Appointment
        </a>
      </div>

      <div class="svc-banner">

        <div>
          <h3>Pet Boarding Services</h3>

          <p>
            Going on a trip? Leave your furry friend
            in safe, loving hands.
          </p>
        </div>

        <a href="boarding.php" class="svc-banner-btn">
          Explore Boarding →
        </a>

      </div>

    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const tabs = document.querySelectorAll(".svc-tab");
  const panels = document.querySelectorAll(".svc-panel");
  const indicator = document.querySelector(".svc-indicator");

  function activateTab(targetId, clickedTab){

    tabs.forEach(tab => tab.classList.remove("active"));
    panels.forEach(panel => panel.classList.remove("active"));

    clickedTab.classList.add("active");

    document.getElementById(targetId)
    .classList.add("active");

    const tabRect = clickedTab.getBoundingClientRect();
    const parentRect = clickedTab.parentElement.getBoundingClientRect();

    indicator.style.width = tabRect.width + "px";

    indicator.style.left =
    (tabRect.left - parentRect.left) + "px";
  }

  tabs.forEach(tab => {

    tab.addEventListener("click", function () {

      activateTab(
        this.getAttribute("data-target"),
        this
      );

    });

  });

  activateTab("dogsPanel", tabs[0]);
});
</script>

<?php include "includes/footer.php"; ?>