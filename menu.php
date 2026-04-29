<?php
/**
 * Casa Freddo - Menu Page
 * Displays all gelato flavors with category filtering
 */

$pageTitle = 'Menu';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Get filter from URL
$activeCategory = isset($_GET['category']) ? (int)$_GET['category'] : 'all';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $itemId = (int)($_POST['item_id'] ?? 0);
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    if ($itemId > 0) {
        if (!isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId] = 0;
        }
        $_SESSION['cart'][$itemId] += $qty;
        setFlashMessage('Item added to cart.', 'success');
    } else {
        setFlashMessage('Unable to add item.', 'error');
    }
    redirect('menu.php' . ($activeCategory !== 'all' ? '?category=' . (int)$activeCategory : ''));
}

// Get all categories
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY id");
$categories = $catStmt->fetchAll();

// Build query based on filter
$sql = "SELECT m.*, c.name as category_name 
        FROM menu_items m 
        JOIN categories c ON m.category_id = c.id";
$params = [];

if ($activeCategory !== 'all' && is_numeric($activeCategory)) {
    $sql .= " WHERE m.category_id = ?";
    $params[] = $activeCategory;
}

$sql .= " ORDER BY m.category_id, m.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menuItems = $stmt->fetchAll();
?>

<section class="section section-cream" style="padding-top: 140px;">
    <div class="container">
        <?php echo showFlashMessage(); ?>
        <p class="section-subtitle reveal">Our Collection</p>
        <h2 class="section-title reveal">Gelato <span>Menu</span></h2>
        
        <!-- Category Filters -->
        <div class="menu-filters reveal">
            <button class="filter-btn <?php echo $activeCategory === 'all' ? 'active' : ''; ?>" data-filter="all" onclick="window.location.href='menu.php'">All</button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn <?php echo $activeCategory == $cat['id'] ? 'active' : ''; ?>" data-filter="<?php echo $cat['id']; ?>" onclick="window.location.href='menu.php?category=<?php echo $cat['id']; ?>'">
                <?php echo sanitize($cat['name']); ?>
            </button>
            <?php endforeach; ?>
        </div>
        
        <!-- Menu Grid -->
        <?php if (count($menuItems) > 0): ?>
        <div class="products-grid">
            <?php foreach ($menuItems as $item): ?>
            <div class="product-card reveal" data-category="<?php echo $item['category_id']; ?>">
                <div class="product-image-wrapper">
                    <?php if ($item['image'] && file_exists($item['image'])): ?>
                        <img src="<?php echo sanitize($item['image']); ?>" alt="<?php echo sanitize($item['name']); ?>" class="product-image">
                    <?php else: ?>
                        <div class="product-image" style="background: linear-gradient(135deg, #e8e0d5, #d4cbbf); display: flex; align-items: center; justify-content: center; color: var(--color-gray);">
                            🍨
                        </div>
                    <?php endif; ?>
                    <span class="product-category"><?php echo sanitize($item['category_name']); ?></span>
                    <button class="product-favorite" aria-label="Add to favorites">🤍</button>
                </div>
                <div class="product-info">
                    <h3 class="product-name"><?php echo sanitize($item['name']); ?></h3>
                    <p class="product-desc"><?php echo sanitize($item['description']); ?></p>
                    <div class="product-footer">
                        <span class="product-price"><?php echo formatPrice($item['price']); ?></span>
                        <form action="menu.php<?php echo $activeCategory !== 'all' ? '?category=' . (int)$activeCategory : ''; ?>" method="POST" class="add-cart-form">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="20" class="qty-input">
                            <button type="submit" class="btn btn-sm btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 0;">
            <p style="font-size: 1.2rem; color: var(--color-gray);">No items found in this category.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
