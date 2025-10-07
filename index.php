<?php
session_start();
include_once __DIR__ . '/db.php';
include 'includes/header.php'; // ✅ HEADER INCLUDED

// Restore session if user is remembered
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = (int)$_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'] ?? null;
}

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// ==================== WISHLIST IDS ====================
$wish_ids = [];
if ($user_id) {
    $wq = $conn->prepare("SELECT product_id FROM wishlist WHERE user_id=?");
    $wq->bind_param("i", $user_id);
    $wq->execute();
    $wr = $wq->get_result();
    while ($w = $wr->fetch_assoc()) $wish_ids[] = (int)$w['product_id'];
}

// ==================== CART COUNT ====================
$sqlCartCount = "SELECT SUM(quantity) as total_items FROM cart WHERE session_id=? OR user_id=?";
$stmtC = $conn->prepare($sqlCartCount);
$stmtC->bind_param("si", $session_id, $user_id);
$stmtC->execute();
$resC = $stmtC->get_result();
$rowC = $resC->fetch_assoc();
$cart_count = $rowC['total_items'] ?? 0;

// ==================== BRANDS ====================
$brands = [];
$resB = $conn->query("SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand<>'' ORDER BY brand");
while ($r = $resB->fetch_assoc()) $brands[] = $r['brand'];

// ==================== CATEGORIES ====================
$categories = [];
$resCat = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category<>'' ORDER BY category");
if ($resCat && $resCat->num_rows) {
    while ($r = $resCat->fetch_assoc()) $categories[] = $r['category'];
} else {
    $categories = ['Men','Women','Unisex','Couple','Smart'];
}

// ==================== BUILD PRODUCT QUERY ====================
$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types = "";

// --- Brands filter ---
if (!empty($_GET['brands']) && is_array($_GET['brands'])) {
    $in = implode(",", array_fill(0, count($_GET['brands']), "?"));
    $sql .= " AND brand IN ($in)";
    foreach ($_GET['brands'] as $b) { $params[] = $b; $types .= "s"; }
}

// --- Categories filter ---
if (!empty($_GET['categories']) && is_array($_GET['categories'])) {
    $in = implode(",", array_fill(0, count($_GET['categories']), "?"));
    $sql .= " AND category IN ($in)";
    foreach ($_GET['categories'] as $c) { $params[] = $c; $types .= "s"; }
}

// --- Price filter ---
if (!empty($_GET['priceRanges']) && is_array($_GET['priceRanges'])) {
    $conds = [];
    foreach ($_GET['priceRanges'] as $range) {
        [$min, $max] = array_pad(explode("-", $range, 2), 2, 0);
        $conds[] = "(price BETWEEN ? AND ?)";
        $params[] = (float)$min; $params[] = (float)$max; $types .= "dd";
    }
    if ($conds) $sql .= " AND (" . implode(" OR ", $conds) . ")";
}

// --- Highlights filter ---
if (!empty($_GET['topRated']))  $sql .= " AND is_top_rated=1";
if (!empty($_GET['topSeller'])) $sql .= " AND is_top_seller=1";

$sql .= " ORDER BY id DESC";

// --- Execute query ---
$stmt = $conn->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$resP = $stmt->get_result();
$products = [];
while ($r = $resP->fetch_assoc()) $products[] = $r;

// Which sections should be open by default
$brandsOpen     = !empty($_GET['brands']);
$categoriesOpen = !empty($_GET['categories']);
$priceOpen      = !empty($_GET['priceRanges']);
$highlightsOpen = !empty($_GET['topRated']) || !empty($_GET['topSeller']);
?>

