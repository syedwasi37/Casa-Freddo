<?php
$pageTitle = 'Users';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
ensureCustomerTables($pdo);
require_once 'includes/admin_header.php';

$users = $pdo->query("SELECT id, username, full_name, email, phone, is_admin, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<div class="section-header"><h2>Registered Users</h2></div>
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Role</th><th>Created</th></tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo (int)$u['id']; ?></td>
                <td><?php echo sanitize($u['full_name'] ?: '-'); ?></td>
                <td><?php echo sanitize($u['username']); ?></td>
                <td><?php echo sanitize($u['email'] ?: '-'); ?></td>
                <td><?php echo sanitize($u['phone'] ?: '-'); ?></td>
                <td><?php echo ((int)$u['is_admin'] === 1) ? 'Admin' : 'Customer'; ?></td>
                <td><?php echo sanitize($u['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once 'includes/admin_footer.php'; ?>

