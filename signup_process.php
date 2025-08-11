<?php
// Database connection
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password
$dbname = "watchshop"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$user = trim($_POST['username']);
$pass = trim($_POST['password']);
$confirm_pass = trim($_POST['confirm_password']);

// Validate passwords match
if ($pass !== $confirm_pass) {
    die("<h3 style='color:red; text-align:center;'>Passwords do not match! <a href='signup.php'>Go Back</a></h3>");
}

// ✅ Check if email already exists
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    die("<h3 style='color:red; text-align:center;'>Email already registered! <a href='signup.php'>Try again</a></h3>");
}
$check_email->close();

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Insert data
$sql = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $fullname, $email, $user, $hashed_password);

if ($stmt->execute()) {
    echo "<h3 style='color:green; text-align:center;'>Signup successful! <a href='login.php'>Login here</a></h3>";
} else {
    echo "<h3 style='color:red; text-align:center;'>Error: " . $stmt->error . "</h3>";
}

$stmt->close();
$conn->close();
?>
