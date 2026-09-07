<?php
/**
 * Product Detail Page
 */
$primaryImage = !empty($images) && isset($images[0]['image_path']) ? $images[0]['image_path'] : '/assets/images/placeholder.jpg';
$price = number_format($product['price'], 0);
$comparePrice = !empty($product['compare_price']) && $product['compare_price'] > $product['price'] 
    ? number_format($product['compare_price'], 0) 
    : null;
$discount = (!empty($product['compare_price']) && $product['compare_price'] > $product['price']) 
    ? round((($product['compare_price'] - $product['price']) / $product['compare_price']) * 100) 
    : 0;
?>

<section class="section product-detail-section">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb" style="margin-bottom: 2rem; font-size: 0.8125rem; color: var(--text-muted);">
            <a href="/pages/home.php" style="color: var(--text-secondary);">Home</a> &nbsp;/&nbsp;
            <a href="/pages/shop.php" style="color: var(--text-secondary);">Shop</a> &nbsp;/&nbsp;
            <span style="color: #ffffff;"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="product-detail-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem; align-items: start;">
            <!-- Product Gallery Column -->
            <div class="product-gallery">
                <div class="main-image-wrap" style="position: relative; aspect-ratio: 4/5; border-radius: var(--radius-lg); overflow: hidden; background: #141414; border: 1px solid var(--border-color); margin-bottom: 1rem;">
                    <?php if ($discount > 0): ?>
                        <span class="product-card-badge" style="top: 16px; left: 16px; font-size: 0.8125rem; padding: 4px 10px;">-<?php echo $discount; ?>% OFF</span>
                    <?php endif; ?>
                    <img id="mainProductImg" src="<?php echo htmlspecialchars($primaryImage); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <?php if (!empty($images) && count($images) > 1): ?>
                    <div class="gallery-thumbs" style="display: flex; gap: 1rem; overflow-x: auto;">
                        <?php foreach ($images as $img): ?>
                            <button type="button" 
                                    onclick="switchMainImg('<?php echo htmlspecialchars($img['image_path']); ?>')"
                                    style="width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); cursor: pointer; background: #141414; padding: 0;">
                                <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Product Details Column -->
            <div class="product-info-col">
                <h1 style="font-size: 2.25rem; font-weight: 800; text-transform: uppercase; color: #ffffff; margin-bottom: 0.75rem; letter-spacing: -0.02em;"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="price-box" style="display: flex; align-items: baseline; gap: 1rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 2rem; font-weight: 800; color: #ffffff;">₹<?php echo $price; ?></span>
                    <?php if ($comparePrice): ?>
                        <span style="font-size: 1.25rem; color: var(--text-muted); text-decoration: line-through;">₹<?php echo $comparePrice; ?></span>
                    <?php endif; ?>
                </div>

                <div class="stock-status" style="margin-bottom: 1.5rem;">
                    <?php if (!empty($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 0.75rem; font-weight: 700; border-radius: var(--radius-sm); border: 1px solid rgba(16, 185, 129, 0.25);">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span> IN STOCK (<?php echo $product['stock_quantity']; ?> AVAILABLE)
                        </span>
                    <?php else: ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(239, 68, 68, 0.12); color: #ef4444; font-size: 0.75rem; font-weight: 700; border-radius: var(--radius-sm); border: 1px solid rgba(239, 68, 68, 0.25);">
                            <span class="material-symbols-outlined" style="font-size: 16px;">cancel</span> OUT OF STOCK
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="description-box" style="margin-bottom: 2rem; color: var(--text-secondary); line-height: 1.7; font-size: 0.9375rem; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 1.5rem 0;">
                    <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <!-- Quantity selector -->
                <div class="qty-section" style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.1em; color: #ffffff; text-transform: uppercase; margin-bottom: 0.5rem;">QUANTITY</label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button class="btn btn-secondary" type="button" onclick="decreaseQty()" style="width: 44px; height: 44px; padding: 0; font-size: 1.25rem;">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?php echo !empty($product['stock_quantity']) ? $product['stock_quantity'] : 10; ?>" style="width: 70px; height: 44px; text-align: center; background: #141414; border: 1px solid var(--border-color); color: #ffffff; font-weight: 700; border-radius: var(--radius-sm);">
                        <button class="btn btn-secondary" type="button" onclick="increaseQty()" style="width: 44px; height: 44px; padding: 0; font-size: 1.25rem;">+</button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons-group" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-lg add-to-cart" data-product-id="<?php echo $product['id']; ?>" style="flex: 2; min-width: 180px;">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span>ADD TO CART</span>
                    </button>
                    <button class="btn btn-outline btn-lg buy-now" data-product-id="<?php echo $product['id']; ?>" style="flex: 2; min-width: 180px;">
                        <span>BUY NOW</span>
                    </button>
                    <button class="btn btn-secondary btn-lg wishlist-toggle" data-product-id="<?php echo $product['id']; ?>" title="Add to Wishlist" aria-label="Add to Wishlist" style="width: 52px; padding: 0;">
                        <span class="material-symbols-outlined">favorite</span>
                    </button>
                </div>
                
                <!-- Specs Card -->
                <div class="specs-card" style="background: #141414; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        <span style="font-weight: 700; color: #ffffff;">SKU:</span>
                        <span><?php echo htmlspecialchars($product['sku']); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.8125rem; color: var(--text-secondary);">
                        <span style="font-weight: 700; color: #ffffff;">SHIPPING:</span>
                        <span>Standard 3-5 Day Express Shipping</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <?php if (!empty($relatedProducts)): ?>
            <div style="margin-top: 5rem; border-top: 1px solid var(--border-color); padding-top: 4rem;">
                <div class="section-header">
                    <span class="section-subtitle">RECOMMENDED</span>
                    <h2 class="section-title">YOU MAY ALSO LIKE</h2>
                </div>
                <div class="product-grid">
                    <?php foreach ($relatedProducts as $related): ?>
                        <?php View::partial('product-card', ['product' => $related]); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function switchMainImg(path) {
    const mainImg = document.getElementById('mainProductImg');
    if (mainImg) mainImg.src = path;
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max) || 10;
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
