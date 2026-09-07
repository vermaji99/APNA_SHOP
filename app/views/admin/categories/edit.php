<?php
/**
 * Admin Edit Category Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Edit Category</h1>
            <a href="/admin/categories" class="btn btn-outline">Back to Categories</a>
        </div>

        <form id="category-form" class="card" style="max-width: 600px;">
            <div class="card-body">
                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                
                <div class="form-group u-mb-md">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-input" value="<?php echo htmlspecialchars($category['slug']); ?>">
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="4"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group u-mb-md">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-input">
                        <option value="">None (Top Level)</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($cat['id'] != $category['id']): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $category['parent_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input" value="<?php echo $category['sort_order']; ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active" <?php echo $category['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $category['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="u-mt-lg">
                    <button type="submit" class="btn btn-primary btn-block">Update Category</button>
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
    submitBtn.textContent = 'Updating...';
    
    try {
        const response = await fetch('/api/admin/categories/update', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'Category updated successfully', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/categories';
            }, 1000);
        } else {
            showToast(data.message || 'Failed to update category', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Category';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Category';
    }
});
</script>



