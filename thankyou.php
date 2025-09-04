<?php
include 'db.php';

$order_id = $_GET['order_id'] ?? 0;

// Fetch order details
$order = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$order->bind_param("i", $order_id);
$order->execute();
$orderData = $order->get_result()->fetch_assoc();

if (!$orderData) {
    die("<h2 style='color:white;text-align:center;'>Order not found!</h2>");
}

// Fetch ordered items
$items = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$items->bind_param("i", $order_id);
$items->execute();
$orderItems = $items->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Invoice - TickNShop</title>
    <style>
        body {
            background: #000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            display: flex;
            justify-content: center;
        }
        .invoice-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
            padding: 25px;
            max-width: 800px;
            width: 100%;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            color: #D4AF37;
            margin: 0;
        }
        .order-info, .customer-info {
            margin-bottom: 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
        }
        .order-info p, .customer-info p {
            margin: 3px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #f1f1f1;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }
        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            background: #D4AF37;
            color: black;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn:hover {
            background: #FFD700;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>🕒 TickNShop Invoice</h1>
            <p>Thank you for shopping with us!</p>
        </div>

        <div class="order-info">
            <p><strong>Order ID:</strong> #<?= htmlspecialchars($order_id) ?></p>
            <p><strong>Order Date:</strong> <?= date("d M Y, h:i A", strtotime($orderData['created_at'])) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($orderData['payment_method']) ?></p>
        </div>

        <div class="customer-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($orderData['fullname']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($orderData['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($orderData['phone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($orderData['address']) ?></p>
        </div>

        <h3>Order Items</h3>
        <table>
            <tr><th>Image</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
            <?php foreach ($orderItems as $item): 
                $lineTotal = $item['price'] * $item['quantity'];
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($item['image']) ?>" alt=""></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
                <td>₹<?= number_format($lineTotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p class="total">Grand Total: ₹<?= number_format($orderData['total_amount'], 2) ?></p>

        <div style="text-align:center;">
            <a href="index.php" class="btn">🏠 Continue Shopping</a>
            <a href="#" class="btn" onclick="window.print();return false;">🖨 Print Invoice</a>
        </div>
    </div>
</body>
</html>
