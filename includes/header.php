<!-- <?php
// if (session_status() == PHP_SESSION_NONE) session_start();
// ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TickNShop - Premium Watches</title>
<link rel="stylesheet" href="/TNS/assets/css/style.css">

  <script defer src="assets/js/main.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header">
  <div class="nav container">
    <a class="logo" href="index.php">TickNShop</a>
    <nav class="main-nav">
      <a href="index.php">Home</a>
      <a href="shop.php">Shop</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
    </nav>
    <div class="nav-actions">
      <a class="icon" href="cart.php" aria-label="Cart">🛒<span id="cart-count">0</span></a>
      <?php if(!empty($_SESSION['username'])): ?>
        <span class="muted">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
      <?php else: ?>
        <a class="btn ghost" href="login.php">Login</a>
      <?php endif; ?>
    </div>
    <button id="mobile-toggle" class="mobile-toggle" aria-label="Toggle menu">☰</button>
  </div>
</header> -->
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>TickNShop | Premium Watches</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .brand-chip.active { background:#333; color:#fff; border-radius:20px; padding:5px 12px; }
    .filter-title { display:flex; justify-content:space-between; align-items:center; cursor:pointer; }
    .filter-title .toggle { user-select:none; }
    .filter-title button.clear-btn { cursor:pointer; }
  </style>
</head>
<body>

<header class="header">
  <div class="logo"><img src="brand_logo/logo7.jpg" alt="TickNShop Logo"></div>
  <nav class="navbar">
    <a href="index.php" class="active">All Watches</a>
    <a href="?categories[]=Men" class="nav-cat">Men</a>
    <a href="?categories[]=Women" class="nav-cat">Women</a>
    <a href="?categories[]=Unisex" class="nav-cat">Unisex</a>
    <a href="#">Brands</a>
    <a href="#">Offers</a>
  </nav>
  <div class="icons">
    <a href="search.php"><i class="fas fa-search"></i></a>
    <a href="wishlist.php" title="Wishlist"><i class="fas fa-heart"></i></a>
    <a href="profile.php" title="Profile"><i class="fas fa-user"></i></a>
    <a href="cart.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
  </div>
</header>
<script>
window.addEventListener("scroll", function () {
  const header = document.querySelector(".header");
  if (window.scrollY > 50) {
    header.classList.add("shrink");
  } else {
    header.classList.remove("shrink");
  }
});
</script>
