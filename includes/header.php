<?php
ob_start();
/**
 * Casa Freddo - Shared Header
 * Included on all frontend pages
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/functions.php';
$cartCount = getCartCount();
$customerLoggedIn = isCustomerLoggedIn();
$customerName = $customerLoggedIn ? getCustomerName() : '';
$locationLabel = $_SESSION['delivery_location']['area'] ?? 'Set Location';
$cartNotice = $_SESSION['cart_notice'] ?? '';
unset($_SESSION['cart_notice']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? sanitize($pageTitle) . ' | ' : ''; ?>Casa Freddo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <div class="logo-container">
                    <div class="logo-icon-styled">CF</div>
                    <div class="logo-text-styled">Casa Freddo</div>
                </div>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="menu.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">Menu</a></li>
                <li><a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
                <li><a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                <?php if ($customerLoggedIn): ?>
                    <?php if ($cartCount > 0): ?>
                        <li><a href="checkout.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'active' : ''; ?>">Checkout</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="nav-link">Logout (<?php echo sanitize($customerName); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">Login</a></li>
                    <li><a href="register.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">Register</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-utility">
                <button type="button" id="locationTrigger" class="nav-link nav-link-btn"><?php echo sanitize($locationLabel); ?></button>
                <a href="cart.php" class="cart-icon-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>" aria-label="Open cart">
                    <span class="cart-icon">🛒</span>
                    <span class="cart-count"><?php echo (int)$cartCount; ?></span>
                </a>
            </div>
        </div>
    </nav>
    <?php if ($cartNotice): ?>
    <div class="cart-toast" id="cartToast"><?php echo sanitize($cartNotice); ?></div>
    <?php endif; ?>
    <main>
