<?php
/**
 * Casa Freddo - Contact Page
 * Contact form, store info, and map placeholder
 */

$pageTitle = 'Contact';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = sanitize($_POST['message'] ?? '');

    // Server-side validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($message) < 10) {
        $error = 'Message must be at least 10 characters long.';
    } else {
        // Insert into database
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            $success = 'Thank you for your message! We will get back to you soon.';
            // Clear form
            $name = $email = $message = '';
        } catch (PDOException $e) {
            $error = 'Something went wrong. Please try again later.';
        }
    }
}
?>

<section class="section section-cream" style="padding-top: 140px;">
    <div class="container">
        <p class="section-subtitle reveal">Get in Touch</p>
        <h1 class="section-title reveal">Contact <span>Us</span></h1>
        
        <div class="contact-grid" style="margin-top: 60px;">
            <!-- Contact Info -->
            <div class="contact-info reveal">
                <h3>We'd Love to Hear From You</h3>
                <p style="color: var(--color-gray); margin-bottom: 30px;">
                    Whether you have a question about our flavors, want to place a custom order, 
                    or just want to say hello, drop us a message!
                </p>
                
                <div class="contact-detail">
                    <div class="contact-icon">📍</div>
                    <div>
                        <h4>Visit Us</h4>
                        <p>Scheme 33, near Saima Icon Mall<br>Karachi, Pakistan</p>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-icon">📞</div>
                    <div>
                        <h4>Call Us</h4>
                        <p>+92 300 1234567<br>Mon - Sun, 11am - 11pm</p>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-icon">✉️</div>
                    <div>
                        <h4>Email Us</h4>
                        <p>hello@casafreddo.com<br>orders@casafreddo.com</p>
                    </div>
                </div>
                
                <!-- Map Placeholder -->
                <div class="map-placeholder" style="margin-top: 30px;">
                    <p style="position: absolute; bottom: 15px; text-align: center; width: 100%;">Interactive Map Placeholder</p>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="reveal">
                <div class="contact-form">
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 24px;">Send a Message</h3>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="contact.php" method="POST" data-validate>
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required 
                                   value="<?php echo isset($name) ? sanitize($name) : ''; ?>" 
                                   placeholder="Your full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" required 
                                   value="<?php echo isset($email) ? sanitize($email) : ''; ?>" 
                                   placeholder="your@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message *</label>
                            <textarea id="message" name="message" class="form-control" required 
                                      placeholder="Tell us what's on your mind..."><?php echo isset($message) ? sanitize($message) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

