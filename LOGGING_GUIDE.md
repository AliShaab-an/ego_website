# Logging System Guide

## 📋 Overview

Your website has a comprehensive logging system with two log directories:

- `/logs/` - Root level logs (general application logs)
- `/app/logs/` - Application-specific logs (controllers, models, etc.)

## 📁 Log Files Explained

### Root Logs (`/logs/`)

- **`error.log`** - System-wide errors and exceptions
- **`app.log`** - General application information and events
- **`debug.log`** - Debug information for troubleshooting

### App Logs (`/app/logs/`)

- **`controller.log`** - Controller-specific logs
- **`model.log`** - Database and model errors
- **`product_post.log`** - Product creation/update logs
- **`upload_debug.log`** - File upload debugging
- **`files_debug.log`** - File handling operations
- **`access.log`** - Access logs (if enabled)

## 🛠️ How to Use Logs

### 1. Viewing Logs in Admin Panel

Access: `https://yourdomain.com/admin/logs.php`

Features:

- View all log files in one place
- See last 100 lines of any log
- Download complete log files
- Clear old logs
- Real-time refresh

### 2. Viewing Logs via FTP/File Manager

On Hostinger:

1. Login to your hosting control panel
2. Open File Manager
3. Navigate to `/logs/` or `/app/logs/`
4. Download or view any `.log` file

### 3. Common Error Patterns

#### Database Errors

**Look in:** `app/logs/model.log` or `logs/error.log`
**Example:**

```
[2025-01-15 10:30:45] [Product] ERROR: SQLSTATE[42S22]: Column not found
```

**Solution:** Check database structure matches code

#### Upload Errors

**Look in:** `app/logs/upload_debug.log`
**Example:**

```
Upload failed: File size exceeds limit
```

**Solution:** Increase upload limits in php.ini

#### Authentication Errors

**Look in:** `logs/error.log`
**Example:**

```
[2025-01-15 10:30:45] [Auth] ERROR: Session expired
```

**Solution:** Check session configuration

#### Product/Order Issues

**Look in:** `app/logs/controller.log` or `app/logs/product_post.log`

## 📝 Adding Custom Logs

### Using the Logger Class

```php
require_once CORE . 'Logger.php';

// Log an error
Logger::error('OrderController', 'Failed to create order: ' . $e->getMessage());

// Log information
Logger::info('ProductController', 'Product created successfully: ID ' . $productId);
```

### Using error_log()

```php
// Simple error logging
error_log("Custom message: " . $variable);

// With more context
error_log("Function: myFunction - Error: " . $errorMessage);
```

### Using file_put_contents()

```php
// Append to specific log
file_put_contents(
    __DIR__ . '/../../logs/custom.log',
    "[" . date('Y-m-d H:i:s') . "] Custom log entry\n",
    FILE_APPEND
);
```

## 🚨 Production Monitoring

### What to Monitor

1. **Error Log** - Check daily for critical errors
2. **Model Log** - Monitor database issues
3. **Upload Debug** - Watch for upload failures
4. **Controller Log** - Track business logic errors

### Setting Up Log Rotation (Recommended for Production)

Create a cron job to rotate logs weekly:

```bash
# Rotate logs every Sunday at 3 AM
0 3 * * 0 cd /home/yourusername/public_html/logs && for f in *.log; do mv "$f" "$f.$(date +\%Y\%m\%d)"; done
```

### Setting Up Email Alerts

You can configure Hostinger to email you when critical errors occur:

1. **Install monitoring tool** (like Cronitor or UptimeRobot)
2. **Set up log monitoring** to scan for ERROR keywords
3. **Configure email notifications**

## 🔒 Security Best Practices

### 1. Protect Log Files

Add to `.htaccess` in `/logs/` directory:

```apache
<Files "*.log">
    Order Allow,Deny
    Deny from all
</Files>
```

### 2. Regular Cleanup

- Clear logs monthly to save space
- Archive important logs before clearing
- Use the admin panel's "Clear" feature

### 3. Sensitive Data

Never log:

- Passwords
- Credit card numbers
- Complete session tokens
- API keys

## 📊 Log Analysis Tips

### Finding Specific Errors

```bash
# Via SSH (if available)
grep "ERROR" logs/error.log | tail -20
grep "Exception" app/logs/*.log
```

### Checking Log Size

```bash
du -sh logs/*.log
```

### Most Common Errors

```bash
grep "ERROR" logs/error.log | cut -d ']' -f 3 | sort | uniq -c | sort -rn | head -10
```

## 🔄 Debugging Workflow

When something goes wrong:

1. **Check Recent Logs**

   - Go to Admin Panel → Logs
   - Look at error.log first
   - Check timestamp matches issue time

2. **Identify Error Type**

   - Database error? → Check model.log
   - Upload issue? → Check upload_debug.log
   - Business logic? → Check controller.log

3. **Read Error Message**

   - Note the exact error message
   - Note the file and line number
   - Note the timestamp

4. **Fix and Verify**
   - Make the fix
   - Test the feature
   - Check logs again to confirm fix

## 📱 Quick Reference

| Issue Type       | Log File         | Priority  |
| ---------------- | ---------------- | --------- |
| Site crash       | error.log        | 🔴 High   |
| Database error   | model.log        | 🔴 High   |
| Order issues     | controller.log   | 🟡 Medium |
| Upload failures  | upload_debug.log | 🟡 Medium |
| Product problems | product_post.log | 🟢 Low    |

## 🎯 Before Hosting Checklist

- [x] Logs directories exist with proper permissions (755)
- [x] .htaccess protects log files from public access
- [x] Admin panel log viewer is accessible
- [x] Test log writing by triggering an intentional error
- [x] Set up log rotation schedule
- [x] Configure log file size limits (max 10MB recommended)

## 💡 Tips

1. **Check logs immediately after deployment** to catch configuration issues
2. **Monitor error.log daily** in production
3. **Download logs before clearing** them for historical analysis
4. **Set up automated backups** of critical logs
5. **Use log viewer in admin panel** instead of FTP for quick checks

## 🆘 Emergency Response

If site is down:

1. Check `logs/error.log` for PHP fatal errors
2. Check `app/logs/model.log` for database connection issues
3. Enable PHP display_errors temporarily (if safe)
4. Contact hosting support with log excerpts
5. Restore from backup if needed

## 📞 Support

If you need help interpreting logs:

1. Download the relevant log file
2. Note the timestamp of the issue
3. Contact support with log excerpt and context
