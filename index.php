<?php
/**
 * Casa Freddo - Home Page
 * Hero section, featured flavors, about preview
 */

$pageTitle = 'Home';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Fetch featured menu items
$stmt = $pdo->query("SELECT m.*, c.name as category_name 
                     FROM menu_items m 
                     JOIN categories c ON m.category_id = c.id 
                     WHERE m.is_featured = 1 
                     LIMIT 6");
$featuredItems = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-subtitle fade-in-up">  Gelato</span>
        <h1 class="hero-title fade-in-up delay-1">Artisan Gelato, <br><span style="color: var(--color-gold);">Crafted with Passion</span></h1>
        <p class="hero-desc fade-in-up delay-2">
            Experience the authentic taste of Italy with our handcrafted gelato, 
            made from the finest ingredients and traditional recipes passed down through generations.
        </p>
        <div class="hero-buttons fade-in-up delay-3">
            <a href="menu.php" class="btn btn-primary">Order Now</a>
            <a href="menu.php" class="btn btn-outline">View Menu</a>
        </div>
    </div>
</section>

<!-- Featured Flavors Section -->
<section class="section section-cream">
    <div class="container">
        <p class="section-subtitle reveal">Our Selection</p>
        <h2 class="section-title reveal">Featured <span>Flavors</span></h2>
        
        <div class="products-grid">
            <?php foreach ($featuredItems as $index => $item): ?>
            <div class="product-card reveal" data-category="<?php echo sanitize($item['category_id']); ?>">
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
                    <p class="product-desc"><?php echo sanitize(substr($item['description'], 0, 80)) . '...'; ?></p>
                    <div class="product-footer">
                        <span class="product-price"><?php echo formatPrice($item['price']); ?></span>
                        <a href="menu.php" class="btn btn-sm btn-outline">View</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="menu.php" class="btn btn-outline reveal">Explore Full Menu</a>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="section section-dark">
    <div class="container">
        <div class="about-grid">
            <div class="about-image reveal">
                <div style="width: 100%; height: 100%; min-height: 400px; background: linear-gradient(135deg, #1a1a1a, #2a2a2a); display: flex; align-items: center; justify-content: center; color: var(--color-gold); font-size: 5rem; border-radius: var(--radius);">
                    ❄
                </div>
            </div>
            <div class="about-content reveal">
                <p class="section-subtitle" style="text-align: left; margin-bottom: 10px;">Our Story</p>
                <h2>A Legacy of <span style="color: var(--color-gold);">Gelato Excellence</span></h2>
                <p>
                    Founded in the heart of Karachi by Syed Wasi Ul Hassan Shah, Casa Freddo brings the authentic Italian gelato experience 
                    to India. Our master gelatiere trained in Sicily, crafting each batch with passion and precision.
                </p>
                <p>
                    We use only natural ingredients — fresh milk, real fruits, and premium nuts — 
                    with no artificial colors or preservatives. Every scoop tells a story of tradition meets innovation.
                </p>
                <a href="about.php" class="btn btn-primary">Discover Our Story</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section section-cream">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <span class="stat-number" data-target="50">50+</span>
                <p class="stat-label">Unique Flavors</p>
            </div>
            <div class="stat-item reveal">
                <span class="stat-number" data-target="10000">10K+</span>
                <p class="stat-label">Happy Customers</p>
            </div>
            <div class="stat-item reveal">
                <span class="stat-number" data-target="15">15+</span>
                <p class="stat-label">Years Experience</p>
            </div>
            <div class="stat-item reveal">
                <span class="stat-number" data-target="5">5⭐</span>
                <p class="stat-label">Rated Excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="section" style="background: linear-gradient(135deg, var(--color-black), #1a1a1a); text-align: center;">
    <div class="container">
        <h2 class="section-title reveal" style="color: var(--color-cream);">Ready to Taste <span>Perfection?</span></h2>
        <p class="reveal" style="color: var(--color-gray); max-width: 600px; margin: 0 auto 40px; font-size: 1.1rem;">
            Visit our store or order online for a gelato experience unlike any other. 
            Every scoop is a journey to Italy.
        </p>
        <div class="reveal">
            <a href="menu.php" class="btn btn-primary" style="margin-right: 16px;">Order Online</a>
            <a href="contact.php" class="btn btn-outline-light">Visit Store</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

