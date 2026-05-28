<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/contact.css">

<section class="contact-page">

<div class="contact-inner">

<h1 class="contact-title">Contact Us</h1>

<!-- ===== CARDS ===== -->
<div class="contact-grid">

  <div class="contact-card">
    <div class="contact-card-icon">
      <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
    </div>
    <h3>Call Us</h3>
    <p class="highlight">+91 8828719786</p>
    <span>Mon – Sun, 10:30 AM – 7 PM</span>
  </div>

  <div class="contact-card">
    <div class="contact-card-icon">
      <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
    </div>
    <h3>Email Us</h3>
    <p class="highlight">abpetgroomingstudio@gmail.com</p>
    <span>We respond within 24 hours</span>
  </div>

  <div class="contact-card">
    <div class="contact-card-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
    </div>
    <h3>Visit Us</h3>
    <p class="highlight">
      Kelkar Wadi Road,<br>
      Chembur East, Mumbai
    </p>
  </div>

</div>

<!-- ===== FORMS ===== -->
<div class="contact-main">

  <!-- LEFT = REVIEW -->
  <div class="review-box">
    <h2>Write a Review</h2>

   <form action="submit_review.php" method="POST" onsubmit="return validateReview()">
      <input type="text" name="name" placeholder="Your Name" required>

      <select name="rating" required>
        <option value="">Select Rating</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
      </select>

      <textarea name="message" placeholder="Write your review" required></textarea>

      <button type="submit">Submit Review</button>

    </form>
  </div>

  <!-- RIGHT = CONTACT -->
  <div class="form-box">
    <h2>Send Us Message</h2>

   <form action="submit_contact.php" method="POST" onsubmit="return validateContact()">

      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="text" name="subject" placeholder="Subject" required>

      <textarea name="message" placeholder="Your Message" required></textarea>

      <button type="submit">Send Message</button>

    </form>
  </div>

</div>

<!-- ===== LOCATION ===== -->
<div class="location-box">
  <h2>Your Location</h2>

  <p>
    Shop No 1, Amar Chawl,<br>
    Kelkar Wadi Road,<br>
    Chembur East, Mumbai
  </p>

  <div class="map">
    <iframe src="https://maps.google.com/maps?q=Chembur&t=&z=13&output=embed"></iframe>
  </div>
</div>

</section>

<style>
/* ===== PAGE INNER ===== */
.contact-inner{
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 20px;
}

