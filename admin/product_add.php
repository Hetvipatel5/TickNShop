<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = $_POST['name'];
$brand = $_POST['brand'];
$category = $_POST['category'];
$price = $_POST['price'];
$description = $_POST['description'];


// Handle image upload
$image = null;
if (!empty($_FILES['image']['name'])) {
$target_dir = "../uploads/";
if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
$image = basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
}


$stmt = $conn->prepare("INSERT INTO products (name, brand, category, price, image, description) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $name, $brand, $category, $price, $image, $description);
$stmt->execute();
header('Location: admin_products.php');
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<h2>Add Product</h2>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
<div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label>Brand</label><input type="text" name="brand" class="form-control"></div>
<div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control"></div>
<div class="mb-3"><label>Price</label><input type="number" name="price" step="0.01" class="form-control" required></div>
<div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
<div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
<button class="btn btn-success">Save</button>
<a href="admin_products.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>