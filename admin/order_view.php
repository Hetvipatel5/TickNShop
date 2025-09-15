<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_logged_in'])) {
header('Location: admin_login.php');
exit();
}


$id = intval($_GET['id']);
$order = $conn->query("SELECT * FROM orders WHERE order_id=$id")->fetch_assoc();
if (!$order) {
die("Order not found");
}
$order_items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=$id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<h2>Order #<?= $order['order_id'] ?> Details</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($order['fullname']) ?><br>
<strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
<strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?><br>
<strong>Status:</strong> <?= $order['status'] ?><br>
<strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>


<h4>Items</h4>
<table class="table table-bordered bg-white shadow">
<thead><tr><th>Product</th><th>Quantity</th><th>Price</th></tr></thead>
<tbody>
<?php while($item = $order_items->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($item['name']) ?></td>
<td><?= $item['quantity'] ?></td>
<td><?= $item['price'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>


<a href="order_update.php?id=<?= $order['order_id'] ?>&status=delivered" class="btn btn-success">Mark Delivered</a>
<a href="order_update.php?id=<?= $order['order_id'] ?>&status=cancelled" class="btn btn-danger">Cancel Order</a>
<a href="admin_orders.php" class="btn btn-secondary">Back</a>
</body>
</html>