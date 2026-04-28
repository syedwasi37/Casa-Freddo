<?php
/**
 * Casa Freddo - Admin Dashboard
 * Overview stats and quick actions
 */
$pageTitle = 'Dashboard';
require_once 'includes/admin_header.php';

// Fetch stats
$stats = [];

// Total menu items
$stats['items'] = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();

// Total categories
$stats['categories'] = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Total messages
$stats['messages'] = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

// Unread messages
$stats['unread'] = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

// Total revenue estimate (sum of all item prices for demo)
$stats['revenue'] = $pdo->query("SELECT COALESCE(SUM(price), 0) FROM menu_items")->fetchColumn();

// Recent messages
$recentMessages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Recent menu items
$recentItems = $pdo->query("SELECT m.*, c.name as category_name 
                            FROM menu_items m 
                            JOIN categories c ON m.category_id = c.id 
                            ORDER BY m.created_at DESC LIMIT 5")->fetchAll();
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">🍨</div>
        <span class="stat-number"><?php echo $stats['items']; ?></span>
        <p class="stat-label">Menu Items</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🏷️</div>
        <span class="stat-number"><?php echo $stats['categories']; ?></span>
        <p class="stat-label">Categories</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✉️</div>
        <span class="stat-number"><?php echo $stats['messages']; ?></span>
        <p class="stat-label">Messages</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <span class="stat-number"><?php echo formatPrice($stats['revenue']); ?></span>
        <p class="stat-label">Total Value</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="section-header" style="margin-bottom: 24px;">
    <h2>Quick Actions</h2>
</div>
<div style="display: flex; gap: 16px; margin-bottom: 40px; flex-wrap: wrap;">
    <a href="menu.php?action=add" class="btn btn-primary">+ Add Menu Item</a>
    <a href="categories.php?action=add" class="btn btn-success">+ Add Category</a>
    <a href="messages.php" class="btn btn-outline">
        View Messages
        <?php if ($stats['unread'] > 0): ?>
            <span style="background: var(--color-danger); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;"><?php echo $stats['unread']; ?> new</span>
        <?php endif; ?>
    </a>
</div>

<!-- Recent Items -->
<div class="section-header">
    <h2>Recent Menu Items</h2>
    <a href="menu.php" class="btn btn-sm btn-outline">View All</a>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Category</th>
                <th>Price</th>
                <th>Featured</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($recentItems) > 0): ?>
                <?php foreach ($recentItems as $item): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <?php if ($item['image'] && file_exists('../' . $item['image'])): ?>
                                <img src="../<?php echo sanitize($item['image']); ?>" alt="" class="table-image">
                            <?php else: ?>
                                <div class="table-image" style="display: flex; align-items: center; justify-content: center; background: var(--color-light-gray);">🍨</div>
                            <?php endif; ?>
                            <strong><?php echo sanitize($item['name']); ?></strong>
                        </div>
                    </td>
                    <td><?php echo sanitize($item['category_name']); ?></td>
                    <td><?php echo formatPrice($item['price']); ?></td>
                    <td>
                        <?php if ($item['is_featured']): ?>
                            <span class="badge badge-featured">Featured</span>
                        <?php else: ?>
                            <span class="badge badge-regular">Regular</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        <div class="empty-state-icon">🍨</div>
                        <p>No menu items yet. <a href="menu.php?action=add">Add your first item</a>.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Recent Messages -->
<div class="section-header" style="margin-top: 40px;">
    <h2>Recent Messages</h2>
    <a href="messages.php" class="btn btn-sm btn-outline">View All</a>
</div>

<?php if (count($recentMessages) > 0): ?>
    <?php foreach ($recentMessages as $msg): ?>
    <div class="message-card <?php echo $msg['is_read'] ? '' : 'unread'; ?>">
        <div class="message-header">
            <div class="message-sender">
                <h3><?php echo sanitize($msg['name']); ?></h3>
                <p><?php echo sanitize($msg['email']); ?></p>
            </div>
            <div class="message-date">
                <?php echo date('M d, Y \a\t h:i A', strtotime($msg['created_at'])); ?>
                <?php if (!$msg['is_read']): ?>
                    <span class="badge badge-unread" style="margin-left: 8px;">New</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="message-body">
            <?php echo nl2br(sanitize($msg['message'])); ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon">✉️</div>
        <p>No messages yet. They will appear here when customers use the contact form.</p>
    </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>

