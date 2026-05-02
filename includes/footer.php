<?php
/**
 * Casa Freddo - Shared Footer
 * Included on all frontend pages
 */
?>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="footer-logo">
                        <div class="logo-container">
                            <div class="logo-icon-styled">CF</div>
                            <div class="logo-text-styled">Casa Freddo</div>
                        </div>
                    </a>
                    <p class="footer-desc">Artisan gelato crafted with passion. Experience the authentic taste of Italy in every scoop.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram" class="social-link">📷</a>
                        <a href="#" aria-label="Facebook" class="social-link">📘</a>
                        <a href="#" aria-label="Twitter" class="social-link">🐦</a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li>📍 Scheme 33, Karachi, Pakistan</li>
                        <li>📍 Near Saima Icon Mall</li>
                        <li>📞 +92 300 1234567</li>
                        <li>✉️ hello@casafreddo.com</li>
                    </ul>
                </div>
                <div class="footer-hours">
                    <h4>Opening Hours</h4>
                    <ul>
                        <li><span>Mon - Fri</span> <span>10:00 AM - 10:00 PM</span></li>
                        <li><span>Sat - Sun</span> <span>09:00 AM - 11:00 PM</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Casa Freddo. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <div class="location-modal-backdrop" id="locationModalBackdrop" aria-hidden="true">
        <div class="location-modal" role="dialog" aria-modal="true" aria-labelledby="locationModalTitle">
            <div class="location-modal-head">
                <span class="location-chip">Delivery Zone</span>
                <h3 id="locationModalTitle">Choose Your Location</h3>
                <p>Select country, city and area before ordering.</p>
            </div>
            <form id="locationForm">
                <div class="location-grid">
                <div class="form-group">
                    <label for="modalCountry">Country</label>
                    <select id="modalCountry" name="country" class="form-control" required>
                        <option value="">Select Country</option>
                        <option value="Pakistan">Pakistan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalCity">City</label>
                    <select id="modalCity" name="city" class="form-control" required>
                        <option value="">Select City</option>
                        <option value="Karachi">Karachi</option>
                        <option value="Lahore">Lahore</option>
                        <option value="Islamabad">Islamabad</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalArea">Area</label>
                    <input type="text" id="modalArea" name="area" class="form-control" placeholder="e.g. Gulshan, DHA, Johar" required>
                </div>
                </div>
                <div class="location-actions">
                    <button type="button" class="btn btn-outline" id="skipLocation">Not Now</button>
                    <button type="submit" class="btn btn-primary">Save Location</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>
