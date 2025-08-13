<?php
session_start();
include 'db.php';
include 'message.php'; // ✅ Include the message function file

// ✅ Prevent undefined key warning
if (!isset($_SESSION['user_id'])) {
    // If not logged in, show a message or redirect to login page
    showMessage("error", "You must be logged in to view your wishlist.", "Login", "login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items
$sql = "SELECT p.* 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_id = $_SESSION['user_id'];

// Fetch wishlist items
$sql = "SELECT p.* 
        FROM wishlist w 
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
    </nav>
</header>

<main class="wishlist-page">
    <h2>Your Wishlist</h2>
    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <h4><?php echo $row['name']; ?></h4>
                    </a>
                    <p class="price"><?php echo $row['price']; ?></p>
                    <div class="buttons">
                        <button class="wishlist-btn" data-product-id="<?php echo $row['id']; ?>">❤️ Add to Wishlist</button>
                        <a href="wishlist_remove.php?id=<?php echo $row['id']; ?>" class="remove-btn">Remove</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#FFD700; text-align:center;">Your wishlist is empty.</p>
        <?php endif; ?>
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
