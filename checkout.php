<?php
$pageTitle = 'Checkout';
require_once 'includes/db.php';
require_once 'includes/functions.php';
ensureCustomerTables($pdo);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isCustomerLoggedIn()) {
    setFlashMessage('Please login before checkout.', 'info');
    redirect('login.php?redirect=checkout.php');
}

if (empty($_SESSION['cart'])) {
    setFlashMessage('Your cart is empty.', 'error');
    redirect('menu.php');
}

$cartItems = [];
$total = 0;
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

if (empty($cartItems)) {
    setFlashMessage('Your cart is empty.', 'error');
    redirect('menu.php');
}

$location = $_SESSION['delivery_location'] ?? ['country' => '', 'city' => '', 'area' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country = sanitize($_POST['country'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $area = sanitize($_POST['area'] ?? '');
    $addressLine = sanitize($_POST['address_line'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    if (!$country || !$city || !$area || !$addressLine) {
        $error = 'Please complete delivery details.';
    } else {
        $orderStmt = $pdo->prepare("INSERT INTO orders (customer_id, total_amount, country, city, area, address_line, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $orderStmt->execute([$_SESSION['customer_id'], $total, $country, $city, $area, $addressLine, $notes]);

        $orderIdResult = $pdo->query("SELECT LAST_INSERT_ID() AS order_id");
        $orderIdRow = $orderIdResult->fetch();
        $orderId = (int)$orderIdRow['order_id'];

        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        }

        $_SESSION['delivery_location'] = ['country' => $country, 'city' => $city, 'area' => $area];
        $_SESSION['cart'] = [];
        setFlashMessage('Order placed successfully.', 'success');
        redirect('index.php');
    }
}

require_once 'includes/header.php';
?>
<section class="section section-cream" style="padding-top: 140px;">
    <div class="container">
        <p class="section-subtitle">Secure Order</p>
        <h2 class="section-title">Checkout <span>Now</span></h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo sanitize($error); ?></div>
        <?php endif; ?>
        <div class="checkout-layout">
            <div class="contact-form">
                <h3 style="margin-bottom:16px;">Delivery Details</h3>
                <form action="checkout.php" method="POST" data-validate>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control" required value="<?php echo sanitize($location['country']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control" required value="<?php echo sanitize($location['city']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" id="area" name="area" class="form-control" required value="<?php echo sanitize($location['area']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address_line">Street Address</label>
                        <input type="text" id="address_line" name="address_line" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Order Notes</label>
                        <textarea id="notes" name="notes" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Place Order</button>
                </form>
            </div>
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <?php foreach ($cartItems as $item): ?>
                    <p><span><?php echo sanitize($item['name']); ?> x <?php echo (int)$item['quantity']; ?></span> <span><?php echo formatPrice($item['line_total']); ?></span></p>
                <?php endforeach; ?>
                <p class="cart-total-row"><span>Total</span> <span><?php echo formatPrice($total); ?></span></p>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>

