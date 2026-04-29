<?php
$pageTitle = 'Cart';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $itemId = (int)($_POST['item_id'] ?? 0);

    if ($action === 'update' && $itemId > 0) {
        $qty = max(0, (int)($_POST['quantity'] ?? 1));
        if ($qty === 0) {
            unset($_SESSION['cart'][$itemId]);
        } else {
            $_SESSION['cart'][$itemId] = $qty;
        }
    } elseif ($action === 'remove' && $itemId > 0) {
        unset($_SESSION['cart'][$itemId]);
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
    }

    redirect('cart.php');
}

$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $result = $pdo->query("SELECT * FROM menu_items WHERE id IN (" . implode(',', $ids) . ")");
    $rows = $result->fetchAll();
    foreach ($rows as $row) {
        $qty = (int)($_SESSION['cart'][$row['id']] ?? 0);
        if ($qty > 0) {
            $lineTotal = $qty * (float)$row['price'];
            $row['quantity'] = $qty;
            $row['line_total'] = $lineTotal;
            $cartItems[] = $row;
            $total += $lineTotal;
        }
    }
}
?>
<section class="section section-cream" style="padding-top: 140px;">
    <div class="container">
        <p class="section-subtitle">Your Basket</p>
        <h2 class="section-title">Shopping <span>Cart</span></h2>
        <?php if (empty($cartItems)): ?>
            <div class="empty-state">
                <p>Your cart is empty.</p>
                <a href="menu.php" class="btn btn-primary">Browse Menu</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?php echo sanitize($item['name']); ?></td>
                                    <td><?php echo formatPrice($item['price']); ?></td>
                                    <td>
                                        <form action="cart.php" method="POST" class="cart-inline-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo (int)$item['quantity']; ?>" min="0" class="qty-input">
                                            <button type="submit" class="btn btn-sm btn-outline">Update</button>
                                        </form>
                                    </td>
                                    <td><?php echo formatPrice($item['line_total']); ?></td>
                                    <td>
                                        <form action="cart.php" method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                            <button class="btn btn-sm btn-outline" type="submit">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="cart-summary">
                    <h3>Summary</h3>
                    <p><span>Subtotal</span> <span><?php echo formatPrice($total); ?></span></p>
                    <p><span>Delivery</span> <span>Calculated at checkout</span></p>
                    <p class="cart-total-row"><span>Total</span> <span><?php echo formatPrice($total); ?></span></p>
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                    <form action="cart.php" method="POST" style="margin-top: 12px;">
                        <input type="hidden" name="action" value="clear">
                        <button class="btn btn-outline" type="submit">Clear Cart</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>

