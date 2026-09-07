<?php
/**
 * Shop Page
 */
?>

<section class="section">
    <div class="container">
        <div class="section-header" style="text-align: left; margin-bottom: 2.5rem; max-width: 100%;">
            <span class="section-subtitle">OUR COLLECTION</span>
            <h1 class="section-title" style="font-size: 2.5rem;">SHOP APPAREL</h1>
            <?php if (!empty($search)): ?>
                <p class="section-desc">Search results for: "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
            <?php else: ?>
                <p class="section-desc">Browse our curated selection of premium menswear essentials.</p>
            <?php endif; ?>
        </div>
        
        <div class="shop-layout" style="display: grid; grid-template-columns: 240px 1fr; gap: 2.5rem; align-items: start;">
            <!-- Category Sidebar -->
            <aside class="shop-sidebar">
                <div class="card" style="background: #141414; border: 1px solid var(--border-color); padding: 1.5rem; border-radius: var(--radius-lg);">
                    <h3 class="card-title" style="font-size: 1rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">CATEGORIES</h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.5rem;">
                            <a href="/pages/shop.php" class="nav-link <?php echo !$currentCategory ? 'active' : ''; ?>" style="display: block; padding: 0.5rem 0.75rem; border-radius: var(--radius-sm);">All Products</a>
                        </li>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <li style="margin-bottom: 0.5rem;">
                                    <a href="/pages/shop.php?category=<?php echo $cat['id']; ?>" class="nav-link <?php echo $currentCategory == $cat['id'] ? 'active' : ''; ?>" style="display: block; padding: 0.5rem 0.75rem; border-radius: var(--radius-sm);"><?php echo htmlspecialchars($cat['name']); ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>
            
            <!-- Products Main Column -->
            <div class="shop-products-column">
                <?php if (empty($products)): ?>
                    <div class="card u-text-center" style="padding: 4rem 2rem; background: #141414; border: 1px solid var(--border-color); text-align: center;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted); margin-bottom: 1rem;">inventory_2</span>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #ffffff;">No Products Found</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">We couldn't find any products matching your selection.</p>
                        <a href="/pages/shop.php" class="btn btn-primary btn-sm">VIEW ALL PRODUCTS</a>
                    </div>
                <?php else: ?>
                    <div class="product-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <?php 
                        foreach ($products as $product): 
                            $product['image'] = $product['image'] ?? '/assets/images/placeholder.jpg';
                            View::partial('product-card', ['product' => $product]);
                        endforeach; 
                        ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($total, $perPage) && $total > $perPage): ?>
                        <div class="pagination-wrapper" style="margin-top: 3.5rem; text-align: center; display: flex; justify-content: center; gap: 0.5rem;">
                            <?php
                            $totalPages = ceil($total / $perPage);
                            for ($i = 1; $i <= $totalPages; $i++):
                            ?>
                                <a href="?page=<?php echo $i; ?><?php echo $currentCategory ? '&category=' . $currentCategory : ''; ?>" 
                                   class="btn btn-secondary btn-sm <?php echo (isset($page) && $page == $i) ? 'active' : ''; ?>"
                                   style="<?php echo (isset($page) && $page == $i) ? 'background-color: var(--primary); border-color: var(--primary); color: #fff;' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
