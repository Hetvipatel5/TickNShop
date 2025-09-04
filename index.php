<?php
session_start();
include 'db.php';

// Restore session if user is remembered
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = (int)$_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'] ?? null;
}

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// Wishlist ids
$wish_ids = [];
if ($user_id) {
    $wq = $conn->prepare("SELECT product_id FROM wishlist WHERE user_id=?");
    $wq->bind_param("i", $user_id);
    $wq->execute();
    $wr = $wq->get_result();
    while ($w = $wr->fetch_assoc()) $wish_ids[] = (int)$w['product_id'];
}

// Cart count
$sqlCartCount = "SELECT SUM(quantity) as total_items FROM cart WHERE session_id=? OR user_id=?";
$stmtC = $conn->prepare($sqlCartCount);
$stmtC->bind_param("si", $session_id, $user_id);
$stmtC->execute();
$resC = $stmtC->get_result();
$rowC = $resC->fetch_assoc();
$cart_count = $rowC['total_items'] ?? 0;

// Brands
$brands = [];
$resB = $conn->query("SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand<>'' ORDER BY brand");
while ($r = $resB->fetch_assoc()) $brands[] = $r['brand'];

// Categories
$categories = [];
$resCat = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category<>'' ORDER BY category");
if ($resCat && $resCat->num_rows) {
    while ($r = $resCat->fetch_assoc()) $categories[] = $r['category'];
} else {
    $categories = ['Men','Women','Unisex','Couple','Smart'];
}

// ===== Build filtered products query =====
$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types = "";

// Brands (multi)
if (!empty($_GET['brands']) && is_array($_GET['brands'])) {
    $in = implode(",", array_fill(0, count($_GET['brands']), "?"));
    $sql .= " AND brand IN ($in)";
    foreach ($_GET['brands'] as $b) { $params[] = $b; $types .= "s"; }
}

// Categories (multi)
if (!empty($_GET['categories']) && is_array($_GET['categories'])) {
    $in = implode(",", array_fill(0, count($_GET['categories']), "?"));
    $sql .= " AND category IN ($in)";
    foreach ($_GET['categories'] as $c) { $params[] = $c; $types .= "s"; }
}

// Price ranges (multi OR)
if (!empty($_GET['priceRanges']) && is_array($_GET['priceRanges'])) {
    $conds = [];
    foreach ($_GET['priceRanges'] as $range) {
        [$min, $max] = array_pad(explode("-", $range, 2), 2, 0);
        $conds[] = "(price BETWEEN ? AND ?)";
        $params[] = (float)$min; $params[] = (float)$max; $types .= "dd";
    }
    if ($conds) $sql .= " AND (" . implode(" OR ", $conds) . ")";
}

// Highlights
if (!empty($_GET['topRated']))  { $sql .= " AND is_top_rated=1"; }
if (!empty($_GET['topSeller'])) { $sql .= " AND is_top_seller=1"; }

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$resP = $stmt->get_result();
$products = [];
while ($r = $resP->fetch_assoc()) $products[] = $r;

