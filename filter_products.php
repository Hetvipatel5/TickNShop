<?php
header('Content-Type: text/html; charset=UTF-8');
include_once __DIR__ . '/db.php'; // ✅ use shared DB connection

// Read JSON safely
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { $data = []; }

// Coerce to arrays/bools to avoid count() on string error
$brands      = isset($data['brands'])      ? (array)$data['brands']      : [];
$categories  = isset($data['categories'])  ? (array)$data['categories']  : [];
$priceRanges = isset($data['priceRanges']) ? (array)$data['priceRanges'] : [];
$topRated    = !empty($data['topRated']);
$topSeller   = !empty($data['topSeller']);

$sql = "SELECT * FROM products";
$where = [];
$params = [];
$types  = "";

/* Brands IN (...) */
if (!empty($brands)) {
    $brands = array_values(array_filter($brands, fn($v)=>$v!=='')); // clean
    if (!empty($brands)) {
        $ph = implode(",", array_fill(0, count($brands), "?"));
        $where[] = "brand IN ($ph)";
        foreach ($brands as $b) { $params[] = $b; $types .= "s"; }
    }
}

/* Categories IN (...) */
if (!empty($categories)) {
    $categories = array_values(array_filter($categories, fn($v)=>$v!=='')); 
    if (!empty($categories)) {
        $ph = implode(",", array_fill(0, count($categories), "?"));
        $where[] = "category IN ($ph)";
        foreach ($categories as $c) { $params[] = $c; $types .= "s"; }
    }
}

/* Price ranges => (price BETWEEN ? AND ?) OR ... */
if (!empty($priceRanges)) {
    $rangeClauses = [];
    foreach ($priceRanges as $r) {
        if (!is_string($r) || strpos($r, '-') === false) continue;
        [$min,$max] = array_map('trim', explode('-', $r, 2));
        $min = is_numeric($min) ? (float)$min : 0.0;
        $max = is_numeric($max) ? (float)$max : 999999999.0;
        $rangeClauses[] = "(price BETWEEN ? AND ?)";
        $params[] = $min;  $types .= "d";
        $params[] = $max;  $types .= "d";
    }
    if (!empty($rangeClauses)) {
        $where[] = "(" . implode(" OR ", $rangeClauses) . ")";
    }
}

/* Badges */
if ($topRated)  { $where[] = "is_top_rated = 1"; }
if ($topSeller) { $where[] = "is_top_seller = 1"; }

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

// Prepare + bind
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo '<p style="color:#FFD700;text-align:center;">Query error.</p>';
    exit;
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Return HTML cards (fragment)
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id    = (int)$row['id'];
        $name  = htmlspecialchars($row['name']);
        $img   = htmlspecialchars($row['image']);
        $price = number_format((float)$row['price'], 2);
        echo '<div class="product-card">';
        echo '  <a href="product.php?id='.$id.'">';
        echo '      <img src="'.$img.'" alt="'.$name.'">';
        echo '      <h4>'.$name.'</h4>';
        echo '  </a>';
        echo '  <p class="price">₹'.$price.'</p>';
        echo '  <form method="post" action="add_to_cart.php" style="display:inline;">
    <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="buy">Add to Cart</button>
</form>

        echo '      <a href="wishlist_add.php?id='.$id.'"><button class="wishlist">♡ Wishlist</button></a>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<p style="color:#FFD700;text-align:center;">No products match the selected filters.</p>';
}

$stmt->close();
$conn->close();
