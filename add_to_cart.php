<?php
// add_to_cart.php
include 'auth.php';      // starts session + helper
require_login_or_redirect();
include 'db.php';

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $qty        = max(1, (int)($_POST['quantity'] ?? 1));

    // upsert: if exists increment, else insert
    $sql = "INSERT INTO cart (user_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $qty);
    $stmt->execute();

    // optional: handle Buy Now
    if (!empty($_POST['buy_now'])) {
        header("Location: checkout.php");
        exit;
    }

    // back to previous page or cart
    $return = $_POST['redirect'] ?? 'cart.php';
    header("Location: " . $return);
    exit;
}

header("Location: index.php");
exit;