// Which sections should be open (server defaults)
$brandsOpen     = !empty($_GET['brands']);
$categoriesOpen = !empty($_GET['categories']);
$priceOpen      = !empty($_GET['priceRanges']);
$highlightsOpen = !empty($_GET['topRated']) || !empty($_GET['topSeller']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>TickNShop | Premium Watches</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<style>
  .brand-chip.active { background:#333; color:#fff; border-radius:20px; padding:5px 12px; }
  .filter-title { display:flex; justify-content:space-between; align-items:center; cursor:pointer; }
  .filter-title .toggle { user-select:none; }
  .filter-title button.clear-btn { cursor:pointer; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<header class="header">
  <div class="logo"><img src="brand_logo/logo7.jpg" alt="TickNShop Logo"></div>
  <nav class="navbar">
    <a href="index.php" class="active">All Watches</a>
    <a href="?categories[]=Men" class="nav-cat">Men</a>
    <a href="?categories[]=Women" class="nav-cat">Women</a>
    <a href="?categories[]=Unisex" class="nav-cat">Unisex</a>
    <a href="?categories[]=Smart" class="nav-cat">Smart</a>
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

<!-- <section class="banner">
  <div class="banner-text">
    <h1>SELECT FROM A CURATION</h1>
    <p>Of 40+ International Brands</p>
  </div>
</section> -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TickNShop Banner Slider</title>
  <style>
    :root {
      --black: #000000;
      --charcoal: #1A1A1A;
      --gold: #D4AF37;
      --bright-gold: #FFD700;
      --white: #FFFFFF;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: var(--black);
    }

    .banner-slider {
      position: relative;
      width: 100%;
      height: 400px;
      overflow: hidden;
      background: var(--charcoal);
    }

    .slide {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }

    .slide.active {
      opacity: 1;
      z-index: 1;
    }

    .slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: blur(2px) brightness(0.7); /* Blur and darken image */
  transition: filter 0.3s ease;
}
.slide:hover img {
  filter: blur(1px) brightness(0.7);
}
    .overlay-text {
      position: absolute;
      bottom: 50px;
      left: 50px;
      right: 50px;
      color: var(--gold);
      text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
      text-align: center;
    }

    .overlay-text h2 {
      font-size: 2.5rem;
      margin: 0 0 10px 0;
    }

    .overlay-text p {
      font-size: 1.2rem;
      margin: 0 0 15px 0;
      color: var(--bright-gold);
    }

    .shop-btn {
      display: inline-block;
      padding: 10px 25px;
      background: var(--gold);
      color: var(--black);
      font-weight: bold;
      text-decoration: none;
      border-radius: 5px;
      transition: background 0.3s, color 0.3s;
    }

    .shop-btn:hover {
      background: var(--bright-gold);
      color: var(--white);
    }

    .dots {
      position: absolute;
      bottom: 20px;
      width: 100%;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .dot {
      width: 12px;
      height: 12px;
      background: var(--gold);
      border-radius: 50%;
      cursor: pointer;
      opacity: 0.6;
      transition: opacity 0.3s;
    }

    .dot.active {
      background: var(--bright-gold);
      opacity: 1;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
      .overlay-text h2 { font-size: 1.8rem; }
      .overlay-text p { font-size: 1rem; }
      .shop-btn { padding: 8px 20px; font-size: 0.9rem; }
    }
  </style>
</head>
<body>

<section class="banner-slider">
  <div class="slide active">
    <img src="brand_logo/men_banner.jpg" alt="Banner 1">
    <div class="overlay-text">
      <h2>Premium Watches</h2>
      <p>Exclusive Collection at TickNShop</p>
      <a href="products.php" class="shop-btn">Shop Now</a>
    </div>
  </div>
  <div class="slide">
    <img src="brand_logo/women_banner.jpg" alt="Banner 2">
    <div class="overlay-text">
      <h2>Luxury for Men & Women</h2>
      <p>Shop the Latest Styles Today</p>
      <a href="products.php" class="shop-btn">Shop Now</a>
    </div>
  </div>
  <div class="slide">
    <img src="brand_logo/couple_banner.jpg" alt="Banner 3">
    <div class="overlay-text">
      <h2>TickNShop Premium</h2>
      <p>Timeless Elegance on Your Wrist</p>
      <a href="products.php" class="shop-btn">Shop Now</a>
    </div>
  </div>
 <div class="slide">
    <img src="brand_logo/premium_banner.jpg" alt="Banner 3">
    <div class="overlay-text">
      <h2>TickNShop Luxury</h2>
      <p>Indulge in Opulence</p>
      <a href="products.php" class="shop-btn">Shop Now</a>
    </div>
  </div>
  <div class="dots">
    <span class="dot active"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>
</section>

<script>
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  let current = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
      dots[i].classList.toggle('active', i === index);
    });
  }

  // Auto slide every 5 seconds
  setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
  }, 5000);

  // Dot click navigation
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      current = i;
      showSlide(current);
    });
  });

  // Initialize
  showSlide(current);
</script>

</body>
</html>


