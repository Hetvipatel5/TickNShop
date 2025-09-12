<?php
session_start();
include 'db.php';

$msg = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);

    $stmt = $conn->prepare("INSERT INTO feedback (name, email, rating, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $rating, $feedback);

    if ($stmt->execute()) {
        $msg = "<p style='color:green;text-align:center;'>✅ Thank you for your feedback!</p>";
    } else {
        $msg = "<p style='color:red;text-align:center;'>❌ Something went wrong. Try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback - TickNShop</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f8f8f8; margin:0; }
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align:center; }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background: #D4AF37;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background:#FFD700; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Share Your Feedback</h2>
        <?= $msg; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name (optional)">
            <input type="email" name="email" placeholder="Your Email (optional)">
            <select name="rating" required>
                <option value="">Rate Us ⭐</option>
                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                <option value="4">⭐⭐⭐⭐ Good</option>
                <option value="3">⭐⭐⭐ Average</option>
                <option value="2">⭐⭐ Poor</option>
                <option value="1">⭐ Very Bad</option>
            </select>
            <textarea name="feedback" rows="4" placeholder="Write your feedback here..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
        <!-- ✅ Back to Home Button -->
        <a href="index.php" class="home-btn">⬅ Back to Home</a>
    </div>
</body>
</html>
