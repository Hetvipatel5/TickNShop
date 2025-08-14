<?php
session_start();
include 'db.php';
include 'message.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    showMessage("error", "You must be logged in to add items to your wishlist.", "Login", "login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if product ID is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    showMessage("error", "Invalid product ID.", "Go Back", "index.php");
    exit;
}

$product_id = intval($_GET['id']);

// Insert into wishlist (avoid duplicates)
$sql = "INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    header("Location: wishlist.php");
    exit;
} else {
    showMessage("error", "Failed to add product to wishlist.", "Go Back", "index.php");
}

$stmt->close();
$conn->close();
?>