<main class="main-content">
  <!-- SIDEBAR FILTERS -->
  <aside class="sidebar">
    <form method="GET" id="filterForm">
      <div class="filters-header">
        <h3>Filters</h3>
        <button type="button" id="clearAll" class="clear-all">Clear All</button>
      </div>

      <!-- Brands -->
      <div class="filter-block" data-block="brands">
        <div class="filter-title">
          <span class="toggle toggle-brands">Brands <?php echo $brandsOpen ? '▲' : '▼'; ?></span>
          <button type="button" class="clear-btn" data-filter="brands">Clear</button>
        </div>
        <div class="filter-body scrollable brand-list" style="display:<?php echo $brandsOpen ? 'block' : 'none'; ?>;">
          <?php foreach ($brands as $b): ?>
            <label class="check-row">
              <input type="checkbox" name="brands[]" value="<?php echo htmlspecialchars($b); ?>"
                <?php if (!empty($_GET['brands']) && in_array($b, $_GET['brands'])) echo 'checked'; ?>>
              <span><?php echo htmlspecialchars($b); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Categories -->
      <div class="filter-block" data-block="categories">
        <div class="filter-title">
          <span class="toggle toggle-categories">Type / Gender <?php echo $categoriesOpen ? '▲' : '▼'; ?></span>
          <button type="button" class="clear-btn" data-filter="categories">Clear</button>
        </div>
        <div class="filter-body" style="display:<?php echo $categoriesOpen ? 'block' : 'none'; ?>;">
          <?php foreach ($categories as $c): ?>
            <label class="check-row">
              <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($c); ?>"
                <?php if (!empty($_GET['categories']) && in_array($c, $_GET['categories'])) echo 'checked'; ?>>
              <span><?php echo htmlspecialchars($c); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Price -->
      <div class="filter-block" data-block="priceRanges">
        <div class="filter-title">
          <span class="toggle toggle-price">Price <?php echo $priceOpen ? '▲' : '▼'; ?></span>
          <button type="button" class="clear-btn" data-filter="priceRanges">Clear</button>
        </div>
        <div class="filter-body" style="display:<?php echo $priceOpen ? 'block' : 'none'; ?>;">
          <label class="check-row"><input type="checkbox" name="priceRanges[]" value="0-5000"         <?php if (!empty($_GET['priceRanges']) && in_array("0-5000", $_GET['priceRanges'])) echo 'checked'; ?>> <span>Under ₹5,000</span></label>
          <label class="check-row"><input type="checkbox" name="priceRanges[]" value="5000-20000"     <?php if (!empty($_GET['priceRanges']) && in_array("5000-20000", $_GET['priceRanges'])) echo 'checked'; ?>> <span>₹5,000 – ₹20,000</span></label>
          <label class="check-row"><input type="checkbox" name="priceRanges[]" value="20000-100000"   <?php if (!empty($_GET['priceRanges']) && in_array("20000-100000", $_GET['priceRanges'])) echo 'checked'; ?>> <span>₹20,000 – ₹1,00,000</span></label>
          <label class="check-row"><input type="checkbox" name="priceRanges[]" value="100000-99999999" <?php if (!empty($_GET['priceRanges']) && in_array("100000-99999999", $_GET['priceRanges'])) echo 'checked'; ?>> <span>₹1,00,000+</span></label>
        </div>
      </div>

      <!-- Highlights -->
      <div class="filter-block" data-block="badges">
        <div class="filter-title">
          <span class="toggle toggle-highlights">Highlights <?php echo $highlightsOpen ? '▲' : '▼'; ?></span>
          <button type="button" class="clear-btn" data-filter="badges">Clear</button>
        </div>
        <div class="filter-body" style="display:<?php echo $highlightsOpen ? 'block' : 'none'; ?>;">
          <label class="check-row"><input type="checkbox" name="topRated"  value="1" <?php if (!empty($_GET['topRated']))  echo 'checked'; ?>> <span>Top Rated</span></label>
          <label class="check-row"><input type="checkbox" name="topSeller" value="1" <?php if (!empty($_GET['topSeller'])) echo 'checked'; ?>> <span>Top Seller</span></label>
        </div>
      </div>
    </form>
  </aside>

  <!-- PRODUCTS -->
  <section class="content-area">
    <!-- Brand chips -->
    <div id="brandSlider" class="brand-slider">
      <?php foreach ($brands as $b): ?>
        <button class="brand-chip <?php if (!empty($_GET['brands']) && in_array($b, $_GET['brands'])) echo 'active'; ?>"
                data-brand="<?php echo htmlspecialchars($b); ?>">
          <?php echo htmlspecialchars($b); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div id="productGrid" class="product-grid">
      <?php if (count($products)): ?>
        <?php foreach ($products as $row): ?>
          <?php $inWish = in_array((int)$row['id'], $wish_ids); ?>
          <div class="product-card" id="product-<?php echo (int)$row['id']; ?>">
            <a href="product.php?id=<?php echo (int)$row['id']; ?>">
              <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
              <h4><?php echo htmlspecialchars($row['name']); ?></h4>
            </a>
            <p class="price">₹<?php echo number_format((float)$row['price'], 2); ?></p>

            <div class="buttons">
              <!-- Add to Cart -->
              <form method="post" action="add_to_cart.php" style="display:inline;">
                <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#product-<?php echo (int)$row['id']; ?>">
                <button type="submit" class="buy">Add to Cart</button>
              </form>

              <!-- Wishlist toggle -->
              <form method="post" action="wishlist_toggle.php" style="display:inline;">
                <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#product-<?php echo (int)$row['id']; ?>">
                <button type="submit" class="btn wishlist-btn <?php echo $inWish ? 'active' : ''; ?>">
                  <span class="heart"><?php echo $inWish ? '♥' : '♡'; ?></span>
                  <?php echo $inWish ? 'Wishlist' : 'Add to Wishlist'; ?>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No products available.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<script>
