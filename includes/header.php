<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AB Pet Grooming</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Floating Social Icons - Responsive */
    .floating-social {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      z-index: 1000;
    }
    
    .floating-social a {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
      transition: all 0.3s ease;
    }
    
    .floating-social a:hover {
      transform: scale(1.1);
    }
    
    .floating-social .whatsapp { background: #25D366; }
    .floating-social .instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2126); }
    .floating-social .call { background: #7158a6; }
    .floating-social .logo-btn { background: #fff; border: 3px solid #7158a6; }
    .floating-social .logo-btn img { width: 28px; height: 28px; object-fit: contain; }
    
    /* Mobile Menu Toggle */
    .menu-toggle {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      width: 44px;
      height: 44px;
      cursor: pointer;
      background: #f5f5f5;
      border: none;
      border-radius: 10px;
      color: #24163a;
      transition: all 0.2s;
    }
    
    .menu-toggle:hover {
      background: #eee;
    }
    
    /* Tablet+ hide toggle */
    @media (min-width: 768px) {
      .menu-toggle {
        display: none;
      }
    }
    
    /* Mobile floating icons - smaller */
    @media (max-width: 480px) {
      .floating-social {
        bottom: 14px;
        right: 14px;
        gap: 8px;
      }
      
      .floating-social a {
        width: 44px;
        height: 44px;
        font-size: 18px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.2);
      }
      
      .floating-social .logo-btn img {
        width: 22px;
        height: 22px;
      }
    }
  </style>
</head>
<body>

<!-- Floating Social Icons -->
<div class="floating-social">
  <a href="tel:+918828719786" class="call" title="Call Us">
    <i class="fas fa-phone"></i>
  </a>
  <a href="https://wa.me/918828719786" target="_blank" class="whatsapp" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="https://instagram.com/abrar_shaikhsk__" target="_blank" class="instagram" title="Instagram">
    <i class="fab fa-instagram"></i>
  </a>
  <a href="index.php" class="logo-btn" title="Home">
    <img src="assets/images/logo.png" alt="Logo">
  </a>
</div>

<header class="site-header">
  <div class="container nav-inner">
    
    <a class="brand" href="index.php">
      <img src="assets/images/logo.png" alt="AB Pet Grooming" class="logo">
      <span>AB Pet Grooming</span>
    </a>

    <button class="menu-toggle" onclick="toggleMenu()" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </button>

    <nav class="nav" id="mainNav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="services.php">Services</a>
      <a href="boarding.php">Boarding</a>
      <a href="petstore.php">Pet Store</a>
      <a href="contact.php">Contact</a>
    </nav>

    <div class="nav-buttons">
      <a href="book-appointment.php" class="btn-pill btn-solid">Book Now</a>
    </div>

  </div>
</header>

<script>
function toggleMenu() {
  const nav = document.getElementById('mainNav');
  const btn = document.querySelector('.menu-toggle i');
  nav.classList.toggle('active');
  
  if (nav.classList.contains('active')) {
    btn.classList.remove('fa-bars');
    btn.classList.add('fa-times');
  } else {
    btn.classList.remove('fa-times');
    btn.classList.add('fa-bars');
  }
}

// Close menu when clicking a link
document.querySelectorAll('.nav a').forEach(function(link) {
  link.addEventListener('click', function() {
    const nav = document.getElementById('mainNav');
    const btn = document.querySelector('.menu-toggle i');
    if (window.innerWidth < 768) {
      nav.classList.remove('active');
      btn.classList.remove('fa-times');
      btn.classList.add('fa-bars');
    }
  });
});
</script>