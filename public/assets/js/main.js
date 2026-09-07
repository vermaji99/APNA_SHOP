/**
 * APNA-MENS - Main JavaScript
 * Entry point and initialization
 */

import { initNavbar } from './navbar.js';
import { initCart } from './cart.js';
import { initWishlist } from './wishlist.js';
import { initSearch } from './search.js';
import { initScrollReveal } from './scroll-reveal.js';
import { initToast } from './toast.js';
import { initAuth } from './auth.js';
import { initProductSlider } from './product-slider.js';

// Initialize all modules when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initCart();
  initWishlist();
  initSearch();
  initScrollReveal();
  initToast();
  initAuth();
  initProductSlider();
  
  console.log('APNA-MENS initialized');
});

// Global error handler
window.addEventListener('error', (e) => {
  console.error('Global error:', e.error);
});

