<?php
/**
 * Admin Create Product Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Add New Product</h1>
            <a href="/admin/products" class="btn btn-outline">Back to Products</a>
        </div>

        <form id="product-form" class="card">
            <div class="card-body">
                <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <!-- Main Form -->
                    <div>
                        <div class="form-group u-mb-md">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-input" placeholder="Auto-generated from name">
                            <small class="u-text-muted">Leave empty to auto-generate</small>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">SKU *</label>
                            <input type="text" name="sku" class="form-input" required>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-input" required>
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No categories available. Please create a category first.</option>
                                <?php endif; ?>
                            </select>
                            <?php if (empty($categories)): ?>
                                <small class="u-text-muted" style="color: var(--error);">You need to create at least one category before adding products.</small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-input" rows="2" placeholder="Brief product description"></textarea>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="6" placeholder="Full product description"></textarea>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Price (₹) *</label>
                                <input type="number" name="price" class="form-input" step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Compare Price (₹)</label>
                                <input type="number" name="compare_price" class="form-input" step="0.01" min="0" placeholder="Original price">
                            </div>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_quantity" class="form-input" min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Stock Status</label>
                                <select name="stock_status" class="form-input">
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="backorder">Backorder</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-input" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dimensions</label>
                                <input type="text" name="dimensions" class="form-input" placeholder="L x W x H">
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div>
                        <div class="card u-mb-md">
                            <div class="card-header">
                                <h3 class="card-title">Publish</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group u-mb-md">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-input">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="u-flex u-items-center u-gap-sm" style="cursor: pointer;">
                                        <input type="checkbox" name="featured" value="1" id="featured-checkbox">
                                        <span>Featured Product</span>
                                    </label>
                                </div>

                                <div class="u-mt-md">
                                    <button type="submit" class="btn btn-primary btn-block">Create Product</button>
                                </div>
                            </div>
                        </div>

                        <div class="card u-mb-md">
                            <div class="card-header">
                                <h3 class="card-title">Product Image</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="file" name="image" class="form-input" accept="image/*">
                                    <small class="u-text-muted">Recommended: 800x800px</small>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">SEO</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group u-mb-md">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-input" placeholder="SEO title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-input" rows="3" placeholder="SEO description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

const form = document.getElementById('product-form');
if (!form) {
    console.error('Product form not found!');
} else {
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Check if category is selected
        const categoryId = form.querySelector('[name="category_id"]').value;
        if (!categoryId) {
            showToast('Please select a category', 'error');
            return;
        }
        
        const formData = new FormData(form);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
        
        try {
            const response = await fetch('/api/admin/products/store', {
                method: 'POST',
                body: formData
            });
            
            let data;
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                showToast('Server error: ' + text.substring(0, 100), 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Product';
                return;
            }
            
            if (data.success) {
                showToast(data.message || 'Product created successfully', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect || '/admin/products';
                }, 1000);
            } else {
                showToast(data.message || data.error || 'Failed to create product', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Product';
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('An error occurred: ' + error.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Product';
        }
    });
}
</script>
