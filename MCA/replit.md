# MCA - Movilización Cristiana

> **Note**: This is a pure PHP/MySQL application designed for deployment on Hostinger or similar PHP hosting. The Replit environment contains some legacy Node.js files from the template which are NOT used by the PHP application. See `DEPLOY_HOSTINGER.md` for deployment instructions.

## Overview

Complete PHP/MySQL web application for Movilización Cristiana with:
- Public website with ministry sections (Jóvenes, Mujeres, Varones, Juveniles)
- Evangelismo and Intercesión (prayer requests) sections
- Role-based management system (Admin → Supervisors → Leaders → Members)
- Weekly reporting system for leaders with attendance tracking
- Dynamic forms system

## Tech Stack

- **Backend**: PHP 8.2
- **Database**: PostgreSQL (Replit) / MySQL (Hostinger)
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Styling**: Custom CSS with modern design

## Project Structure

```
/
├── config/
│   ├── database.php    # Database connection (auto-detects PG/MySQL)
│   └── install.php     # Database schema installation
├── includes/
│   ├── functions.php   # Helper functions
│   ├── header.php      # Public site header
│   └── footer.php      # Public site footer
├── admin/              # Admin panel (admin role only)
│   ├── index.php       # Dashboard
│   ├── usuarios.php    # User management
│   ├── supervisores.php # Supervisor assignments
│   ├── lideres.php     # Leaders overview
│   ├── publicaciones.php # Blog posts
│   └── intercesion.php # Prayer requests management
├── supervisor/         # Supervisor panel
│   ├── index.php       # Dashboard
│   ├── lideres.php     # Assigned leaders
│   ├── informes.php    # View reports
│   └── estadisticas.php # Statistics
├── lider/              # Leader panel
│   ├── index.php       # Dashboard
│   ├── miembros.php    # Member management
│   ├── informe.php     # Weekly report form
│   ├── historial.php   # Report history
│   └── estadisticas.php # Statistics
├── public/
│   ├── css/style.css   # Main stylesheet
│   └── js/main.js      # JavaScript
├── uploads/            # User uploads
├── index.php           # Homepage
├── login.php           # Login page
├── nosotros.php        # About page
├── jovenes.php         # Youth ministry
├── mujeres.php         # Women's ministry
├── varones.php         # Men's ministry
├── juveniles.php       # Children's ministry
├── evangelismo.php     # Evangelism section
├── intercesion.php     # Prayer requests
└── formularios.php     # Public forms
```

## User Roles

1. **admin** - Full system access
2. **supervisor** - Manages assigned leaders
3. **lider** - Creates weekly reports, manages members
4. **admin_jovenes/mujeres/varones/juveniles/evangelismo/intercesion** - Section admins
5. **user** - Basic user

## Installation

### Replit (PostgreSQL)

1. The database is automatically configured using `DATABASE_URL`
2. Run `php config/install.php` to create tables
3. Start the PHP server on port 5000

### Hostinger (MySQL)

1. Edit `config/database.php` and update MySQL credentials:
   ```php
   define('DB_HOST', 'your_host');
   define('DB_NAME', 'your_database');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```
2. Upload all files via FTP
3. Access `yourdomain.com/config/install.php` once
4. Delete or rename install.php after setup
5. Login with admin/admin123

## Default Login

- **Username**: admin
- **Password**: admin123

⚠️ Change the password immediately after first login!

## Weekly Report System

Leaders track attendance for 5 activities:
- Grupo Familiar (Family Group)
- Escuela (School)
- Reunión Red (Network Meeting)
- Culto Domingo (Sunday Service)
- Actividad OMT (OMT Activity)

Each has three states: Sí (Yes) / No / No Hubo (Didn't happen)

## Features

### Public Website
- Responsive design with Bootstrap 5
- Hero sections with gradients
- Blog posts per ministry section
- Prayer request submission
- Contact forms
- Dynamic forms system

### Admin Panel
- User management (CRUD)
- Supervisor-Leader assignments
- Publication management
- Prayer request moderation
- Statistics and reports

### Leader Panel
- Member management
- Weekly attendance reports
- Monthly statistics
- Historical report viewing

### Supervisor Panel
- View assigned leaders
- Monitor report submissions
- Compare leader performance
- Monthly statistics

## Development Notes

- The application auto-detects PostgreSQL (Replit) vs MySQL (Hostinger)
- Sessions are handled via PHP native sessions
- Passwords are hashed with `password_hash()`
- All user input is sanitized with `htmlspecialchars()`
