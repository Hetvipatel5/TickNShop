<?php
// cart_remove.php
include 'auth.php'; require_login_or_redirect();
include 'db.php';

$user_id = (int)$_SESSION['user_id'];

if (isset($_GET['product_id'])) {
    $pid = (int)$_GET['product_id'];
    $sql = "DELETE FROM cart WHERE user_id=? AND product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $pid);
    $stmt->execute();
}
header("Location: cart.php");
exit;
