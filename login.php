<?php
$pageTitle = 'Login';
require_once 'includes/db.php';
require_once 'includes/functions.php';
ensureCustomerTables($pdo);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isCustomerLoggedIn()) {
    redirect('index.php');
}

$error = '';
$redirectTo = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $redirectTo = $_POST['redirect'] ?? 'index.php';

    if (!$email || !$password) {
        $error = 'Please enter email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            $_SESSION['customer_email'] = $user['email'];
            redirect($redirectTo);
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

require_once 'includes/header.php';
?>
<section class="section section-cream auth-wrap" style="padding-top: 140px;">
    <div class="container">
        <div class="auth-shell reveal active">
            <div class="auth-side">
                <span class="auth-kicker">Casa Freddo</span>
                <h2>Welcome Back</h2>
                <p>Sign in to continue your order and checkout faster.</p>
                <ul class="auth-points">
                    <li>Faster checkout</li>
                    <li>Saved delivery location</li>
                    <li>Track recent orders</li>
                </ul>
            </div>
            <div class="auth-card">
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo sanitize($error); ?></div>
                <?php endif; ?>
                <?php echo showFlashMessage(); ?>
                <form action="login.php" method="POST" data-validate>
                    <input type="hidden" name="redirect" value="<?php echo sanitize($redirectTo); ?>">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary auth-btn" type="submit">Login</button>
                </form>
                <p class="auth-switch">New customer? <a href="register.php">Create account</a></p>
                <p class="auth-mini"><a href="menu.php">Continue as guest</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
