<?php
/**
 * Admin Create Category Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Add New Category</h1>
            <a href="/admin/categories" class="btn btn-outline">Back to Categories</a>
        </div>

        <form id="category-form" class="card" style="max-width: 600px;">
            <div class="card-body">
                <div class="form-group u-mb-md">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-input" required>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-input" placeholder="Auto-generated from name">
                    <small class="u-text-muted">Leave empty to auto-generate</small>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="4"></textarea>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-input">
                        <option value="">None (Top Level)</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="u-mt-lg">
                    <button type="submit" class="btn btn-primary btn-block">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

const form = document.getElementById('category-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';
    
    try {
        const response = await fetch('/api/admin/categories/store', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'Category created successfully', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/categories';
            }, 1000);
        } else {
            showToast(data.message || 'Failed to create category', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Category';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Category';
    }
});
</script>



