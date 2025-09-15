<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_logged_in'])) {
header('Location: admin_login.php');
exit();
}


$id = intval($_GET['id']);
$status = $_GET['status'];
$allowed = ['pending','delivered','cancelled'];
if (!in_array($status, $allowed)) {
die("Invalid status");
}


$stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();


header('Location: admin_orders.php');
exit();