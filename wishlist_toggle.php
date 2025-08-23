<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = "Please login to manage wishlist.";
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Get product_id from POST
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Already exists → remove it
        $del = $conn->prepare("DELETE FROM wishlist WHERE user_id=? AND product_id=?");
        $del->bind_param("ii", $user_id, $product_id);
        $del->execute();
        $_SESSION['flash'] = "Removed from Wishlist.";
    } else {
        // Not in wishlist → insert
        $ins = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $ins->bind_param("ii", $user_id, $product_id);
        $ins->execute();
        $_SESSION['flash'] = "Added to Wishlist.";
    }
}

// Redirect back (stay on same product position if anchor given)
$redirect = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $redirect");
exit;

