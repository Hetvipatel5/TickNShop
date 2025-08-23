<?php
session_start();
include 'db.php';

// who is adding
$session_id = session_id();
$user_id    = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// accept both product_id and id
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : (int)($_POST['id'] ?? 0);
$qty        = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

// fallback redirect targets
$redirect   = isset($_POST['redirect']) && $_POST['redirect'] !== '' 
                ? $_POST['redirect'] 
                : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'cart.php');
$buy_now    = isset($_POST['buy_now']);

// validate product id
if ($product_id <= 0) {
    $_SESSION['flash'] = "Could not add to cart (missing product).";
    header("Location: " . $redirect);
    exit;
}

// verify product exists
$chk = $conn->prepare("SELECT id, price FROM products WHERE id = ? LIMIT 1");
$chk->bind_param("i", $product_id);
$chk->execute();
$prod = $chk->get_result()->fetch_assoc();
if (!$prod) {
    $_SESSION['flash'] = "Product not found.";
    header("Location: " . $redirect);
    exit;
}

// If logged in: use user_id. Else: use session_id.
// This avoids the (session_id OR user_id) condition and keeps indexes efficient.
if ($user_id) {
    // does row exist?
    $sel = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");
    $sel->bind_param("ii", $user_id, $product_id);
    $sel->execute();
    $row = $sel->get_result()->fetch_assoc();

    if ($row) {
        $newQty = $row['quantity'] + $qty;
        $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $upd->bind_param("ii", $newQty, $row['id']);
        $upd->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        $ins->bind_param("isii", $user_id, $session_id, $product_id, $qty);
        $ins->execute();
    }
} else {
    // guest cart by session_id
    $sel = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? LIMIT 1");
    $sel->bind_param("si", $session_id, $product_id);
    $sel->execute();
    $row = $sel->get_result()->fetch_assoc();

    if ($row) {
        $newQty = $row['quantity'] + $qty;
        $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $upd->bind_param("ii", $newQty, $row['id']);
        $upd->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (NULL, ?, ?, ?)");
        $ins->bind_param("sii", $session_id, $product_id, $qty);
        $ins->execute();
    }
}

// success flash
$_SESSION['flash'] = "Item added to cart.";

// redirect: buy-now goes straight to checkout
if ($buy_now) {
    header("Location: checkout.php");
    exit;
}

header("Location: " . $redirect);
exit;
