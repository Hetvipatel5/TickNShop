<?php
session_start();
include 'db.php';

// Restore session from cookies (optional "stay logged in")
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = (int)$_COOKIE['user_id'];
    $_SESSION['username'] = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;
}

// Distinct brands for slider + sidebar
$brands = [];
$resB = $conn->query("SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand <> '' ORDER BY brand");
if ($resB) {
    while ($r = $resB->fetch_assoc()) $brands[] = $r['brand'];
}

// Distinct categories (fallback to common ones if table is empty)
$categories = [];
$resC = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category <> '' ORDER BY category");
if ($resC && $resC->num_rows) {
    while ($r = $resC->fetch_assoc()) $categories[] = $r['category'];
} else {
    $categories = ['Men','Women','Unisex','Couple','Smart'];
}

// Initial products
$products = [];
$resP = $conn->query("SELECT * FROM products ORDER BY id DESC");
if ($resP) {
    while ($r = $resP->fetch_assoc()) $products[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>TickNShop | Premium Watches</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="header">
    <div class="logo">TickNShop</div>
    <nav class="navbar">
        <a href="index.php">All Watches</a>
        <a href="#" data-cat="Men" class="nav-cat">Men</a>
        <a href="#" data-cat="Women" class="nav-cat">Women</a>
        <a href="#" data-cat="Unisex" class="nav-cat">Unisex</a>
        <a href="#" data-cat="Smart" class="nav-cat">Smart</a>
        <a href="#">Brands</a>
        <a href="#">Offers</a>
        <a href="#">Corporate Sale</a>
    </nav>
    <div class="icons">
        <span>🔍</span>
        <a href="wishlist.php" title="Wishlist"><span>❤</span></a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" title="Logout"><span>🔓</span></a>
        <?php else: ?>
            <a href="login.php" title="Login"><span>👤</span></a>
        <?php endif; ?>
        <span>🛒</span>
    </div>
</header>

<section class="banner">
    <div class="banner-text">
        <h1>SELECT FROM A CURATION</h1>
        <p>Of 40+ International Brands</p>
    </div>
</section>

<main class="main-content">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="filters-header">
            <h3>Filters</h3>
            <button id="clearAll" class="clear-all">Clear All</button>
        </div>

        <!-- Brands -->
        <div class="filter-block" data-block="brands">
            <div class="filter-title">
                <span>Brands</span>
                <button class="clear-btn" data-filter="brands">Clear</button>
            </div>
            <div class="filter-body scrollable">
                <?php foreach ($brands as $b): ?>
                    <label class="check-row">
                        <input type="checkbox" name="brands" value="<?php echo htmlspecialchars($b); ?>">
                        <span><?php echo htmlspecialchars($b); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Category -->
        <div class="filter-block" data-block="categories">
            <div class="filter-title">
                <span>Type / Gender</span>
                <button class="clear-btn" data-filter="categories">Clear</button>
            </div>
            <div class="filter-body">
                <?php foreach ($categories as $c): ?>
                    <label class="check-row">
                        <input type="checkbox" name="categories" value="<?php echo htmlspecialchars($c); ?>">
                        <span><?php echo htmlspecialchars($c); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Price -->
        <div class="filter-block" data-block="priceRanges">
            <div class="filter-title">
                <span>Price</span>
                <button class="clear-btn" data-filter="priceRanges">Clear</button>
            </div>
            <div class="filter-body">
                <label class="check-row"><input type="checkbox" name="priceRanges" value="0-5000"><span>Under ₹5,000</span></label>
                <label class="check-row"><input type="checkbox" name="priceRanges" value="5000-20000"><span>₹5,000 – ₹20,000</span></label>
                <label class="check-row"><input type="checkbox" name="priceRanges" value="20000-100000"><span>₹20,000 – ₹1,00,000</span></label>
                <label class="check-row"><input type="checkbox" name="priceRanges" value="100000-99999999"><span>₹1,00,000+</span></label>
            </div>
        </div>

        <!-- Badges -->
        <div class="filter-block" data-block="badges">
            <div class="filter-title">
                <span>Highlights</span>
                <button class="clear-btn" data-filter="badges">Clear</button>
            </div>
            <div class="filter-body">
                <label class="check-row"><input type="checkbox" name="topRated" value="1"><span>Top Rated</span></label>
                <label class="check-row"><input type="checkbox" name="topSeller" value="1"><span>Top Seller</span></label>
            </div>
        </div>
    </aside>

    <section class="content-area">
        <!-- BRAND SLIDER (clickable brand chips, horizontal scroll) -->
        <div id="brandSlider" class="brand-slider">
            <?php foreach ($brands as $b): ?>
                <button class="brand-chip" data-brand="<?php echo htmlspecialchars($b); ?>">
                    <?php echo htmlspecialchars($b); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- PRODUCTS -->
        <div id="productGrid" class="product-grid">
            <?php if (count($products)): ?>
                <?php foreach ($products as $row): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo (int)$row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        </a>
                        <p class="price">₹<?php echo number_format((float)$row['price'], 2); ?></p>
                        <div class="buttons">
                            <button class="buy">Add to Cart</button>
                            <a href="wishlist_add.php?id=<?php echo (int)$row['id']; ?>"><button class="wishlist">♡ Wishlist</button></a>
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
// Collect selected filters into a payload
function getFilters() {
    const getVals = (name) => Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(i => i.value);
    return {
        brands: getVals('brands'),
        categories: getVals('categories'),
        priceRanges: getVals('priceRanges'),
        topRated: document.querySelector('input[name="topRated"]:checked') ? 1 : 0,
        topSeller: document.querySelector('input[name="topSeller"]:checked') ? 1 : 0
    };
}

// Trigger AJAX filter
function applyFilters() {
    const payload = getFilters();
    fetch('filter_products.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.text())
    .then(html => {
        document.getElementById('productGrid').innerHTML = html;
        // sync brand chips active state
        syncBrandChips();
    })
    .catch(() => {
        document.getElementById('productGrid').innerHTML = '<p style="color:#FFD700;text-align:center;">Failed to load products.</p>';
    });
}

// Sidebar listeners (any checkbox change)
document.querySelectorAll('.sidebar input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', applyFilters);
});

// Clear buttons per section
document.querySelectorAll('.clear-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-filter'); // brands | categories | priceRanges | badges
        if (key === 'badges') {
            document.querySelectorAll('input[name="topRated"], input[name="topSeller"]').forEach(i => i.checked = false);
        } else {
            document.querySelectorAll(`input[name="${key}"]`).forEach(i => i.checked = false);
        }
        applyFilters();
    });
});

