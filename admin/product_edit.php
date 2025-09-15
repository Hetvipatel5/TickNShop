<?php
if (!isset($_SESSION['admin_logged_in'])) {
header('Location: admin_login.php');
exit();
}


$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) {
die("Product not found");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = $_POST['name'];
$brand = $_POST['brand'];
$category = $_POST['category'];
$price = $_POST['price'];
$description = $_POST['description'];


$image = $product['image'];
if (!empty($_FILES['image']['name'])) {
$target_dir = "../uploads/";
if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
$image = basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
}


$stmt = $conn->prepare("UPDATE products SET name=?, brand=?, category=?, price=?, image=?, description=? WHERE id=?");
$stmt->bind_param("ssssssi", $name, $brand, $category, $price, $image, $description, $id);
$stmt->execute();
header('Location: admin_products.php');
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<h2>Edit Product</h2>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
<div class="mb-3"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required></div>
<div class="mb-3"><label>Brand</label><input type="text" name="brand" value="<?= htmlspecialchars($product['brand']) ?>" class="form-control"></div>
<div class="mb-3"><label>Category</label><input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" class="form-control"></div>
<div class="mb-3"><label>Price</label><input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" class="form-control" required></div>
<div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"> <small>Current: <?= $product['image'] ?></small></div>
<div class="mb-3"><label>Description</label><textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea></div>
<button class="btn btn-primary">Update</button>
<a href="admin_products.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>