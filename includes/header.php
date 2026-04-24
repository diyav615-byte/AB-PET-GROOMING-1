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
      bottom: 24px;
      right: 24px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      z-index: 1000;
    }
    
    .floating-social a {
      width: 52px;
      height: 52px;
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
    
    .floating-social a:hover {
      transform: translateY(-4px) scale(1.1);
    }
    
    .floating-social .whatsapp {
      background: #25D366;
    }
    
    .floating-social .instagram {
      background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2126);
    }
    
    .floating-social .call {
      background: #7158a6;
    }
    
    .floating-social .logo-btn {
      background: #fff;
      border: 3px solid #7158a6;
    }
    
    .floating-social .logo-btn img {
      width: 28px;
      height: 28px;
      object-fit: contain;
    }
    
    @media (max-width: 768px) {
      .floating-social {
        bottom: 16px;
        right: 16px;
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
  <img src="assets/images/logo.png" alt="Logo" class="logo">
  <span>AB Pet Grooming</span>
</a>

    <nav class="nav">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="services.php">Services</a>
      <a href="boarding.php">Boarding</a>
      <a href="petstore.php">Pet Store</a>
      <a href="contact.php">Contact</a>
    </nav>

    <div class="nav-buttons">
      <a href="book-appointment.php" class="btn-pill btn-solid">Book Appointment</a>
    </div>

  </div>
</header>