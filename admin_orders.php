<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';

if (isset($_POST['update_status'])) {
    $id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Orders</title>
  <style>
    body{background:#000;color:#fff;font-family:Arial;padding:20px;}
    table{width:100%;border-collapse:collapse;background:#111;}
    th,td{border:1px solid #D4AF37;padding:10px;text-align:center;}
    th{background:#222;color:#FFD700;}
    .btn{padding:5px 10px;background:#D4AF37;color:#000;border:none;border-radius:6px;cursor:pointer;}
    .btn:hover{background:#FFD700;}
  </style>
</head>
<body>
  <h2 style="color:#FFD700;">Manage Orders</h2>
  <table>
    <tr>
      <th>ID</th><th>User</th><th>Total</th><th>Status</th><th>Action</th>
    </tr>
    <?php while($row = $orders->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id']; ?></td>
      <td><?= $row['user_id']; ?></td>
      <td>$<?= $row['total_price']; ?></td>
      <td><?= $row['status']; ?></td>
      <td>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
          <select name="status">
            <option value="Pending" <?= $row['status']=="Pending"?"selected":""; ?>>Pending</option>
            <option value="Completed" <?= $row['status']=="Completed"?"selected":""; ?>>Completed</option>
          </select>
          <button type="submit" name="update_status" class="btn">Update</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
