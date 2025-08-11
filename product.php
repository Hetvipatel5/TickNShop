<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo strip_tags($product['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><?php echo $product['name']; ?></h1>
</header>

<main class="product-detail">
    <img src="<?php echo $product['image']; ?>" alt="<?php echo strip_tags($product['name']); ?>">
    <div class="details">
        <h2><?php echo $product['name']; ?></h2>
        <p class="price"><?php echo $product['price']; ?></p>
        <button class="buy">Add to Cart</button>
        <button class="wishlist">♡ Wishlist</button>
    </div>
</main>
</body>
</html>
