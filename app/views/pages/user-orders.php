<?php
/**
 * User Orders Page
 * Note: This file is rendered through View::render() which handles the layout
 */
?>

<section class="section">
    <div class="container">
        <h1 class="reveal">My Orders</h1>
        
        <?php if (empty($orders)): ?>
            <div class="card u-text-center u-p-xl u-mt-xl">
                <p class="u-text-muted u-mb-lg">No orders yet</p>
                <a href="/pages/shop.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="reveal">
                <div class="orders-list">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-number">
                                    <strong>Order #<?php echo htmlspecialchars($order['order_number']); ?></strong>
                                </div>
                                <div class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                                <div class="order-status">
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                    <?php if ($order['payment_status'] === 'paid'): ?>
                                        <span class="status-badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: 0.5rem;">
                                            Paid
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="order-amount">
                                <strong>₹<?php echo number_format($order['total'], 2); ?></strong>
                            </div>
                            <div class="order-actions">
                                <a href="/pages/order-details?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


