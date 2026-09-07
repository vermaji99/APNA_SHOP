<?php
/**
 * Admin Categories List Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Manage Categories</h1>
            <div class="u-flex u-gap-sm">
                <a href="/admin/categories/create" class="btn btn-primary">Add Category</a>
                <a href="/admin/dashboard" class="btn btn-outline">Back to Dashboard</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <div class="u-text-center u-p-xl">
                        <p class="u-text-muted u-mb-lg">No categories found</p>
                        <a href="/admin/categories/create" class="btn btn-primary">Add First Category</a>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--border-color);">
                                    <th style="padding: 1rem; text-align: left;">Name</th>
                                    <th style="padding: 1rem; text-align: left;">Slug</th>
                                    <th style="padding: 1rem; text-align: left;">Parent</th>
                                    <th style="padding: 1rem; text-align: left;">Sort Order</th>
                                    <th style="padding: 1rem; text-align: left;">Status</th>
                                    <th style="padding: 1rem; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 1rem;"><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                                        <td style="padding: 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($category['slug']); ?></td>
                                        <td style="padding: 1rem;"><?php echo $category['parent_id'] ? 'Parent Category' : '—'; ?></td>
                                        <td style="padding: 1rem;"><?php echo $category['sort_order']; ?></td>
                                        <td style="padding: 1rem;">
                                            <span class="status-badge status-<?php echo $category['status']; ?>">
                                                <?php echo ucfirst($category['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; text-align: center;">
                                            <div class="u-flex u-gap-sm u-justify-center">
                                                <a href="/admin/categories/edit?id=<?php echo $category['id']; ?>" class="btn btn-outline btn-sm" title="Edit">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                                </a>
                                                <button onclick="deleteCategory(<?php echo $category['id']; ?>)" class="btn btn-outline btn-sm" style="color: var(--error);" title="Delete">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

window.deleteCategory = async function(categoryId) {
    if (!confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await ajaxRequest('/api/admin/categories/delete', {
            method: 'POST',
            body: JSON.stringify({
                id: categoryId,
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        });
        
        if (response.success) {
            showToast(response.message || 'Category deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(response.message || 'Failed to delete category', 'error');
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

.status-active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-inactive {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}
</style>



