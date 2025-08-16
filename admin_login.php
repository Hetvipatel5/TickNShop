<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']); // encrypt

    $sql = "SELECT * FROM admins WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login | TickNShop</title>
  <style>
    body{background:#000;color:#fff;font-family:Arial;display:flex;justify-content:center;align-items:center;height:100vh;}
    .box{background:#1a1a1a;padding:30px;border-radius:10px;border:2px solid #D4AF37;text-align:center;width:300px;}
    input{width:90%;padding:10px;margin:10px 0;border:none;border-radius:6px;}
    button{background:#D4AF37;color:#000;border:none;padding:10px 20px;border-radius:6px;font-weight:bold;cursor:pointer;}
    button:hover{background:#FFD700;}
    .error{color:red;}
  </style>
</head>
<body>
  <div class="box">
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
