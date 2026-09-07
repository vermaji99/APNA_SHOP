<?php
/**
 * User Controller
 */

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Wishlist.php';
require_once APP_PATH . '/helpers/sanitize.php';
require_once APP_PATH . '/helpers/validator.php';

class UserController extends Controller {
    
    public function profile() {
        require_auth();
        
        $user = Auth::user();
        
        $this->view('user-profile', [
            'title' => 'My Profile - APNA-MENS',
            'user' => $user
        ]);
    }
    
    public function updateProfile() {
        require_auth();
        $this->verifyCsrf();
        
        $name = sanitize_string($this->post('name'));
        $phone = sanitize_string($this->post('phone'));
        
        if (!validate_required($name) || !validate_length($name, 2, 100)) {
            $this->json(['success' => false, 'message' => 'Name must be between 2 and 100 characters'], 400);
            return;
        }
        
        $userModel = new User();
        $userModel->update(Auth::id(), [
            'name' => $name,
            'phone' => $phone
        ]);
        
        $this->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
    
    public function changePassword() {
        require_auth();
        $this->verifyCsrf();
        
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        
        if (!validate_password($newPassword)) {
            $this->json(['success' => false, 'message' => 'Password must be at least 8 characters'], 400);
            return;
        }
        
        $userModel = new User();
        $user = Auth::user();
        
        if (!password_verify($currentPassword, $user['password'])) {
            $this->json(['success' => false, 'message' => 'Current password is incorrect'], 400);
            return;
        }
        
        $userModel->update(Auth::id(), [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
        
        $this->json(['success' => true, 'message' => 'Password changed successfully']);
    }
    
    public function createAddress() {
        require_auth();
        $this->verifyCsrf();
        
        require_once APP_PATH . '/helpers/sanitize.php';
        require_once APP_PATH . '/models/Address.php';
        
        $addressModel = new Address();
        $userId = Auth::id();
        
        // If this is first address, make it default
        $existingAddresses = $addressModel->findAll(['user_id' => $userId]);
        $isDefault = empty($existingAddresses) ? 1 : 0;
        
        $addressId = $addressModel->create([
            'user_id' => $userId,
            'first_name' => sanitize_string($this->post('first_name')),
            'last_name' => sanitize_string($this->post('last_name')),
            'phone' => sanitize_string($this->post('phone')),
            'address_line1' => sanitize_string($this->post('address_line1')),
            'address_line2' => sanitize_string($this->post('address_line2')),
            'city' => sanitize_string($this->post('city')),
            'state' => sanitize_string($this->post('state')),
            'zip_code' => sanitize_string($this->post('zip_code')),
            'country' => sanitize_string($this->post('country', 'India')),
            'type' => 'both',
            'is_default' => $isDefault
        ]);
        
        $this->json(['success' => true, 'address_id' => $addressId, 'message' => 'Address saved']);
    }
    
    public function wishlist() {
        require_auth();
        
        $wishlistModel = new Wishlist();
        $items = $wishlistModel->getByUser(Auth::id());
        
        $this->view('wishlist', [
            'title' => 'My Wishlist - APNA-MENS',
            'items' => $items
        ]);
    }
}

