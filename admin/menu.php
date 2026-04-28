<?php
/**
 * Casa Freddo - Admin Menu Items
 * CRUD for gelato menu items with image upload
 */
$pageTitle = 'Menu Items';
require_once 'includes/admin_header.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete
if ($action === 'delete' && $id > 0) {
    // Get image path to delete file
    $stmt = $pdo->prepare("SELECT image FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if ($item && $item['image'] && file_exists('../' . $item['image'])) {
        unlink('../' . $item['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    setFlashMessage('Menu item deleted successfully.', 'success');
    redirect('menu.php');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';
    if ($category_id <= 0) $errors[] = 'Please select a category.';
    
    if (empty($errors)) {
        $imagePath = null;
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadImage($_FILES['image'], '../uploads/');
            if ($uploaded) {
                $imagePath = $uploaded;
            } else {
                $errors[] = 'Image upload failed. Please use JPG, PNG, GIF, or WEBP under 2MB.';
            }
        }
        
        if (empty($errors)) {
            if ($id > 0) {
                // Update existing
                if ($imagePath) {
                    // Delete old image
                    $stmt = $pdo->prepare("SELECT image FROM menu_items WHERE id = ?");
                    $stmt->execute([$id]);
                    $old = $stmt->fetch();
                    if ($old && $old['image'] && file_exists('../' . $old['image'])) {
                        unlink('../' . $old['image']);
                    }
                    $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, description = ?, price = ?, category_id = ?, is_featured = ?, image = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category_id, $is_featured, $imagePath, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, description = ?, price = ?, category_id = ?, is_featured = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category_id, $is_featured, $id]);
                }
                setFlashMessage('Menu item updated successfully.', 'success');
            } else {
                // Insert new
                $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, image, category_id, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $imagePath, $category_id, $is_featured]);
                setFlashMessage('Menu item added successfully.', 'success');
            }
            redirect('menu.php');
        }
    }
    
    if (!empty($errors)) {
        foreach ($errors as $err) {
            setFlashMessage($err, 'error');
        }
    }
}

// Fetch single item for edit
$item = null;
if (($action === 'edit') && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        setFlashMessage('Menu item not found.', 'error');
        redirect('menu.php');
    }
}

// Fetch all categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Fetch all menu items with category names
$menuItems = $pdo->query("SELECT m.*, c.name as category_name 
                          FROM menu_items m 
                          JOIN categories c ON m.category_id = c.id 
                          ORDER BY m.created_at DESC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Menu Item Form -->
    <div class="section-header">
        <h2><?php echo $action === 'edit' ? 'Edit Menu Item' : 'Add New Menu Item'; ?></h2>
        <a href="menu.php" class="btn btn-sm btn-secondary">← Back</a>
    </div>

    <form action="menu.php<?php echo $id > 0 ? '?action=edit&id=' . $id : '?action=add'; ?>" method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-row">
            <label for="name">Item Name *</label>
            <input type="text" id="name" name="name" class="form-control" required 
                   value="<?php echo $item ? sanitize($item['name']) : ''; ?>"
                   placeholder="e.g., Vanilla Bean Dream">
        </div>
        
        <div class="form-row">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" 
                      placeholder="Describe the flavor and ingredients"><?php echo $item ? sanitize($item['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-row">
            <label for="price">Price (₹) *</label>
            <input type="number" id="price" name="price" class="form-control" required step="0.01" min="0.01"
                   value="<?php echo $item ? $item['price'] : ''; ?>"
                   placeholder="e.g., 180.00">
        </div>
        
        <div class="form-row">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($item && $item['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo sanitize($cat['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-row">
            <label class="checkbox-wrapper">
                <input type="checkbox" name="is_featured" value="1" <?php echo ($item && $item['is_featured']) ? 'checked' : ''; ?>>
                <span>Feature this item on the homepage</span>
            </label>
        </div>
        
        <div class="form-row">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
            <small style="color: var(--color-gray); display: block; margin-top: 6px;">JPG, PNG, GIF, or WEBP. Max 2MB. Leave empty to keep existing image.</small>
            <?php if ($item && $item['image']): ?>
                <div style="margin-top: 12px;">
                    <p style="font-size: 0.85rem; color: var(--color-gray); margin-bottom: 6px;">Current image:</p>
                    <img src="../<?php echo sanitize($item['image']); ?>" alt="" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                </div>
            <?php endif; ?>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <?php echo $action === 'edit' ? 'Update Item' : 'Add Item'; ?>
            </button>
            <a href="menu.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php else: ?>
    <!-- Menu Items List -->
    <div class="section-header">
        <h2>All Menu Items</h2>
        <a href="menu.php?action=add" class="btn btn-success">+ Add Item</a>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($menuItems) > 0): ?>
                    <?php foreach ($menuItems as $m): ?>
                    <tr>
                        <td>
                            <?php if ($m['image'] && file_exists('../' . $m['image'])): ?>
                                <img src="../<?php echo sanitize($m['image']); ?>" alt="" class="table-image">
                            <?php else: ?>
                                <div class="table-image" style="display: flex; align-items: center; justify-content: center;">🍨</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo sanitize($m['name']); ?></strong></td>
                        <td><?php echo sanitize($m['category_name']); ?></td>
                        <td><?php echo formatPrice($m['price']); ?></td>
                        <td>
                            <?php if ($m['is_featured']): ?>
                                <span class="badge badge-featured">Featured</span>
                            <?php else: ?>
                                <span class="badge badge-regular">No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="menu.php?action=edit&id=<?php echo $m['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="menu.php?action=delete&id=<?php echo $m['id']; ?>" class="btn btn-delete">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-icon">🍨</div>
                            <p>No menu items found. <a href="menu.php?action=add">Add your first item</a>.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>

