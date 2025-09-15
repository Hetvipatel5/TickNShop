<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <h2>Manage Orders</h2>
  <table class="table table-bordered table-hover bg-white shadow">
    <thead class="table-dark">
      <tr>
        <th>Order ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($o = $orders->fetch_assoc()): ?>
        <tr>
          <td><?= $o['order_id'] ?></td>
          <td><?= htmlspecialchars($o['fullname']) ?></td>
          <td><?= htmlspecialchars($o['email']) ?></td>
          <td><span class="badge bg-<?= $o['status']=='pending'?'warning':($o['status']=='delivered'?'success':'danger') ?>"><?= ucfirst($o['status']) ?></span></td>
          <td><?= $o['created_at'] ?></td>
          <td><a href="order_view.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-info">View</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="index.php" class="btn btn-secondary">Back</a>
</body>
</html>
