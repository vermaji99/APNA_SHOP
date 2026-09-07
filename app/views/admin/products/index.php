<?php
/**
 * Admin Products List Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Manage Products</h1>
            <a href="/admin/dashboard" class="btn btn-outline">Back to Dashboard</a>
        </div>

        <!-- Filters and Search -->
        <div class="card u-mb-lg">
            <div class="card-body">
                <form method="GET" action="/admin/products" class="grid" style="grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-input" placeholder="Name, SKU, Description..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo ($status ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($status ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo ($status ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                    <div class="form-group">
                        <a href="/admin/products/create" class="btn btn-primary">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">add</span>
                            Add Product
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="u-text-center u-p-xl">
                        <p class="u-text-muted u-mb-lg">No products found</p>
                        <a href="/admin/products/create" class="btn btn-primary">Add First Product</a>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--border-color);">
                                    <th style="padding: 1rem; text-align: left;">Image</th>
                                    <th style="padding: 1rem; text-align: left;">Name</th>
                                    <th style="padding: 1rem; text-align: left;">SKU</th>
                                    <th style="padding: 1rem; text-align: left;">Category</th>
                                    <th style="padding: 1rem; text-align: left;">Price</th>
                                    <th style="padding: 1rem; text-align: left;">Stock</th>
                                    <th style="padding: 1rem; text-align: left;">Status</th>
                                    <th style="padding: 1rem; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 1rem;">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-md);">
                                            <?php else: ?>
                                                <div style="width: 60px; height: 60px; background: var(--bg-secondary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                                    <span class="material-symbols-outlined">image</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem;">
                                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                            <?php if ($product['featured']): ?>
                                                <span class="badge" style="background: var(--primary); color: white; margin-left: 0.5rem; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem;">Featured</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($product['sku']); ?></td>
                                        <td style="padding: 1rem;"><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                        <td style="padding: 1rem;">
                                            <strong>₹<?php echo number_format($product['price'], 2); ?></strong>
                                            <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                                                <br><span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.875rem;">₹<?php echo number_format($product['compare_price'], 2); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem;">
                                            <?php if ($product['stock_quantity'] > 0): ?>
                                                <span style="color: var(--success);"><?php echo $product['stock_quantity']; ?></span>
                                            <?php else: ?>
                                                <span style="color: var(--error);">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem;">
                                            <span class="status-badge status-<?php echo $product['status']; ?>">
                                                <?php echo ucfirst($product['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; text-align: center;">
                                            <div class="u-flex u-gap-sm u-justify-center">
                                                <a href="/admin/products/edit?id=<?php echo $product['id']; ?>" 
                                                   class="btn btn-outline btn-sm" 
                                                   title="Edit">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                                </a>
                                                <button onclick="deleteProduct(<?php echo $product['id']; ?>)" 
                                                        class="btn btn-outline btn-sm" 
                                                        style="color: var(--error);"
                                                        title="Delete">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total > $perPage): ?>
                        <div class="u-flex u-justify-between u-items-center u-mt-lg" style="padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <div class="u-text-muted">
                                Showing <?php echo (($page - 1) * $perPage) + 1; ?> - <?php echo min($page * $perPage, $total); ?> of <?php echo $total; ?> products
                            </div>
                            <div class="u-flex u-gap-sm">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&category=<?php echo urlencode($category ?? ''); ?>" 
                                       class="btn btn-outline btn-sm">Previous</a>
                                <?php endif; ?>
                                <?php if ($page * $perPage < $total): ?>
                                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&category=<?php echo urlencode($category ?? ''); ?>" 
                                       class="btn btn-outline btn-sm">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

window.deleteProduct = async function(productId) {
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await ajaxRequest('/api/admin/products/delete', {
            method: 'POST',
            body: JSON.stringify({
                id: productId,
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        });
        
        if (response.success) {
            showToast(response.message || 'Product deleted successfully', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(response.message || 'Failed to delete product', 'error');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showToast('An error occurred', 'error');
    }
};
</script>

<style>
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-draft {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-published {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-archived {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

@media (max-width: 768px) {
    .grid[style*="grid-template-columns: 2fr 1fr 1fr 1fr auto"] {
        grid-template-columns: 1fr !important;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.5rem !important;
    }
}
</style>
