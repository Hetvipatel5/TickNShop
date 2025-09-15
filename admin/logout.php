<?php
session_start();

// Destroy admin session
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);
session_destroy();

// Redirect to login page
header("Location: admin_login.php");
exit();
