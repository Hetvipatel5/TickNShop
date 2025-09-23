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
   <!-- Header Search Form -->
<!-- Header Search Form -->
<form action="search.php" method="GET" class="search-form" autocomplete="off">
  <input type="text" id="search-box" name="query" placeholder="Search products..." required>
  <i class="fas fa-search"></i>
  <div id="suggestions"></div>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    let selectedIndex = -1;

    function fetchSuggestions(query){
        $.ajax({
            url: "search_suggestions.php",
            method: "GET",
            data: {query: query},
            success: function(data){
                $("#suggestions").fadeIn().html(data);
                selectedIndex = -1;
            }
        });
    }

    $("#search-box").on("keyup", function(e){
        let query = $(this).val();
        let items = $("#suggestions .suggestion-item");

        // Arrow Down
        if(e.key === "ArrowDown"){
            if(selectedIndex < items.length - 1){
                selectedIndex++;
                items.removeClass("active");
                $(items[selectedIndex]).addClass("active");
            }
            return;
        }

        // Arrow Up
        if(e.key === "ArrowUp"){
            if(selectedIndex > 0){
                selectedIndex--;
                items.removeClass("active");
                $(items[selectedIndex]).addClass("active");
            }
            return;
        }

        // Enter
        if(e.key === "Enter"){
            if(selectedIndex >= 0){
                let id = $(items[selectedIndex]).data("id");
                window.location.href = "product.php?id=" + id;
                return;
            }
            // Otherwise submit form
        }

        // Normal typing
        if(query.length > 1){
            fetchSuggestions(query);
        } else {
            $("#suggestions").fadeOut();
        }
    });

    // Click on suggestion
    $(document).on("click", ".suggestion-item", function(){
        let id = $(this).data("id");
        window.location.href = "product.php?id=" + id;
    });

    // Click outside to close suggestions
    $(document).on("click", function(e){
        if(!$(e.target).closest(".search-form").length){
            $("#suggestions").fadeOut();
        }
    });
});
</script>

<style>
/* ===== Search Form ===== */
.search-form {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  max-width: 600px;
  margin: 20px auto;
  transition: transform 0.25s ease;
}

.search-form:hover {
  transform: scale(1.01);
}

#search-box {
  width: 100%;
  padding: 12px 50px 12px 18px;
  border-radius: 30px;
  border: 1px solid #555;
  background: var(--black);
  color: var(--white);
  font-size: 16px;
  outline: none;
  transition: all 0.25s ease;
}

#search-box::placeholder { 
  color: #aaa; 
  transition: color 0.25s ease;
}

#search-box:focus {
  border-color: var(--gold);
  box-shadow: 0 0 10px rgba(212,175,55,0.6);
}

.search-form i {
  position: absolute;
  right: 18px;
  font-size: 18px;
  color: var(--gold);
  cursor: pointer;
  transition: transform 0.25s ease, color 0.25s ease;
}

.search-form i:hover {
  transform: scale(1.15);
  color: var(--bright-gold);
}

/* ===== Suggestions Box ===== */
#suggestions {
  position: absolute;
  top: 110%;
  left: 0;
  width: 100%;
  background: var(--charcoal);
  border: 1px solid #333;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.5);
  max-height: 400px;
  overflow-y: auto;
  z-index: 9999;
  display: none;
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===== Suggestion Items ===== */
.suggestion-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  cursor: pointer;
  border-bottom: 1px solid #444;
  font-size: 15px;
  color: var(--white);
  transition: background 0.25s ease, transform 0.15s ease;
}

.suggestion-item:last-child { border-bottom: none; }

.suggestion-item:hover,
.suggestion-item.active {
  background: #2a2a2a;
  transform: translateX(3px);
  color: var(--bright-gold);
}

/* For better UX when scrolling */
.suggestion-item:active {
  background: var(--gold);
  color: var(--black);
}

/* ===== Responsive ===== */
@media(max-width:768px){
  .search-form { max-width: 90%; }
  #search-box { font-size: 14px; padding: 10px 45px 10px 15px; }
}

</style>




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
