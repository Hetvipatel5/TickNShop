<?php
session_start();
include 'db.php';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// --- Update quantity ---
if (isset($_POST['update_quantity'])) {
    $id = (int)$_POST['id'];
    $qty = max(1, (int)$_POST['quantity']);

    $sql = "UPDATE cart SET quantity=? WHERE id=? AND (session_id=? OR user_id=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $qty, $id, $session_id, $user_id);
    $stmt->execute();

    header("Location: cart.php");
    exit;
}

// --- Remove item ---
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];

    $sql = "DELETE FROM cart WHERE id=? AND (session_id=? OR user_id=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $id, $session_id, $user_id);
    $stmt->execute();

    header("Location: cart.php");
    exit;
}

// --- Clear cart ---
if (isset($_GET['clear'])) {
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id=?");
        $stmt->bind_param("s", $session_id);
    }
    $stmt->execute();

    header("Location: cart.php");
    exit;
}

// --- Fetch cart items ---
$sql = "SELECT c.id, c.quantity, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.session_id=? OR c.user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $session_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

$cart_items = [];
$total_items = 0;
$total_amount = 0;

while ($row = $res->fetch_assoc()) {
    $cart_items[] = $row;
    $total_items += $row['quantity'];
    $total_amount += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | TickNShop</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php">Cart</a>
    </nav>
    <div class="icons">
        🛒 <?php echo $total_items; ?>
    </div>
</header>

<section class="cart-page">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p style="text-align:center; color:#FFD700;">Your cart is empty.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="" width="60">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form method="post" action="cart.php" class="qty-form">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                        </td>
                        <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-btn">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <p><strong>Total Items:</strong> <?php echo $total_items; ?></p>
            <p><strong>Total Amount:</strong> ₹<?php echo number_format($total_amount, 2); ?></p>
        </div>

        <div class="cart-actions">
            <a href="cart.php?clear=1" class="remove-btn">Clear Cart</a>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</section>

</body>
</html>
