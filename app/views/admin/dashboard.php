<?php
/**
 * Admin Dashboard Page
 */
?>

<section class="section">
    <div class="container">
        <!-- Top Header -->
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1 style="color: white; font-size: 2rem; font-weight: bold;">Admin Dashboard</h1>
            <a href="/" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;">Back to Site</a>
        </div>

        <!-- QUICK ACTIONS - Row 1 -->
        <div class="grid grid-cols-4 reveal u-mb-xl" style="gap: 1.5rem;">
            <a href="/admin/products" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📦</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Manage Products</div>
                </div>
            </a>

            <a href="/admin/products/create" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">➕</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Add Product</div>
                </div>
            </a>

            <a href="/admin/orders" class="card admin-box" style="background: var(--bg-card); border: 2px solid #ef4444; text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🧾</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: #ef4444;">Manage Orders</div>
                </div>
            </a>

            <a href="/admin/users" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">👥</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Manage Users</div>
                </div>
            </a>
        </div>

        <!-- QUICK ACTIONS - Row 2 -->
        <div class="grid grid-cols-4 reveal u-mb-xl" style="gap: 1.5rem;">
            <a href="/admin/categories" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📁</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Manage Categories</div>
                </div>
            </a>

            <a href="/admin/categories/create" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">➕</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Add Category</div>
                </div>
            </a>

            <a href="/" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🏠</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">View Site</div>
                </div>
            </a>

            <a href="/pages/shop.php" class="card admin-box" style="background: var(--bg-card); border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s;">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🛍️</div>
                    <div style="font-size: 1.125rem; font-weight: 600; color: var(--primary);">Shop Page</div>
                </div>
            </a>
        </div>

        <!-- STATISTICS BOXES -->
        <div class="grid grid-cols-4 reveal u-mb-xl" style="gap: 1.5rem;">
            <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 2.5rem; font-weight: bold; color: white; margin-bottom: 0.5rem;">
                        <?php echo $stats['total_orders']; ?>
                    </div>
                    <div style="color: white; font-size: 0.875rem;">Total Orders</div>
                </div>
            </div>

            <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 2.5rem; font-weight: bold; color: white; margin-bottom: 0.5rem;">
                        ₹<?php echo number_format($stats['total_revenue'], 0); ?>
                    </div>
                    <div style="color: white; font-size: 0.875rem;">Total Revenue</div>
                </div>
            </div>

            <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 2.5rem; font-weight: bold; color: white; margin-bottom: 0.5rem;">
                        <?php echo $stats['total_users']; ?>
                    </div>
                    <div style="color: white; font-size: 0.875rem;">Total Users</div>
                </div>
            </div>

            <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="card-body u-text-center" style="padding: 2rem;">
                    <div style="font-size: 2.5rem; font-weight: bold; color: white; margin-bottom: 0.5rem;">
                        <?php echo $stats['total_products']; ?>
                    </div>
                    <div style="color: white; font-size: 0.875rem;">Total Products</div>
                </div>
            </div>
        </div>

        <!-- RECENT ORDERS -->
        <div class="card reveal" style="background: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-header u-flex u-justify-between u-items-center" style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 class="card-title" style="color: white; margin: 0;">Recent Orders</h3>
                <a href="/admin/orders" class="btn btn-outline btn-sm" style="border-color: var(--primary); color: var(--primary);">View All</a>
            </div>

            <div class="card-body" style="padding: 1.5rem;">
                <?php if (empty($recentOrders)): ?>
                    <p class="u-text-muted u-text-center u-p-lg" style="color: var(--text-muted);">No orders yet</p>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($recentOrders as $order): ?>
                            <div class="order-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid var(--border-color);">
                                <div class="order-info" style="flex: 1;">
                                    <div class="order-number" style="font-weight: 600; color: white; margin-bottom: 0.25rem;">
                                        Order #<?php echo htmlspecialchars($order['order_number']); ?>
                                    </div>
                                    <div class="order-date" style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                        <?php echo htmlspecialchars($order['user_name']); ?> – 
                                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?php echo $order['status']; ?>" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="order-amount" style="font-weight: bold; color: white; margin: 0 2rem;">
                                    ₹<?php echo number_format($order['total'], 2); ?>
                                </div>
                                <div class="order-actions">
                                    <a href="/admin/orders?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm" style="border-color: var(--primary); color: var(--primary);">View</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.admin-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    border-color: var(--primary) !important;
}

.status-badge.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-badge.status-processing {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-badge.status-shipped {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.status-badge.status-delivered {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-badge.status-cancelled {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

@media (max-width: 1024px) {
    .grid.grid-cols-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 640px) {
    .grid.grid-cols-4 {
        grid-template-columns: 1fr !important;
    }
}
</style>
