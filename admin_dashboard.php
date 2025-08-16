<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db.php';

// Fetch quick stats
$total_orders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$pending_orders = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Pending'")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$total_sales = $conn->query("SELECT SUM(total_price) AS s FROM orders WHERE status='Completed'")->fetch_assoc()['s'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    body{background:#000;color:#fff;font-family:Arial;}
    .header{background:#111;padding:15px;display:flex;justify-content:space-between;align-items:center;border-bottom:2px solid #D4AF37;}
    .header h1{color:#FFD700;margin:0;}
    .header a{color:#fff;margin-left:15px;text-decoration:none;}
    .dashboard{display:grid;grid-template-columns:repeat(auto-fit, minmax(200px,1fr));gap:20px;padding:20px;}
    .card{background:#1a1a1a;border:2px solid #D4AF37;padding:20px;border-radius:10px;text-align:center;}
    .card h2{color:#FFD700;margin:0;}
    .nav{text-align:center;margin:20px;}
    .nav a{margin:5px;padding:10px 15px;background:#D4AF37;color:#000;border-radius:6px;text-decoration:none;font-weight:bold;}
    .nav a:hover{background:#FFD700;}
  </style>
</head>
<body>
  <div class="header">
    <h1>Admin Dashboard</h1>
    <div>
      <a href="admin_orders.php">Orders</a>
      <a href="admin_products.php">Products</a>
      <a href="admin_logout.php">Logout</a>
    </div>
  </div>

  <div class="dashboard">
    <div class="card"><h2><?php echo $total_orders; ?></h2><p>Total Orders</p></div>
    <div class="card"><h2><?php echo $pending_orders; ?></h2><p>Pending Orders</p></div>
    <div class="card"><h2><?php echo $total_products; ?></h2><p>Total Products</p></div>
    <div class="card"><h2>$<?php echo number_format($total_sales, 2); ?></h2><p>Total Sales</p></div>
  </div>

  <div class="nav">
    <a href="admin_orders.php">Manage Orders</a>
    <a href="admin_products.php">Manage Products</a>
  </div>
</body>
</html>
