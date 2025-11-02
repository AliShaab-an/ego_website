# Centralized Path Configuration - Deployment Guide

## How It Works

Your website now uses **centralized path configuration** in TWO places:

1. **PHP Paths**: `app/config/path.php` - Controls all PHP paths
2. **JavaScript Paths**: `public/assets/js/config.php` - Auto-generates JS config from PHP

## For LOCALHOST Testing

**NO CHANGES NEEDED!** Everything works as is.

## For PRODUCTION (Hostinger)

### STEP 1: Update ONE Line in path.php

Open: `app/config/path.php`

Change line 8:

```php
// FROM:
define('IS_LOCAL', true);

// TO:
define('IS_LOCAL', false);
```

**That's it!** All paths automatically update.

### STEP 2: Update JavaScript Files to Use Config

You need to update your JavaScript files to import and use the config.

#### Option A: Update HTML to load config.php (Recommended)

In your HTML header files, add:

```html
<script type="module">
  import Config from "/assets/js/config.php";
  window.Config = Config;
</script>
```

#### Option B: Update each JavaScript module

Example for `auth.js`:

```javascript
// At the top of file
import Config from '../config.php';

// Then use:
url: Config.getApiUrl("register-user.php"),
// Instead of: url: "/Ego_website/public/api/register-user.php",
```

## Available Functions

### In PHP Files:

```php
<?php
require_once 'app/config/path.php';

// Use constants:
echo PUBLIC_URL;        // '/Ego_website/public/' or '/'
echo API_URL;           // '/Ego_website/public/api/' or '/api/'
echo CSS_PATH;          // '/Ego_website/public/assets/css/' or '/assets/css/'

// Use helper functions:
echo url('shop.php');                    // Full URL to shop.php
echo asset('images/logo.png');           // Asset URL
echo admin_asset('js/dashboard.js');     // Admin asset URL
?>

<!-- In HTML -->
<link rel="stylesheet" href="<?= CSS_PATH ?>style.css">
<img src="<?= PUBLIC_URL ?>admin/uploads/product.jpg">
<a href="<?= url('shop.php') ?>">Shop</a>
```

### In JavaScript Files:

```javascript
import Config from "./config.php";

// Use methods:
fetch(Config.getApiUrl("register-user.php"));
fetch(Config.getAdminApiUrl("list-products.php"));

// Image paths:
const imgSrc = Config.getAssetUrl(product.image_path);
```

## Quick Migration Steps

### Files That Need Updates:

#### JavaScript Files (Use Config):

1. ✅ `public/assets/js/modules/auth.js`
2. ✅ `public/assets/js/modules/products.js`
3. ✅ `public/assets/js/modules/productDetail.js`
4. ✅ `public/assets/js/modules/categories.js`
5. ✅ `public/admin/assets/js/modules/*`

Replace:

```javascript
// FROM:
url: "/Ego_website/public/api/register-user.php";

// TO:
url: Config.getApiUrl("register-user.php");
```

#### PHP Files (Use Constants/Functions):

1. ✅ All Session::configure() calls
2. ✅ header("Location: ...") redirects
3. ✅ <img src="..."> in views
4. ✅ <link href="..."> in headers

Replace:

```php
// FROM:
Session::configure(1800,'/Ego_website/public/index.php', true);

// TO:
Session::configure(1800, url('index.php'), true);

// FROM:
<img src="/Ego_website/public/<?= $product['image'] ?>">

// TO:
<img src="<?= PUBLIC_URL . $product['image'] ?>">
```

## Testing

### Test Locally:

1. Set `IS_LOCAL = true` in path.php
2. Visit: http://localhost/Ego_website/public/
3. Everything should work normally

### Test for Production:

1. Set `IS_LOCAL = false` in path.php
2. Visit: http://localhost/ (simulate production)
3. Check all links, images, and API calls work
4. Set back to `true` for local development

## Deployment Checklist

- [ ] Set `IS_LOCAL = false` in `app/config/path.php`
- [ ] Update database credentials in `app/config/database.php`
- [ ] Upload files to Hostinger
- [ ] Import database
- [ ] Test all functionality
- [ ] Clear browser cache

## Benefits

✅ **One-line deployment**: Just change `IS_LOCAL = false`
✅ **Easy testing**: Switch between local/production instantly
✅ **No find/replace**: All paths update automatically
✅ **Consistent**: Same config for PHP and JavaScript
✅ **Maintainable**: Update once, applies everywhere

## Need Help?

If something doesn't work:

1. Check browser console for errors
2. Check `IS_LOCAL` is set correctly
3. Make sure `config.php` is imported in JavaScript
4. Clear browser and server cache
