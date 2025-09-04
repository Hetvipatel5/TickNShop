<?php
session_start();
include 'db.php';

$session_id = session_id();
$user_id    = $_SESSION['user_id'] ?? null;

// Get POST data
$fullname = $_POST['fullname'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];
$address  = $_POST['address'];
$payment  = $_POST['payment_method'];

// Fetch cart items again
if ($user_id) {
    $cart_sql = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
                                FROM cart c 
                                JOIN products p ON c.product_id = p.id 
                                WHERE c.user_id = ?");
    $cart_sql->bind_param("i", $user_id);
} else {
    $cart_sql = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
                                FROM cart c 
                                JOIN products p ON c.product_id = p.id 
                                WHERE c.session_id = ?");
    $cart_sql->bind_param("s", $session_id);
}
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
$order_sql = $conn->prepare("INSERT INTO orders (user_id, fullname, email, phone, address, payment_method, total_amount) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
$order_sql->bind_param("isssssd", $user_id, $fullname, $email, $phone, $address, $payment, $total);
$order_sql->execute();

$order_id = $conn->insert_id;

// Insert order items
$item_sql = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $item_sql->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $item_sql->execute();
}

// Clear the cart
if ($user_id) {
    $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_cart->bind_param("i", $user_id);
} else {
    $clear_cart = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $clear_cart->bind_param("s", $session_id);
}
$clear_cart->execute();

// Redirect to Thank You page
header("Location: thankyou.php?order_id=" . $order_id);
exit;
