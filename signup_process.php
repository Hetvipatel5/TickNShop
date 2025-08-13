<?php
include 'message.php'; // ✅ Include the message function file
// Database connection
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password
$dbname = "watchshop"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(showMessage("error", "Connection failed: " . $conn->connect_error, "Try Again", "signup.php"));
}

// Get form data
$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$user = trim($_POST['username']);
$pass = trim($_POST['password']);
$confirm_pass = trim($_POST['confirm_password']);

// Validate passwords match
if ($pass !== $confirm_pass) {
    die(showMessage("error", "Passwords do not match!", "Go Back", "signup.php"));
}

// ✅ Check if email already exists
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    die(showMessage("error", "Email already registered!", "Try again", "signup.php"));
}
$check_email->close();

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Insert data
$sql = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $fullname, $email, $user, $hashed_password);

if ($stmt->execute()) {
    showMessage("success", "Signup successful!", "Login here", "login.php");
} else {
    showMessage("error", "Error: " . $stmt->error, "Try Again", "signup.php");
}

$stmt->close();
$conn->close();
?>
