<?php
$pageTitle = 'Orders';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
ensureCustomerTables($pdo);
require_once 'includes/admin_header.php';

$orders = $pdo->query("SELECT o.*, u.username, u.full_name, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
?>
<div class="section-header"><h2>Order Management</h2></div>
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Customer</th><th>Total</th><th>Location</th><th>Status</th><th>Items</th><th>Date</th></tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?php echo (int)$o['id']; ?></td>
                <td><?php echo sanitize($o['full_name'] ?: $o['username']); ?><br><small><?php echo sanitize($o['email'] ?: '-'); ?></small></td>
                <td><?php echo formatPrice($o['total_amount']); ?></td>
                <td><?php echo sanitize($o['country'] . ', ' . $o['city'] . ', ' . $o['area']); ?></td>
                <td><?php echo sanitize($o['status']); ?></td>
                <td><details><summary>View</summary><pre style="white-space: pre-wrap;"><?php echo sanitize($o['order_items_json']); ?></pre></details></td>
                <td><?php echo sanitize($o['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once 'includes/admin_footer.php'; ?>

