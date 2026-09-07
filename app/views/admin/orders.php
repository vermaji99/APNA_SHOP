<?php
/**
 * Admin Orders Management
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Order Management</h1>
            <a href="/admin/dashboard.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
        
        <div class="card reveal">
            <div class="card-header">
                <div class="u-flex u-justify-between u-items-center">
                    <h3 class="card-title">All Orders</h3>
                    <div class="u-flex u-gap-sm">
                        <a href="/admin/orders.php" class="btn btn-ghost btn-sm <?php echo !$status ? 'active' : ''; ?>">All</a>
                        <a href="/admin/orders.php?status=pending" class="btn btn-ghost btn-sm <?php echo $status === 'pending' ? 'active' : ''; ?>">Pending</a>
                        <a href="/admin/orders.php?status=processing" class="btn btn-ghost btn-sm <?php echo $status === 'processing' ? 'active' : ''; ?>">Processing</a>
                        <a href="/admin/orders.php?status=shipped" class="btn btn-ghost btn-sm <?php echo $status === 'shipped' ? 'active' : ''; ?>">Shipped</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <p class="u-text-muted u-text-center u-p-lg">No orders found</p>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="order-number">Order #<?php echo htmlspecialchars($order['order_number']); ?></div>
                                    <div class="order-date"><?php echo htmlspecialchars($order['user_name']); ?> (<?php echo htmlspecialchars($order['user_email']); ?>)</div>
                                    <div class="order-date"><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                        <span class="status-badge" style="background: rgba(59, 130, 246, 0.2); color: #3b82f6; margin-left: 0.5rem;">
                                            <?php echo ucfirst($order['payment_status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="order-amount">₹<?php echo number_format($order['total'], 2); ?></div>
                                <div class="order-actions">
                                    <select class="form-select" style="width: auto;" onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

window.updateOrderStatus = async function(orderId, status) {
    try {
        const response = await ajaxRequest('/api/admin/orders/updateStatus', {
            method: 'POST',
            body: JSON.stringify({
                order_id: orderId,
                status: status,
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        });
        
        if (response.success) {
            showToast('Order status updated', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(response.message || 'Failed to update', 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
    }
};
</script>

