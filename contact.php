<?php include 'includes/header.php'; ?> <!-- ✅ keep your header sticky -->
<?php
if (isset($_SESSION['contact_msg'])) {
    $msg = $_SESSION['contact_msg'];
    $type = $_SESSION['contact_msg_type'] ?? 'info';
    echo "<div class='flash-msg {$type}'>$msg</div>";
    unset($_SESSION['contact_msg']);
}
?>

<!-- ✅ Back to Home Button -->
        <a href="index.php" class="home-btn">⬅ Back to Home</a>
<section class="contact-section">
  <h2>Contact Us</h2>
  <p>We’d love to hear from you! Fill out the form below or reach us via email.</p>

  <form action="send_contact.php" method="POST" class="contact-form">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <input type="text" name="subject" placeholder="Subject">
    <textarea name="message" rows="4" placeholder="Your Message" required></textarea>
    <button type="submit" class="btn">Send Message</button>
  </form>

  <div class="contact-info">
    <p><strong>Email:</strong> support@ticknshop.com</p>
    <p><strong>Phone:</strong> +91 98765 43210</p>
  </div>
</section>
<style>
/* ===== Contact Page Styles ===== */
.contact-section {
  max-width: 800px;
  margin: 50px auto;
  padding: 20px;
  background: #1A1A1A;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.3);
  color: white;
  font-family: Arial, sans-serif;
}

.contact-section h2 {
  text-align: center;
  color: #FFD700;
  margin-bottom: 10px;
}

.contact-section p {
  text-align: center;
  margin-bottom: 20px;
  color: #ddd;
}

.contact-section .contact-form input,
.contact-section .contact-form textarea {
  width: 100%;
  margin: 8px 0;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #444;
  background: #000;
  color: white;
  font-size: 1rem;
}

.contact-section .contact-form textarea {
  resize: vertical;
}

.contact-section .contact-form button.btn {
  background: #FFD700;
  color: black;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 8px;
  font-weight: bold;
  display: inline-block;
  transition: background 0.3s;
}

.contact-section .contact-form button.btn:hover {
  background: #D4AF37;
}

.contact-section .contact-info {
  margin-top: 30px;
  text-align: center;
  color: #ccc;
}

.contact-section .contact-info p {
  margin: 5px 0;
}

/* Optional: Back to Home Button */
.home-btn {
  display: inline-block;
  margin: 20px auto;
  padding: 8px 12px;
  background: #FFD700;
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
}

.home-btn:hover {
  background: #D4AF37;
}
.flash-msg {
  text-align: center;
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: bold;
}

.flash-msg.success {
  background: #28a745;
  color: white;
}

.flash-msg.error {
  background: #dc3545;
  color: white;
}


<?php include 'includes/footer.php'; ?>
