<?php
/**
 * User Profile Page - Modern Design
 * Note: This file is rendered through View::render() which handles the layout
 */
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/Address.php';

$orderModel = new Order();
$addressModel = new Address();
$recentOrders = $orderModel->getByUser(Auth::id());
$addresses = $addressModel->findAll(['user_id' => Auth::id()]);
$orderCount = count($recentOrders);
?>

<section class="section">
    <div class="container">
        <div class="profile-header reveal">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <span class="material-symbols-outlined" style="font-size: 48px;">account_circle</span>
                </div>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['name']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="profile-badges">
                    <span class="role-badge role-<?php echo $user['role']; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                    <?php if ($user['email_verified']): ?>
                        <span class="status-badge verified">
                            <span class="material-symbols-outlined">verified</span>
                            Verified
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-tabs reveal reveal-delay-1">
            <button class="profile-tab active" data-tab="profile">Profile</button>
            <button class="profile-tab" data-tab="orders">Orders (<?php echo $orderCount; ?>)</button>
            <button class="profile-tab" data-tab="addresses">Addresses</button>
            <button class="profile-tab" data-tab="security">Security</button>
            <button class="profile-tab" data-tab="permissions">Permissions</button>
        </div>

        <div class="profile-content">
            <!-- Profile Tab -->
            <div class="profile-tab-content active" id="tab-profile">
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Personal Information</h3>
                        </div>
                        <form id="profile-form" class="card-body">
                            <input type="hidden" name="csrf_token" value="<?php echo Session::get('csrf_token'); ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                <div class="form-help">Email cannot be changed</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Account Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="stat-grid">
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $orderCount; ?></div>
                                    <div class="stat-label">Total Orders</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo count($addresses); ?></div>
                                    <div class="stat-label">Saved Addresses</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                                    <div class="stat-label">Member Since</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="profile-tab-content" id="tab-orders">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Orders</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentOrders)): ?>
                            <div class="empty-state">
                                <span class="material-symbols-outlined" style="font-size: 64px; opacity: 0.3;">shopping_bag</span>
                                <p>No orders yet</p>
                                <a href="/pages/shop.php" class="btn btn-primary">Start Shopping</a>
                            </div>
                        <?php else: ?>
                            <div class="orders-list">
                                <?php foreach (array_slice($recentOrders, 0, 10) as $order): ?>
                                    <div class="order-item">
                                        <div class="order-info">
                                            <div class="order-number">
                                                <strong>Order #<?php echo htmlspecialchars($order['order_number']); ?></strong>
                                            </div>
                                            <div class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                                            <div class="order-status">
                                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="order-amount">
                                            <strong>₹<?php echo number_format($order['total'], 2); ?></strong>
                                        </div>
                                        <div class="order-actions">
                                            <a href="/pages/order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">View Details</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="u-text-center u-mt-lg">
                                <a href="/pages/user-orders.php" class="btn btn-outline">View All Orders</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div class="profile-tab-content" id="tab-addresses">
                <div class="card">
                    <div class="card-header u-flex u-justify-between u-items-center">
                        <h3 class="card-title">Saved Addresses</h3>
                        <button class="btn btn-primary btn-sm" id="add-address-btn">Add Address</button>
                    </div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <div class="empty-state">
                                <span class="material-symbols-outlined" style="font-size: 64px; opacity: 0.3;">location_on</span>
                                <p>No addresses saved</p>
                            </div>
                        <?php else: ?>
                            <div class="addresses-grid">
                                <?php foreach ($addresses as $address): ?>
                                    <div class="address-card">
                                        <div class="address-header">
                                            <strong><?php echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name']); ?></strong>
                                            <?php if ($address['is_default']): ?>
                                                <span class="badge">Default</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="address-body">
                                            <p><?php echo htmlspecialchars($address['address_line1']); ?></p>
                                            <?php if ($address['address_line2']): ?>
                                                <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                                            <?php endif; ?>
                                            <p><?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['zip_code']); ?></p>
                                            <p><?php echo htmlspecialchars($address['country']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($address['phone']); ?></p>
                                        </div>
                                        <div class="address-actions">
                                            <button class="btn btn-ghost btn-sm">Edit</button>
                                            <button class="btn btn-ghost btn-sm">Delete</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="profile-tab-content" id="tab-security">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <form id="change-password-form" class="card-body">
                        <input type="hidden" name="csrf_token" value="<?php echo Session::get('csrf_token'); ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-input" required minlength="8">
                            <div class="form-help">Must be at least 8 characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-input" required minlength="8">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>

            <!-- Permissions Tab -->
            <div class="profile-tab-content" id="tab-permissions">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Account Permissions & Rights</h3>
                    </div>
                    <div class="card-body">
                        <div class="permissions-info">
                            <div class="permission-section">
                                <h4>Your Role: <span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></h4>
                                
                                <?php if ($user['role'] === 'customer'): ?>
                                    <div class="permission-list">
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Browse and purchase products</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Add items to cart and wishlist</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Place orders and track shipments</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Manage profile and addresses</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>View order history</span>
                                        </div>
                                    </div>
                                <?php elseif ($user['role'] === 'vendor'): ?>
                                    <div class="permission-list">
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>All customer permissions</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Add and manage products</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>View sales and analytics</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Manage inventory</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Update order status</span>
                                        </div>
                                    </div>
                                <?php elseif ($user['role'] === 'admin'): ?>
                                    <div class="permission-list">
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>All vendor and customer permissions</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Manage all users and vendors</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Manage all products and categories</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>View all orders and analytics</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>System settings and configuration</span>
                                        </div>
                                        <div class="permission-item">
                                            <span class="material-symbols-outlined">check_circle</span>
                                            <span>Approve/reject vendors</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="permission-section u-mt-lg">
                                <h4>Account Status</h4>
                                <div class="status-info">
                                    <p><strong>Status:</strong> <span class="status-badge status-<?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span></p>
                                    <p><strong>Email Verified:</strong> <?php echo $user['email_verified'] ? 'Yes' : 'No'; ?></p>
                                    <p><strong>Account Created:</strong> <?php echo date('F d, Y', strtotime($user['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.profile-avatar .avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: var(--radius-full);
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
}

.profile-info h1 {
    margin-bottom: 0.5rem;
}

.profile-email {
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.profile-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semibold);
}

.role-badge.role-customer {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.role-badge.role-vendor {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.role-badge.role-admin {
    background: rgba(212, 17, 50, 0.2);
    color: var(--primary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: var(--font-size-sm);
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.verified {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-badge.status-pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.status-badge.status-processing {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-badge.status-shipped {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.status-badge.status-delivered {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.profile-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
    overflow-x: auto;
}

.profile-tab {
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--text-muted);
    cursor: pointer;
    font-size: var(--font-size-base);
    transition: all var(--transition-fast);
}

.profile-tab:hover {
    color: var(--text-primary);
}

.profile-tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.profile-tab-content {
    display: none;
}

.profile-tab-content.active {
    display: block;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    background: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
}

.stat-value {
    font-size: var(--font-size-3xl);
    font-weight: var(--font-weight-bold);
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-muted);
    font-size: var(--font-size-sm);
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: grid;
    grid-template-columns: 2fr 1fr auto;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    align-items: center;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.order-number {
    font-weight: var(--font-weight-semibold);
}

.order-date {
    color: var(--text-muted);
    font-size: var(--font-size-sm);
}

.order-amount {
    text-align: right;
    font-size: var(--font-size-lg);
}

.addresses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.address-card {
    padding: 1.5rem;
    background: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.address-body p {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.address-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.permission-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}

.permission-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
}

.permission-item .material-symbols-outlined {
    color: var(--success);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}

.user-dropdown-menu {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: var(--bg-darker);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    min-width: 250px;
    box-shadow: var(--shadow-xl);
    display: none;
    z-index: var(--z-dropdown);
}

.user-dropdown-menu.active {
    display: block;
}

.user-dropdown-header {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.user-dropdown-name {
    font-weight: var(--font-weight-semibold);
    margin-bottom: 0.25rem;
}

.user-dropdown-email {
    font-size: var(--font-size-sm);
    color: var(--text-muted);
    margin-bottom: 0.5rem;
}

.user-dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 0.5rem 0;
}

.user-dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    text-decoration: none;
    transition: background-color var(--transition-fast);
}

.user-dropdown-item:hover {
    background: var(--bg-card);
}

.user-dropdown-item .material-symbols-outlined {
    font-size: 20px;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Tab switching
document.querySelectorAll('.profile-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const targetTab = tab.dataset.tab;
        
        // Remove active from all tabs and contents
        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.profile-tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active to clicked tab and corresponding content
        tab.classList.add('active');
        document.getElementById(`tab-${targetTab}`).classList.add('active');
    });
});

// User dropdown menu
const userMenuToggle = document.getElementById('user-menu-toggle');
const userDropdownMenu = document.getElementById('user-dropdown-menu');

if (userMenuToggle && userDropdownMenu) {
    userMenuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdownMenu.classList.toggle('active');
    });
    
    document.addEventListener('click', () => {
        userDropdownMenu.classList.remove('active');
    });
    
    userDropdownMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });
}

// Logout functionality
const logoutBtn = document.getElementById('logout-btn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
        if (confirm('Are you sure you want to logout?')) {
            try {
                const response = await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        csrf_token: document.querySelector('meta[name="csrf-token"]').content
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/pages/home.php';
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
        }
    });
}

