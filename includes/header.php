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
    /* Floating Social Icons */
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
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    
    .floating-social a:hover {
      transform: translateY(-3px) scale(1.05);
    }
    
    .floating-social .whatsapp { background: #25D366; }
    .floating-social .instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2126); }
    .floating-social .call { background: #7158a6; }
    .floating-social .logo-btn { background: #fff; border: 2px solid #7158a6; }
    .floating-social .logo-btn img { width: 24px; height: 24px; object-fit: contain; }
    
    /* Mobile Menu Toggle */
    .menu-toggle {
      display: block;
      font-size: 26px;
      padding: 8px 12px;
      cursor: pointer;
      background: none;
      border: none;
      color: #24163a;
    }
    
    @media (max-width: 767px) {
      .floating-social {
        bottom: 16px;
        right: 16px;
        gap: 8px;
      }
      
      .floating-social a {
        width: 44px;
        height: 44px;
        font-size: 18px;
      }
    }
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
  <div class="container nav-inner">
    
    <a class="brand" href="index.php">
      <img src="assets/images/logo.png" alt="Logo" class="logo">
      <span>AB Pet Grooming</span>
    </a>

    <button class="menu-toggle" onclick="toggleMenu()" aria-label="Menu">
      <i class="fas fa-bars"></i>
    </button>

    <nav class="nav" id="mainNav">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
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
  const icon = document.querySelector('.menu-toggle i');
  nav.classList.toggle('active');
  if(nav.classList.contains('active')) {
    icon.classList.remove('fa-bars');
    icon.classList.add('fa-times');
  } else {
    icon.classList.remove('fa-times');
    icon.classList.add('fa-bars');
  }
}

// Close menu when clicking a link
document.querySelectorAll('.nav a').forEach(link => {
  link.addEventListener('click', () => {
    document.getElementById('mainNav').classList.remove('active');
    document.querySelector('.menu-toggle i').classList.remove('fa-times');
    document.querySelector('.menu-toggle i').classList.add('fa-bars');
  });
});
</script>