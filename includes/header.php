<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>AB Pet Grooming</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Mobile Hamburger */
    .menu-toggle {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 44px;
      height: 44px;
      background: #f0f0f5;
      border: none;
      border-radius: 12px;
      font-size: 22px;
      color: #24163a;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .menu-toggle:active {
      background: #e5e5ee;
      transform: scale(0.95);
    }
    
    .menu-toggle i {
      transition: transform 0.3s ease;
    }
    
    .menu-toggle.active i {
      transform: rotate(90deg);
    }
    
    /* Floating Social */
    .floating-social {
      position: fixed;
      bottom: 16px;
      right: 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      z-index: 9998;
    }
    
    .floating-social a {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    
    .floating-social a:active {
      transform: scale(0.9);
    }
    
    .floating-social .whatsapp { background: #25D366; }
    .floating-social .instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2126); }
    .floating-social .call { background: #7158a6; }
    .floating-social .logo-btn { background: #fff; border: 2px solid #7158a6; }
    .floating-social .logo-btn img { width: 24px; height: 24px; }
  </style>
</head>
<body>

<!-- Floating Social Icons -->
<div class="floating-social">
  <a href="tel:+918828719786" class="call" title="Call"><i class="fas fa-phone"></i></a>
  <a href="https://wa.me/918828719786" target="_blank" class="whatsapp" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
  <a href="https://instagram.com/abrar_shaikhsk__" target="_blank" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
  <a href="index.php" class="logo-btn" title="Home"><img src="assets/images/logo.png" alt="Logo"></a>
</div>

<header class="site-header">
  <div class="nav-inner">
    <a class="brand" href="index.php">
      <img src="assets/images/logo.png" alt="AB" class="logo">
    </a>

    <!-- Hamburger Menu Button -->
    <button class="menu-toggle" onclick="toggleMenu()" aria-label="Toggle Menu">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navigation Menu -->
    <nav class="nav" id="mainNav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="services.php">Services</a>
      <a href="boarding.php">Boarding</a>
      <a href="petstore.php">Pet Store</a>
      <a href="contact.php">Contact</a>
      <a href="book-appointment.php" class="btn-book">Book Now</a>
    </nav>
  </div>
</header>

<script>
function toggleMenu() {
  var nav = document.getElementById('mainNav');
  var btn = document.querySelector('.menu-toggle');
  var icon = btn.querySelector('i');
  
  // Toggle menu
  nav.classList.toggle('active');
  btn.classList.toggle('active');
  
  // Toggle icon between bars and times
  if (nav.classList.contains('active')) {
    icon.classList.remove('fa-bars');
    icon.classList.add('fa-times');
  } else {
    icon.classList.remove('fa-times');
    icon.classList.add('fa-bars');
  }
}

// Close menu when clicking a link
document.querySelectorAll('.nav a').forEach(function(link) {
  link.addEventListener('click', function() {
    var nav = document.getElementById('mainNav');
    var btn = document.querySelector('.menu-toggle');
    var icon = btn.querySelector('i');
    
    // Close on mobile
    if (window.innerWidth < 768) {
      nav.classList.remove('active');
      btn.classList.remove('active');
      icon.classList.remove('fa-times');
      icon.classList.add('fa-bars');
    }
  });
});

// Close menu when clicking outside
document.addEventListener('click', function(e) {
  var nav = document.getElementById('mainNav');
  var btn = document.querySelector('.menu-toggle');
  var icon = btn.querySelector('i');
  
  if (!nav.contains(e.target) && !btn.contains(e.target) && nav.classList.contains('active')) {
    nav.classList.remove('active');
    btn.classList.remove('active');
    icon.classList.remove('fa-times');
    icon.classList.add('fa-bars');
  }
});
</script>