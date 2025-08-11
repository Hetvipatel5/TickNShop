<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "watchshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login form data
$user = trim($_POST['username']);
$pass = trim($_POST['password']);

// Find user by username or email
$sql = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($pass, $row['password'])) {

        // ✅ Record login in database
        $log_sql = "INSERT INTO logins (user_id) VALUES (?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("i", $row['id']);
        $log_stmt->execute();
        $log_stmt->close();

        echo "<h3 style='color:green; text-align:center;'>Login successful! Welcome, {$row['username']}.</h3>";
        // Redirect to home page
        // header("Location: index.php");
        // exit;
    } else {
        echo "<h3 style='color:red; text-align:center;'>Invalid password! <a href='login.php'>Try again</a></h3>";
    }
} else {
    echo "<h3 style='color:red; text-align:center;'>User not found! <a href='login.php'>Try again</a></h3>";
}

$stmt->close();
$conn->close();
?>
