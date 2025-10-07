<?php
session_start();
include_once __DIR__ . '/db.php';

$category = $_GET['category'] ?? null;

if (!$category) {
    echo "<h2 style='color:red; text-align:center;'>Invalid request.</h2>";
    exit;
}

// $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
// $stmt->bind_param("s", $category);
$category = ucfirst(strtolower($_GET['category'] ?? ''));

$stmt = $conn->prepare("SELECT * FROM products WHERE LOWER(category) = LOWER(?)");
$stmt->bind_param("s", $category);

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= ucfirst($category) ?> - TickNShop</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background:#111; color:#fff; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
    .product-card { background:#1a1a1a; padding: 15px; border-radius: 10px; text-align:center; transition:0.3s; }
    .product-card:hover { transform: translateY(-5px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
    .product-card img { width: 100%; height: 200px; object-fit: contain; border-radius:8px; background:#fff; }
    .product-name { font-size:16px; font-weight:600; margin:10px 0; }
    .product-price { color:#FFD700; font-weight:700; }
    a { text-decoration:none; color:inherit; }
  </style>
</head>
<body>
  <h1><?= ucfirst($category) ?> Collection</h1>
  <div class="product-grid">
    <?php while ($row = $result->fetch_assoc()) { ?>
      <a href="product.php?id=<?= $row['id'] ?>">
        <div class="product-card">
          <img src="uploads/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
          <div class="product-price">₹<?= $row['price'] ?></div>
        </div>
      </a>
    <?php } ?>
  </div>
</body>
</html>
