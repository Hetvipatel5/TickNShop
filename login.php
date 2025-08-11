<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TickNShop | Login</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    body {
        height: 100vh;
        background: url('images/login.jpg') no-repeat center center/cover;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        top: 0;
        left: 0;
    }
    .login-box {
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
    .login-box h2 {
        margin-bottom: 20px;
    }
    .login-box input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: none;
        border-radius: 8px;
        outline: none;
    }
    .login-box input[type="submit"] {
        background: #ff9800;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    .login-box input[type="submit"]:hover {
        background: #e68900;
    }
    .login-box a {
        color: #ff9800;
        text-decoration: none;
        font-size: 14px;
    }
</style>
</head>
<body>
<div class="overlay"></div>

<div class="login-box">
    <h2>Login to TickNShop</h2>
    <form action="login_process.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <p><a href="#">Forgot Password?</a></p>
    <p><a href="signup.php">Create an Account</a></p>
</div>

</body>
</html>