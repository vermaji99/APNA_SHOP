/**
 * Wishlist Functionality
 * Add/remove items from wishlist
 */

import { ajaxRequest } from './ajax.js';
import { showToast } from './toast.js';

export function initWishlist() {
  // Wishlist toggle buttons
  document.querySelectorAll('.wishlist-toggle').forEach(btn => {
    btn.addEventListener('click', handleWishlistToggle);
  });
}

async function handleWishlistToggle(e) {
  e.preventDefault();
  const btn = e.currentTarget;
  const productId = btn.dataset.productId;
  const isActive = btn.classList.contains('active');
  
  if (!productId) return;
  
  try {
    const endpoint = isActive ? '/api/wishlist/remove' : '/api/wishlist/add';
    const response = await ajaxRequest(endpoint, {
      method: 'POST',
      body: JSON.stringify({
        product_id: productId,
        csrf_token: getCsrfToken()
      })
    });
    
    if (response.success) {
      btn.classList.toggle('active');
      showToast(
        isActive ? 'Removed from wishlist' : 'Added to wishlist',
        'success'
      );
    } else {
      showToast(response.message || 'Operation failed', 'error');
    }
  } catch (error) {
    console.error('Wishlist error:', error);
    showToast('An error occurred', 'error');
  }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}




