<?php
session_start();
include 'db.php';

// Validate & fetch product
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<h2 style='color:red; text-align:center;'>Product not found!</h2>";
        exit;
    }
} else {
    echo "<h2 style='color:red; text-align:center;'>Invalid request.</h2>";
    exit;
}

// Check if product is in wishlist
$inWish = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $wq = $conn->prepare("SELECT 1 FROM wishlist WHERE user_id=? AND product_id=? LIMIT 1");
    $wq->bind_param("ii", $user_id, $id);
    $wq->execute();
    $wr = $wq->get_result();
    if ($wr && $wr->num_rows > 0) {
        $inWish = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> | TickNShop</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

<!-- HEADER -->
<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>

<!-- PRODUCT DETAIL -->
<div class="product-detail-container">
    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="details">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>
            <p class="description">
                <?php echo !empty($product['description']) 
                    ? htmlspecialchars($product['description']) 
                    : "No description available."; ?>
            </p>

            <div class="buttons">
                <!-- Add to Cart -->
                <form action="add_to_cart.php" method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <button type="submit" class="buy">Add to Cart</button>
                </form>

                <!-- Buy Now -->
                <form action="add_to_cart.php" method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" class="buy">Buy Now</button>
                </form>

                <!-- Wishlist toggle -->
                <form method="post" action="wishlist_toggle.php" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <button type="submit" 
                            class="buy wishlist-btn <?php echo $inWish ? 'active' : ''; ?>">
                        <span class="heart"><?php echo $inWish ? '♥' : '♡'; ?></span>
                        <?php echo $inWish ? 'Wishlist' : 'Add to Wishlist'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
