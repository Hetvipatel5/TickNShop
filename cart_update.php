<?php
session_start();
include 'db.php';

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = intval($_POST['id']);
    $qty = max(1, intval($_POST['quantity']));
    $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
    $stmt->bind_param("ii", $qty, $id);
    $stmt->execute();
}
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: cart.php");
exit;
?>
