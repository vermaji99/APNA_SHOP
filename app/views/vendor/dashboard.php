<?php
/**
 * Vendor Dashboard
 */
?>

<section class="section">
    <div class="container">
        <div class="u-flex u-justify-between u-items-center u-mb-xl">
            <h1>Vendor Dashboard</h1>
            <a href="/pages/home.php" class="btn btn-outline">Back to Site</a>
        </div>
        
        <div class="grid grid-cols-2 reveal u-mb-xl">
            <div class="card">
                <div class="card-body u-text-center">
                    <div class="u-text-3xl u-font-bold u-text-primary u-mb-sm"><?php echo $productCount; ?></div>
                    <div class="u-text-muted">My Products</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body u-text-center">
                    <div class="u-text-3xl u-font-bold u-text-primary u-mb-sm"><?php echo $orderCount; ?></div>
                    <div class="u-text-muted">Total Orders</div>
                </div>
            </div>
        </div>
        
        <div class="card reveal">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="u-flex u-gap-md u-flex-wrap">
                    <a href="#" class="btn btn-primary">Add New Product</a>
                    <a href="#" class="btn btn-outline">Manage Products</a>
                    <a href="#" class="btn btn-outline">View Orders</a>
                    <a href="#" class="btn btn-outline">Analytics</a>
                </div>
            </div>
        </div>
    </div>
</section>




