

<?php include "includes/header.php"; ?>

<link rel="stylesheet" href="assets/css/contact.css">

<section class="contact-page">
  <div class="container">

    <h1 class="contact-title">Contact Us</h1>

    <p class="contact-sub">
      Have questions? We'd love to hear from you. Get in touch with AB Pet Grooming today.
    </p>

    <?php if (isset($_GET['message']) && $_GET['message'] == 'success'): ?>
      <div class="review-msg success">Your message has been sent successfully.</div>
    <?php endif; ?>

    <!-- CONTACT INFO -->
    <div class="contact-cards">

      <div class="contact-card">
        
        <h3>Call Us</h3>
        <p class="highlight">+91 8828719786</p>
        <span>Mon - Sun, 10:30 AM - 7:00 PM</span>
      </div>

      <div class="contact-card">
       
        <h3>Email Us</h3>
        <p class="highlight">abrarshaikhsk10@gmail.com</p>
        <span>We will respond within 24 hours</span>
      </div>

      <div class="contact-card">
        
        <h3>Visit Us</h3>
        <p class="highlight">Kelkar Wadi Road,Chembur East, Mumbai
        </p>
        <span>Mumbai, India</span>
      </div>

    </div>

    <!-- FORM + MAP -->
    <div class="contact-grid">

      <!-- CONTACT FORM -->
      <div class="contact-form">
        <h2>Send us a Message</h2>

        <form action="submit_contact.php" method="POST">
          <label>Name *</label>
          <input type="text" name="name" placeholder="Your name" required>

          <label>Email *</label>
          <input type="email" name="email" placeholder="your@email.com" required>

          <label>Phone</label>
          <input type="text" name="phone" placeholder="+91">

          <label>Subject *</label>
          <input type="text" name="subject" placeholder="How can we help?" required>

          <label>Message *</label>
          <textarea name="message" rows="5" placeholder="Tell us more about your inquiry..." required></textarea>

          <button type="submit" name="send_message" class="contact-btn">Send Message</button>
        </form>
      </div>

      <!-- LOCATION + MAP -->
      <div class="contact-location">

        <h2>Our Location</h2>

        <div class="address-box">
          <p>
            Shop No 1, Amar Chawl,<br>
            Kelkar Wadi Road,<br>
            Ghatla Village, Chembur East,<br>
            Mumbai – 400071
          </p>
        </div>

        <div class="map-box">
          <iframe
            src="https://www.google.com/maps?q=Shop+No+1+Amar+Chawl+Kelkar+Wadi+Road+Ghatla+Village+Chembur+East+Mumbai+400071&output=embed"
            width="100%"
            height="300"
            style="border:0;border-radius:14px;"
            loading="lazy"
            allowfullscreen="">
          </iframe>
        </div>

        <div class="quick-chat">
          <h3>Quick Chat</h3>

          <a href="https://wa.me/918828719786" class="whatsapp-btn" target="_blank">
            Chat on WhatsApp
          </a>

          <p>Get instant responses to your questions</p>
        </div>

      </div>
    </div>

    <!-- REVIEW FORM -->
      <section class="reviews-submit-section">
      <div class="container">
        <div class="review-form-card">
          <h2>Share Your Experience</h2>
          <p>We would love to hear about your pet’s grooming or boarding experience.</p>

          <?php if (isset($_GET['review']) && $_GET['review'] == 'success'): ?>
            <div class="review-msg success">Thank you! Your review has been submitted for approval.</div>
          <?php endif; ?>

          <?php if (isset($_GET['review']) && $_GET['review'] == 'error'): ?>
            <div class="review-msg error">Something went wrong. Please try again.</div>
          <?php endif; ?>

          <form action="submit_review.php" method="POST" class="review-form">

            <div class="review-row">
              <div class="review-group">
                <label>Your Name *</label>
                <input type="text" name="customer_name" placeholder="Enter your name" required>
              </div>

           

              <div class="review-group">
                <label>Rating *</label>
                <select name="rating" required>
                  <option value="">Select rating</option>
                  <option value="5">5 Star</option>
                  <option value="4">4 Star</option>
                  <option value="3">3 Star</option>
                  <option value="2">2 Star</option>
                  <option value="1">1 Star</option>
                </select>
              </div>
            </div>

            <div class="review-group">
              <label>Your Review *</label>
              <textarea name="review_text" rows="5" placeholder="Write your experience here..." required></textarea>
            </div>

            <button type="submit" name="submit_review" class="review-submit-btn">Submit Review</button>
          </form>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <div class="faq-section">
      <h2>Frequently Asked Questions</h2>

      <div class="faq-grid">
        <div class="faq-item">
          <h4>How do I book grooming?</h4>
          <p>You can book grooming through our website, phone call, or WhatsApp.</p>
        </div>

        <div class="faq-item">
          <h4>Do you offer pet boarding?</h4>
          <p>Yes, we offer safe and hygienic boarding for dogs and cats.</p>
        </div>

        <div class="faq-item">
          <h4>Do you use safe grooming products?</h4>
          <p>Yes, we use high-quality pet-safe grooming products.</p>
        </div>

        <div class="faq-item">
          <h4>Where is your salon located?</h4>
          <p>We are located in Chembur East, Mumbai near Kelkar Wadi Road.</p>
        </div>
      </div>
    </div>

  </div>
</section>

<form action="submit_contact.php" method="POST" onsubmit="return validateContact()">

<input type="text" id="name" name="name" placeholder="Name" required><br><br>

<input type="email" id="email" name="email" placeholder="Email" required><br><br>

<input type="text" id="phone" name="phone" placeholder="Phone" required><br><br>

<input type="text" id="subject" name="subject" placeholder="Subject" required><br><br>

<textarea id="message" name="message" placeholder="Message" required></textarea><br><br>

<button type="submit">Send</button>

</form>

<script>
function validateContact() {

let name = document.getElementById("name").value.trim();
let email = document.getElementById("email").value.trim();
let phone = document.getElementById("phone").value.trim();
let subject = document.getElementById("subject").value.trim();
let message = document.getElementById("message").value.trim();

let nameRegex = /^[A-Za-z ]+$/;
let phoneRegex = /^[0-9]{10}$/;
let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (!nameRegex.test(name) || name.length > 20) {
alert("Name only letters max 20");
return false;
}

if (!emailRegex.test(email)) {
alert("Invalid email");
return false;
}

if (!phoneRegex.test(phone)) {
alert("Phone must be 10 digits");
return false;
}

if (subject.length > 40) {
alert("Subject max 40 characters");
return false;
}

let words = message.split(/\s+/);
if (words.length > 40) {
alert("Message max 40 words");
return false;
}

return true;
}
</script>

<?php include "includes/footer.php"; ?>
