<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TickNShop | Premium Watches</title>
    <link rel="stylesheet" href="style.css">
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
            <a href="wishlist.php" title="Login"><span>❤</span></a>
            <a href="login.php" title="Login"><span>👤</span></a>
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
                <li>Dial Colour</li>
                <li>Strap Material</li>
            </ul>
        </aside>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo strip_tags($row['name']); ?>">
                        <h4><?php echo $row['name']; ?></h4>
                    </a>
                    <p class="price"><?php echo $row['price']; ?></p>
                    <div class="buttons">
                        <button class="buy">Add to Cart</button>
                        <button class="wishlist">♡ Wishlist</button>
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
</body>
</html>
        