<!-- Add to Wishlist button -->
<button class="wishlist-btn" data-product-id="123">❤️ Add to Wishlist</button>

<script>
  document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', () => {
      const productId = button.getAttribute('data-product-id');

      fetch('add_to_wishlist.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId: productId }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          button.textContent = '✔️ Added to Wishlist';
        } else {
          alert(data.message || 'Failed to add to wishlist.');
        }
      });
    });
  });
</script>
