<?php
session_start();
include 'db.php';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check product_id
if (!isset($_POST['product_id'])) {
    die("Invalid request.");
}

$product_id = (int)$_POST['product_id'];
$qty = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

// Check if product already exists in cart
$sql = "SELECT id, quantity FROM cart WHERE product_id=? AND (session_id=? OR user_id=?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $product_id, $session_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $newQty = $row['quantity'] + $qty;

    $update = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
    $update->bind_param("ii", $newQty, $row['id']);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
    $insert->bind_param("isii", $user_id, $session_id, $product_id, $qty);
    $insert->execute();
}

// --- Redirect Logic ---
if (isset($_POST['buy_now']) && $_POST['buy_now'] == "1") {
    header("Location: checkout.php"); // go straight to checkout
    exit;
}

if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
    header("Location: " . $_POST['redirect']); // back to same page (wishlist/product details)
    exit;
}

header("Location: cart.php"); // default
exit;
