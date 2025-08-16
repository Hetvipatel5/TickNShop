<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'] ?? 0;

    // Check if product already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE product_id=? AND user_id=?");
    $check->bind_param("ii", $product_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // update quantity
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id=$product_id AND user_id=$user_id");
    } else {
        // insert new
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    header("Location: cart.php");
    exit;
}
?>
