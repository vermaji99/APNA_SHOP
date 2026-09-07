<?php
/**
 * Admin Edit User Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Edit User</h1>
            <a href="/admin/users" class="btn btn-outline">Back to Users</a>
        </div>

        <form id="user-form" class="card" style="max-width: 600px;">
            <div class="card-body">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                
                <div class="form-group u-mb-md">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-input" required>
                        <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="vendor" <?php echo $user['role'] === 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="u-mt-lg">
                    <button type="submit" class="btn btn-primary btn-block">Update User</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

const form = document.getElementById('user-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    try {
        const response = await fetch('/api/admin/users/update', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'User updated successfully', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/users';
            }, 1000);
        } else {
            showToast(data.message || 'Failed to update user', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update User';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update User';
    }
});
</script>



