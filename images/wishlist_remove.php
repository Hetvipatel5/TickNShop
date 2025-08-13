<?php
session_start();
include 'db.php';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $sql = "DELETE FROM wishlist WHERE product_id=? AND (session_id=? OR user_id=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $product_id, $session_id, $user_id);
    $stmt->execute();
}
/*...*/

header("Location: wishlist.php");
exit;
?>
