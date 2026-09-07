# APNA-MENS - Premium Menswear E-Commerce Platform

A complete, production-ready, futuristic men's eCommerce platform built with PHP 8+, MySQL, vanilla JavaScript, and plain CSS.

## 🚀 Features

- **Dark Futuristic Theme** - Modern, avant-garde design with glassmorphism effects
- **Full MVC Architecture** - Clean, organized, and maintainable codebase
- **User Authentication** - Secure login/signup with rate limiting
- **Shopping Cart** - Persistent cart with session and database support
- **Order Management** - Complete order processing system
- **Wishlist** - Save favorite products
- **Product Search** - Real-time product search
- **Responsive Design** - Mobile-first, fully responsive layouts
- **Security** - CSRF protection, XSS prevention, secure sessions
- **Admin & Vendor Panels** - Scaffolded for future development

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite enabled (or Nginx)
- Composer (optional, for future dependencies)

## 🛠️ Installation

### 1. Clone/Download the Project

```bash
cd /path/to/your/webroot
# Extract or clone the project to APNA-MENS directory
```

### 2. Database Setup

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE apna_mens CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit MySQL
exit

# Import schema
mysql -u root -p apna_mens < database/schema.sql

# Import sample data
mysql -u root -p apna_mens < database/sample-data.sql
```

### 3. Configure Database

Edit `app/config/database.php`:

```php
return [
    'host' => 'localhost',
    'dbname' => 'apna_mens',
    'username' => 'root',
    'password' => 'your_password',
    // ...
];
```

### 4. Configure Application

Edit `app/config/app.php` if needed:

```php
'url' => 'http://localhost', // Change to your domain
'env' => 'development', // Change to 'production' for live site
```

### 5. Set Permissions

```bash
# Make uploads directory writable
chmod -R 755 public/uploads
```

### 6. Web Server Configuration

#### Apache (XAMPP/WAMP)

1. Place project in `htdocs` or `www` directory
2. Ensure mod_rewrite is enabled
3. Access via: `http://localhost/APNA-MENS/public/`

#### Nginx

Add to your Nginx configuration:

```nginx
server {
    listen 80;
    server_name apnamens.local;
    root /path/to/APNA-MENS/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

#### PHP Built-in Server (Development)

```bash
cd public
php -S localhost:8000
```

Access at: `http://localhost:8000`

## 📁 Project Structure

```
APNA-MENS/
├── app/
│   ├── config/          # Configuration files
│   ├── core/            # Core MVC classes
│   ├── controllers/     # Controllers
│   ├── models/          # Database models
│   ├── views/           # View templates
│   └── helpers/         # Helper functions
├── public/
│   ├── assets/
│   │   ├── css/         # Stylesheets
│   │   ├── js/          # JavaScript modules
│   │   └── images/      # Images
│   ├── uploads/         # User uploads
│   └── pages/           # Page routes
├── database/
│   ├── schema.sql       # Database schema
│   └── sample-data.sql  # Sample data
└── README.md
```

## 🔐 Default Login Credentials

After importing sample data:

**Admin:**
- Email: `admin@apnamens.com`
- Password: `password`

**Vendor:**
- Email: `vendor@apnamens.com`
- Password: `password`

**Customer:**
- Email: `john@example.com`
- Password: `password`

## 🎨 Design System

### Colors
- Primary: `#d41132` (Red)
- Background: `#101010` (Dark)
- Accent: `#C69C6D` (Gold)
- Muted: `#bfbfbf` (Gray)

### Typography
- Font: Space Grotesk (Google Fonts)
- Icons: Material Symbols

### CSS Architecture
- `settings.css` - Variables and tokens
- `base.css` - Reset and typography
- `layout.css` - Grid and layout
- `components.css` - UI components
- `utilities.css` - Utility classes
- `animations.css` - Animations
- `responsive.css` - Responsive breakpoints

## 🔒 Security Features

- ✅ CSRF token protection
- ✅ XSS prevention (htmlspecialchars)
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ Secure password hashing (bcrypt)
- ✅ Session security (regenerate ID)
- ✅ Rate limiting (5 failed logins = 5 min lockout)
- ✅ File upload validation
- ✅ Role-based access control

## 📱 API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/signup` - User registration
- `POST /api/auth/logout` - User logout

### Cart
- `GET /api/cart/count` - Get cart count
- `POST /api/cart/add` - Add to cart
- `POST /api/cart/update` - Update cart item
- `POST /api/cart/remove` - Remove from cart

### Wishlist
- `POST /api/wishlist/add` - Add to wishlist
- `POST /api/wishlist/remove` - Remove from wishlist

### Search
- `GET /api/search?q=query` - Search products

### Checkout
- `POST /api/checkout/process` - Process order

## 🚀 Development

### Adding New Features

1. **Controller**: Create in `app/controllers/`
2. **Model**: Create in `app/models/`
3. **View**: Create in `app/views/pages/`
4. **Route**: Add route mapping in `public/index.php`

### Database Migrations

For new tables/changes, update `database/schema.sql` and create migration scripts.

## 🐛 Troubleshooting

### Database Connection Error
- Check `app/config/database.php` credentials
- Ensure MySQL service is running
- Verify database exists

### 404 Errors
- Ensure mod_rewrite is enabled (Apache)
- Check `.htaccess` file exists
- Verify file permissions

### Session Issues
- Check PHP session directory is writable
- Verify session configuration in `app/config/app.php`

## 📝 License

This project is provided as-is for educational and commercial use.

## 🤝 Support

For issues and questions, please create an issue in the repository.

## 🎯 Future Enhancements

- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] Product reviews and ratings
- [ ] Advanced search filters
- [ ] Multi-vendor marketplace features
- [ ] Admin dashboard completion
- [ ] Vendor dashboard completion
- [ ] Analytics integration
- [ ] SEO optimization

---

**Built with ❤️ for modern eCommerce**




