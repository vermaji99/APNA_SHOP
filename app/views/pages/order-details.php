<?php
/**
 * Order Details Page
 * Note: This file is rendered through View::render() which handles the layout
 */
?>

<section class="section">
    <div class="container">
        <div class="u-mb-lg">
            <a href="/pages/user-orders.php" class="btn btn-outline btn-sm">
                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">arrow_back</span>
                Back to Orders
            </a>
        </div>
        
        <h1 class="reveal">Order Details</h1>
        
        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
            <!-- Order Items -->
            <div class="reveal">
                <div class="card u-mb-lg">
                    <h3 class="card-title">Order Items</h3>
                    <div class="card-body">
                        <?php if (!empty($order['items'])): ?>
                            <div class="order-items-list">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-item-detail" style="display: flex; gap: 1rem; padding: 1rem; border-bottom: 1px solid var(--border-color);">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md);">
                                        <?php endif; ?>
                                        <div style="flex: 1;">
                                            <h4 style="margin: 0 0 0.5rem 0;"><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">
                                                SKU: <?php echo htmlspecialchars($item['product_sku']); ?>
                                            </p>
                                            <p style="margin: 0.5rem 0 0 0;">
                                                Quantity: <strong><?php echo $item['quantity']; ?></strong> × 
                                                ₹<?php echo number_format($item['price'], 2); ?> = 
                                                <strong>₹<?php echo number_format($item['subtotal'], 2); ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="u-text-muted">No items found</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <?php if ($shippingAddress): ?>
                    <div class="card u-mb-lg">
                        <h3 class="card-title">Shipping Address</h3>
                        <div class="card-body">
                            <p style="margin: 0;">
                                <strong><?php echo htmlspecialchars($shippingAddress['first_name'] . ' ' . $shippingAddress['last_name']); ?></strong><br>
                                <?php echo htmlspecialchars($shippingAddress['address_line1']); ?><br>
                                <?php if ($shippingAddress['address_line2']): ?>
                                    <?php echo htmlspecialchars($shippingAddress['address_line2']); ?><br>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($shippingAddress['city'] . ', ' . $shippingAddress['state'] . ' ' . $shippingAddress['zip_code']); ?><br>
                                <?php echo htmlspecialchars($shippingAddress['country']); ?><br>
                                Phone: <?php echo htmlspecialchars($shippingAddress['phone']); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Billing Address -->
                <?php if ($billingAddress && $billingAddress['id'] != $shippingAddress['id']): ?>
                    <div class="card">
                        <h3 class="card-title">Billing Address</h3>
                        <div class="card-body">
                            <p style="margin: 0;">
                                <strong><?php echo htmlspecialchars($billingAddress['first_name'] . ' ' . $billingAddress['last_name']); ?></strong><br>
                                <?php echo htmlspecialchars($billingAddress['address_line1']); ?><br>
                                <?php if ($billingAddress['address_line2']): ?>
                                    <?php echo htmlspecialchars($billingAddress['address_line2']); ?><br>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($billingAddress['city'] . ', ' . $billingAddress['state'] . ' ' . $billingAddress['zip_code']); ?><br>
                                <?php echo htmlspecialchars($billingAddress['country']); ?><br>
                                Phone: <?php echo htmlspecialchars($billingAddress['phone']); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Order Summary -->
            <div class="reveal reveal-delay-1">
                <div class="card" style="position: sticky; top: 2rem;">
                    <h3 class="card-title">Order Summary</h3>
                    <div class="card-body">
                        <div class="u-mb-md">
                            <p style="margin: 0 0 0.5rem 0;">
                                <strong>Order Number:</strong><br>
                                <span style="color: var(--primary);"><?php echo htmlspecialchars($order['order_number']); ?></span>
                            </p>
                            <p style="margin: 0.5rem 0;">
                                <strong>Order Date:</strong><br>
                                <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?>
                            </p>
                        </div>
                        
                        <hr class="u-mb-md">
                        
                        <div class="u-mb-sm">
                            <div class="u-flex u-justify-between u-mb-sm">
                                <span>Subtotal</span>
                                <span>₹<?php echo number_format($order['subtotal'], 2); ?></span>
                            </div>
                            <div class="u-flex u-justify-between u-mb-sm">
                                <span>Tax</span>
                                <span>₹<?php echo number_format($order['tax'], 2); ?></span>
                            </div>
                            <div class="u-flex u-justify-between u-mb-sm">
                                <span>Shipping</span>
                                <span><?php echo $order['shipping'] > 0 ? '₹' . number_format($order['shipping'], 2) : 'Free'; ?></span>
                            </div>
                            <?php if ($order['discount'] > 0): ?>
                                <div class="u-flex u-justify-between u-mb-sm" style="color: var(--success);">
                                    <span>Discount</span>
                                    <span>-₹<?php echo number_format($order['discount'], 2); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <hr class="u-mb-md">
                        
                        <div class="u-flex u-justify-between u-font-bold u-text-lg u-mb-md">
                            <span>Total</span>
                            <span>₹<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                        
                        <hr class="u-mb-md">
                        
                        <div class="u-mb-sm">
                            <p style="margin: 0 0 0.5rem 0;">
                                <strong>Payment Method:</strong><br>
                                <?php echo strtoupper($order['payment_method']); ?>
                            </p>
                            <p style="margin: 0.5rem 0;">
                                <strong>Payment Status:</strong><br>
                                <span class="status-badge status-<?php echo $order['payment_status']; ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </p>
                        </div>
                        
                        <hr class="u-mb-md">
                        
                        <div>
                            <p style="margin: 0 0 0.5rem 0;">
                                <strong>Order Status:</strong><br>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </p>
                            <?php if (!empty($order['tracking_number'])): ?>
                                <p style="margin: 0.5rem 0;">
                                    <strong>Tracking Number:</strong><br>
                                    <span style="color: var(--primary);"><?php echo htmlspecialchars($order['tracking_number']); ?></span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.order-item-detail:last-child {
    border-bottom: none;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-processing {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-shipped {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.status-delivered {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.status-refunded {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-paid {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-failed {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

@media (max-width: 768px) {
    .grid[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>



