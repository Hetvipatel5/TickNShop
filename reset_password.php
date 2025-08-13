<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';
if (empty($email)) {
    die("<h3 style='color:red;'>Invalid request!</h3>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | TickNShop</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('images/login.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            top: 0;
            left: 0;
        }
        .box {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 12px;
            backdrop-filter: blur(8px);
            width: 350px;
            text-align: center;
            color: white;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            outline: none;
        }
        input[type="submit"] {
            background: #ff9800;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="overlay"></div>

<div class="box">
    <h2>Reset Password</h2>
    <form action="reset_password_process.php" method="post">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" value="Update Password">
    </form>
</div>

</body>
</html>
