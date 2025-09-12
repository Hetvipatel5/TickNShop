<!-- <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <h4>TickNShop</h4>
      <p>Premium watches curated for style & durability.</p>
    </div>
    <div>
      <h5>Help</h5>
      <a href="contact.php">Contact</a>
      <a href="faq.php">FAQ</a>
      <a href="returns.php">Returns</a>
    </div>
    <div>
      <h5>Follow</h5>
      <div class="socials">Instagram • Facebook • YouTube</div>
      <form id="newsletter" class="newsletter">
        <input type="email" placeholder="Your email" required>
        <button class="btn small">Subscribe</button>
      </form>
    </div>
  </div>
  <div class="container muted copyright">© <?php echo date('Y'); ?> TickNShop. All rights reserved.</div>
</footer>
</body>
</html> -->
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="toast" id="toast"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
  <script>
    const t = document.getElementById('toast');
    setTimeout(()=> t.style.opacity='0', 2000);
    setTimeout(()=> t.remove(), 2600);
  </script>
<?php endif; ?>

<script>
// JavaScript for filters, scroll memory, etc.
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filterForm');
  if (!form) return;

  // Auto-submit on checkbox change
  form.querySelectorAll('input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', () => {
      localStorage.setItem("scrollY", window.scrollY);
      form.submit();
    });
  });

  // Clear All
  document.getElementById('clearAll')?.addEventListener('click', () => {
    form.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
    localStorage.setItem("scrollY", window.scrollY);
    form.submit();
  });

  // Clear individual filter groups
  document.querySelectorAll('.clear-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const block = btn.closest('.filter-block');
      if (block) block.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
      localStorage.setItem("scrollY", window.scrollY);
      form.submit();
    });
  });

  // Toggle sections open/close with localStorage
  function makeToggle(titleSel, bodySel, key) {
    const title = document.querySelector(titleSel);
    const body = document.querySelector(bodySel);
    if (!title || !body) return;

    const saved = localStorage.getItem(key);
    if (saved === 'open')  body.style.display = 'block';
    if (saved === 'close') body.style.display = 'none';

    title.addEventListener('click', () => {
      const isOpen = body.style.display !== 'none';
      body.style.display = isOpen ? 'none' : 'block';
      title.textContent = title.textContent.replace(isOpen ? '▲' : '▼', isOpen ? '▼' : '▲');
      localStorage.setItem(key, isOpen ? 'close' : 'open');
    });
  }

  makeToggle('.toggle-brands',     '.brand-list',                                'open_brands');
  makeToggle('.toggle-categories', '[data-block="categories"] .filter-body',     'open_categories');
  makeToggle('.toggle-price',      '[data-block="priceRanges"] .filter-body',    'open_price');
  makeToggle('.toggle-highlights', '[data-block="badges"] .filter-body',         'open_highlights');

  // Restore scroll position
  const y = localStorage.getItem("scrollY");
  if (y !== null) {
    window.scrollTo(0, parseInt(y));
    localStorage.removeItem("scrollY");
  }
});
</script>

</body>
</html>
<footer class="tns-footer">
  <div class="footer-container">
    <!-- Brand Info -->
    <div class="footer-col">
      <h2>TickNShop</h2>
      <p>Premium watches curated for style & durability.</p>
      <p class="copyright">© 2025 TickNShop. All rights reserved.</p>
    </div>

    <!-- Quick Links -->
    <div class="footer-col">
      <h3>Help</h3>
      <ul>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="faq.php">FAQ</a></li>
        <li><a href="return.php">Returns</a></li>
      </ul>
    </div>

    <!-- Social + Subscribe -->
    <div class="footer-col">
      <h3>Follow</h3>
      <div class="social-icons">
        <a href="#"><img src="icons/facebook.svg" alt="Facebook"></a>
        <a href="#"><img src="icons/instagram.svg" alt="Instagram"></a>
        <a href="#"><img src="icons/youtube.svg" alt="YouTube"></a>
      </div>
      <form class="subscribe-form">
        <input type="email" placeholder="Your email" required>
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </div>
</footer>
