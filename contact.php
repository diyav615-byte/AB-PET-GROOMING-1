<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/contact.css">

<section class="contact-page">

<div class="container">

<h1 class="contact-title">Contact Us</h1>

<!-- ===== CARDS ===== -->
<div class="contact-grid">

  <div class="contact-card">
    <h3>Call Us</h3>
    <p class="highlight">+91 8828719786</p>
    <span>Mon – Sun, 10:30 AM – 7 PM</span>
  </div>

  <div class="contact-card">
    <h3>Email Us</h3>
    <p class="highlight">abpetgroomingstudio@gmail.com</p>
    <span>We respond within 24 hours</span>
  </div>

  <div class="contact-card">
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

</div>
</section>

<style>
/* ===== PAGE BACKGROUND ===== */
.contact-page{
  background: linear-gradient(135deg, #f6f2ff, #ffffff);
  padding: 50px 0;
}

/* ===== TITLE ===== */
.contact-title{
  text-align:center;
  font-size:48px;
  font-weight:900;
  color:#2b154d;
  margin-bottom:40px;
}

/* ===== CARDS ===== */
.contact-grid{
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:25px;
  max-width:1100px;
  margin:0 auto 50px;
}

.contact-card{
  background:#fff;
  border-radius:20px;
  padding:30px 20px;
  text-align:center;
  box-shadow:0 12px 35px rgba(0,0,0,0.08);
}

.contact-card h3{
  color:#2b154d;
  margin-bottom:10px;
}

.contact-card .highlight{
  color:#7158a6;
  font-weight:700;
  word-break: break-word;
}

.contact-card span{
  display:block;
  margin-top:6px;
  color:#777;
  font-size:14px;
}

/* ===== FORM SECTION ===== */
.contact-main{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:30px;
  max-width:1100px;
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
  padding:12px;
  border:none;
  border-radius:10px;
  margin-bottom:12px;
}

.review-box button{
  background:#fff;
  color:#7158a6;
  border:none;
  padding:12px 20px;
  border-radius:25px;
  font-weight:700;
  cursor:pointer;
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
  padding:12px;
  border:none;
  border-radius:10px;
  margin-bottom:12px;
}

.form-box button{
  background:#fff;
  color:#7158a6;
  border:none;
  padding:12px 20px;
  border-radius:25px;
  font-weight:700;
  cursor:pointer;
}

/* ===== LOCATION ===== */
.location-box{
  margin-top:60px;
  background:#fff;
  padding:40px;
  border-radius:25px;
  max-width:1100px;
  margin-left:auto;
  margin-right:auto;
  box-shadow:0 12px 35px rgba(0,0,0,0.08);
}

.location-box h2{
  margin-bottom:10px;
}

.location-box p{
  color:#7158a6;
  font-weight:600;
  margin-bottom:20px;
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
    </style>

<script>

// ===== CONTACT FORM VALIDATION =====
<script>
function validateContact(form) {

  let name = form.querySelector('input[name="name"]').value.trim();
  let email = form.querySelector('input[name="email"]').value.trim();
  let phone = form.querySelector('input[name="phone"]').value.trim();
  let subject = form.querySelector('input[name="subject"]').value.trim();
  let message = form.querySelector('textarea').value.trim();

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
</script>

<script>
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