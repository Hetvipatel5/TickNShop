<?php
session_start();
include_once __DIR__ . '/db.php';

// ✅ Ensure the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    // Save a flash message to show after login
    $_SESSION['flash'] = "You must be logged in to checkout.";
    header("Location: login.php"); // Redirect to login page
    exit;
}

$session_id = session_id();

// Get POST data
$fullname = $_POST['fullname'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$address  = $_POST['address'] ?? '';
$payment  = $_POST['payment_method'] ?? '';

// Fetch cart items for logged-in user
$cart_sql = $conn->prepare("
    SELECT c.product_id, c.quantity, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$cart_sql->bind_param("i", $user_id);
$cart_sql->execute();
$cart_items = $cart_sql->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    $_SESSION['flash'] = "Your cart is empty.";
    header("Location: cart.php");
    exit;
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders table
$order_sql = $conn->prepare("
    INSERT INTO orders (user_id, fullname, email, phone, address, payment_method, total_amount) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$order_sql->bind_param("isssssd", $user_id, $fullname, $email, $phone, $address, $payment, $total);
$order_sql->execute();
$order_id = $conn->insert_id;

// Insert order items
$item_sql = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price) 
    VALUES (?, ?, ?, ?)
");
foreach ($cart_items as $item) {
    $item_sql->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $item_sql->execute();
}

// Clear the user's cart
$clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$clear_cart->bind_param("i", $user_id);
$clear_cart->execute();

// Redirect to Thank You page
header("Location: thankyou.php?order_id=" . $order_id);
exit;
