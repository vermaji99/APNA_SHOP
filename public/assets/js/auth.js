/**
 * Authentication Functions
 * Login, signup, logout handling
 */

import { ajaxRequest } from './ajax.js';
import { showToast } from './toast.js';

export function initAuth() {
  const loginForm = document.querySelector('#login-form');
  const signupForm = document.querySelector('#signup-form');
  
  if (loginForm) {
    loginForm.addEventListener('submit', handleLogin);
  }
  
  if (signupForm) {
    signupForm.addEventListener('submit', handleSignup);
  }
}

async function handleLogin(e) {
  e.preventDefault();
  const form = e.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const formData = new FormData(form);
  
  submitBtn.disabled = true;
  submitBtn.textContent = 'Logging in...';
  
  try {
    const response = await ajaxRequest('/api/auth/login', {
      method: 'POST',
      body: JSON.stringify({
        email: formData.get('email'),
        password: formData.get('password'),
        csrf_token: formData.get('csrf_token')
      })
    });
    
    if (response.success) {
      showToast('Login successful', 'success');
      setTimeout(() => {
        window.location.href = response.redirect || '/pages/home.php';
      }, 1000);
    } else {
      showToast(response.message || 'Login failed', 'error');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Login';
    }
  } catch (error) {
    console.error('Login error:', error);
    showToast('An error occurred', 'error');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Login';
  }
}

async function handleSignup(e) {
  e.preventDefault();
  const form = e.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const formData = new FormData(form);
  
  // Validate password match
  if (formData.get('password') !== formData.get('password_confirm')) {
    showToast('Passwords do not match', 'error');
    return;
  }
  
  submitBtn.disabled = true;
  submitBtn.textContent = 'Creating account...';
  
  try {
    const response = await ajaxRequest('/api/auth/signup', {
      method: 'POST',
      body: JSON.stringify({
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        phone: formData.get('phone'),
        csrf_token: formData.get('csrf_token')
      })
    });
    
    if (response.success) {
      showToast('Account created successfully', 'success');
      setTimeout(() => {
        window.location.href = '/pages/login.php';
      }, 1500);
    } else {
      showToast(response.message || 'Signup failed', 'error');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Sign Up';
    }
  } catch (error) {
    console.error('Signup error:', error);
    showToast('An error occurred', 'error');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Sign Up';
  }
}

export async function handleLogout() {
  try {
    const response = await ajaxRequest('/api/auth/logout', {
      method: 'POST',
      body: JSON.stringify({
        csrf_token: getCsrfToken()
      })
    });
    
    if (response.success) {
      showToast('Logged out successfully', 'success');
      setTimeout(() => {
        window.location.href = '/pages/home.php';
      }, 1000);
    }
  } catch (error) {
    console.error('Logout error:', error);
  }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}




