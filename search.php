<?php
session_start();
include_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TickNShop - Search</title>
  <style>
    .search-container {
      position: relative;
      width: 300px;
      margin: 20px auto;
    }

    .search-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 6px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
      z-index: 1000;
      display: none;
      max-height: 250px;
      overflow-y: auto;
    }

    .suggestion-item {
      padding: 10px;
      cursor: pointer;
      font-size: 14px;
    }

    .suggestion-item:hover {
      background: #f1f1f1;
    }
  </style>
</head>
<body>
  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search for products..." autocomplete="off">
    <div id="suggestionsBox" class="suggestions"></div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestionsBox");

    // Prevent form submission on Enter
    searchInput.addEventListener("keydown", function(e) {
      if (e.key === "Enter") {
        e.preventDefault();
      }
    });

    // Fetch suggestions
    searchInput.addEventListener("keyup", function() {
      let query = this.value.trim();

      if (query.length > 1) {
        fetch("search_suggestions.php?query=" + encodeURIComponent(query))
          .then(response => response.text())
          .then(data => {
            suggestionsBox.innerHTML = data;
            suggestionsBox.style.display = "block";
          });
      } else {
        suggestionsBox.style.display = "none";
      }
    });

    // Click on suggestion
    document.addEventListener("click", function(e) {
      if (e.target.classList.contains("suggestion-item")) {
        // Option 1: Fill input
        // searchInput.value = e.target.textContent.trim();
        // suggestionsBox.style.display = "none";

        // ✅ Option 2: Redirect to product page directly
        let productId = e.target.getAttribute("data-id");
        if (productId) {
          window.location.href = "product.php?id=" + productId;
        }
      }
    });
  });
  </script>
</body>
</html>
