/**
 * Cart Functionality
 * Add to cart, update quantity, remove items
 */

import { ajaxRequest } from './ajax.js';
import { showToast } from './toast.js';

let cartCount = 0;

export function initCart() {
  updateCartCount();
  
  // Add to cart buttons
  document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', handleAddToCart);
  });
  
  // Buy now buttons
  document.querySelectorAll('.buy-now').forEach(btn => {
    btn.addEventListener('click', handleBuyNow);
  });
  
  // Update quantity buttons
  document.querySelectorAll('.cart-qty-btn').forEach(btn => {
    btn.addEventListener('click', handleUpdateQuantity);
  });
  
  // Remove from cart buttons
  document.querySelectorAll('.cart-remove').forEach(btn => {
    btn.addEventListener('click', handleRemoveFromCart);
  });
}

async function handleAddToCart(e) {
  e.preventDefault();
  const btn = e.currentTarget;
  const productId = btn.dataset.productId;
  const quantityInput = document.getElementById('quantity');
  const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
  
  if (!productId) return;
  
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="loader" style="width: 16px; height: 16px; border-width: 2px;"></span>';
  
  try {
    const response = await ajaxRequest('/api/cart/add', {
      method: 'POST',
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity,
        csrf_token: getCsrfToken()
      })
    });
    
    if (response.success) {
      showToast('Product added to cart', 'success');
      updateCartCount();
      btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Added';
      setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }, 2000);
    } else {
      showToast(response.message || 'Failed to add to cart', 'error');
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  } catch (error) {
    console.error('Add to cart error:', error);
    showToast('An error occurred', 'error');
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

async function handleBuyNow(e) {
  e.preventDefault();
  const btn = e.currentTarget;
  const productId = btn.dataset.productId;
  const quantityInput = document.getElementById('quantity');
  const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
  
  if (!productId) return;
  
  // Check if user is logged in
  const isLoggedIn = document.querySelector('.navbar-user-dropdown') !== null;
  if (!isLoggedIn) {
    if (confirm('Please login to continue. Redirect to login page?')) {
      window.location.href = '/pages/login.php?redirect=' + encodeURIComponent(window.location.pathname);
    }
    return;
  }
  
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="loader" style="width: 16px; height: 16px; border-width: 2px;"></span>';
  
  try {
    // First add to cart
    const addResponse = await ajaxRequest('/api/cart/add', {
      method: 'POST',
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity,
        csrf_token: getCsrfToken()
      })
    });
    
    if (addResponse.success) {
      updateCartCount();
      // Redirect to checkout
      window.location.href = '/pages/checkout.php';
    } else {
      showToast(addResponse.message || 'Failed to add to cart', 'error');
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  } catch (error) {
    console.error('Buy now error:', error);
    showToast('An error occurred', 'error');
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

async function handleUpdateQuantity(e) {
  const btn = e.currentTarget;
  const cartId = btn.dataset.cartId;
  const action = btn.dataset.action; // 'increase' or 'decrease'
  
  if (!cartId) return;
  
  const qtyInput = document.querySelector(`input[data-cart-id="${cartId}"]`);
  if (!qtyInput) return;
  
  let newQty = parseInt(qtyInput.value) || 1;
  if (action === 'increase') {
    newQty++;
  } else if (action === 'decrease' && newQty > 1) {
    newQty--;
  } else {
    return;
  }
  
  try {
    const response = await ajaxRequest('/api/cart/update', {
      method: 'POST',
      body: JSON.stringify({
        cart_id: cartId,
        quantity: newQty,
        csrf_token: getCsrfToken()
      })
    });
    
    if (response.success) {
      qtyInput.value = newQty;
      updateCartCount();
      updateCartTotal();
      showToast('Cart updated', 'success');
    } else {
      showToast(response.message || 'Failed to update cart', 'error');
    }
  } catch (error) {
    console.error('Update cart error:', error);
    showToast('An error occurred', 'error');
  }
}

async function handleRemoveFromCart(e) {
  e.preventDefault();
  const btn = e.currentTarget;
  const cartId = btn.dataset.cartId;
  
  if (!cartId || !confirm('Remove this item from cart?')) return;
  
  try {
    const response = await ajaxRequest('/api/cart/remove', {
      method: 'POST',
      body: JSON.stringify({
        cart_id: cartId,
        csrf_token: getCsrfToken()
      })
    });
    
    if (response.success) {
      btn.closest('.cart-item')?.remove();
      updateCartCount();
      updateCartTotal();
      showToast('Item removed from cart', 'success');
    } else {
      showToast(response.message || 'Failed to remove item', 'error');
    }
  } catch (error) {
    console.error('Remove from cart error:', error);
    showToast('An error occurred', 'error');
  }
}

async function updateCartCount() {
  try {
    const response = await ajaxRequest('/api/cart/count');
    if (response.success) {
      cartCount = response.count || 0;
      document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = cartCount;
        el.style.display = cartCount > 0 ? 'flex' : 'none';
      });
    }
  } catch (error) {
    console.error('Update cart count error:', error);
  }
}

function updateCartTotal() {
  // Recalculate cart total on cart page
  const cartItems = document.querySelectorAll('.cart-item');
  let total = 0;
  
  cartItems.forEach(item => {
    const price = parseFloat(item.dataset.price || 0);
    const qty = parseInt(item.querySelector('.cart-qty-input')?.value || 0);
    total += price * qty;
  });
  
  const totalEl = document.querySelector('.cart-total');
  if (totalEl) {
    totalEl.textContent = `₹${total.toFixed(2)}`;
  }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

export { updateCartCount };

