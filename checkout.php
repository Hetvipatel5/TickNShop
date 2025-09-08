<?php
session_start();
include_once __DIR__ . '/db.php'; // ✅ use shared DB connection

$session_id = session_id();
$user_id    = $_SESSION['user_id'] ?? null;

// Fetch cart items
if ($user_id) {
    $sql = $conn->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $sql->bind_param("i", $user_id);
} else {
    $sql = $conn->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
    $sql->bind_param("s", $session_id);
}
$sql->execute();
$cart_items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    die("<h2 style='color:yellow;text-align:center;'>Your cart is empty. <a href='index.php' style='color:#FFD700;'>Continue Shopping</a></h2>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - TickNShop</title>
    <style>
        body {
            background: #000000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 0.8fr;
            gap: 20px;
            max-width: 1000px;
            width: 100%;
        }
        .card {
            background: #FFFFFF;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
            padding: 20px;
        }
        h2 {
            color: #D4AF37;
            margin-bottom: 15px;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 5px;
        }
        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }
        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        textarea { resize: none; height: 70px; }
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-summary th, .order-summary td {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .order-summary img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }
        .qty-input {
            width: 50px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #aaa;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
        }
        .btn {
            background: #D4AF37;
            color: black;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #FFD700;
        }
        @media(max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <!-- Billing & Shipping Form -->
    <div class="card">
        <h2>Billing & Shipping</h2>
        <form action="process_checkout.php" method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Address</label>
            <textarea name="address" required></textarea>

          <label>Payment Method</label>
<input type="hidden" name="payment_method" value="cod">
<p style="margin:10px 0; font-weight:bold;">Cash on Delivery</p>


            <button type="submit" class="btn">🛒 Place Order</button>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="card order-summary">
        <h2>Order Summary</h2>
        <table id="orderTable">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): 
                $lineTotal = $item['price'] * $item['quantity'];
                $total += $lineTotal;
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" data-price="<?= $item['price'] ?>"></td>
                    <td class="line-total">₹<?= number_format($lineTotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Total</td>
                <td id="grandTotal"><strong>₹<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
    </div>
</div>

<script>
    // JS to update totals live
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            let price = parseFloat(this.dataset.price);
            let qty = parseInt(this.value) || 1;
            let row = this.closest('tr');
            let lineTotalCell = row.querySelector('.line-total');

            // Update line total
            let newLineTotal = price * qty;
            lineTotalCell.innerText = "₹" + newLineTotal.toFixed(2);

            // Recalculate grand total
            let grandTotal = 0;
            document.querySelectorAll('.line-total').forEach(cell => {
                grandTotal += parseFloat(cell.innerText.replace('₹',''));
            });
            document.getElementById('grandTotal').innerHTML = "<strong>₹" + grandTotal.toFixed(2) + "</strong>";
        });
    });
</script>

</body>
</html>