/* ===== PAGE BACKGROUND ===== */
.contact-page{
  background: linear-gradient(135deg, #f6f2ff, #ffffff);
  padding: 60px 0;
}

/* ===== TITLE ===== */
.contact-title{
  text-align:center;
  font-size:48px;
  font-weight:900;
  color:#2b154d;
  margin-bottom:50px;
}

/* ===== CARDS ===== */
.contact-grid{
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:24px;
  width:100%;
  max-width:900px;
  margin:0 auto 60px;
}

.contact-card{
  background:#fff;
  border-radius:24px;
  padding:32px 24px;
  text-align:center;
  box-shadow:0 16px 48px rgba(113,88,166,0.10);
  border: 1px solid rgba(113,88,166,0.08);
  transition: all 0.3s ease;
}

.contact-card:hover{
  transform: translateY(-4px);
  box-shadow:0 20px 56px rgba(113,88,166,0.15);
}

.contact-card-icon{
  width:56px;
  height:56px;
  background: linear-gradient(135deg, #7158a6, #8e79bf);
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 16px;
}

.contact-card-icon svg{
  width:28px;
  height:28px;
  fill:#fff;
}

.contact-card h3{
  color:#2b154d;
  margin-bottom:12px;
  font-size:18px;
}

.contact-card .highlight{
  color:#7158a6;
  font-weight:700;
  word-break: break-word;
  font-size:16px;
  line-height:1.5;
}

.contact-card span{
  display:block;
  margin-top:10px;
  color:#777;
  font-size:14px;
}

/* ===== FORM SECTION ===== */
.contact-main{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:30px;
  max-width:900px;
  margin:0 auto;
}

/* ===== REVIEW BOX (LEFT) ===== */
.review-box{
  background: linear-gradient(135deg,#7158a6,#5b4588);
  padding:30px;
  border-radius:25px;
  color:#fff;
}

.review-box h2{
  margin-bottom:20px;
}

.review-box input,
.review-box textarea,
.review-box select{
  width:100%;
  padding:14px 16px;
  border:none;
  border-radius:12px;
  margin-bottom:14px;
  font-size:15px;
}

.review-box button{
  background:#fff;
  color:#7158a6;
  border:none;
  padding:14px 24px;
  border-radius:25px;
  font-weight:700;
  cursor:pointer;
  font-size:15px;
  transition: transform 0.2s ease;
}

.review-box button:hover{
  transform: translateY(-2px);
}

/* ===== CONTACT FORM (RIGHT) ===== */
.form-box{
  background: linear-gradient(135deg,#7158a6,#5b4588);
  padding:30px;
  border-radius:25px;
  color:#fff;
}

.form-box h2{
  margin-bottom:20px;
}

.form-box input,
.form-box textarea{
  width:100%;
  padding:14px 16px;
  border:none;
  border-radius:12px;
  margin-bottom:14px;
  font-size:15px;
}

.form-box button{
  background:#fff;
  color:#7158a6;
  border:none;
  padding:14px 24px;
  border-radius:25px;
  font-weight:700;
  cursor:pointer;
  font-size:15px;
  transition: transform 0.2s ease;
}

.form-box button:hover{
  transform: translateY(-2px);
}

/* ===== LOCATION ===== */
.location-box{
  margin-top:60px;
  background:#fff;
  padding:40px;
  border-radius:25px;
  max-width:900px;
  margin-left:auto;
  margin-right:auto;
  box-shadow:0 16px 48px rgba(113,88,166,0.08);
}

.location-box h2{
  margin-bottom:10px;
  text-align:center;
  color:#2b154d;
  font-size:28px;
}

.location-box p{
  color:#7158a6;
  font-weight:600;
  margin-bottom:20px;
  text-align:center;
}

/* ===== MAP ===== */
.map iframe{
  width:100%;
  height:300px;
  border:none;
  border-radius:15px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){

  .contact-grid{
    grid-template-columns:1fr;
  }

  .contact-main{
    grid-template-columns:1fr;
  }
}

@media(max-width:600px){
  .contact-title{
    font-size: 36px;
    margin-bottom: 30px;
  }

  .contact-card{
    padding: 24px 20px;
  }

  .review-box,
  .form-box{
    padding: 24px 20px;
  }

  .review-box h2,
  .form-box h2{
    font-size: 22px;
  }

  .location-box{
    padding: 28px 20px;
  }
}

@media(max-width:400px){
  .contact-title{
    font-size: 30px;
  }

  .contact-grid{
    gap: 16px;
  }
}
    </style>

<script>
function validateContact() {
  let name = document.querySelector('.form-box input[name="name"]').value.trim();
  let email = document.querySelector('.form-box input[name="email"]').value.trim();
  let phone = document.querySelector('.form-box input[name="phone"]').value.trim();
  let subject = document.querySelector('.form-box input[name="subject"]').value.trim();
  let message = document.querySelector('.form-box textarea').value.trim();

  if(!/^[A-Za-z\s]+$/.test(name)){
    alert("Name should contain only letters");
    return false;
  }

  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
    alert("Enter valid email");
    return false;
  }

  if(!/^[0-9]{10}$/.test(phone)){
    alert("Enter valid phone number");
    return false;
  }

  if(subject.split(" ").length > 20){
    alert("Subject max 20 words");
    return false;
  }

  if(message.split(" ").length > 40){
    alert("Message max 40 words");
    return false;
  }

  return true;
}

function validateReview(){
  let name = document.querySelector('.review-box input[name="name"]').value.trim();
  let review = document.querySelector('.review-box textarea').value.trim();

  if(!/^[A-Za-z\s]+$/.test(name)){
    alert("Name should contain only letters");
    return false;
  }

  if(name.length > 20){
    alert("Name max 20 characters");
    return false;
  }

  let words = review.split(" ").filter(w => w !== "");
  if(words.length > 40){
    alert("Max 40 words allowed");
    return false;
  }

  return true;
}
</script>

<?php include 'includes/footer.php'; ?>