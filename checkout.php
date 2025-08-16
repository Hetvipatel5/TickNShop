<?php
// checkout.php
include 'auth.php'; require_login_or_redirect();
include 'db.php';

$user_id = (int)$_SESSION['user_id'];

// fetch cart items
$sql = "SELECT c.product_id, c.quantity, p.price
        FROM cart c
        JOIN products p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$total = 0;
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
    $total += ($r['price'] * $r['quantity']);
}

if (!$items) {
    header("Location: cart.php");
    exit;
}

// create order
$sql = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// order items
$sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
foreach ($items as $it) {
    $stmt->bind_param("iiid", $order_id, $it['product_id'], $it['quantity'], $it['price']);
    $stmt->execute();
}

// clear cart
$sql = "DELETE FROM cart WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Placed | TickNShop</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
  <main style="max-width:700px;margin:50px auto;background:#1A1A1A;padding:24px;border:2px solid #D4AF37;border-radius:12px;color:#fff;text-align:center;">
    <h2 style="color:#FFD700;">Thank you! 🎉</h2>
    <p>Your order <strong>#<?php echo (int)$order_id; ?></strong> has been placed successfully.</p>
    <a href="index.php" class="btn" style="display:inline-block;margin-top:12px;background:#D4AF37;color:#000;padding:10px 16px;border-radius:6px;text-decoration:none;font-weight:bold;">Continue Shopping</a>
  </main>
</body>
</html>


images/titan_raga.jpg