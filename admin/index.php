<?php
// admin/index.php - Admin Dashboard
session_start();
require_once '../db.php';

// ✅ Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// ✅ Fetch basic stats
$total_users = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$total_orders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$pending_orders = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <h1 class="text-center mb-4">Admin Dashboard</h1>
    <div class="row text-center mb-4">
<div class="row text-center mb-4">
  <div class="col-md-3">
    <a href="admin_users.php" class="text-decoration-none">
      <div class="card shadow p-3">
        👥 <strong><?= $total_users ?></strong><br>Users
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="admin_products.php" class="text-decoration-none">
      <div class="card shadow p-3">
        📦 <strong><?= $total_products ?></strong><br>Products
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="admin_orders.php" class="text-decoration-none">
      <div class="card shadow p-3">
        🛒 <strong><?= $total_orders ?></strong><br>Orders
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="admin_orders.php?status=pending" class="text-decoration-none">
      <div class="card shadow p-3">
        ⏳ <strong><?= $pending_orders ?></strong><br>Pending Orders
      </div>
    </a>
  </div>
</div>

    </div>
    <div class="d-flex justify-content-center gap-3">
      <a href="admin_products.php" class="btn btn-primary">Manage Products</a>
      <a href="admin_orders.php" class="btn btn-secondary">Manage Orders</a>
      <a href="admin_users.php" class="btn btn-info">Manage Users</a>
      <a href="admin_feedback.php" class="btn btn-warning">View Feedback</a>
      <a href="admin/logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
