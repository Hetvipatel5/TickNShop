<?php
include 'db.php';
session_start();

// Restore session from cookies
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TickNShop | Premium Watches</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="logo">TickNShop</div>
        <nav class="navbar">
            <a href="#">All Watches</a>
            <a href="#">Men</a>
            <a href="#">Women</a>
            <a href="#">Smart</a>
            <a href="#">Brands</a>
            <a href="#">Offers</a>
            <a href="#">Corporate Sale</a>
        </nav>
        <div class="icons">
    <span>🔍</span>
    <a href="wishlist.php" title="Wishlist"><span>❤</span></a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Show logout if logged in -->
        <a href="logout.php" title="Logout"><span>🔓</span></a>
    <?php else: ?>
        <!-- Show login if not logged in -->
        <a href="login.php" title="Login"><span>👤</span></a>
    <?php endif; ?>

    <span>🛒</span>
</div>

        
    </header>

    <!-- BANNER -->
    <section class="banner">
        <div class="banner-text">
            <h1>SELECT FROM A CURATION</h1>
            <p>Of 40+ International Brands</p>
        </div>
    </section>


    <main class="main-content">
        <!-- SIDEBAR FILTERS -->
        <aside class="sidebar">
            <h3>Filters</h3>
            <ul>
                <li>Brands</li>
                <li>Type</li>
                <li>Gender</li>
                <li>Price</li>
                <li>Discount</li>
                <li>Top Rated</li>
                <li>Top Seller</li>
            </ul>
        </aside>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  // ✅ Check if product is already in wishlist (only if logged in)
                $is_in_wishlist = false;
                if (isset($_SESSION['user_id'])) {
                    $check_sql = "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bind_param("ii", $_SESSION['user_id'], $row['id']);
                    $check_stmt->execute();
                    $is_in_wishlist = $check_stmt->get_result()->num_rows > 0;
                    $check_stmt->close();
                }
                ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo strip_tags($row['name']); ?>">
                        <h4><?php echo $row['name']; ?></h4>
                    </a>
                    <p class="price"><?php echo $row['price']; ?></p>
                    <div class="buttons">
                    <button class="buy">Add to Cart</button>
                     <?php if ($is_in_wishlist): ?>
                    <button class="wishlist" disabled>❤️ In Wishlist</button>
                     <?php else: ?>
                     <a href="wishlist_add.php?id=<?php echo $row['id']; ?>"><button class="wishlist">♡ Wishlist</button></a>
                     <?php endif; ?>
</div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div>
</main>
<script>
document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-product-id');

        fetch('wishlist_add.php', {
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
                button.disabled = true;
            } else {
                alert(data.message || 'Failed to add to wishlist.');
            }
        });
    });
});
</script>
</body>
</html>
        