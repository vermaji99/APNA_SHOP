-- APNA-MENS Sample Data
-- Insert sample data for testing

USE `apna_mens`;

-- Insert Admin User
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `status`, `email_verified`) VALUES
('Admin User', 'admin@apnamens.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'admin', 'active', 1);

SET @admin_user_id = LAST_INSERT_ID();

INSERT INTO `admins` (`user_id`, `admin_level`, `permissions`) VALUES
(@admin_user_id, 'super', '{"all": true}');

-- Insert Vendor User
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `status`, `email_verified`) VALUES
('Vendor User', 'vendor@apnamens.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'vendor', 'active', 1);

SET @vendor_user_id = LAST_INSERT_ID();

INSERT INTO `vendors` (`user_id`, `business_name`, `business_email`, `business_phone`, `address`, `city`, `state`, `zip_code`, `country`, `status`) VALUES
(@vendor_user_id, 'Premium Menswear Co.', 'vendor@apnamens.com', '9876543211', '123 Fashion Street', 'Mumbai', 'Maharashtra', '400001', 'India', 'approved');

SET @vendor_id = LAST_INSERT_ID();

-- Insert Customer Users
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `status`, `email_verified`) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 'customer', 'active', 1),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', 'customer', 'active', 1);

-- Password for all test users: password

