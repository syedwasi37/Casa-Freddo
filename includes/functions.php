<?php
/**
 * Casa Freddo - Helper Functions
 */

/**
 * Sanitize user input to prevent XSS
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Display flash messages from session
 */
function showFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return "<div class='alert alert-{$type}'>{$msg}</div>";
    }
    return '';
}

/**
 * Set flash message in session
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Check if user is logged in (admin)
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Check if a customer is logged in
 */
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

/**
 * Get customer display name
 */
function getCustomerName() {
    return $_SESSION['customer_name'] ?? 'Guest';
}

/**
 * Redirect to a given URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Format price with currency symbol
 */
function formatPrice($price) {
    return 'Rs ' . number_format($price, 2);
}

/**
 * Create customer/order tables if missing
 */
function ensureCustomerTables($pdo) {
    $pdo->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) NOT NULL DEFAULT 0");
    $pdo->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS full_name VARCHAR(120) DEFAULT NULL");
    $pdo->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL");
    $pdo->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(30) DEFAULT NULL");
    $pdo->query("ALTER TABLE users ADD UNIQUE KEY IF NOT EXISTS uniq_users_email (email)");
    $pdo->query("UPDATE users SET is_admin = 1 WHERE id = 1 AND (is_admin = 0 OR is_admin IS NULL)");

    $pdo->query("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status VARCHAR(30) NOT NULL DEFAULT 'pending',
        country VARCHAR(100) NOT NULL,
        city VARCHAR(100) NOT NULL,
        area VARCHAR(150) NOT NULL,
        address_line VARCHAR(255) NOT NULL,
        order_items_json LONGTEXT NOT NULL,
        notes TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->query("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        menu_item_id INT NOT NULL,
        rating TINYINT NOT NULL,
        comment TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
}

/**
 * Get total cart items count
 */
function getCartCount() {
    if (empty($_SESSION['cart'])) {
        return 0;
    }

    $count = 0;
    foreach ($_SESSION['cart'] as $qty) {
        $count += (int)$qty;
    }
    return $count;
}

/**
 * Upload image file and return path or false
 */
function uploadImage($file, $uploadDir = '../uploads/') {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return false;
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }

    if ($file['size'] > $maxSize) {
        return false;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . strtolower($ext);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'uploads/' . $filename;
    }

    return false;
}
?>
