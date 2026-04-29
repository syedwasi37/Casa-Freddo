<?php
$pageTitle = 'Register';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirmPassword) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $insert->execute([$name, $email, $phone, $hash]);
            setFlashMessage('Registration successful. Please log in.', 'success');
            redirect('login.php');
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
                <h2>Create Account</h2>
                <p>Register once and order in seconds next time.</p>
                <ul class="auth-points">
                    <li>Secure customer profile</li>
                    <li>Saved cart and delivery info</li>
                    <li>Smoother checkout flow</li>
                </ul>
            </div>
            <div class="auth-card">
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo sanitize($error); ?></div>
                <?php endif; ?>
                <?php echo showFlashMessage(); ?>
                <form action="register.php" method="POST" data-validate>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary auth-btn" type="submit">Create Account</button>
                </form>
                <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