-- Insert Categories
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`, `status`) VALUES
('T-Shirts', 't-shirts', 'Premium quality t-shirts for men', 1, 'active'),
('Shirts', 'shirts', 'Formal and casual shirts', 2, 'active'),
('Jeans', 'jeans', 'Denim jeans and trousers', 3, 'active'),
('Shoes', 'shoes', 'Footwear collection', 4, 'active'),
('Accessories', 'accessories', 'Watches, belts, and more', 5, 'active');

SET @cat_tshirts = (SELECT id FROM categories WHERE slug = 't-shirts');
SET @cat_shirts = (SELECT id FROM categories WHERE slug = 'shirts');
SET @cat_jeans = (SELECT id FROM categories WHERE slug = 'jeans');
SET @cat_shoes = (SELECT id FROM categories WHERE slug = 'shoes');
SET @cat_accessories = (SELECT id FROM categories WHERE slug = 'accessories');

-- Insert Products
INSERT INTO `products` (`vendor_id`, `category_id`, `name`, `slug`, `description`, `short_description`, `sku`, `price`, `compare_price`, `stock_quantity`, `stock_status`, `status`, `featured`) VALUES
(@vendor_id, @cat_tshirts, 'Premium Cotton T-Shirt', 'premium-cotton-t-shirt', 'High-quality 100% cotton t-shirt with modern fit. Perfect for casual wear.', 'Comfortable cotton t-shirt', 'TSH-001', 899.00, 1299.00, 50, 'in_stock', 'published', 1),
(@vendor_id, @cat_tshirts, 'Graphic Print T-Shirt', 'graphic-print-t-shirt', 'Stylish graphic print t-shirt with unique designs. Made from premium fabric.', 'Trendy graphic t-shirt', 'TSH-002', 799.00, 1099.00, 30, 'in_stock', 'published', 1),
(@vendor_id, @cat_shirts, 'Formal White Shirt', 'formal-white-shirt', 'Classic formal white shirt perfect for office and formal occasions.', 'Professional formal shirt', 'SHT-001', 1499.00, 1999.00, 25, 'in_stock', 'published', 1),
(@vendor_id, @cat_shirts, 'Casual Checkered Shirt', 'casual-checkered-shirt', 'Stylish checkered pattern shirt for casual outings.', 'Casual checkered shirt', 'SHT-002', 1299.00, 1699.00, 20, 'in_stock', 'published', 0),
(@vendor_id, @cat_jeans, 'Slim Fit Denim Jeans', 'slim-fit-denim-jeans', 'Modern slim fit denim jeans with stretch comfort.', 'Comfortable slim fit jeans', 'JNS-001', 1999.00, 2499.00, 40, 'in_stock', 'published', 1),
(@vendor_id, @cat_jeans, 'Regular Fit Jeans', 'regular-fit-jeans', 'Classic regular fit jeans for everyday wear.', 'Classic regular fit', 'JNS-002', 1799.00, 2299.00, 35, 'in_stock', 'published', 0),
(@vendor_id, @cat_shoes, 'Leather Formal Shoes', 'leather-formal-shoes', 'Premium leather formal shoes for business and formal events.', 'Premium leather shoes', 'SHO-001', 3499.00, 4499.00, 15, 'in_stock', 'published', 1),
(@vendor_id, @cat_shoes, 'Casual Sneakers', 'casual-sneakers', 'Comfortable casual sneakers for daily wear and sports.', 'Comfortable sneakers', 'SHO-002', 2299.00, 2999.00, 30, 'in_stock', 'published', 1),
(@vendor_id, @cat_accessories, 'Leather Belt', 'leather-belt', 'Genuine leather belt with modern buckle design.', 'Premium leather belt', 'ACC-001', 899.00, 1299.00, 50, 'in_stock', 'published', 0),
(@vendor_id, @cat_accessories, 'Classic Watch', 'classic-watch', 'Elegant classic watch with leather strap.', 'Elegant timepiece', 'ACC-002', 4999.00, 6999.00, 10, 'in_stock', 'published', 1);

-- Insert Product Images (placeholder paths)
INSERT INTO `product_images` (`product_id`, `image_path`, `alt_text`, `sort_order`, `is_primary`) VALUES
((SELECT id FROM products WHERE sku = 'TSH-001'), '/uploads/products/tsh-001-1.jpg', 'Premium Cotton T-Shirt', 1, 1),
((SELECT id FROM products WHERE sku = 'TSH-001'), '/uploads/products/tsh-001-2.jpg', 'Premium Cotton T-Shirt Back', 2, 0),
((SELECT id FROM products WHERE sku = 'TSH-002'), '/uploads/products/tsh-002-1.jpg', 'Graphic Print T-Shirt', 1, 1),
((SELECT id FROM products WHERE sku = 'SHT-001'), '/uploads/products/sht-001-1.jpg', 'Formal White Shirt', 1, 1),
((SELECT id FROM products WHERE sku = 'SHT-002'), '/uploads/products/sht-002-1.jpg', 'Casual Checkered Shirt', 1, 1),
((SELECT id FROM products WHERE sku = 'JNS-001'), '/uploads/products/jns-001-1.jpg', 'Slim Fit Denim Jeans', 1, 1),
((SELECT id FROM products WHERE sku = 'JNS-002'), '/uploads/products/jns-002-1.jpg', 'Regular Fit Jeans', 1, 1),
((SELECT id FROM products WHERE sku = 'SHO-001'), '/uploads/products/sho-001-1.jpg', 'Leather Formal Shoes', 1, 1),
((SELECT id FROM products WHERE sku = 'SHO-002'), '/uploads/products/sho-002-1.jpg', 'Casual Sneakers', 1, 1),
((SELECT id FROM products WHERE sku = 'ACC-001'), '/uploads/products/acc-001-1.jpg', 'Leather Belt', 1, 1),
((SELECT id FROM products WHERE sku = 'ACC-002'), '/uploads/products/acc-002-1.jpg', 'Classic Watch', 1, 1);

-- Insert Banners
INSERT INTO `banners` (`title`, `subtitle`, `image`, `link`, `link_text`, `position`, `sort_order`, `status`) VALUES
('New Collection 2024', 'Discover the latest trends in menswear', '/uploads/banners/hero-1.jpg', '/pages/shop.php', 'Shop Now', 'hero', 1, 'active'),
('Summer Sale', 'Up to 50% off on selected items', '/uploads/banners/hero-2.jpg', '/pages/shop.php?sale=1', 'Explore Sale', 'hero', 2, 'active');

-- Insert Settings
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `description`) VALUES
('site_name', 'APNA-MENS', 'text', 'general', 'Website name'),
('site_email', 'info@apnamens.com', 'text', 'general', 'Contact email'),
('currency', 'INR', 'text', 'general', 'Default currency'),
('tax_rate', '18', 'number', 'general', 'Tax rate percentage'),
('shipping_cost', '99', 'number', 'general', 'Default shipping cost'),
('free_shipping_threshold', '2000', 'number', 'general', 'Minimum order for free shipping'),
('maintenance_mode', '0', 'boolean', 'general', 'Maintenance mode status');




