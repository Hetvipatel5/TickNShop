<?php
session_start();
include_once __DIR__ . '/db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = trim($_POST['order_id']);
    $reason = trim($_POST['reason']);
    $comments = trim($_POST['comments']);

    if (empty($order_id) || empty($reason)) {
        $_SESSION['return_msg'] = "Please fill all required fields!";
        $_SESSION['return_msg_type'] = "error";
        header("Location: return.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO returns (order_id, reason, comments, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $order_id, $reason, $comments);

    if ($stmt->execute()) {
        $_SESSION['return_msg'] = "Return request submitted successfully!";
        $_SESSION['return_msg_type'] = "success";
    } else {
        $_SESSION['return_msg'] = "Failed to submit return request. Please try again.";
        $_SESSION['return_msg_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: return.php");
    exit;
} else {
    header("Location: return.php");
    exit;
}
?>
