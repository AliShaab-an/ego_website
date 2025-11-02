# Hostinger Deployment Checklist

## Pre-Deployment Preparation

### 1. Database Export

- [ ] Export your database from phpMyAdmin (ego database)
- [ ] Save the SQL file (ego.sql)
- [ ] Make sure all tables are included

### 2. Update Configuration Files

#### a) Database Configuration (`app/config/database.php`)

```php
// Change from localhost to Hostinger's database host
define('DB_HOST', 'localhost'); // May need to change to actual host
define('DB_NAME', 'your_hostinger_db_name');
define('DB_USER', 'your_hostinger_db_user');
define('DB_PASS', 'your_hostinger_db_password');
```

#### b) Update Base Paths (`app/config/path.php`)

- [ ] Update paths to match Hostinger's directory structure
- [ ] Remove `/Ego_website/public` from URLs if deployed to root

#### c) API URLs (search all files for localhost references)

- [ ] Update all API URLs from `/Ego_website/public/api/` to `/api/` or your actual path
- [ ] Check: auth.js, checkout, cart, product pages

### 3. Files to Upload

- [ ] All files in `app/` folder
- [ ] All files in `public/` folder (this will be your public_html root)
- [ ] composer.json and vendor folder
- [ ] .htaccess files

### 4. Files to Exclude (Don't Upload)

- [ ] ego.sql (upload separately to database)
- [ ] check_revenue.php (testing file)
- [ ] fix_orders.php (testing file)
- [ ] logs/ folder content (can keep folder structure)
- [ ] cache/ folder content

## Hostinger Setup Steps

### Step 1: Get Hostinger Details

1. Log into your Hostinger account
2. Go to your hosting panel (hPanel)
3. Note down:
   - Database Host (usually: localhost or mysqlXX.hostinger.com)
   - Your hosting file path
   - PHP version (make sure it's 8.0+)

### Step 2: Create Database

1. In hPanel, go to "Databases" → "MySQL Databases"
2. Create a new database
3. Create a database user
4. Grant all privileges to user
5. Note down: Database Name, Username, Password

### Step 3: Import Database

1. Go to phpMyAdmin in hPanel
2. Select your new database
3. Click "Import" tab
4. Upload your ego.sql file
5. Click "Go" to import

### Step 4: Upload Files

**Option A: File Manager (Easier)**

1. In hPanel, go to "File Manager"
2. Navigate to public_html folder
3. Upload all files from your `public/` folder to public_html
4. Create an `app/` folder in the root (one level up from public_html)
5. Upload all files from your `app/` folder there

**Option B: FTP (Recommended for large sites)**

1. Use FileZilla or similar FTP client
2. Connect using credentials from hPanel → "FTP Accounts"
3. Upload structure:
   ```
   /public_html/          (all files from your public/ folder)
   /app/                  (all files from your app/ folder)
   /vendor/               (composer dependencies)
   ```

### Step 5: Update Configuration

1. In File Manager, edit `app/config/database.php`
2. Update with your Hostinger database credentials:

```php
define('DB_HOST', 'localhost'); // or your specific host
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
```

### Step 6: Update Base URLs

Search and replace in these files:

1. All JavaScript files in `public/assets/js/`
2. Change `/Ego_website/public/` to `/` or your actual path
3. Common files to check:
   - `public/assets/js/modules/auth.js`
   - `public/assets/js/modules/cart.js`
   - `public/assets/js/modules/products.js`
   - All API calls

### Step 7: Set Permissions

1. Set folder permissions to 755:
   - `public/admin/uploads/`
   - `app/logs/`
   - `cache/`
2. Set file permissions to 644 for PHP files

### Step 8: Test Your Website

- [ ] Homepage loads correctly
- [ ] Shop page shows products
- [ ] Product details page works
- [ ] Cart functionality works
- [ ] User registration/login works
- [ ] Checkout process works
- [ ] Admin panel login works
- [ ] Admin can manage products, orders, etc.

## Common Issues & Solutions

### Issue: "Database connection failed"

- **Solution**: Check database credentials in `app/config/database.php`
- Verify database exists in Hostinger
- Verify user has correct privileges

### Issue: "404 Not Found" errors

- **Solution**: Create/update .htaccess file in public_html:

```apache
RewriteEngine On
RewriteBase /

# Redirect to public folder if not already there
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]

# Remove public from URL
RewriteCond %{REQUEST_URI} ^/public/(.*)$
RewriteRule ^public/(.*)$ /$1 [L,R=301]
```

### Issue: Images not loading

- **Solution**: Check image paths in database
- Update image paths to absolute URLs
- Verify upload folder permissions (755)

### Issue: "500 Internal Server Error"

- **Solution**: Check error logs in hPanel
- Verify PHP version compatibility (8.0+)
- Check file permissions
- Look for syntax errors

### Issue: WhatsApp redirect not working

- **Solution**: Update WhatsApp number in:
  - `app/views/frontend/checkoutSection.php` (line ~370)
  - Change to your actual WhatsApp number with country code

## Security Checklist

- [ ] Remove any test/debug files
- [ ] Disable error display (set `display_errors = Off` in php.ini)
- [ ] Change database password to strong password
- [ ] Secure admin panel with strong passwords
- [ ] Add .htaccess to protect sensitive directories
- [ ] Enable HTTPS (SSL certificate - usually free with Hostinger)

## Final Steps

1. [ ] Test all functionality thoroughly
2. [ ] Set up regular database backups in hPanel
3. [ ] Configure email settings if using contact forms
4. [ ] Monitor error logs for first few days
5. [ ] Update DNS if using custom domain

## Support Resources

- Hostinger Documentation: https://support.hostinger.com
- Hostinger Live Chat (available 24/7)
- Check PHP error logs in hPanel → "Error Log"

## Notes

- Keep a backup of your local files
- Save database credentials securely
- Document any custom configurations
- Your local XAMPP URL structure may differ from production
