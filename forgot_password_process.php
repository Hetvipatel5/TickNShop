<?php
include 'message.php'; // ✅ Include the message function file
// Show all errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "watchshop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die(showMessage("error", "Database connection failed: " . $conn->connect_error));
}

// Get email from form
if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
    die(showMessage("error", "Please enter your email.", "Go back", "forgot_password.php"));
}

$email = strtolower(trim($_POST['email'])); // lowercasing to match

// Check if email exists
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(showMessage("error", "SQL error: " . $conn->error));
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Email exists → redirect to reset form
    header("Location: reset_password.php?email=" . urlencode($email));
    exit;
} else {
    showMessage("error", "Email not found in database!", "Try again", "forgot_password.php");
}

$stmt->close();
$conn->close();
?>
