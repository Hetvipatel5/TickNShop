<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $desc = $_POST['description'];
    $sql = "INSERT INTO products (name, price, image, description) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdss",$name,$price,$image,$desc);
    $stmt->execute();
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Products</title>
  <style>
    body{background:#000;color:#fff;font-family:Arial;padding:20px;}
    table{width:100%;border-collapse:collapse;background:#111;margin-top:20px;}
    th,td{border:1px solid #D4AF37;padding:10px;text-align:center;}
    th{background:#222;color:#FFD700;}
    input,textarea{padding:8px;margin:5px;width:90%;border-radius:6px;}
    .btn{padding:8px 12px;background:#D4AF37;color:#000;border:none;border-radius:6px;cursor:pointer;}
    .btn:hover{background:#FFD700;}
  </style>
</head>
<body>
  <h2 style="color:#FFD700;">Add New Product</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br>
    <input type="text" name="image" placeholder="Image Path (e.g. images/p1.jpg)" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <button type="submit" class="btn">Add Product</button>
  </form>

  <h2 style="color:#FFD700;margin-top:30px;">All Products</h2>
  <table>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th></tr>
    <?php while($row = $products->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td>$<?= $row['price']; ?></td>
        <td><img src="<?= $row['image']; ?>" width="80"></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
