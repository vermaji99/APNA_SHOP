<?php
/**
 * Checkout Page
 * Note: This file is rendered through View::render() which handles the layout
 */
?>

<section class="section">
    <div class="container">
        <h1 class="reveal">Checkout</h1>
        
        <form id="checkout-form" class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="reveal">
                <div class="card u-mb-lg">
                    <h3 class="card-title">Shipping Address</h3>
                    <div class="card-body">
                        <?php if (!empty($addresses)): ?>
                            <?php foreach ($addresses as $address): ?>
                                <label class="u-flex u-items-start u-gap-sm u-mb-md" style="cursor: pointer; padding: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                    <input type="radio" name="shipping_address_id" value="<?php echo $address['id']; ?>" <?php echo $address['is_default'] ? 'checked' : ''; ?> required>
                                    <div>
                                        <strong><?php echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name']); ?></strong>
                                        <?php if ($address['is_default']): ?>
                                            <span class="badge" style="margin-left: 0.5rem; padding: 0.25rem 0.5rem; background: var(--primary); color: white; border-radius: var(--radius-sm); font-size: 0.75rem;">Default</span>
                                        <?php endif; ?>
                                        <br>
                                        <span style="color: var(--text-muted);"><?php echo htmlspecialchars($address['address_line1']); ?></span><br>
                                        <?php if ($address['address_line2']): ?>
                                            <span style="color: var(--text-muted);"><?php echo htmlspecialchars($address['address_line2']); ?></span><br>
                                        <?php endif; ?>
                                        <span style="color: var(--text-muted);"><?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['zip_code']); ?></span><br>
                                        <span style="color: var(--text-muted);">Phone: <?php echo htmlspecialchars($address['phone']); ?></span>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div id="new-address-form">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" name="phone" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address Line 1</label>
                                    <input type="text" name="address_line1" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="address_line2" class="form-input">
                                </div>
                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state" class="form-input" required>
                                    </div>
                                </div>
                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div class="form-group">
                                        <label class="form-label">Zip Code</label>
                                        <input type="text" name="zip_code" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-input" value="India" required>
                                    </div>
                                </div>
                                <input type="hidden" name="shipping_address_id" value="new">
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($addresses)): ?>
                            <div class="u-mt-md">
                                <button type="button" class="btn btn-outline" id="add-new-address-btn">Add New Address</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <h3 class="card-title">Payment Method</h3>
                    <div class="card-body">
                        <label class="u-flex u-items-center u-gap-sm u-mb-md" style="cursor: pointer;">
                            <input type="radio" name="payment_method" value="cod" checked required>
                            <span>Cash on Delivery</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="reveal reveal-delay-1">
                <div class="card">
                    <h3 class="card-title">Order Summary</h3>
                    <div class="card-body">
                        <?php foreach ($items as $item): ?>
                            <div class="u-flex u-justify-between u-mb-sm">
                                <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                                <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr class="u-mb-md">
                        <div class="u-flex u-justify-between u-mb-sm">
                            <span>Subtotal</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="u-flex u-justify-between u-mb-sm">
                            <span>Tax</span>
                            <span>₹<?php echo number_format($tax, 2); ?></span>
                        </div>
                        <div class="u-flex u-justify-between u-mb-sm">
                            <span>Shipping</span>
                            <span><?php echo $shipping > 0 ? '₹' . number_format($shipping, 2) : 'Free'; ?></span>
                        </div>
                        <hr class="u-mb-md">
                        <div class="u-flex u-justify-between u-font-bold u-text-lg">
                            <span>Total</span>
                            <span>₹<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="csrf_token" value="<?php echo Session::get('csrf_token'); ?>">
                        <button type="submit" class="btn btn-primary btn-block">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="module">
import { ajaxRequest } from '/assets/js/ajax.js';
import { showToast } from '/assets/js/toast.js';

const checkoutForm = document.getElementById('checkout-form');
const addNewAddressBtn = document.getElementById('add-new-address-btn');

// Show new address form
if (addNewAddressBtn) {
    addNewAddressBtn.addEventListener('click', () => {
        const newAddressForm = document.getElementById('new-address-form');
        if (newAddressForm) {
            newAddressForm.style.display = 'block';
            addNewAddressBtn.style.display = 'none';
        } else {
            // Create new address form
            const addressCard = addNewAddressBtn.closest('.card-body');
            const formHTML = `
                <div id="new-address-form" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <h4 style="margin-bottom: 1rem;">Add New Address</h4>
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address Line 1</label>
                        <input type="text" name="address_line1" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-input">
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-input" required>
                        </div>
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="zip_code" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-input" value="India" required>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_address_id" value="new">
                </div>
            `;
            addressCard.insertAdjacentHTML('beforeend', formHTML);
            addNewAddressBtn.style.display = 'none';
        }
    });
}

checkoutForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';
    
    try {
        const data = {
            payment_method: formData.get('payment_method'),
            csrf_token: formData.get('csrf_token')
        };
        
        const shippingAddressId = formData.get('shipping_address_id');
        if (shippingAddressId === 'new') {
            // Create new address first
            const addressData = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                phone: formData.get('phone'),
                address_line1: formData.get('address_line1'),
                address_line2: formData.get('address_line2'),
                city: formData.get('city'),
                state: formData.get('state'),
                zip_code: formData.get('zip_code'),
                country: formData.get('country') || 'India',
                type: 'both',
                is_default: true,
                csrf_token: formData.get('csrf_token')
            };
            
            const addressResponse = await ajaxRequest('/api/user/create-address', {
                method: 'POST',
                body: JSON.stringify(addressData)
            });
            
            if (addressResponse.success) {
                data.shipping_address_id = addressResponse.address_id;
                data.billing_address_id = addressResponse.address_id;
            } else {
                showToast(addressResponse.message || 'Failed to save address', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Place Order';
                return;
            }
        } else {
            data.shipping_address_id = shippingAddressId;
            data.billing_address_id = shippingAddressId;
        }
        
        const response = await ajaxRequest('/api/checkout/process', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.success) {
            showToast('Order placed successfully!', 'success');
            setTimeout(() => {
                window.location.href = response.redirect;
            }, 1000);
        } else {
            showToast(response.message || 'Checkout failed', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Place Order';
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showToast('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Place Order';
    }
});
</script>


