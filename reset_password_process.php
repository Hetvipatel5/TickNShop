<?php
include 'message.php'; // ✅ Include the message function file
$conn = new mysqli("localhost", "root", "", "watchshop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = trim($_POST['email']);
$pass = trim($_POST['password']);
$confirm_pass = trim($_POST['confirm_password']);

if ($pass !== $confirm_pass) {
    die("<h3 style='color:red; text-align:center;'>Passwords do not match! <a href='reset_password.php?email=" . urlencode($email) . "'>Try again</a></h3>");
}

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Update password
$sql = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    (showMessage("success", "Password updated successfully!", "Login", "login.php"));
} else {
    (showMessage("error", "Error updating password!", "Try Again", "reset_password.php?email=" . urlencode($email)));
}

$stmt->close();
$conn->close();
?>
