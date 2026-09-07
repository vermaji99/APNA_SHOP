# APNA-MENS Setup Guide

## Quick Start

### 1. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE apna_mens CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p apna_mens < database/schema.sql

# Import sample data
mysql -u root -p apna_mens < database/sample-data.sql
```

### 2. Configure Database

Edit `app/config/database.php` with your MySQL credentials.

### 3. Start Development Server

```bash
cd public
php -S localhost:8000
```

### 4. Access the Application

Open browser: `http://localhost:8000/pages/home.php`

## Default Accounts

- **Admin**: admin@apnamens.com / password
- **Vendor**: vendor@apnamens.com / password  
- **Customer**: john@example.com / password

## File Permissions

```bash
chmod -R 755 public/uploads
```

## Production Deployment

1. Set `env` to `production` in `app/config/app.php`
2. Set `debug` to `false`
3. Update database credentials
4. Configure web server (Apache/Nginx)
5. Enable HTTPS
6. Set secure session cookies

## Notes

- All images are placeholders - replace with actual product images
- Favicon is a placeholder - add your own favicon.ico
- Update site settings in database `settings` table




