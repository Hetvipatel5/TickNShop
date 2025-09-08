<?php
$servername = "localhost";
$username   = "root";
$password   = "";  // If you set a password for root, add it here
$dbname     = "watchshop";
$port       = 3307; // <-- important

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
}
?>
