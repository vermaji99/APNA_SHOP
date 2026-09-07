<?php
/**
 * Cart Page
 */
?>

<section class="section">
    <div class="container">
        <div class="section-header" style="text-align: left; margin-bottom: 2.5rem;">
            <span class="section-subtitle">YOUR SELECTION</span>
            <h1 class="section-title" style="font-size: 2.5rem;">SHOPPING CART</h1>
        </div>
        
        <?php if (empty($items)): ?>
            <div class="card u-text-center" style="padding: 5rem 2rem; background: #141414; border: 1px solid var(--border-color); text-align: center; max-width: 540px; margin: 0 auto;">
                <span class="material-symbols-outlined" style="font-size: 56px; color: var(--text-muted); margin-bottom: 1.5rem;">shopping_bag</span>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; text-transform: uppercase;">YOUR CART IS EMPTY</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Explore our latest collections and find your signature style.</p>
                <a href="/pages/shop.php" class="btn btn-primary btn-lg">EXPLORE SHOP</a>
            </div>
        <?php else: ?>
            <div class="cart-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem; align-items: start;">
                <div class="cart-items-list">
                    <?php foreach ($items as $item): ?>
                        <div class="card cart-item u-mb-md" data-price="<?php echo $item['price']; ?>" style="background: #141414; border: 1px solid var(--border-color); padding: 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.25rem;">
                            <div class="u-flex u-items-center u-gap-md" style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;">
                                <img src="<?php echo htmlspecialchars($item['image'] ?? '/assets/images/placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     style="width: 90px; height: 110px; object-fit: cover; border-radius: var(--radius-md); background: #0A0A0A;">
                                
                                <div class="u-flex-1" style="flex: 1; min-width: 180px;">
                                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #ffffff; margin-bottom: 0.25rem;">
                                        <a href="/pages/product.php?id=<?php echo $item['product_id']; ?>" style="color: #ffffff;"><?php echo htmlspecialchars($item['name']); ?></a>
                                    </h3>
                                    <p class="u-text-muted" style="font-size: 0.8125rem; color: var(--text-muted); margin-bottom: 0.5rem;">SKU: <?php echo htmlspecialchars($item['sku']); ?></p>
                                    <p class="u-text-primary u-text-lg u-font-bold" style="font-size: 1.125rem; font-weight: 800; color: var(--primary); margin: 0;">₹<?php echo number_format($item['price'], 0); ?></p>
                                </div>
                                
                                <div class="u-flex u-items-center u-gap-sm" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <button class="cart-qty-btn btn btn-secondary" data-cart-id="<?php echo $item['id']; ?>" data-action="decrease" style="width: 36px; height: 36px; padding: 0;">-</button>
                                    <input type="number" class="cart-qty-input" value="<?php echo $item['quantity']; ?>" 
                                           data-cart-id="<?php echo $item['id']; ?>" min="1" style="width: 50px; height: 36px; text-align: center; background: #1A1A1A; border: 1px solid var(--border-color); color: #fff; font-weight: 700; border-radius: var(--radius-sm);">
                                    <button class="cart-qty-btn btn btn-secondary" data-cart-id="<?php echo $item['id']; ?>" data-action="increase" style="width: 36px; height: 36px; padding: 0;">+</button>
                                </div>
                                
                                <button class="cart-remove btn btn-ghost" data-cart-id="<?php echo $item['id']; ?>" title="Remove item" aria-label="Remove item" style="color: #ef4444;">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary-col">
                    <div class="card" style="background: #141414; border: 1px solid var(--border-color); padding: 1.75rem; border-radius: var(--radius-lg);">
                        <h3 class="card-title" style="font-size: 1.125rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #ffffff; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">ORDER SUMMARY</h3>
                        <div class="card-body">
                            <div class="u-flex u-justify-between u-mb-sm" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9375rem; color: var(--text-secondary);">
                                <span>Subtotal</span>
                                <span>₹<?php echo number_format($subtotal, 0); ?></span>
                            </div>
                            <div class="u-flex u-justify-between u-mb-sm" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9375rem; color: var(--text-secondary);">
                                <span>GST Tax (18%)</span>
                                <span>₹<?php echo number_format($tax, 0); ?></span>
                            </div>
                            <div class="u-flex u-justify-between u-mb-sm" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9375rem; color: var(--text-secondary);">
                                <span>Shipping</span>
                                <span><?php echo $shipping > 0 ? '₹' . number_format($shipping, 0) : 'Free'; ?></span>
                            </div>
                            <hr style="border: none; border-top: 1px solid var(--border-color); margin: 1rem 0;">
                            <div class="u-flex u-justify-between u-font-bold u-text-lg" style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 800; color: #ffffff;">
                                <span>TOTAL</span>
                                <span class="cart-total" style="color: var(--primary);">₹<?php echo number_format($total, 0); ?></span>
                            </div>
                        </div>
                        <div class="card-footer" style="margin-top: 1.5rem; padding-top: 0; border-top: none;">
                            <a href="/pages/checkout.php" class="btn btn-primary btn-block btn-lg" style="width: 100%;">PROCEED TO CHECKOUT</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
