<?php
include 'auth.php'; require_login_or_redirect();
include 'db.php';

$user_id = (int)$_SESSION['user_id'];

$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN products p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$total = 0;
while ($row = $res->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart | TickNShop</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
  <style>
    .cart{max-width:900px;margin:30px auto;background:#111;padding:20px;border:2px solid #D4AF37;border-radius:12px;}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #333;text-align:center;color:#fff}
    th{color:#FFD700}
    img{width:70px;height:70px;object-fit:cover;border-radius:6px}
    .actions a, .actions button{margin:0 4px}
    .total{display:flex;justify-content:flex-end;gap:20px;align-items:center;margin-top:15px}
    .btn{background:#D4AF37;color:#000;padding:10px 14px;border:none;border-radius:6px;font-weight:bold;cursor:pointer}
    .btn:hover{background:#FFD700}
    input[type=number]{width:70px;padding:6px;border-radius:6px;border:1px solid #444;background:#000;color:#fff;text-align:center}
  </style>
</head>
<body>
  <div class="cart">
    <h2 style="text-align:center;color:#FFD700;">Your Cart</h2>

    <?php if (!$items): ?>
      <p style="text-align:center;color:#ccc;">Your cart is empty.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Image</th><th>Name</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Remove</th>
        </tr>
        <?php foreach ($items as $it): ?>
        <tr>
          <td><img src="<?php echo htmlspecialchars($it['image']); ?>"></td>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td>₹<?php echo number_format($it['price'], 2); ?></td>
          <td>
            <form action="cart_update.php" method="POST" style="display:inline-flex;gap:6px;justify-content:center;align-items:center">
              <input type="hidden" name="product_id" value="<?php echo (int)$it['id']; ?>">
              <input type="number" name="quantity" min="1" value="<?php echo (int)$it['quantity']; ?>">
              <button class="btn" type="submit">Update</button>
            </form>
          </td>
          <td>₹<?php echo number_format($it['subtotal'], 2); ?></td>
          <td class="actions">
            <a class="btn" href="cart_remove.php?product_id=<?php echo (int)$it['id']; ?>">Remove</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <div class="total">
        <h3 style="color:#FFD700;margin:0">Grand Total: ₹<?php echo number_format($total, 2); ?></h3>
        <a class="btn" href="checkout.php">Checkout</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
