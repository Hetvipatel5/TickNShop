<?php
session_start();
include_once __DIR__ . '/db.php'; // Adjust path if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Essentials Watches</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      color: #333;
    }

    .header {
      background: #FFD700;
      padding: 15px 20px;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      color: black;
    }

    .container {
      max-width: 1200px;
      margin: 30px auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      padding: 0 20px;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
    }

    .product-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 12px;
    }

    .product-card h3 {
      margin: 10px 0 5px 0;
      font-size: 1.1rem;
      text-align: center;
    }

    .product-card p {
      margin: 0 0 10px 0;
      font-weight: bold;
      color: #FFD700;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .home-btn {
      display: inline-block;
      margin: 20px;
      padding: 8px 12px;
      background: #FFD700;
      color: black;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .home-btn:hover {
      background: #D4AF37;
    }

    @media(max-width:600px){
      .product-card img {
        height: 150px;
      }
    }
  </style>
</head>
<body>

<div class="header">Essentials Watches (Under ₹5000)</div>
<a href="../landing.php" class="home-btn">⬅ Back to Home</a>

<div class="container">
  <?php
  // Fetch watches under 5000
  $sql = "SELECT * FROM products WHERE price <= 5000 ORDER BY name ASC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<div class='product-card'>";
          echo "<img src='../" . $row['image'] . "' alt='" . $row['name'] . "'>";
          echo "<h3>" . $row['name'] . "</h3>";
          echo "<p>₹" . number_format($row['price'], 2) . "</p>";
          echo "</div>";
      }
  } else {
      echo "<p style='text-align:center;'>No watches under ₹5000 found.</p>";
  }
  ?>
</div>

</body>
</html>
