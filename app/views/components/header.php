<?php
require_once APP_PATH . '/models/Cart.php';
$user = Auth::user();
$cartCount = 0;
if (Auth::check()) {
    $cartModel = new Cart();
    $cartCount = $cartModel->getCount(Auth::id());
} else {
    $cartModel = new Cart();
    $cartCount = $cartModel->getCount(null, session_id());
}
?>
<header class="header">
    <div class="container">
        <div class="header-content">
            <!-- Brand Logo -->
            <a href="/pages/home.php" class="logo">
                <span class="logo-brand">APNA<span class="logo-accent">-MENS</span></span>
            </a>
            
            <!-- Navigation Links -->
            <nav class="nav" id="main-nav">
                <a href="/pages/home.php" class="nav-link">HOME</a>
                <a href="/pages/shop.php" class="nav-link">SHOP</a>
                <a href="/pages/home.php#new-arrivals" class="nav-link">NEW ARRIVALS</a>
                <a href="/pages/home.php#categories" class="nav-link">COLLECTIONS</a>
                <a href="/pages/shop.php?sale=1" class="nav-link nav-link-sale">SALE</a>
            </nav>
            
            <!-- Action Icons & Controls -->
            <div class="navbar-actions">
                <!-- Search Bar -->
                <div class="navbar-search">
                    <span class="navbar-search-icon material-symbols-outlined">search</span>
                    <input type="text" class="navbar-search-input" placeholder="Search apparel..." aria-label="Search products">
                    <div class="search-results"></div>
                </div>
                
                <!-- Wishlist -->
                <a href="/pages/wishlist.php" class="navbar-icon-btn" title="Wishlist" aria-label="Wishlist">
                    <span class="material-symbols-outlined">favorite</span>
                </a>
                
                <!-- Cart Icon with Badge -->
                <a href="/pages/cart.php" class="navbar-icon-btn cart-icon-btn" title="Shopping Cart" aria-label="Shopping Cart">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    <span class="navbar-icon-badge cart-count <?php echo $cartCount > 0 ? '' : 'u-hidden'; ?>"><?php echo $cartCount; ?></span>
                </a>

                <!-- Account / User Menu -->
                <?php if (Auth::check()): ?>
                    <div class="navbar-user-dropdown">
                        <button class="navbar-icon-btn" title="Account" id="user-menu-toggle" type="button" aria-label="User Account">
                            <span class="material-symbols-outlined">person</span>
                        </button>
                        <div class="user-dropdown-menu" id="user-dropdown-menu">
                            <div class="user-dropdown-header">
                                <div class="user-dropdown-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                <div class="user-dropdown-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                <div class="user-dropdown-role">
                                    <span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                                </div>
                            </div>
                            <div class="user-dropdown-divider"></div>
                            <a href="/pages/user-profile.php" class="user-dropdown-item">
                                <span class="material-symbols-outlined">account_circle</span>
                                <span>My Profile</span>
                            </a>
                            <a href="/pages/user-orders.php" class="user-dropdown-item">
                                <span class="material-symbols-outlined">orders</span>
                                <span>My Orders</span>
                            </a>
                            <a href="/pages/wishlist.php" class="user-dropdown-item">
                                <span class="material-symbols-outlined">favorite</span>
                                <span>Wishlist</span>
                            </a>
                            <?php if ($user['role'] === 'admin'): ?>
                                <a href="/admin/dashboard.php" class="user-dropdown-item">
                                    <span class="material-symbols-outlined">admin_panel_settings</span>
                                    <span>Admin Dashboard</span>
                                </a>
                            <?php elseif ($user['role'] === 'vendor'): ?>
                                <a href="/vendor/dashboard.php" class="user-dropdown-item">
                                    <span class="material-symbols-outlined">store</span>
                                    <span>Vendor Panel</span>
                                </a>
                            <?php endif; ?>
                            <div class="user-dropdown-divider"></div>
                            <button class="user-dropdown-item" id="logout-btn" type="button" style="width: 100%; text-align: left; border: none; background: none; color: #ef4444; cursor: pointer;">
                                <span class="material-symbols-outlined">logout</span>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/pages/login.php" class="navbar-icon-btn" title="Sign In" aria-label="Sign In">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                <?php endif; ?>
                
                <!-- Mobile Hamburger Toggle -->
                <button class="menu-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </div>
</header>
