<?php
/**
 * Product Card Component
 * @var array $product
 */
$image = !empty($product['image']) ? $product['image'] : '/assets/images/placeholder.jpg';
$price = number_format($product['price'], 0);
$comparePrice = !empty($product['compare_price']) && $product['compare_price'] > $product['price'] 
    ? number_format($product['compare_price'], 0) 
    : null;
$discount = (!empty($product['compare_price']) && $product['compare_price'] > $product['price']) 
    ? round((($product['compare_price'] - $product['price']) / $product['compare_price']) * 100) 
    : 0;
?>
<div class="product-card">
    <div class="product-card-image-wrap">
        <?php if ($discount > 0): ?>
            <span class="product-card-badge">-<?php echo $discount; ?>%</span>
        <?php endif; ?>
        
        <a href="/pages/product.php?id=<?php echo $product['id']; ?>" class="product-card-img-link" tabIndex="-1">
            <img src="<?php echo htmlspecialchars($image); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 loading="lazy">
        </a>
        
        <button class="product-card-wishlist wishlist-toggle" 
                data-product-id="<?php echo $product['id']; ?>" 
                title="Add to Wishlist"
                aria-label="Add to Wishlist">
            <span class="material-symbols-outlined">favorite</span>
        </button>
    </div>
    
    <div class="product-card-content">
        <h3 class="product-card-title">
            <a href="/pages/product.php?id=<?php echo $product['id']; ?>">
                <?php echo htmlspecialchars($product['name']); ?>
            </a>
        </h3>
        
        <div class="product-card-price-row">
            <span class="price-current">₹<?php echo $price; ?></span>
            <?php if ($comparePrice): ?>
                <span class="price-old">₹<?php echo $comparePrice; ?></span>
            <?php endif; ?>
        </div>
        
        <div class="product-card-actions">
            <button class="btn btn-primary btn-sm add-to-cart" 
                    data-product-id="<?php echo $product['id']; ?>">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span>Add to Cart</span>
            </button>
            <button class="btn btn-outline btn-sm buy-now" 
                    data-product-id="<?php echo $product['id']; ?>">
                <span>Buy Now</span>
            </button>
        </div>
    </div>
</div>
