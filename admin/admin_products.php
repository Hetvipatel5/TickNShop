<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <h2>Manage Products</h2>
  <a href="product_add.php" class="btn btn-primary mb-3">➕ Add Product</a>
  <table class="table table-bordered table-hover bg-white shadow">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><img src="../uploads/<?= htmlspecialchars($p['image']) ?>" width="60"></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td>₹<?= $p['price'] ?></td>
          <td><?= htmlspecialchars($p['category']) ?></td>
          <td>
            <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="index.php" class="btn btn-secondary">Back</a>
</body>
</html>
