<?php
/**
 * Home Page
 * Rendered using View::render()
 */
?>

<!-- ===================== HERO SECTION ===================== -->
<section class="section-hero">
    <?php View::partial('banner-hero', ['banners' => $banners ?? []]); ?>
</section>

<!-- ===================== FEATURED PRODUCTS ===================== -->
<?php if (!empty($featuredProducts)): ?>
<section class="section section-featured">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">CURATED SELECTION</span>
            <h2 class="section-title">FEATURED PRODUCTS</h2>
            <p class="section-desc">Discover our handpicked selection of premium essentials.</p>
        </div>

        <!-- Product Grid (4 columns desktop) -->
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <?php View::partial('product-card', ['product' => $product]); ?>
            <?php endforeach; ?>
        </div>

        <!-- View All Action -->
        <div class="section-cta">
            <a href="/pages/shop.php" class="btn btn-outline btn-lg">VIEW ALL PRODUCTS</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== SHOP BY CATEGORY ===================== -->
<?php if (!empty($categories)): ?>
<section class="section section-categories" id="categories">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">EXPLORE COLLECTIONS</span>
            <h2 class="section-title">SHOP BY CATEGORY</h2>
            <p class="section-desc">Explore our premium wardrobe essentials.</p>
        </div>

        <!-- Category Grid -->
        <div class="category-grid">
            <?php foreach ($categories as $index => $category): 
                // Default placeholders if image not set
                $categoryImages = [
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&q=80'
                ];
                $bgImg = !empty($category['image']) ? '/uploads/categories/' . $category['image'] : ($categoryImages[$index % count($categoryImages)]);
            ?>
                <a href="/pages/shop.php?category=<?php echo $category['id']; ?>" class="category-tile">
                    <div class="category-tile-bg" style="background-image: url('<?php echo htmlspecialchars($bgImg); ?>');"></div>
                    <div class="category-tile-overlay"></div>
                    <div class="category-tile-content">
                        <h3 class="category-tile-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <span class="category-tile-link">SHOP NOW <span class="material-symbols-outlined">arrow_forward</span></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== NEW ARRIVALS ===================== -->
<?php if (!empty($latestProducts)): ?>
<section class="section section-new-arrivals" id="new-arrivals">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">FRESH DROPS</span>
            <h2 class="section-title">NEW ARRIVALS</h2>
            <p class="section-desc">Fresh styles. New energy.</p>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php foreach ($latestProducts as $product): ?>
                <?php View::partial('product-card', ['product' => $product]); ?>
            <?php endforeach; ?>
        </div>

        <!-- Section CTA -->
        <div class="section-cta">
            <a href="/pages/shop.php?sort=latest" class="btn btn-outline btn-lg">VIEW ALL NEW ARRIVALS &rarr;</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== PROMOTIONAL BANNER ===================== -->
<section class="section section-promo">
    <div class="container">
        <div class="promo-banner" style="background-image: linear-gradient(90deg, rgba(11, 11, 11, 0.92) 0%, rgba(11, 11, 11, 0.6) 100%), url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1600&q=80');">
            <div class="promo-content">
                <span class="promo-badge">LIMITED TIME</span>
                <h2 class="promo-title">UP TO 50% OFF</h2>
                <h3 class="promo-subtitle">SELECTED STYLES</h3>
                <p class="promo-desc">Upgrade your wardrobe without compromising on style. Premium fabrics and precision tailoring now at exclusive seasonal rates.</p>
                <a href="/pages/shop.php?sale=1" class="btn btn-primary btn-lg">SHOP SALE</a>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHY APNA-MENS (TRUST BADGES) ===================== -->
<section class="section section-trust">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">WHY APNA-MENS?</h2>
        </div>
        <div class="trust-grid">
            <div class="trust-card">
                <div class="trust-icon">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <h3 class="trust-title">QUALITY FIRST</h3>
                <p class="trust-desc">Premium materials and carefully selected products built for longevity.</p>
            </div>
            
            <div class="trust-card">
                <div class="trust-icon">
                    <span class="material-symbols-outlined">local_shipping</span>
                </div>
                <h3 class="trust-title">FAST DELIVERY</h3>
                <p class="trust-desc">Reliable express delivery right to your doorstep across India.</p>
            </div>
            
            <div class="trust-card">
                <div class="trust-icon">
                    <span class="material-symbols-outlined">published_with_changes</span>
                </div>
                <h3 class="trust-title">EASY RETURNS</h3>
                <p class="trust-desc">Simple, hassle-free 7-day customer-friendly return policy.</p>
            </div>
            
            <div class="trust-card">
                <div class="trust-icon">
                    <span class="material-symbols-outlined">lock</span>
                </div>
                <h3 class="trust-title">SECURE PAYMENTS</h3>
                <p class="trust-desc">Safe, encrypted and 100% secure online checkout experience.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== NEWSLETTER ===================== -->
<section class="section section-newsletter">
    <div class="container">
        <div class="newsletter-card">
            <div class="newsletter-content">
                <h2 class="newsletter-title">STAY IN THE LOOP</h2>
                <p class="newsletter-desc">Get first access to new collections, exclusive offers and seasonal drops.</p>
                <form class="newsletter-form" id="newsletterForm" onsubmit="handleNewsletterSubmit(event)">
                    <div class="newsletter-input-group">
                        <input type="email" id="newsletterEmail" class="newsletter-input" placeholder="Enter your email address..." required aria-label="Email address">
                        <button type="submit" class="btn btn-primary newsletter-btn">SUBSCRIBE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function handleNewsletterSubmit(e) {
    e.preventDefault();
    const emailInput = document.getElementById('newsletterEmail');
    if (emailInput && emailInput.value) {
        if (typeof window.showToast === 'function') {
            window.showToast('Thank you for subscribing to APNA-MENS!', 'success');
        } else {
            alert('Thank you for subscribing to APNA-MENS!');
        }
        emailInput.value = '';
    }
}
</script>
