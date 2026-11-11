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



<!-- Dynamic Menu Section -->
    <section class="menu" id="menu">
        <div class="menu-container">
            <h2 class="section-title">Our Sweet Menu 🧁</h2>
            <p class="section-subtitle">Handcrafted with love and a sprinkle of magic</p>
            <div class="menu-grid">
                <?php if ($menuLoadError): ?>
                    <p class="menu-message">Our menu is temporarily unavailable. Please try again later.</p>
                <?php elseif (empty($menuItems)): ?>
                    <p class="menu-message">Our bakers are preparing the menu. Please check back soon!</p>
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

     <script src="tooplate-2168-sweet-bakery.js"></script>