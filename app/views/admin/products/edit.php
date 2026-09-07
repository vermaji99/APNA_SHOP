<?php
/**
 * Admin Edit Product Page
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Edit Product</h1>
            <a href="/admin/products" class="btn btn-outline">Back to Products</a>
        </div>

        <form id="product-form" class="card">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <div class="card-body">
                <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <!-- Main Form -->
                    <div>
                        <div class="form-group u-mb-md">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-input" value="<?php echo htmlspecialchars($product['slug']); ?>">
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">SKU *</label>
                            <input type="text" name="sku" class="form-input" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-input" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-input" rows="2"><?php echo htmlspecialchars($product['short_description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group u-mb-md">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="6"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Price (₹) *</label>
                                <input type="number" name="price" class="form-input" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Compare Price (₹)</label>
                                <input type="number" name="compare_price" class="form-input" step="0.01" min="0" value="<?php echo $product['compare_price'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_quantity" class="form-input" min="0" value="<?php echo $product['stock_quantity']; ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Stock Status</label>
                                <select name="stock_status" class="form-input">
                                    <option value="in_stock" <?php echo ($product['stock_status'] ?? 'in_stock') === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                                    <option value="out_of_stock" <?php echo ($product['stock_status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                    <option value="backorder" <?php echo ($product['stock_status'] ?? '') === 'backorder' ? 'selected' : ''; ?>>Backorder</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-input" step="0.01" min="0" value="<?php echo $product['weight'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dimensions</label>
                                <input type="text" name="dimensions" class="form-input" value="<?php echo htmlspecialchars($product['dimensions'] ?? ''); ?>">
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
                                        <option value="draft" <?php echo $product['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                        <option value="published" <?php echo $product['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                        <option value="archived" <?php echo $product['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="u-flex u-items-center u-gap-sm" style="cursor: pointer;">
                                        <input type="checkbox" name="featured" value="1" <?php echo $product['featured'] ? 'checked' : ''; ?>>
                                        <span>Featured Product</span>
                                    </label>
                                </div>

                                <div class="u-mt-md">
                                    <button type="submit" class="btn btn-primary btn-block">Update Product</button>
                                </div>
                            </div>
                        </div>

                        <div class="card u-mb-md">
                            <div class="card-header">
                                <h3 class="card-title">Product Image</h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($productImages)): ?>
                                    <div class="u-mb-md">
                                        <img src="<?php echo htmlspecialchars($productImages[0]['image_path']); ?>" 
                                             alt="Current image" 
                                             style="width: 100%; max-width: 200px; border-radius: var(--radius-md);">
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <input type="file" name="image" class="form-input" accept="image/*">
                                    <small class="u-text-muted">Leave empty to keep current image</small>
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
                                    <input type="text" name="meta_title" class="form-input" value="<?php echo htmlspecialchars($product['meta_title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-input" rows="3"><?php echo htmlspecialchars($product['meta_description'] ?? ''); ?></textarea>
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
const submitBtn = form.querySelector('button[type="submit"]');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    try {
        const response = await fetch('/api/admin/products/update', {
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
            submitBtn.textContent = 'Update Product';
            return;
        }
        
        if (data.success) {
            showToast(data.message || 'Product updated successfully', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/products';
            }, 1000);
        } else {
            showToast(data.message || data.error || 'Failed to update product', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Product';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Product';
    }
});
</script>

