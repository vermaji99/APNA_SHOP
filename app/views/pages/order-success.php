<?php
/**
 * Order Success Page
 * Note: This file is rendered through View::render() which handles the layout
 */
?>

<section class="section">
    <div class="container">
        <div class="u-text-center reveal">
            <div class="u-mb-xl">
                <span class="material-symbols-outlined" style="font-size: 80px; color: var(--success);">check_circle</span>
            </div>
            <h1 class="u-mb-md">Order Confirmed!</h1>
            <p class="u-text-muted u-mb-xl">Thank you for your purchase. Your order number is: <strong><?php echo htmlspecialchars($order['order_number']); ?></strong></p>
            
            <div class="u-flex u-justify-center u-gap-md">
                <a href="/pages/user-orders.php" class="btn btn-primary">View Orders</a>
                <a href="/pages/shop.php" class="btn btn-outline">Continue Shopping</a>
            </div>
        </div>
    </div>
</section>


