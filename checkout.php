<?php 
session_start(); 
include 'db.php';

// Ensure cart exists
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$totalAmount = 0;
$totalItems = count($cart);
$totalQuantity = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout | TickNShop</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<h2 style="text-align:center;color:#FFD700;">Checkout</h2>

<div style="max-width:900px;margin:auto;background:#1A1A1A;color:#fff;padding:20px;border-radius:10px;">
    <h3>Order Summary</h3>
    <?php if ($totalItems > 0): ?>
        <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse;text-align:center;">
            <tr style="background:#FFD700;color:#000;">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $totalAmount += $subtotal;
                $totalQuantity += $item['quantity'];
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>₹<?php echo number_format($item['price'],2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($subtotal,2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3 style="text-align:right;margin-top:15px;">
            Items: <?php echo $totalItems; ?> | 
            Quantity: <?php echo $totalQuantity; ?> | 
            Total: <span style="color:#FFD700;">₹<?php echo number_format($totalAmount,2); ?></span>
        </h3>
    <?php else: ?>
        <p style="text-align:center;color:#FFD700;">Your cart is empty.</p>
    <?php endif; ?>
</div>

<?php if ($totalItems > 0): ?>
<form action="process_order.php" method="post" style="max-width:500px;margin:auto;color:#000;background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
    <h3>Billing Details</h3>
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="text" name="address" placeholder="Address" required><br><br>
    <input type="text" name="phone" placeholder="Phone" required><br><br>
    
    <h3>Payment Method</h3>
    <label><input type="radio" name="payment" value="COD" required> Cash on Delivery</label><br>
    <label><input type="radio" name="payment" value="UPI"> UPI</label><br>
    <label><input type="radio" name="payment" value="Card"> Credit/Debit Card</label><br><br>
    
    <!-- hidden fields to send order summary -->
    <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
    <input type="hidden" name="totalItems" value="<?php echo $totalItems; ?>">
    <input type="hidden" name="totalQuantity" value="<?php echo $totalQuantity; ?>">
    
    <button type="submit">Place Order</button>
</form>
<?php endif; ?>

</body>
</html>
