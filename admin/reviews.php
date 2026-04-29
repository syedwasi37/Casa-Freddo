<?php
$pageTitle = 'Reviews';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
ensureCustomerTables($pdo);
require_once 'includes/admin_header.php';

$reviews = $pdo->query("SELECT r.*, u.username, u.full_name, m.name AS item_name
                        FROM reviews r
                        JOIN users u ON r.user_id = u.id
                        JOIN menu_items m ON r.menu_item_id = m.id
                        ORDER BY r.created_at DESC")->fetchAll();
?>
<div class="section-header"><h2>Customer Reviews</h2></div>
<?php if (count($reviews) === 0): ?>
<div class="empty-state"><p>No reviews yet.</p></div>
<?php else: ?>
<?php foreach ($reviews as $r): ?>
<div class="message-card">
    <div class="message-header">
        <div class="message-sender">
            <h3><?php echo sanitize($r['full_name'] ?: $r['username']); ?></h3>
            <p><?php echo sanitize($r['item_name']); ?> | Rating: <?php echo (int)$r['rating']; ?>/5</p>
        </div>
        <div class="message-date"><?php echo sanitize($r['created_at']); ?></div>
    </div>
    <div class="message-body"><?php echo nl2br(sanitize($r['comment'] ?: 'No comment')); ?></div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php require_once 'includes/admin_footer.php'; ?>

