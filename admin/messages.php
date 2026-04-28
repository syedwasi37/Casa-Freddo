<?php
/**
 * Casa Freddo - Admin Messages
 * View and manage contact form submissions
 */
$pageTitle = 'Messages';
require_once 'includes/admin_header.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Mark as Read/Unread
if ($action === 'read' && $id > 0) {
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    setFlashMessage('Message marked as read.', 'success');
    redirect('messages.php');
}

if ($action === 'unread' && $id > 0) {
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 0 WHERE id = ?");
    $stmt->execute([$id]);
    setFlashMessage('Message marked as unread.', 'success');
    redirect('messages.php');
}

// Handle Delete
if ($action === 'delete' && $id > 0) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    setFlashMessage('Message deleted.', 'success');
    redirect('messages.php');
}

// Fetch all messages
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>

<div class="section-header">
    <h2>Contact Messages</h2>
    <div style="display: flex; gap: 12px;">
        <a href="messages.php" class="btn btn-sm btn-outline">All</a>
    </div>
</div>

<?php if (count($messages) > 0): ?>
    <?php foreach ($messages as $msg): ?>
    <div class="message-card <?php echo $msg['is_read'] ? '' : 'unread'; ?>">
        <div class="message-header">
            <div class="message-sender">
                <h3><?php echo sanitize($msg['name']); ?></h3>
                <p><a href="mailto:<?php echo sanitize($msg['email']); ?>" style="color: var(--color-gold);"><?php echo sanitize($msg['email']); ?></a></p>
            </div>
            <div style="text-align: right;">
                <div class="message-date"><?php echo date('M d, Y \a\t h:i A', strtotime($msg['created_at'])); ?></div>
                <div class="table-actions" style="margin-top: 10px; justify-content: flex-end;">
                    <?php if ($msg['is_read']): ?>
                        <a href="messages.php?action=unread&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-secondary">Mark Unread</a>
                    <?php else: ?>
                        <a href="messages.php?action=read&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-success">Mark Read</a>
                    <?php endif; ?>
                    <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-delete">Delete</a>
                </div>
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
        <p>No messages yet. Customer submissions from the contact form will appear here.</p>
    </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>

