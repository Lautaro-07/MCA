# Deployment Guide - MCA to Hostinger

## Files to Upload to Hostinger

Upload ONLY these folders and files to your Hostinger hosting:

```
/config/
  - database.php
  - install.php

/includes/
  - functions.php
  - header.php
  - footer.php

/admin/
  - All PHP files

/supervisor/
  - All PHP files

/lider/
  - All PHP files

/public/
  - css/style.css
  - js/main.js

/uploads/
  - (empty folder, will store uploads)

Root files:
  - index.php
  - login.php
  - logout.php
  - nosotros.php
  - jovenes.php
  - mujeres.php
  - varones.php
  - juveniles.php
  - evangelismo.php
  - intercesion.php
  - formularios.php
  - publicacion.php
```

**DO NOT upload:**
- node_modules/
- client/
- server/
- shared/
- package.json
- Any TypeScript or JavaScript config files
- .replit or replit.md

## Step by Step

### 1. Create MySQL Database in Hostinger

1. Login to Hostinger hPanel
2. Go to Databases → MySQL Databases
3. Create a new database (e.g., `mca_database`)
4. Create a database user and password
5. Add the user to the database with all privileges
6. Note down:
   - Database name
   - Username
   - Password
   - Host (usually localhost)

### 2. Configure Database Connection

Edit `config/database.php` before uploading:

```php
// Hostinger MySQL configuration
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');      // Usually localhost
define('DB_PORT', 3306);
define('DB_NAME', 'your_database');  // Your database name
define('DB_USER', 'your_username');  // Your database username
define('DB_PASS', 'your_password');  // Your database password
```

### 3. Upload Files

1. Use Hostinger File Manager or FTP (FileZilla)
2. Upload all files to `public_html` folder
3. Make sure folder structure is maintained

### 4. Set Permissions

```
/uploads/ - 755
/uploads/publicaciones/ - 755
/uploads/images/ - 755
```

### 5. Install Database

1. Open browser: `https://yourdomain.com/config/install.php`
2. Wait for "Base de datos instalada correctamente!" message
3. **IMPORTANT**: Delete or rename `install.php` after installation

### 6. First Login

- URL: `https://yourdomain.com/login.php`
- Username: `admin`
- Password: `admin123`

**Change password immediately after login!**

### 7. Update BASE_URL (if needed)

If your site doesn't work correctly, edit `config/database.php`:

```php
define('BASE_URL', 'https://yourdomain.com');
```

## Troubleshooting

### "Connection failed" error
- Verify database credentials in `config/database.php`
- Check if MySQL user has proper permissions

### Images not uploading
- Check `/uploads/` folder permissions (755)
- Verify PHP upload settings in `.htaccess` or php.ini

### Blank pages
- Enable PHP error display temporarily:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```

## Security Checklist

- [ ] Change admin password
- [ ] Delete `config/install.php`
- [ ] Verify `uploads/` is not publicly listable
- [ ] Enable HTTPS
- [ ] Update database credentials from defaults
