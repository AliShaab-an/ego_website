# Quick Deployment Guide for Hostinger

## Step-by-Step Process

### 1. Export Database (5 minutes)

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click on "ego" database
3. Click "Export" tab
4. Click "Go" to download SQL file
5. Save as `ego_backup.sql`

### 2. Update Configuration for Production (10 minutes)

#### Update these files before uploading:

**File: `app/config/database.php`**

```php
// BEFORE (Local):
define('DB_HOST', 'localhost');
define('DB_NAME', 'ego');
define('DB_USER', 'root');
define('DB_PASS', '');

// AFTER (Production - get these from Hostinger):
define('DB_HOST', 'localhost'); // Check Hostinger panel for exact host
define('DB_NAME', 'u123456789_ego'); // Your Hostinger database name
define('DB_USER', 'u123456789_user'); // Your Hostinger database user
define('DB_PASS', 'YourSecurePassword'); // Your Hostinger database password
```

**File: `public/assets/js/config.js`** (I just created this)

```javascript
// Change BASE_URL from:
BASE_URL: '/Ego_website/public',

// To (if deploying to root):
BASE_URL: '',

// Or (if deploying to subfolder):
BASE_URL: '/yourfolder',
```

### 3. Upload Files to Hostinger (15 minutes)

#### Using File Manager:

1. Login to Hostinger hPanel
2. Go to "File Manager"
3. Navigate to `public_html` folder
4. Delete any default files (index.html, etc.)
5. Upload ALL files from your local `public/` folder
6. Go up one directory (to the root, before public_html)
7. Create folder named `app`
8. Upload ALL files from your local `app/` folder
9. Upload `vendor/` folder if you have composer dependencies

#### File Structure on Hostinger should look like:

```
/
├── public_html/           (all files from your public/ folder)
│   ├── index.php
│   ├── shop.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│   ├── api/
│   ├── admin/
│   └── assets/
├── app/                   (all files from your app/ folder)
│   ├── config/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── core/
└── vendor/               (if using composer)
```

### 4. Create Database on Hostinger (5 minutes)

1. In hPanel, go to "MySQL Databases"
2. Click "Create Database"
3. Enter database name (e.g., `ego_shop`)
4. Create database user with strong password
5. Add user to database with ALL PRIVILEGES
6. **Write down**: Database name, username, password

### 5. Import Database (5 minutes)

1. In hPanel, go to "phpMyAdmin"
2. Click on your new database name on the left
3. Click "Import" tab
4. Click "Choose File" and select your `ego_backup.sql`
5. Scroll down and click "Go"
6. Wait for success message

### 6. Set File Permissions (5 minutes)

1. In File Manager, right-click on these folders:
   - `public_html/admin/uploads/` → Set to 755
   - `app/logs/` → Set to 755
   - `cache/` → Set to 755

### 7. Test Your Website

Visit your domain and test:

- [ ] Homepage loads
- [ ] Shop page shows products with images
- [ ] Can add items to cart
- [ ] Can register/login
- [ ] Can place order
- [ ] Admin login works (yourdomain.com/admin)
- [ ] Can manage products in admin

### 8. Enable HTTPS (SSL) - Free with Hostinger

1. In hPanel, go to "SSL"
2. Find your domain
3. Click "Install" for free SSL
4. Wait 10-15 minutes for activation
5. Enable "Force HTTPS" option

## Important Files to Check After Upload

### Check Database Connection:

Create a test file `test.php` in public_html:

```php
<?php
require_once '../app/config/database.php';
require_once '../app/core/DB.php';

try {
    $conn = DB::getConnection();
    echo "Database connected successfully!";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

Visit: `yourdomain.com/test.php`
Delete this file after testing!

## Troubleshooting

### Problem: "Database connection error"

**Solution:**

1. Check `app/config/database.php` has correct credentials
2. Verify database exists in Hostinger
3. Check database user has correct privileges

### Problem: "Page not found" or broken links

**Solution:**

1. Check that `public/assets/js/config.js` BASE_URL is correct
2. Update BASE_URL to empty string `''` if deployed to root
3. Clear browser cache

### Problem: Images not showing

**Solution:**

1. Check upload folder exists: `public_html/admin/uploads/`
2. Verify folder permission is 755
3. Check image paths in database

### Problem: Admin panel not accessible

**Solution:**

1. Clear browser cache
2. Check admin files uploaded correctly to `public_html/admin/`
3. Try accessing: `yourdomain.com/admin/login.php`

## After Successful Deployment

1. ✅ Delete test files (`test.php`, `check_revenue.php`, `fix_orders.php`)
2. ✅ Test all functionality
3. ✅ Change all default passwords
4. ✅ Set up automatic backups in Hostinger
5. ✅ Monitor error logs for first week
6. ✅ Update WhatsApp number in checkout if needed

## Need Help?

- Hostinger Support: Available 24/7 via Live Chat
- Check error logs: hPanel → Error Log
- PHP settings: hPanel → PHP Configuration

## Backup Your Site

**Before making any changes:**

1. hPanel → Backups
2. Create backup
3. Download to local computer

Remember: Keep your local version as backup!
