<?php
session_start();
include_once __DIR__ . '/db.php';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// --- Flash message helper ---
function set_flash($msg, $type = 'info') {
    $_SESSION['cart_msg'] = $msg;
    $_SESSION['cart_msg_type'] = $type;
}

// --- Handle POST: update quantity ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $qty = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    if ($id > 0) {
        if ($user_id !== null) {
            $sql = "UPDATE cart SET quantity=? WHERE id=? AND (user_id=? OR session_id=?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiis", $qty, $id, $user_id, $session_id);
        } else {
            $sql = "UPDATE cart SET quantity=? WHERE id=? AND session_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $qty, $id, $session_id);
        }

        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            set_flash('Quantity updated successfully.', 'success');
        } else {
            set_flash('Quantity not changed.', 'warning');
        }
        $stmt->close();
    } else {
        set_flash('Invalid cart item.', 'error');
    }

    header("Location: cart.php");
    exit;
}

// --- Handle GET: remove item ---
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if ($id > 0) {
        if ($user_id !== null) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE id=? AND (user_id=? OR session_id=?)");
            $stmt->bind_param("iis", $id, $user_id, $session_id);
        } else {
            $stmt = $conn->prepare("DELETE FROM cart WHERE id=? AND session_id=?");
            $stmt->bind_param("is", $id, $session_id);
        }
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            set_flash('Item removed from cart.', 'success');
        } else {
            set_flash('Failed to remove item.', 'error');
        }
        $stmt->close();
    } else {
        set_flash('Invalid remove request.', 'error');
    }
    header("Location: cart.php");
    exit;
}

// --- Handle GET: clear cart ---
if (isset($_GET['clear'])) {
    if ($user_id !== null) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id=?");
        $stmt->bind_param("s", $session_id);
    }
    $stmt->execute();
    set_flash('Your cart has been cleared.', 'success');
    $stmt->close();

    header("Location: cart.php");
    exit;
}

// --- Fetch cart items ---
if ($user_id !== null) {
    $sql = "SELECT c.id, c.quantity, p.id AS product_id, p.name, p.price, p.image
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id=? OR c.user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $session_id, $user_id);
} else {
    $sql = "SELECT c.id, c.quantity, p.id AS product_id, p.name, p.price, p.image
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$res = $stmt->get_result();

$cart_items = [];
$total_items = 0;
$total_amount = 0.0;

while ($row = $res->fetch_assoc()) {
    $cart_items[] = $row;
    $total_items += (int)$row['quantity'];
    $total_amount += (float)$row['price'] * (int)$row['quantity'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | TickNShop</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .flash {
            width: 80%;
            margin: 15px auto;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            animation: fadeOut 0.5s ease-in-out forwards;
            animation-delay: 3s;
        }
        .flash.success { background: #1b5e20; color: #fff; border: 1px solid #4caf50; }
        .flash.error { background: #b71c1c; color: #fff; border: 1px solid #f44336; }
        .flash.warning { background: #ff9800; color: #000; border: 1px solid #ffb300; }
        .flash.info { background: #333; color: #ffd700; border: 1px solid #ffd700; }
        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php" class="active">Cart</a>
    </nav>
    <div class="icons">
        🛒 <?php echo $total_items; ?>
    </div>
</header>

<section class="cart-page">
    <h2>Your Shopping Cart</h2>

    <!-- Flash Message -->
    <?php if (!empty($_SESSION['cart_msg'])): ?>
        <?php $type = $_SESSION['cart_msg_type'] ?? 'info'; ?>
        <div class="flash <?php echo htmlspecialchars($type); ?>">
            <?php echo htmlspecialchars($_SESSION['cart_msg']); ?>
        </div>
        <?php unset($_SESSION['cart_msg'], $_SESSION['cart_msg_type']); ?>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <p style="text-align:center; color:#FFD700;">Your cart is empty.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" width="60"></td>
                        <td><a href="product.php?id=<?php echo $item['product_id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form method="post" action="cart.php" class="qty-form">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo (int)$item['quantity']; ?>" min="1" step="1" style="width:70px;">
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                        </td>
                        <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>" onclick="return confirm('Remove this item?');" class="remove-btn">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="cart-summary">
                            <p><strong>Total Items:</strong> <?php echo $total_items; ?></p>
                            <p><strong>Total Amount:</strong> ₹<?php echo number_format($total_amount, 2); ?></p>
                        </div>
                        <div class="cart-actions">
                            <a href="cart.php?clear=1" onclick="return confirm('Clear your entire cart?');" class="remove-btn">Clear Cart</a>
                            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                            <a href="index.php" class="continue-btn">Continue Shopping</a>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</section>

<script>
// Auto-hide flash messages smoothly
document.addEventListener("DOMContentLoaded", () => {
    const flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(() => flash.style.display = "none", 3500);
    }
});
</script>

</body>
</html>
