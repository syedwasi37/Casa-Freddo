<?php
/**
 * Casa Freddo - About Page
 * Brand story, mission, and vision
 */

$pageTitle = 'About';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<section class="section section-cream" style="padding-top: 140px;">
    <div class="container">
        <p class="section-subtitle reveal">Who We Are</p>
        <h1 class="section-title reveal" style="font-size: 3.5rem;">The Casa Freddo <span>Story</span></h1>
        
        <!-- Story Section -->
        <div class="about-grid" style="margin-top: 60px;">
            <div class="about-image reveal">
                <div style="width: 100%; min-height: 450px; background: linear-gradient(135deg, #1a1a1a, #2a2a2a); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--color-cream); border-radius: var(--radius); padding: 40px; text-align: center;">
                    <span style="font-size: 4rem; margin-bottom: 20px;">❄</span>
                    <h3 style="font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 10px;">Casa Freddo</h3>
                    <p style="color: var(--color-gold); text-transform: uppercase; letter-spacing: 3px; font-size: 0.85rem;">Est. 2009</p>
                </div>
            </div>
            <div class="about-content reveal">
                <h2 style="color: var(--color-black); font-size: 2.2rem;">From Sicily to <span style="color: var(--color-gold);">Karachi</span></h2>
                <p>
                    Casa Freddo was born from a simple dream: to bring authentic Italian gelato to Pakistan. 
                    Our founder, Syed Wasi Ul Hassan Shah, after training under master gelatieri in Sicily, returned home with a mission — 
                    to create gelato that honors tradition while embracing local flavors.
                </p>
                <p>
                    Every batch is churned fresh daily using time-honored techniques. We source our milk from 
                    local dairy farms, import pistachios from Bronte, and use real Madagascar vanilla beans. 
                    No shortcuts, no compromises.
                </p>
                <p>
                    Over the years, Casa Freddo has grown from a tiny corner shop to one of Karachi's most 
                    beloved gelato destinations. But our commitment to quality remains unchanged.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="section section-dark">
    <div class="container">
        <p class="section-subtitle reveal">What Drives Us</p>
        <h2 class="section-title reveal">Our <span>Mission</span> & Values</h2>
        
        <div class="values-grid">
            <div class="reveal">
                <div style="width: 70px; height: 70px; background: rgba(201,169,110,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem;">🌿</div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; margin-bottom: 12px; color: var(--color-cream);">Natural Ingredients</h3>
                <p style="color: var(--color-gray); font-size: 0.95rem; line-height: 1.7;">We never use artificial colors, flavors, or preservatives. Only real fruits, fresh dairy, and premium nuts.</p>
            </div>
            <div class="reveal">
                <div style="width: 70px; height: 70px; background: rgba(201,169,110,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem;">🇮🇹</div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; margin-bottom: 12px; color: var(--color-cream);">Italian Tradition</h3>
                <p style="color: var(--color-gray); font-size: 0.95rem; line-height: 1.7;">Our recipes are rooted in centuries-old Sicilian gelato-making traditions passed down through generations.</p>
            </div>
            <div class="reveal">
                <div style="width: 70px; height: 70px; background: rgba(201,169,110,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem;">💡</div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; margin-bottom: 12px; color: var(--color-cream);">Constant Innovation</h3>
                <p style="color: var(--color-gray); font-size: 0.95rem; line-height: 1.7;">While we honor tradition, we also love experimenting with bold new flavors inspired by India's rich palate.</p>
            </div>
        </div>
    </div>
</section>

<!-- Future Vision Section -->
<section class="section section-cream">
    <div class="container">
        <div class="about-grid">
            <div class="about-content reveal">
                <p class="section-subtitle" style="text-align: left; margin-bottom: 10px;">Looking Ahead</p>
                <h2 style="font-size: 2.2rem; color: var(--color-black);">Our Vision for the <span style="color: var(--color-gold);">Future</span></h2>
                <p>
                    Casa Freddo is more than just gelato — we are building an ecosystem of premium dessert experiences. 
                    In the coming years, we plan to expand our offerings to include:
                </p>
                <ul style="list-style: none; margin: 20px 0;">
                    <li style="padding: 10px 0; display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--color-gold); font-size: 1.2rem;">☕</span>
                        <span><strong>Artisan Coffee Bar</strong> — Single-origin brews paired with our gelato</span>
                    </li>
                    <li style="padding: 10px 0; display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--color-gold); font-size: 1.2rem;">🍰</span>
                        <span><strong>Pastry & Desserts</strong> — Italian pastries, tiramisu, and cannoli</span>
                    </li>
                    <li style="padding: 10px 0; display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--color-gold); font-size: 1.2rem;">🍫</span>
                        <span><strong>Bean-to-Bar Chocolate</strong> — House-made chocolate creations</span>
                    </li>
                    <li style="padding: 10px 0; display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--color-gold); font-size: 1.2rem;">📦</span>
                        <span><strong>Subscription Boxes</strong> — Curated gelato delivered monthly</span>
                    </li>
                </ul>
                <a href="contact.php" class="btn btn-primary">Get in Touch</a>
            </div>
            <div class="about-image reveal">
                <div style="width: 100%; min-height: 450px; background: linear-gradient(135deg, #2a2a2a, #1a1a1a); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--color-gold); border-radius: var(--radius); padding: 40px; text-align: center;">
                    <span style="font-size: 4rem; margin-bottom: 20px;">✨</span>
                    <h3 style="font-family: var(--font-heading); font-size: 1.6rem; margin-bottom: 10px; color: var(--color-cream);">The Future is Sweet</h3>
                    <p style="color: var(--color-gray);">Casa Freddo 2.0</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team / Founders Section -->
<section class="section section-dark" style="padding-bottom: 60px;">
    <div class="container" style="text-align: center;">
        <p class="section-subtitle reveal">The People</p>
        <h2 class="section-title reveal">Meet Our <span>Founders</span></h2>
        
        <div class="founders-grid">
            <div class="reveal" style="text-align: center;">
                <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #333, #444); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--color-gold); border: 3px solid var(--color-gold);">👨‍🍳</div>
                <h3 style="font-family: var(--font-heading); font-size: 1.3rem; color: var(--color-cream); margin-bottom: 6px;">Marco Rossi</h3>
                <p style="color: var(--color-gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Co-Founder & Master Gelatiere</p>
                <p style="color: var(--color-gray); font-size: 0.9rem; line-height: 1.6;">Trained in Sicily, Marco brings 20 years of gelato expertise to every batch we make.</p>
            </div>
            <div class="reveal">
                <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #333, #444); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--color-gold); border: 3px solid var(--color-gold);">👩‍💼</div>
                <h3 style="font-family: var(--font-heading); font-size: 1.3rem; color: var(--color-cream); margin-bottom: 6px;">Priya Sharma</h3>
                <p style="color: var(--color-gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Co-Founder & CEO</p>
                <p style="color: var(--color-gray); font-size: 0.9rem; line-height: 1.6;">With a passion for food and business, Priya leads Casa Freddo's growth and innovation.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
