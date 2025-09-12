<?php include 'includes/header.php'; ?>

<!-- Back to Home Button -->
<a href="index.php" class="home-btn">⬅ Back to Home</a>

<section class="faq-section">
  <h2>Frequently Asked Questions</h2>

  <div class="faq-item">
    <h3>How do I place an order?</h3>
    <p>Browse products, add them to your cart, and proceed to checkout.</p>
  </div>

  <div class="faq-item">
    <h3>What payment methods are available?</h3>
    <p>Currently, only Cash on Delivery (COD) is available.</p>
  </div>

  <div class="faq-item">
    <h3>How long will delivery take?</h3>
    <p>Usually 3-7 business days depending on your location.</p>
  </div>

  <div class="faq-item">
    <h3>What is your return policy?</h3>
    <p>
      You can return any product within 7 days of delivery if it is unused, in original packaging, and accompanied by a receipt. Some items may have specific conditions.
    </p>
  </div>

  <div class="faq-item">
    <h3>Are there any criteria for returns?</h3>
    <p>
      Products must be in original condition, unused, and with all tags/labels intact. Items damaged due to user mishandling are not eligible for return.
    </p>
  </div>

</section>
<script>
  const faqItems = document.querySelectorAll('.faq-section .faq-item');

  faqItems.forEach(item => {
    item.addEventListener('click', () => {
      const currentlyActive = document.querySelector('.faq-item.active');
      
      // Close the currently active item
      if (currentlyActive && currentlyActive !== item) {
        currentlyActive.classList.remove('active');
        currentlyActive.querySelector('p').style.display = 'none';
      }

      // Toggle the clicked item
      item.classList.toggle('active');
      const answer = item.querySelector('p');
      if (item.classList.contains('active')) {
        answer.style.display = 'block';
      } else {
        answer.style.display = 'none';
      }
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
<style>
/* ===== FAQ Page Styles ===== */
.faq-section {
  max-width: 900px;
  margin: 50px auto;
  padding: 20px;
  background: #1A1A1A;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.3);
  color: white;
  font-family: Arial, sans-serif;
}

.faq-section h2 {
  text-align: center;
  color: #FFD700;
  margin-bottom: 30px;
  font-size: 2rem;
}

.faq-section .faq-item {
  margin-bottom: 15px;
  padding: 15px 20px;
  background: #111;
  border-radius: 10px;
  cursor: pointer;
  transition: background 0.3s, transform 0.3s;
}

.faq-section .faq-item.active {
  background: #222;
}

.faq-section .faq-item h3 {
  color: #FFD700;
  margin-bottom: 0;
}

.faq-section .faq-item p {
  color: #ddd;
  line-height: 1.5;
  margin-top: 10px;
  display: none; /* Hidden by default, shown on click */
}

/* Back to Home Button */
.home-btn {
  display: inline-block;
  margin: 20px auto;
  padding: 8px 12px;
  background: #FFD700;
  color: black;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
}

.home-btn:hover {
  background: #D4AF37;
}
