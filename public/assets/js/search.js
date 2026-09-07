/**
 * Search Functionality
 * Product search with debouncing
 */

import { ajaxRequest } from './ajax.js';

let searchTimeout;
const searchResults = document.querySelector('.search-results');

export function initSearch() {
  const searchInput = document.querySelector('.navbar-search-input');
  if (!searchInput) return;
  
  searchInput.addEventListener('input', handleSearch);
  searchInput.addEventListener('focus', showSearchResults);
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.navbar-search')) {
      hideSearchResults();
    }
  });
}

function handleSearch(e) {
  const query = e.target.value.trim();
  
  clearTimeout(searchTimeout);
  
  if (query.length < 2) {
    hideSearchResults();
    return;
  }
  
  searchTimeout = setTimeout(() => {
    performSearch(query);
  }, 300);
}

async function performSearch(query) {
  try {
    const response = await ajaxRequest(`/api/search?q=${encodeURIComponent(query)}`);
    
    if (response.success && response.products) {
      displaySearchResults(response.products);
    } else {
      hideSearchResults();
    }
  } catch (error) {
    console.error('Search error:', error);
    hideSearchResults();
  }
}

function displaySearchResults(products) {
  if (!searchResults) return;
  
  if (products.length === 0) {
    searchResults.innerHTML = '<div class="search-no-results">No products found</div>';
    showSearchResults();
    return;
  }
  
  const html = products.map(product => `
    <a href="/pages/product.php?id=${product.id}" class="search-result-item">
      <img src="${product.image || '/assets/images/placeholder.jpg'}" alt="${product.name}">
      <div class="search-result-info">
        <div class="search-result-name">${product.name}</div>
        <div class="search-result-price">₹${product.price}</div>
      </div>
    </a>
  `).join('');
  
  searchResults.innerHTML = html;
  showSearchResults();
}

function showSearchResults() {
  if (searchResults) {
    searchResults.classList.add('active');
  }
}

function hideSearchResults() {
  if (searchResults) {
    searchResults.classList.remove('active');
  }
}