// Profile update form
const profileForm = document.getElementById('profile-form');
if (profileForm) {
    profileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(profileForm);
        
        try {
            const response = await fetch('/api/user/update-profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': formData.get('csrf_token')
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    phone: formData.get('phone'),
                    csrf_token: formData.get('csrf_token')
                })
            });
            
            const data = await response.json();
            if (data.success) {
                alert('Profile updated successfully');
                location.reload();
            } else {
                alert(data.message || 'Failed to update profile');
            }
        } catch (error) {
            console.error('Update error:', error);
            alert('An error occurred');
        }
    });
}

// Change password form
const changePasswordForm = document.getElementById('change-password-form');
if (changePasswordForm) {
    changePasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(changePasswordForm);
        
        if (formData.get('new_password') !== formData.get('confirm_password')) {
            alert('Passwords do not match');
            return;
        }
        
        try {
            const response = await fetch('/api/user/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': formData.get('csrf_token')
                },
                body: JSON.stringify({
                    current_password: formData.get('current_password'),
                    new_password: formData.get('new_password'),
                    csrf_token: formData.get('csrf_token')
                })
            });
            
            const data = await response.json();
            if (data.success) {
                alert('Password changed successfully');
                changePasswordForm.reset();
            } else {
                alert(data.message || 'Failed to change password');
            }
        } catch (error) {
            console.error('Change password error:', error);
            alert('An error occurred');
        }
    });
}
</script>