// Clear All
document.getElementById('clearAll').addEventListener('click', () => {
    document.querySelectorAll('.sidebar input[type="checkbox"]').forEach(i => i.checked = false);
    // also clear active brand chips
    document.querySelectorAll('.brand-chip.active').forEach(c => c.classList.remove('active'));
    applyFilters();
});

// Brand slider clicks toggle brand filter
function syncBrandChips() {
    const selected = new Set(Array.from(document.querySelectorAll('input[name="brands"]:checked')).map(i => i.value));
    document.querySelectorAll('.brand-chip').forEach(chip => {
        const val = chip.getAttribute('data-brand');
        chip.classList.toggle('active', selected.has(val));
    });
}
document.querySelectorAll('.brand-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        const brand = chip.getAttribute('data-brand');
        // toggle the matching checkbox
        const cb = document.querySelector(`input[name="brands"][value="${CSS.escape(brand)}"]`);
        if (cb) cb.checked = !cb.checked;
        applyFilters();
    });
});

// Top navbar category quick filter
document.querySelectorAll('.nav-cat').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const cat = link.getAttribute('data-cat');
        // toggle only that category on; clear others
        document.querySelectorAll('input[name="categories"]').forEach(i => i.checked = (i.value === cat));
        applyFilters();
    });
});

// On load, sync brand chip active states (no filters yet)
syncBrandChips();
</script>
</body>
</html>
