<?php
session_start();
include 'db.php';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// --- Validate product ---
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid request.");
}

$product_id = (int)$_POST['id'];
$qty = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

// --- Check if product already exists in cart ---
$sql = "SELECT id, quantity FROM cart WHERE product_id=? AND (session_id=? OR user_id=?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $product_id, $session_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Already in cart → update quantity
    $row = $res->fetch_assoc();
    $newQty = $row['quantity'] + $qty;

    $update = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
    $update->bind_param("ii", $newQty, $row['id']);
    $update->execute();
} else {
    // Not in cart → insert new row
    $insert = $conn->prepare(
        "INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)"
    );
    $insert->bind_param("isii", $user_id, $session_id, $product_id, $qty);
    $insert->execute();
}

// --- Redirect back ---
header("Location: cart.php");
exit;
?>