// All JS runs after DOM is ready (prevents null errors that break everything)
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filterForm');

  // Auto-submit on checkbox change
  form.querySelectorAll('input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', () => form.submit());
  });

  // Clear All
  const clearAll = document.getElementById('clearAll');
  if (clearAll) {
    clearAll.addEventListener('click', () => {
      form.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
      form.submit();
    });
  }

  // Clear individual groups
  document.querySelectorAll('.clear-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const block = btn.closest('.filter-block');
      if (block) block.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
      form.submit();
    });
  });

  // Helper to toggle a section and remember state
  function makeToggle(titleSel, bodySel, storageKey) {
    const title = document.querySelector(titleSel);
    const body  = document.querySelector(bodySel);
    if (!title || !body) return;

    // Apply saved state (if any)
    const saved = localStorage.getItem(storageKey);
    if (saved === 'open')  body.style.display = 'block';
    if (saved === 'close') body.style.display = 'none';

    title.addEventListener('click', () => {
      const isOpen = body.style.display !== 'none';
      body.style.display = isOpen ? 'none' : 'block';
      title.textContent = title.textContent.replace(isOpen ? '▲' : '▼', isOpen ? '▼' : '▲');
      localStorage.setItem(storageKey, isOpen ? 'close' : 'open');
    });
  }

  makeToggle('.toggle-brands',     '.brand-list',                                'open_brands');
  makeToggle('.toggle-categories', '[data-block="categories"] .filter-body',     'open_categories');
  makeToggle('.toggle-price',      '[data-block="priceRanges"] .filter-body',    'open_price');
  makeToggle('.toggle-highlights', '[data-block="badges"] .filter-body',         'open_highlights');

  // Brand chips -> toggle corresponding checkbox
  function cssEscape(v){ try { return CSS.escape(v); } catch(e){ return v.replace(/"/g,'\\"'); } }
  document.querySelectorAll('.brand-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      const brand = chip.dataset.brand;
      const box = form.querySelector(`input[name="brands[]"][value="${cssEscape(brand)}"]`);
      if (box) { box.checked = !box.checked; form.submit(); }
    });
  });
});
</script>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="toast" id="toast"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
  <script>
    const t = document.getElementById('toast');
    setTimeout(()=> t.style.opacity='0', 2000);
    setTimeout(()=> t.remove(), 2600);
  </script>
<?php endif; ?>
<script>
// Save scroll position BEFORE submitting form
document.querySelectorAll("#filterForm input[type=checkbox]").forEach(cb => {
    cb.addEventListener("change", (e) => {
        // store scroll position
        localStorage.setItem("scrollY", window.scrollY);

        // submit form
        document.getElementById("filterForm").submit();
    });
});

// Clear All
document.getElementById("clearAll")?.addEventListener("click", function () {
    document.querySelectorAll("#filterForm input[type=checkbox]").forEach(cb => cb.checked = false);
    localStorage.setItem("scrollY", window.scrollY);
    document.getElementById("filterForm").submit();
});

// Clear Group
document.querySelectorAll(".clear-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        const block = this.closest(".filter-block");
        if (block) block.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
        localStorage.setItem("scrollY", window.scrollY);
        document.getElementById("filterForm").submit();
    });
});

// Restore scroll position after reload
window.addEventListener("load", function() {
    const y = localStorage.getItem("scrollY");
    if (y !== null) {
        window.scrollTo(0, parseInt(y));
        localStorage.removeItem("scrollY"); // clear after use
    }
});
</script>

</body>
</html>
