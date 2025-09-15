<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_logged_in'])) {
header('Location: admin_login.php');
exit();
}


$feedback = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<h2>Feedback Messages</h2>
<table class="table table-bordered bg-white shadow">
<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th></tr></thead>
<tbody>
<?php while($f = $feedback->fetch_assoc()): ?>
<tr>
<td><?= $f['id'] ?></td>
<td><?= htmlspecialchars($f['name']) ?></td>
<td><?= htmlspecialchars($f['email']) ?></td>
<td><?= htmlspecialchars($f['message']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>