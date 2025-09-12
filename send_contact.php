<?php
session_start();
include 'db.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['contact_msg'] = "Please fill in all required fields!";
        $_SESSION['contact_msg_type'] = "error";
        header("Location: contact.php");
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['contact_msg'] = "Thank you! Your message has been sent successfully.";
        $_SESSION['contact_msg_type'] = "success";
    } else {
        $_SESSION['contact_msg'] = "Failed to send your message. Please try again.";
        $_SESSION['contact_msg_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to contact page
    header("Location: contact.php");
    exit;
} else {
    // Accessed directly without POST
    header("Location: contact.php");
    exit;
}
?>
