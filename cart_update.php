<?php
// cart_update.php
include 'auth.php'; require_login_or_redirect();
include 'db.php';

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $pid = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);

    $sql = "UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $qty, $user_id, $pid);
    $stmt->execute();
}

header("Location: cart.php");
exit;