<!-- ==================== BANNER SLIDER ==================== -->
<section class="banner-slider">
  <!-- Men -->
  <div class="slide active">
    <img src="brand_logo/men_banner.jpg" alt="Men Banner">
    <div class="overlay-text">
      <h2>Premium Watches for Men</h2>
      <p>Style Meets Precision</p>
      <a href="products.php?category=men" class="shop-btn">Shop Now</a>
    </div>
  </div>

  <!-- Women -->
  <div class="slide">
    <img src="brand_logo/women_banner.jpg" alt="Women Banner">
    <div class="overlay-text">
      <h2>Luxury Watches for Women</h2>
      <p>Elegance on Every Wrist</p>
      <a href="products.php?category=women" class="shop-btn">Shop Now</a>
    </div>
  </div>

  <!-- Couple -->
  <div class="slide">
    <img src="brand_logo/couple1_banner.jpg" alt="Couple Banner">
    <div class="overlay-text">
      <h2>Couple Edition</h2>
      <p>Perfect Pair, Perfect Time</p>
      <a href="products.php?category=couple" class="shop-btn">Shop Now</a>
    </div>
  </div>

  <!-- Premium -->
  <div class="slide">
    <img src="brand_logo/premium_banner.jpg" alt="Premium Banner">
    <div class="overlay-text">
      <h2>TickNShop Premium</h2>
      <p>Timeless Luxury, Unmatched Craftsmanship</p>
      <a href="products.php?category=premium" class="shop-btn">Shop Now</a>
    </div>
  </div>
</section>

<!-- Navigation Arrows -->
  <button class="banner-prev">&#10094;</button>
  <button class="banner-next">&#10095;</button>
  <!-- Dots -->
  <div class="dots">
    <span class="dot active"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>

</section>

<script>
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
const prevBtn = document.querySelector(".banner-prev");
const nextBtn = document.querySelector(".banner-next");

let currentSlide = 0;
let slideInterval;

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle("active", i === index);
    dots[i].classList.toggle("active", i === index);
  });
  currentSlide = index;
}

function nextSlide() {
  let next = (currentSlide + 1) % slides.length;
  showSlide(next);
}

function prevSlide() {
  let prev = (currentSlide - 1 + slides.length) % slides.length;
  showSlide(prev);
}

function startAutoSlide() {
  slideInterval = setInterval(nextSlide, 4000);
}

function stopAutoSlide() {
  clearInterval(slideInterval);
}

// Dots click event
dots.forEach((dot, i) => {
  dot.addEventListener("click", () => {
    showSlide(i);
    stopAutoSlide();
    startAutoSlide();
  });
});

// Arrow click events
nextBtn.addEventListener("click", () => {
  nextSlide();
  stopAutoSlide();
  startAutoSlide();
});

prevBtn.addEventListener("click", () => {
  prevSlide();
  stopAutoSlide();
  startAutoSlide();
});

// Init
showSlide(currentSlide);
startAutoSlide();
</script>



<main class="main-content">
  <!-- ==================== SIDEBAR FILTERS ==================== -->
  <aside class="sidebar">
    <form method="GET" id="filterForm">
      <div class="filters-header">
        <h3>Filters</h3>
        <button type="button" id="clearAll" class="clear-all">Clear All</button>
      </div>

      <!-- BRANDS FILTER -->
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

      <!-- CATEGORIES FILTER -->
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

      <!-- PRICE FILTER -->
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

      <!-- HIGHLIGHTS FILTER -->
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

  <!-- ==================== PRODUCT GRID ==================== -->
  <section class="content-area">
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
              <form method="post" action="add_to_cart.php" style="display:inline;">
                <input type="hidden" name="product_id" value="<?php echo (int)$row['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#product-<?php echo (int)$row['id']; ?>">
                <button type="submit" class="buy">Add to Cart</button>
              </form>

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
<!-- Floating Feedback Button -->
<a href="feedback.php" class="feedback-btn">Feedback</a>

<style>
.feedback-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #D4AF37; /* Gold */
    color: black;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    transition: background 0.3s ease;
    z-index: 999;
}
.feedback-btn:hover {
    background: #FFD700; /* Brighter gold */
}
</style>

<?php include 'includes/footer.php'; // ✅ FOOTER INCLUDED ?>
