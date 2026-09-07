<?php
/**
 * Admin Users List Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Manage Users</h1>
            <a href="/admin/dashboard" class="btn btn-outline">Back to Dashboard</a>
        </div>

        <!-- Filters -->
        <div class="card u-mb-lg">
            <div class="card-body">
                <form method="GET" action="/admin/users" class="grid" style="grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-input" placeholder="Name, Email, Phone..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-input">
                            <option value="">All Roles</option>
                            <option value="customer" <?php echo ($role ?? '') === 'customer' ? 'selected' : ''; ?>>Customer</option>
                            <option value="vendor" <?php echo ($role ?? '') === 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                            <option value="admin" <?php echo ($role ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="">All Status</option>
                            <option value="active" <?php echo ($status ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($status ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <div class="u-text-center u-p-xl">
                        <p class="u-text-muted">No users found</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--border-color);">
                                    <th style="padding: 1rem; text-align: left;">Name</th>
                                    <th style="padding: 1rem; text-align: left;">Email</th>
                                    <th style="padding: 1rem; text-align: left;">Phone</th>
                                    <th style="padding: 1rem; text-align: left;">Role</th>
                                    <th style="padding: 1rem; text-align: left;">Orders</th>
                                    <th style="padding: 1rem; text-align: left;">Total Spent</th>
                                    <th style="padding: 1rem; text-align: left;">Status</th>
                                    <th style="padding: 1rem; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 1rem;"><strong style="color: white;"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></strong></td>
                                        <td style="padding: 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                        <td style="padding: 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                        <td style="padding: 1rem;">
                                            <span class="badge" style="background: var(--primary); color: white; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm);">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem;"><?php echo $user['order_count'] ?? 0; ?></td>
                                        <td style="padding: 1rem;">₹<?php echo number_format($user['total_spent'] ?? 0, 2); ?></td>
                                        <td style="padding: 1rem;">
                                            <span class="status-badge status-<?php echo $user['status']; ?>">
                                                <?php echo ucfirst($user['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; text-align: center;">
                                            <div class="u-flex u-gap-sm u-justify-center">
                                                <a href="/admin/users/edit?id=<?php echo $user['id']; ?>" class="btn btn-outline btn-sm" title="Edit">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                                </a>
                                                <?php if ($user['id'] != Auth::id()): ?>
                                                    <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="btn btn-outline btn-sm" style="color: var(--error);" title="Delete">
                                                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                                    </button>
                                                <?php endif; ?>
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
                            <div class="u-text-muted">Showing <?php echo (($page - 1) * $perPage) + 1; ?> - <?php echo min($page * $perPage, $total); ?> of <?php echo $total; ?> users</div>
                            <div class="u-flex u-gap-sm">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&role=<?php echo urlencode($role ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>" class="btn btn-outline btn-sm">Previous</a>
                                <?php endif; ?>
                                <?php if ($page * $perPage < $total): ?>
                                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&role=<?php echo urlencode($role ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>" class="btn btn-outline btn-sm">Next</a>
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

window.deleteUser = async function(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await ajaxRequest('/api/admin/users/delete', {
            method: 'POST',
            body: JSON.stringify({
                id: userId,
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        });
        
        if (response.success) {
            showToast(response.message || 'User deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(response.message || 'Failed to delete user', 'error');
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
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
</style>

