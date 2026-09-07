/**
 * Navbar Functionality
 * Mobile menu toggle, navigation, user dropdown, and logout
 */

import { handleLogout } from './auth.js'; // Make sure handleLogout is exported from auth.js

export function initNavbar() {
  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav');
  const navLinks = document.querySelectorAll('.nav-link');
  
  // Mobile menu toggle
  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      nav.classList.toggle('active');
      menuToggle.setAttribute('aria-expanded', nav.classList.contains('active'));
    });
  }
  
  // Close mobile menu when clicking outside
  document.addEventListener('click', (e) => {
    if (nav && nav.classList.contains('active')) {
      if (!nav.contains(e.target) && !menuToggle.contains(e.target)) {
        nav.classList.remove('active');
        if (menuToggle) {
          menuToggle.setAttribute('aria-expanded', 'false');
        }
      }
    }
  });
  
  // Close mobile menu when clicking a link
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 768) {
        nav.classList.remove('active');
        if (menuToggle) {
          menuToggle.setAttribute('aria-expanded', 'false');
        }
      }
    });
  });
  
  // Active link highlighting based on current page and hash
  const updateActiveNav = () => {
    const currentPath = window.location.pathname;
    const currentHash = window.location.hash;
    
    navLinks.forEach(link => {
      link.classList.remove('active');
      
      try {
        const linkUrl = new URL(link.href);
        const linkPath = linkUrl.pathname;
        const linkHash = linkUrl.hash;
        
        if (linkHash) {
          if ((currentPath === linkPath || currentPath === '/') && currentHash === linkHash) {
            link.classList.add('active');
          }
        } else {
          if (!currentHash) {
            if (currentPath === linkPath || (currentPath === '/' && (linkPath === '/pages/home.php' || linkPath === '/'))) {
              link.classList.add('active');
            } else if (linkPath !== '/' && linkPath !== '/pages/home.php' && currentPath.startsWith(linkPath)) {
              link.classList.add('active');
            }
          }
        }
      } catch (err) {
        // Fallback
      }
    });
  };

  updateActiveNav();
  window.addEventListener('hashchange', updateActiveNav);
  
  // User dropdown menu toggle
  const userMenuToggle = document.getElementById('user-menu-toggle');
  const userDropdownMenu = document.getElementById('user-dropdown-menu');
  
  if (userMenuToggle && userDropdownMenu) {
    userMenuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdownMenu.classList.toggle('active');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (userDropdownMenu && userDropdownMenu.classList.contains('active')) {
        if (!userDropdownMenu.contains(e.target) && !userMenuToggle.contains(e.target)) {
          userDropdownMenu.classList.remove('active');
        }
      }
    });
    
    // Close dropdown when clicking on a link inside
    const dropdownLinks = userDropdownMenu.querySelectorAll('a, button');
    dropdownLinks.forEach(link => {
      link.addEventListener('click', () => {
        setTimeout(() => {
          userDropdownMenu.classList.remove('active');
        }, 100);
      });
    });

    // --- Attach logout button ---
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        handleLogout(); // Call your auth.js logout function
      });
    }
  }
}
