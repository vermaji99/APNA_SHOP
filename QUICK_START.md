# 🚀 Quick Start Guide - APNA-MENS

## Step-by-Step Setup for XAMPP (Windows)

### Prerequisites
- ✅ XAMPP installed and running
- ✅ PHP 8.0+ (check: `php -v`)
- ✅ MySQL/MariaDB running in XAMPP Control Panel

---

## Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start **Apache** (click "Start")
3. Start **MySQL** (click "Start")
4. Both should show green "Running" status

---

## Step 2: Setup Database

### Option A: Using phpMyAdmin (Recommended)

1. Open browser: `http://localhost/phpmyadmin`
2. Click **"New"** in left sidebar
3. Database name: `apna_mens`
4. Collation: `utf8mb4_unicode_ci`
5. Click **"Create"**

6. Select `apna_mens` database
7. Click **"Import"** tab
8. Click **"Choose File"**
9. Select: `C:\xampp\htdocs\APNA-MENS\database\schema.sql`
10. Click **"Go"** (wait for success message)

11. Click **"Import"** tab again
12. Select: `C:\xampp\htdocs\APNA-MENS\database\sample-data.sql`
13. Click **"Go"**

### Option B: Using Command Line

Open **Command Prompt** or **PowerShell**:

```bash
cd C:\xampp\mysql\bin
mysql.exe -u root -e "CREATE DATABASE apna_mens CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql.exe -u root apna_mens < C:\xampp\htdocs\APNA-MENS\database\schema.sql
mysql.exe -u root apna_mens < C:\xampp\htdocs\APNA-MENS\database\sample-data.sql
```

---

## Step 3: Configure Database Connection

1. Open: `C:\xampp\htdocs\APNA-MENS\app\config\database.php`
2. Verify settings (default XAMPP settings should work):
   ```php
   'host' => 'localhost',
   'dbname' => 'apna_mens',
   'username' => 'root',
   'password' => '',  // Empty for default XAMPP
   ```
3. If you changed MySQL root password, update it here

---

## Step 4: Set File Permissions (Optional)

The `public/uploads` folder needs write permissions:

```powershell
# In PowerShell (Run as Administrator)
icacls "C:\xampp\htdocs\APNA-MENS\public\uploads" /grant Users:F
```

Or manually:
- Right-click `public/uploads` folder
- Properties → Security → Edit
- Give "Users" full control

---

## Step 5: Access the Application

### Option A: Using Apache (Recommended)

1. Open browser
2. Go to: `http://localhost/APNA-MENS/public/pages/home.php`

**OR** if you set up virtual host:
- `http://apnamens.local/pages/home.php`

### Option B: Using PHP Built-in Server

1. Open **Command Prompt** or **PowerShell**
2. Navigate to project:
   ```bash
   cd C:\xampp\htdocs\APNA-MENS\public
   ```
3. Start server:
   ```bash
   php -S localhost:8000
   ```
4. Open browser: `http://localhost:8000/pages/home.php`

---

## Step 6: Test Login

### Default Accounts:

**Admin:**
- Email: `admin@apnamens.com`
- Password: `password`

**Vendor:**
- Email: `vendor@apnamens.com`
- Password: `password`

**Customer:**
- Email: `john@example.com`
- Password: `password`

---

## 🐛 Troubleshooting

### Database Connection Error

**Error:** "Database connection failed"

**Solutions:**
1. Check MySQL is running in XAMPP Control Panel
2. Verify database `apna_mens` exists in phpMyAdmin
3. Check `app/config/database.php` credentials
4. Try: `http://localhost/phpmyadmin` - can you login?

### 404 Not Found

**Error:** Page not found

**Solutions:**
1. Make sure you're accessing: `http://localhost/APNA-MENS/public/pages/home.php`
2. Check `.htaccess` file exists in `public` folder
3. Enable mod_rewrite in Apache:
   - Open `C:\xampp\apache\conf\httpd.conf`
   - Find: `#LoadModule rewrite_module modules/mod_rewrite.so`
   - Remove the `#` to uncomment
   - Restart Apache

### CSS/JS Not Loading

**Error:** Styles or scripts not working

**Solutions:**
1. Check browser console (F12) for errors
2. Verify file paths in browser Network tab
3. Make sure all CSS/JS files exist in `public/assets/`
4. Clear browser cache (Ctrl+F5)

### Session Errors

**Error:** Session-related errors

**Solutions:**
1. Check `C:\xampp\tmp` folder exists and is writable
2. Check PHP `session.save_path` in `php.ini`
3. Restart Apache

---

## ✅ Verification Checklist

- [ ] Apache is running (green in XAMPP)
- [ ] MySQL is running (green in XAMPP)
- [ ] Database `apna_mens` created
- [ ] Tables imported (check phpMyAdmin - should see 13 tables)
- [ ] Sample data imported (check `users` table has 4 rows)
- [ ] Can access: `http://localhost/APNA-MENS/public/pages/home.php`
- [ ] Homepage loads with dark theme
- [ ] Can login with test account

---

## 📝 Next Steps

1. **Add Product Images**: Place images in `public/uploads/products/`
2. **Configure Email**: Set up email in `app/config/app.php` for notifications
3. **Customize**: Update colors, logo, content
4. **Production**: Change `env` to `production` in `app/config/app.php`

---

## 🎯 Quick Commands Reference

```bash
# Start PHP server
cd C:\xampp\htdocs\APNA-MENS\public
php -S localhost:8000

# Check PHP version
php -v

# Check MySQL connection
mysql.exe -u root -e "SHOW DATABASES;"
```

---

**Need Help?** Check `README.md` for detailed documentation.




