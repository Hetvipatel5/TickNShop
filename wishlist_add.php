<?php
session_start();
include 'db.php';

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);
$product_id = intval($data['productId']);

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check if already exists
$check_sql = "SELECT * FROM wishlist WHERE product_id=? AND (session_id=? OR user_id=?)";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("isi", $product_id, $session_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already in wishlist"]);
    exit;
}

// Insert into wishlist
$sql = "INSERT INTO wishlist (user_id, session_id, product_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $user_id, $session_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
?>
