<?php
/**
 * Wishlist Page
 */
?>

<section class="section">
    <div class="container">
        <div class="section-header" style="text-align: left; margin-bottom: 2.5rem;">
            <span class="section-subtitle">SAVED ITEMS</span>
            <h1 class="section-title" style="font-size: 2.5rem;">MY WISHLIST</h1>
        </div>
        
        <?php if (empty($items)): ?>
            <div class="card u-text-center" style="padding: 5rem 2rem; background: #141414; border: 1px solid var(--border-color); text-align: center; max-width: 540px; margin: 0 auto;">
                <span class="material-symbols-outlined" style="font-size: 56px; color: var(--text-muted); margin-bottom: 1.5rem;">favorite</span>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; text-transform: uppercase;">YOUR WISHLIST IS EMPTY</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Save your favorite fashion items here for easy access anytime.</p>
                <a href="/pages/shop.php" class="btn btn-primary btn-lg">BROWSE PRODUCTS</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($items as $item): ?>
                    <?php View::partial('product-card', ['product' => $item]); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
