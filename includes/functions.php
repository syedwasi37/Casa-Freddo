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

