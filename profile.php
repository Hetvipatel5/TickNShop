<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user data
$user_query = $conn->prepare("SELECT fullname, email, username, phone, address FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

// ✅ Update profile
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];

    $update_query = $conn->prepare("UPDATE users SET fullname=?, phone=?, address=? WHERE id=?");
    $update_query->bind_param("sssi", $fullname, $phone, $address, $user_id);
    $update_query->execute();

    header("Location: profile.php?updated=1");
    exit();
}

// ✅ Cancel order (only if status = pending)
if (isset($_GET['cancel_order'])) {
    $cancel_order_id = intval($_GET['cancel_order']);
    $cancel_query = $conn->prepare("UPDATE orders SET status='cancelled' WHERE order_id=? AND user_id=? AND status='pending'");
    $cancel_query->bind_param("ii", $cancel_order_id, $user_id);
    $cancel_query->execute();

    header("Location: profile.php?order_cancelled=1");
    exit();
}

// ✅ Fetch orders
$orders_query = $conn->prepare("
    SELECT o.order_id, o.payment_method, o.status, o.created_at
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.order_id DESC
");
$orders_query->bind_param("i", $user_id);
$orders_query->execute();
$orders_result = $orders_query->get_result();

$orders = [];
while ($order = $orders_result->fetch_assoc()) {
    // Fetch items for each order
    $items_query = $conn->prepare("
        SELECT oi.quantity, oi.price, p.name, p.image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $items_query->bind_param("i", $order['order_id']);
    $items_query->execute();
    $items = $items_query->get_result()->fetch_all(MYSQLI_ASSOC);
    $order['items'] = $items;

    // Calculate total from items
    $total = 0;
    foreach ($items as $it) {
        $total += $it['price'] * $it['quantity'];
    }
    $order['total_amount'] = $total;

    $orders[] = $order;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - TickNShop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: var(--charcoal); color: var(--white); font-family: Arial, sans-serif; }
        .profile-container { max-width: 900px; margin: 30px auto; padding: 15px; }
        .card { background: var(--white); color: black; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0px 3px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-primary { background: var(--gold); color: black; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; }
        .btn-danger { background: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .order-card { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; padding: 15px; background: #f8f8f8; }
        .order-header { font-weight: bold; margin-bottom: 10px; }
        .order-products { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
        .product-item { display: flex; gap: 10px; border: 1px solid #ddd; border-radius: 6px; padding: 10px; background: white; width: 45%; }
        .product-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .order-footer { margin-top: 10px; display: flex; justify-content: space-between; align-items: center; }
        .status.pending { color: orange; font-weight: bold; }
        .status.cancelled { color: red; font-weight: bold; }
        .status.delivered { color: green; font-weight: bold; }
    </style>
</head>
<body>
<div class="profile-container">
    <!-- Back to Home -->
    <div style="margin-bottom: 20px;">
        <a href="index.php" class="btn-primary" style="text-decoration: none;">🏠 Back to Home</a>
    </div>
<div class="profile-container">
    <!-- Profile Section -->
    <div class="card">
        <h2>👤 My Profile</h2>
        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="form-control">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            <button type="submit" name="update_profile" class="btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- Orders Section -->
    <div class="card">
        <h2>📦 My Orders</h2>
        <?php if (empty($orders)): ?>
            <p>No orders yet.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        Order #<?= $order['order_id'] ?> |
                        <?= ucfirst($order['payment_method']) ?> |
                        <?= $order['total_amount'] ?> INR |
                        <?= $order['created_at'] ?>
                    </div>
                    <div class="order-products">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="product-item">
                                <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <div>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                    Qty: <?= $item['quantity'] ?> | Price: <?= $item['price'] ?> INR
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-footer">
                        <span class="status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        <?php if ($order['status'] === 'pending'): ?>
                            <a href="?cancel_order=<?= $order['order_id'] ?>" class="btn-danger">Cancel</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
