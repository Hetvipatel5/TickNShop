<?php
session_start();
include_once __DIR__ . '/db.php';

// Validate & fetch product
if (!isset($_GET['id'])) {
    echo "<h2 style='color:red; text-align:center;'>Invalid request.</h2>";
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "<h2 style='color:red; text-align:center;'>Product not found!</h2>";
    exit;
}
$product = $result->fetch_assoc();

// Check wishlist
$inWish = false;
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $wq = $conn->prepare("SELECT 1 FROM wishlist WHERE user_id=? AND product_id=? LIMIT 1");
    $wq->bind_param("ii", $user_id, $id);
    $wq->execute();
    $wr = $wq->get_result();
    $inWish = ($wr && $wr->num_rows > 0);
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $rating = intval($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');
    if ($rating >= 1 && $rating <= 5 && !empty($review)) {
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, review, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $id, $user_id, $rating, $review);
        $stmt->execute();
        $_SESSION['flash'] = "Thank you for your review!";
        header("Location: product.php?id=".$id);
        exit;
    }
}

// Get rating summary from product_reviews
$ratingQuery = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM product_reviews WHERE product_id=?");
$ratingQuery->bind_param("i", $id);
$ratingQuery->execute();
$ratingData = $ratingQuery->get_result()->fetch_assoc();
$avgRating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 1) : 0;
$totalReviews = $ratingData['total_reviews'];

// Fetch reviews with username
$reviews = [];
$reviewsQuery = $conn->prepare("
    SELECT r.*, u.username 
    FROM product_reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ? 
    ORDER BY r.created_at DESC
");
$reviewsQuery->bind_param("i", $id);
$reviewsQuery->execute();
$res = $reviewsQuery->get_result();
while ($row = $res->fetch_assoc()) $reviews[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($product['name']); ?> | TickNShop</title>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<style>
/* Product layout */
.product-detail-container { max-width: 900px; margin: 30px auto; display: flex; flex-direction: column; gap: 20px; }
.product-detail { display: flex; gap: 30px; }
.product-image img { max-width: 350px; border-radius: 12px; }
.details h2 { margin: 0; }
.price { font-size: 22px; font-weight: bold; color: var(--bright-gold); }
.buttons { display: flex; gap: 10px; margin-top: 10px; }

/* Reviews */
.review-section { margin-top: 30px; }
.review-summary { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
.review-summary .avg-stars span { color: gold; font-size: 18px; }
.review-list { display: flex; flex-direction: column; gap: 15px; }
.review { background: #1A1A1A; padding: 12px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
.review-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; }
.stars { display: flex; gap: 2px; }
.stars .star { font-size: 18px; color: #ccc; }
.stars .star.filled { color: gold; }
.review-text { margin: 5px 0; line-height: 1.4; }
.review-date { color: #aaa; font-size: 12px; }

/* Review Form */
.review-form { margin-top: 15px; }
.star-rating { display: flex; gap: 5px; cursor: pointer; font-size: 24px; margin-bottom: 8px; }
.star-rating .star { color: #ccc; transition: color 0.3s; }
.star-rating .star.selected { color: gold; }
.review-form textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ddd; }
.review-form button { background-color: var(--gold); color: black; padding: 8px 15px; border: none; border-radius: 8px; cursor: pointer; margin-top: 5px; transition: background-color 0.3s ease; }
.review-form button:hover { background-color: var(--bright-gold); }

/* Toast */
.toast { position: fixed; bottom: 20px; right: 20px; background: #333; color: #fff; padding: 10px 15px; border-radius: 5px; opacity: 1; transition: opacity 0.5s; }
</style>
</head>
<body>

<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>

<div class="product-detail-container">
    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="details">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="price">₹<?php echo number_format((float)$product['price'], 2); ?></p>

            <?php if ($totalReviews > 0): ?>
            <div class="review-summary">
                <div class="avg-stars">
                    <?php for ($i=1; $i<=5; $i++): ?>
                        <span><?php echo $i <= $avgRating ? "★" : "☆"; ?></span>
                    <?php endfor; ?>
                </div>
                <span>(<?php echo $avgRating; ?> / <?php echo $totalReviews; ?> reviews)</span>
            </div>
            <?php endif; ?>

            <p class="description"><?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : "No description available."; ?></p>

            <div class="buttons">
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <button type="submit" class="buy">Add to Cart</button>
                </form>

                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" class="buy">Buy Now</button>
                </form>

                <form action="wishlist_toggle.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    <button type="submit" class="buy wishlist-btn <?php echo $inWish ? 'active' : ''; ?>">
                        <span class="heart"><?php echo $inWish ? '♥' : '♡'; ?></span>
                        <?php echo $inWish ? 'Wishlist' : 'Add to Wishlist'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- REVIEW SECTION -->
    <div class="review-section">
        <?php if (!empty($reviews)): ?>
            <h3>Customer Reviews</h3>
            <div class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <div class="review-header">
                            <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                            <div class="stars">
                                <?php for ($i=1; $i<=5; $i++): ?>
                                    <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                        <small class="review-date"><?php echo date("d M Y", strtotime($review['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No reviews yet. Be the first to review!</p>
        <?php endif; ?>

       
