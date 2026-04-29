<?php
/**
 * Casa Freddo - Admin Header
 * Included on all admin pages
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? sanitize($pageTitle) . ' | ' : ''; ?>Admin - Casa Freddo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="logo-icon">CF</span>
                <span class="logo-text">Casa Freddo</span>
                <small>Admin Panel</small>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>"><span class="nav-icon">D</span><span>Dashboard</span></a>
                <a href="menu.php" class="nav-item <?php echo $currentPage == 'menu' ? 'active' : ''; ?>"><span class="nav-icon">M</span><span>Menu Items</span></a>
                <a href="categories.php" class="nav-item <?php echo $currentPage == 'categories' ? 'active' : ''; ?>"><span class="nav-icon">C</span><span>Categories</span></a>
                <a href="orders.php" class="nav-item <?php echo $currentPage == 'orders' ? 'active' : ''; ?>"><span class="nav-icon">O</span><span>Orders</span></a>
                <a href="messages.php" class="nav-item <?php echo $currentPage == 'messages' ? 'active' : ''; ?>"><span class="nav-icon">Q</span><span>Complaints</span></a>
                <a href="reviews.php" class="nav-item <?php echo $currentPage == 'reviews' ? 'active' : ''; ?>"><span class="nav-icon">R</span><span>Reviews</span></a>
                <a href="users.php" class="nav-item <?php echo $currentPage == 'users' ? 'active' : ''; ?>"><span class="nav-icon">U</span><span>Users</span></a>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-user">
                    <span class="user-icon">A</span>
                    <span><?php echo sanitize($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                </div>
                <a href="logout.php" class="logout-btn">
                    <span>X</span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-header">
                <h1><?php echo isset($pageTitle) ? sanitize($pageTitle) : 'Dashboard'; ?></h1>
                <div class="header-actions">
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline">View Website</a>
                </div>
            </header>

            <div class="admin-content">
                <?php echo showFlashMessage(); ?>

