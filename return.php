<?php include 'includes/header.php'; ?>


<!-- ✅ Back to Home Button -->
<a href="index.php" class="home-btn">⬅ Back to Home</a>

<section class="returns-section">
  <h2>Return & Refund Policy</h2>
  <p>You can return products within 7 days of delivery. Refunds are processed within 5-7 business days.</p>

  <!-- Flash message -->
  <?php
  if (isset($_SESSION['return_msg'])) {
      $msg = $_SESSION['return_msg'];
      $type = $_SESSION['return_msg_type'] ?? 'info';
      echo "<div class='flash-msg {$type}'>$msg</div>";
      unset($_SESSION['return_msg']);
  }
  ?>

  <form action="request_return.php" method="POST" class="return-form">
    <input type="text" name="order_id" placeholder="Order ID" required>
    <select name="reason" required>
      <option value="">Select Reason</option>
      <option value="defective">Defective Product</option>
      <option value="wrong-item">Wrong Item Delivered</option>
      <option value="other">Other</option>
    </select>
    <textarea name="comments" rows="4" placeholder="Additional details"></textarea>
    <button type="submit" class="btn">Submit Return Request</button>
  </form>
</section>

<!-- ===== CSS ===== -->
<style>
.returns-section {
  max-width: 800px;
  margin: 30px auto;
  padding: 25px;
  background: #1A1A1A;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.3);
  color: white;
  font-family: Arial, sans-serif;
}

.returns-section h2 {
  text-align: center;
  color: #FFD700;
  margin-bottom: 15px;
  font-size: 2rem;
}

.returns-section p {
  text-align: center;
  color: #ddd;
  margin-bottom: 25px;
  line-height: 1.6;
}

.returns-section .return-form input,
.returns-section .return-form select,
.returns-section .return-form textarea {
  width: 100%;
  margin: 10px 0;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #444;
  background: #000;
  color: white;
  font-size: 1rem;
  transition: border 0.3s, box-shadow 0.3s;
}

.returns-section .return-form input:invalid,
.returns-section .return-form select:invalid,
.returns-section .return-form textarea:invalid {
  border-color: #FF4C4C;
  box-shadow: 0 0 5px rgba(255, 76, 76, 0.7);
}

.returns-section .return-form textarea {
  resize: vertical;
  min-height: 80px;
}

.returns-section .return-form button.btn {
  background: #FFD700;
  color: black;
  border: none;
  padding: 12px 18px;
  cursor: pointer;
  border-radius: 8px;
  font-weight: bold;
  display: inline-block;
  margin-top: 10px;
  transition: background 0.3s, transform 0.2s;
}

.returns-section .return-form button.btn:hover {
  background: #D4AF37;
  transform: translateY(-2px);
}

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

/* Flash Message Styles */
.flash-msg {
  text-align: center;
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: bold;
  font-size: 1rem;
}

.flash-msg.success {
  background: #28a745;
  color: white;
}

.flash-msg.error {
  background: #dc3545;
  color: white;
}

/* Responsive */
@media (max-width: 600px) {
  .returns-section {
    padding: 15px;
    margin: 20px 10px;
  }

  .returns-section h2 {
    font-size: 1.6rem;
  }

  .returns-section .return-form input,
  .returns-section .return-form select,
  .returns-section .return-form textarea {
    padding: 10px;
    font-size: 0.95rem;
  }

  .returns-section .return-form button.btn {
    width: 100%;
    padding: 12px;
  }
}
</style>

<?php include 'includes/footer.php'; ?>
