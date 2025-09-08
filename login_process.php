<?php
session_start();
include_once __DIR__ . '/db.php'; // ✅ use shared DB connection

include 'message.php';


// Get form data
$user = trim($_POST['username']);
$pass = trim($_POST['password']);
$remember = isset($_POST['remember']); // Checkbox from login form

// Find user
$sql = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($pass, $row['password'])) {
        // ✅ Store session data
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];

        // ✅ Set cookie for persistent login
        if ($remember) {
            setcookie("user_id", $row['id'], time() + (86400 * 30), "/"); // 30 days
            setcookie("username", $row['username'], time() + (86400 * 30), "/");
        }

        header("Location: index.php");
        exit;
    } else {
        showMessage("error", "Invalid password!", "Try Again", "login.php");
    }
} else {
    showMessage("error", "User not found!", "Try Again", "login.php");
}

$stmt->close();
$conn->close();
?>
