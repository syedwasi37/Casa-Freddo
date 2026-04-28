<?php
/**
 * Casa Freddo - Admin Categories
 * CRUD for gelato categories
 */
$pageTitle = 'Categories';
require_once 'includes/admin_header.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('Category deleted successfully.', 'success');
    } catch (PDOException $e) {
        setFlashMessage('Cannot delete category: it has menu items assigned to it.', 'error');
    }
    redirect('categories.php');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    
    if (empty($name)) {
        setFlashMessage('Category name is required.', 'error');
    } else {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $id]);
            setFlashMessage('Category updated successfully.', 'success');
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            setFlashMessage('Category added successfully.', 'success');
        }
        redirect('categories.php');
    }
}

// Fetch single category for edit
$category = null;
if (($action === 'edit' || $action === 'add') && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch();
    if (!$category) {
        setFlashMessage('Category not found.', 'error');
        redirect('categories.php');
    }
}

// Fetch all categories with item count
$categories = $pdo->query("SELECT c.*, COUNT(m.id) as item_count 
                           FROM categories c 
                           LEFT JOIN menu_items m ON c.id = m.category_id 
                           GROUP BY c.id 
                           ORDER BY c.id")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Category Form -->
    <div class="section-header">
        <h2><?php echo $action === 'edit' ? 'Edit Category' : 'Add New Category'; ?></h2>
        <a href="categories.php" class="btn btn-sm btn-secondary">← Back</a>
    </div>

    <form action="categories.php<?php echo $id > 0 ? '?action=edit&id=' . $id : '?action=add'; ?>" method="POST" class="admin-form">
        <div class="form-row">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control" required 
                   value="<?php echo $category ? sanitize($category['name']) : ''; ?>"
                   placeholder="e.g., Classic, Premium, Seasonal">
        </div>
        <div class="form-row">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" 
                      placeholder="Brief description of this category"><?php echo $category ? sanitize($category['description']) : ''; ?></textarea>
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <?php echo $action === 'edit' ? 'Update Category' : 'Add Category'; ?>
            </button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php else: ?>
    <!-- Categories List -->
    <div class="section-header">
        <h2>All Categories</h2>
        <a href="categories.php?action=add" class="btn btn-success">+ Add Category</a>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td>#<?php echo $cat['id']; ?></td>
                        <td><strong><?php echo sanitize($cat['name']); ?></strong></td>
                        <td><?php echo sanitize($cat['description'] ?: '-'); ?></td>
                        <td><?php echo $cat['item_count']; ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="categories.php?action=edit&id=<?php echo $cat['id']; ?>" class="btn btn-edit">Edit</a>
                                <?php if ($cat['item_count'] == 0): ?>
                                    <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-delete">Delete</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-state-icon">🏷️</div>
                            <p>No categories found. <a href="categories.php?action=add">Add your first category</a>.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>

