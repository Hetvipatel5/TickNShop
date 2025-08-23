<?php
session_start();
include 'db.php';
include 'message.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    showMessage("error", "You must be logged in to view your wishlist.", "Login", "login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get wishlist products
$sql = "SELECT p.* FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Wishlist - TickNShop</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="wishlist-page">
    <h2>Your Wishlist</h2>
    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                             alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                    </a>
                    <p class="price">₹<?php echo number_format($row['price'], 2); ?></p>
                    <div class="buttons">
                        <!-- Add to Cart -->
                        <form action="add_to_cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                            <button type="submit" class="buy">Add to Cart</button>
                        </form>

                        <!-- Remove from Wishlist -->
                        <form action="wishlist_remove.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="buy remove-btn" style="background-color:#e74c3c;">
                                ❌ Remove
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#FFD700; text-align:center;">Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</main>
</body>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="toast" id="toast"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
  <script>
    const t = document.getElementById('toast');
    setTimeout(()=> t.style.opacity='0', 2000);
    setTimeout(()=> t.remove(), 2600);
  </script>
<?php endif; ?>

</html>
