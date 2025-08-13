<?php
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
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>

<!-- PRODUCT DETAIL -->
<main class="product-detail">
    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
         alt="<?php echo htmlspecialchars($product['name']); ?>">
    <div class="details">
        <h2 style="color: #D4AF37;"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="price"><?php echo htmlspecialchars($product['price']); ?></p>
        <p class="description">
            <?php echo !empty($product['description']) 
                ? htmlspecialchars($product['description']) 
                : "No description available."; ?>
        </p>
        <div class="buttons">
            <button class="buy">Add to Cart</button>
            <button class="wishlist">♡ Wishlist</button>
        </div>
    </div>
</main>

</body>
</html>
