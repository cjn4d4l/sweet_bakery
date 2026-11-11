<?php

// Database Connection

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/helpers.php';

$menuItems = [];
$menuLoadError = false;

// Database Quiry

try {
    $pdo = getDatabaseConnection();
    // Only active products are shown publicly. Sorting lets bakery staff control the display order.
    $menuStatement = $pdo->query(
        'SELECT name, description, price, image_path
         FROM products
         WHERE is_active = 1
         ORDER BY sort_order ASC, name ASC'
    );
    $menuItems = $menuStatement->fetchAll();
} catch (PDOException $exception) {
    error_log('Unable to load bakery menu: ' . $exception->getMessage());
    $menuLoadError = true;
} catch (RuntimeException $exception) {
    error_log('Unable to connect to the bakery database: ' . $exception->getMessage());
    $menuLoadError = true;
}
?>

<!DOCTYPE html>
<!--

    Tooplate 2168 Sweet Bakery

    https://www.tooplate.com/view/2168-sweet-bakery

    Free HTML CSS Template

-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Dreams Bakery - Kawaii Café & Bakery</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="tooplate-2168-sweet-bakery.css">
</head>
<body>
    <!-- Floating decorative elements -->
    <div class="float-decoration sparkle sparkle-1">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 0L22.5 17.5L40 20L22.5 22.5L20 40L17.5 22.5L0 20L17.5 17.5L20 0Z" fill="#FFB4A2" opacity="0.6"/>
        </svg>
    </div>
    <div class="float-decoration sparkle sparkle-2">
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 0L17 13L30 15L17 17L15 30L13 17L0 15L13 13L15 0Z" fill="#D4C5F9" opacity="0.6"/>
        </svg>
    </div>
    <div class="float-decoration sparkle sparkle-3">
        <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.5 0L19.5 15.5L35 17.5L19.5 19.5L17.5 35L15.5 19.5L0 17.5L15.5 15.5L17.5 0Z" fill="#B8E6D5" opacity="0.6"/>
        </svg>
    </div>

    <!-- Header -->
    <header>
        <div class="header-wrapper">
            <div class="logo">
                <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="45" r="35" fill="#FFB4A2"/>
                    <circle cx="38" cy="38" r="5" fill="#8DC4B2"/>
                    <circle cx="62" cy="38" r="5" fill="#8DC4B2"/>
                    <path d="M35 55Q50 65 65 55" stroke="#8DC4B2" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <circle cx="30" cy="35" r="8" fill="#FFD4C4"/>
                    <circle cx="70" cy="35" r="8" fill="#FFD4C4"/>
                </svg>
                Pet-astic Stuff
            </div>
            <div class="menu-toggle" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav id="mainNav">
                <a href="#home" class="active">Home</a>
                <a href="#menu">Menu</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero-wrapper">
        <section class="hero" id="home">
            <div class="hero-content">
                <h1>Where pets life is a dream 🌸</h1>
                <p>Kawii Products for your pets to enjoy! 💖</p>
                <div class="hero-buttons">
                    <a href="#menu" class="btn btn-primary">View Menu</a>
                    <a href="#about" class="btn btn-secondary">Our Story</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-container">
                    <!-- Graphic 1: Sweet Treats -->
                    <svg class="hero-illustration active" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        
                        <!-- Stars and hearts -->
                        <path d="M100 100L102 108L110 110L102 112L100 120L98 112L90 110L98 108L100 100Z" fill="#FFB4A2"/>
                        <path d="M420 150L422 158L430 160L422 162L420 170L418 162L410 160L418 158L420 150Z" fill="#D4C5F9"/>
                        <path d="M150 400 L145 405 L150 410 L155 405 Z" fill="#FF6B9D"/>
                        <path d="M440 320 L435 325 L440 330 L445 325 Z" fill="#B8E6D5"/>
                    </svg>

                    <!-- Graphic 2: Cakes & Pastries -->
                    <svg class="hero-illustration" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Layer Cake -->
                        <ellipse cx="250" cy="350" rx="80" ry="15" fill="#FFB4A2"/>
                        <rect x="170" y="320" width="160" height="30" fill="#FFD4C4"/>
                        <ellipse cx="250" cy="320" rx="80" ry="15" fill="#FFB4A2"/>
                        <rect x="180" y="280" width="140" height="40" fill="#D4C5F9"/>
                        <ellipse cx="250" cy="280" rx="70" ry="15" fill="#C4B5E9"/>
                        <rect x="190" y="240" width="120" height="40" fill="#FFB8D2"/>
                        <ellipse cx="250" cy="240" rx="60" ry="15" fill="#FFA8C2"/>
                        <!-- Candles -->
                        <rect x="230" y="210" width="8" height="30" fill="#FFF4B8"/>
                        <rect x="260" y="210" width="8" height="30" fill="#FFF4B8"/>
                        <ellipse cx="234" cy="207" rx="5" ry="8" fill="#FF6B9D"/>
                        <ellipse cx="264" cy="207" rx="5" ry="8" fill="#FF6B9D"/>
                        
                        <!-- Croissant -->
                        <path d="M100 200 Q80 180 90 160 Q100 140 120 150 Q140 160 130 180 Q125 195 115 205 Z" fill="#E8C9A0"/>
                        <path d="M115 205 Q100 220 85 210 Q70 200 75 185 Q80 175 90 180 Q100 185 105 195 Z" fill="#D4B490"/>
                        <ellipse cx="95" cy="175" rx="4" ry="3" fill="#C4A480" transform="rotate(20 95 175)"/>
                        <ellipse cx="110" cy="170" rx="4" ry="3" fill="#C4A480" transform="rotate(-15 110 170)"/>
                        <ellipse cx="100" cy="190" rx="5" ry="3" fill="#C4A480"/>
                        
                        <!-- Pie -->
                        <ellipse cx="380" cy="280" rx="60" ry="15" fill="#E8C9A0"/>
                        <path d="M320 280 Q380 220 440 280 Z" fill="#FFB4A2"/>
                        <line x1="350" y1="250" x2="350" y2="280" stroke="#D4A480" stroke-width="3"/>
                        <line x1="380" y1="235" x2="380" y2="280" stroke="#D4A480" stroke-width="3"/>
                        <line x1="410" y1="250" x2="410" y2="280" stroke="#D4A480" stroke-width="3"/>
                        
                        <!-- Muffin -->
                        <ellipse cx="380" cy="150" rx="35" ry="15" fill="#8B6F47"/>
                        <path d="M345 150 Q365 100 380 95 Q395 100 415 150 Z" fill="#D4B490"/>
                        <circle cx="370" cy="120" r="6" fill="#FFB4A2"/>
                        <circle cx="390" cy="125" r="5" fill="#FFB4A2"/>
                        <circle cx="380" cy="110" r="7" fill="#FFB4A2"/>
                        
                        <!-- Decorative elements -->
                        <path d="M100 370 L103 380 L112 382 L103 384 L100 394 L97 384 L88 382 L97 380 Z" fill="#B8E6D5"/>
                        <path d="M450 180 L453 190 L462 192 L453 194 L450 204 L447 194 L438 192 L447 190 Z" fill="#D4C5F9"/>
                        <circle cx="120" cy="100" r="15" fill="#FFD4C4" opacity="0.5"/>
                        <circle cx="430" cy="350" r="20" fill="#FFB8D2" opacity="0.5"/>
                    </svg>

                    <!-- Graphic 3: Breads & Bagels -->
                    <svg class="hero-illustration" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Baguette -->
                        <ellipse cx="250" cy="250" rx="150" ry="35" fill="#E8C9A0" transform="rotate(-25 250 250)"/>
                        <line x1="150" y1="290" x2="160" y2="270" stroke="#D4A480" stroke-width="3" stroke-linecap="round" transform="rotate(-25 155 280)"/>
                        <line x1="200" y1="275" x2="210" y2="255" stroke="#D4A480" stroke-width="3" stroke-linecap="round" transform="rotate(-25 205 265)"/>
                        <line x1="250" y1="260" x2="260" y2="240" stroke="#D4A480" stroke-width="3" stroke-linecap="round" transform="rotate(-25 255 250)"/>
                        <line x1="300" y1="245" x2="310" y2="225" stroke="#D4A480" stroke-width="3" stroke-linecap="round" transform="rotate(-25 305 235)"/>
                        
                        <!-- Bagel -->
                        <circle cx="380" cy="200" r="50" fill="#D4B490"/>
                        <circle cx="380" cy="200" r="25" fill="#FFF8F0"/>
                        <path d="M350 180 Q370 175 390 180" stroke="#C4A480" stroke-width="2" fill="none"/>
                        <path d="M370 220 Q380 225 390 220" stroke="#C4A480" stroke-width="2" fill="none"/>
                        <ellipse cx="360" cy="195" rx="3" ry="2" fill="#E8D4B0"/>
                        <ellipse cx="370" cy="190" rx="3" ry="2" fill="#E8D4B0"/>
                        <ellipse cx="395" cy="205" rx="3" ry="2" fill="#E8D4B0"/>
                        
                        <!-- Pretzel -->
                        <path d="M120 150 Q140 120 160 150 Q180 180 160 210 Q140 240 120 210 Q100 180 120 150 Z" fill="#8B6F47" stroke="#6B5737" stroke-width="3"/>
                        <circle cx="135" cy="165" r="15" fill="#FFF8F0"/>
                        <circle cx="145" cy="195" r="15" fill="#FFF8F0"/>
                        <ellipse cx="125" cy="160" rx="2" ry="3" fill="white"/>
                        <ellipse cx="140" cy="155" rx="2" ry="3" fill="white"/>
                        <ellipse cx="155" cy="200" rx="2" ry="3" fill="white"/>
                        
                        <!-- Cinnamon Roll -->
                        <circle cx="200" cy="380" r="45" fill="#D4B490"/>
                        <path d="M200 380 Q215 375 220 385 Q220 400 205 405 Q190 405 185 390 Q185 375 200 380 Z" fill="#C4A480"/>
                        <path d="M200 380 Q210 378 213 385" stroke="#8B6F47" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M207 390 Q205 397 198 399" stroke="#8B6F47" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <ellipse cx="205" cy="385" rx="3" ry="2" fill="white" opacity="0.7"/>
                        
                        <!-- Bread Loaf -->
                        <ellipse cx="350" cy="370" rx="70" ry="35" fill="#E8C9A0"/>
                        <path d="M280 370 Q320 320 350 315 Q380 320 420 370 Z" fill="#D4B490"/>
                        <line x1="310" y1="350" x2="315" y2="335" stroke="#C4A480" stroke-width="2"/>
                        <line x1="340" y1="340" x2="345" y2="325" stroke="#C4A480" stroke-width="2"/>
                        <line x1="370" y1="345" x2="375" y2="330" stroke="#C4A480" stroke-width="2"/>
                        
                        <!-- Decorative elements -->
                        <path d="M420 120 L423 130 L432 132 L423 134 L420 144 L417 134 L408 132 L417 130 Z" fill="#FFB4A2"/>
                        <path d="M80 350 L83 360 L92 362 L83 364 L80 374 L77 364 L68 362 L77 360 Z" fill="#B8E6D5"/>
                        <circle cx="450" cy="300" r="18" fill="#D4C5F9" opacity="0.5"/>
                        <circle cx="90" cy="80" r="22" fill="#FFB8D2" opacity="0.5"/>
                    </svg>
                </div>
            </div>
        </section>
    </div>

    <!-- Today's Special Section -->
    <!-- <section class="todays-special">
        <div class="special-container">
            <div style="text-align: center;">
                <span class="special-badge">⚡ LIMITED TIME OFFER ⚡</span>
                <h2 class="section-title" style="margin-bottom: 0.5rem;">Today's Special</h2>
                <p class="section-subtitle">Hurry! This sweet deal won't last long! 🎯</p>
            </div>
            
            <div class="special-content">
                <div class="special-image">
                    <img src="images/unicorn-dream-cake.jpg" alt="Unicorn Dream Cake">
                    <span class="special-tag">🦄 50% OFF!</span>
                    <div class="stock-indicator">
                        <div>⚡ Only <strong>7 left</strong> in stock!</div>
                        <div class="stock-bar">
                            <div class="stock-fill" style="width: 35%;"></div>
                        </div>
                    </div>
                </div>

                <div class="special-details">
                    <h2>Unicorn Dream Cake</h2>
                    
                    <div class="special-price-row">
                        <span class="special-original-price">$24.00</span>
                        <span class="special-price">$12.00</span>
                        <span class="special-discount">50% OFF</span>
                    </div>

                    <p class="special-description">
                        A magical rainbow-layered cake with vanilla buttercream, topped with edible glitter, colorful swirls, and a golden horn. Perfect for birthdays or just making any day special! This enchanting creation is handcrafted fresh daily.
                    </p>

                    <div class="countdown-container">
                        <div class="countdown-label">⏰ Offer Ends In:</div>
                        <div class="countdown">
                            <div class="countdown-item">
                                <span class="countdown-number" id="days">00</span>
                                <span class="countdown-label-text">Days</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-number" id="hours">00</span>
                                <span class="countdown-label-text">Hours</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-number" id="minutes">00</span>
                                <span class="countdown-label-text">Minutes</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-number" id="seconds">00</span>
                                <span class="countdown-label-text">Seconds</span>
                            </div>
                        </div>
                    </div>

                    <div class="special-cta">
                        <button class="btn-special"><span>🛒 Grab This Deal Now!</span></button>
                        <button class="btn-notify"><span>🔔</span></button>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Why We're Special ✨</h2>
        <p class="section-subtitle">Discover what makes our petshop a dreamy paradise for your pets</p>
        <div class="features-grid">
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" fill="#FFD4C4"/>
                    <path d="M30 45L45 60L70 35" stroke="#8DC4B2" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>Freshly Baked Daily</h3>
                <p>Every treat is made fresh each morning with love and the finest ingredients</p>
            </div>
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 20L60 45H80L65 58L72 83L50 68L28 83L35 58L20 45H40L50 20Z" fill="#FFB8D2"/>
                </svg>
                <h3>Instagram-Worthy</h3>
                <p>Our kawaii creations are almost too cute to eat (but definitely too delicious not to!)</p>
            </div>
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="35" cy="40" r="20" fill="#B8E6D5"/>
                    <circle cx="65" cy="40" r="20" fill="#D4C5F9"/>
                    <circle cx="50" cy="60" r="20" fill="#FFB4A2"/>
                </svg>
                <h3>Custom Orders</h3>
                <p>Special occasion? We'll create the perfect custom treats for your celebration</p>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="menu" id="menu">
        <div class="menu-container">
            <h2 class="section-title">Our Products</h2>
            <p class="section-subtitle">Products for your pets</p>
            <div class="menu-grid">
                <?php if ($menuLoadError): ?>
                    <p class="menu-message">Our menu is temporarily unavailable. Please try again later.</p>
                <?php elseif (empty($menuItems)): ?>
                    <p class="menu-message">Our Suppliers are preparing something. Come back soon to check it out!</p>
                <?php else: ?>
                    <?php foreach ($menuItems as $menuItem): ?>
                        <article class="menu-item">
                            <div class="menu-item-image">
                                <img src="<?= h($menuItem['image_path']) ?>" alt="<?= h($menuItem['name']) ?>">
                                <div class="menu-item-overlay"></div>
                            </div>
                            <div class="menu-item-content">
                                <div class="menu-item-header">
                                    <h3><?= h($menuItem['name']) ?></h3>
                                    <span class="menu-item-price">$<?= number_format((float) $menuItem['price'], 2) ?></span>
                                </div>
                                <p><?= nl2br(h($menuItem['description'])) ?></p>
                                <button class="add-to-cart" type="button"><span>Add to Cart ♡</span></button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>Our Story – Where Every Paw Finds a Home</h2>
                <p>Our pet shop started with a simple love for animals and a dream of making pet care easier and more enjoyable for everyone. We believe that every pet deserves love, comfort, and the best care possible.</p>
                <p>We offer a variety of pet essentials, including nutritious food, fun toys, accessories, and everyday supplies to keep your furry companions happy and healthy.</p>
                <p>More than just a pet shop, we're a place built for pet lovers. Because to us, pets aren't just animals — they're family. And every family deserves the very best!</p>
                <a href="#contact" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Visit Us Today</a>
            </div>
            <div class="about-image">
                <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Cute chef character -->
                    <circle cx="200" cy="180" r="80" fill="#FFD4C4"/>
                    <circle cx="175" cy="165" r="10" fill="#8DC4B2"/>
                    <circle cx="225" cy="165" r="10" fill="#8DC4B2"/>
                    <path d="M180 195Q200 210 220 195" stroke="#8DC4B2" stroke-width="4" stroke-linecap="round" fill="none"/>
                    <circle cx="165" cy="175" r="15" fill="#FFB4A2"/>
                    <circle cx="235" cy="175" r="15" fill="#FFB4A2"/>
                    
                    <!-- Chef hat -->
                    <ellipse cx="200" cy="100" rx="70" ry="30" fill="white"/>
                    <rect x="130" y="95" width="140" height="15" fill="white"/>
                    
                    <!-- Apron -->
                    <path d="M140 260L200 270L260 260L240 350Q200 360 160 350Z" fill="#B8E6D5"/>
                    <circle cx="200" cy="300" r="8" fill="#FFB4A2"/>
                    
                    <!-- Heart -->
                    <path d="M190 310L200 320L210 310Q215 305 215 300Q215 290 205 290Q200 290 200 295Q200 290 195 290Q185 290 185 300Q185 305 190 310Z" fill="#FF6B9D"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="newsletter-container">
            <div class="newsletter-content">
                <div class="newsletter-icon">💌</div>
                <h2>Join Our Sweet Club!</h2>
                <p>Subscribe to get exclusive deals, new menu items, and sweet surprises delivered to your inbox every week!</p>
                
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing! 💕');">
                    <input 
                        type="email" 
                        class="newsletter-input" 
                        placeholder="Enter your email address" 
                        required
                    >
                    <button type="submit" class="newsletter-submit">Subscribe ✨</button>
                </form>

                <div class="newsletter-benefits">
                    <div class="newsletter-benefit">Weekly special offers</div>
                    <div class="newsletter-benefit">First access to new treats</div>
                    <div class="newsletter-benefit">Birthday surprises</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Visit Us</h3>
                    <p>123 Sweet Street<br>Cupcake City, CC 12345</p>
                    <p>Open Daily: 8am - 8pm</p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <a href="tel:555-CAKE">📞 (555) CAKE-123</a>
                    <a href="mailto:hello@sweetdreams.com">✉️ hello@sweetdreams.com</a>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#8DC4B2">
                                <rect x="2" y="2" width="20" height="20" rx="5" stroke="#8DC4B2" stroke-width="2" fill="none"/>
                                <circle cx="12" cy="12" r="4" stroke="#8DC4B2" stroke-width="2" fill="none"/>
                                <circle cx="18" cy="6" r="1" fill="#8DC4B2"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#8DC4B2">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#8DC4B2">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="YouTube">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#8DC4B2">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#FFF8F0"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Opening Hours</h3>
                    <p>Monday - Friday: 8am - 8pm</p>
                    <p>Saturday: 8am - 9pm</p>
                    <p>Sunday: 9am - 7pm</p>
                    <p style="margin-top: 1rem; font-size: 0.9rem;">🎂 Special orders available daily!</p>
                </div>
            </div>
            <div class="copyright">
                <p>© 2026 Sweet Dreams Bakery. Made with ♡ and sugar. All rights reserved.</p>
                <p style="margin-top: 0.5rem;">Designed by <a href="https://www.tooplate.com" target="_blank" rel="nofollow" style="color: #B5EAD7; text-decoration: none; font-weight: 600;">Tooplate</a></p>
            </div>
        </div>
    </footer>
    <script src="tooplate-2168-sweet-bakery.js"></script>
</body>
</html>
