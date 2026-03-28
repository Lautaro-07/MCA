# Instalación en XAMPP (localhost)

## Requisitos
- XAMPP con PHP 8.0+ y MySQL/MariaDB
- Navegador web

## Paso 1: Descargar archivos

Copia **SOLO** estas carpetas a `C:\xampp\htdocs\mca\`:

```
mca/
├── config/
│   └── database.php
├── includes/
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── admin/
│   └── (todos los .php)
├── supervisor/
│   └── (todos los .php)
├── lider/
│   └── (todos los .php)
├── public/
│   └── css/style.css
│   └── js/main.js
├── uploads/
│   └── (crear vacía)
├── index.php
├── login.php
├── logout.php
├── nosotros.php
├── jovenes.php
├── mujeres.php
├── varones.php
├── juveniles.php
├── evangelismo.php
├── intercesion.php
├── formularios.php
├── publicacion.php
└── test.php (para verificar instalación)
```

## ⚠️ NO COPIES estos archivos (son de Replit/Node.js):
- `node_modules/`
- `client/`
- `server/`
- `shared/`
- `package.json`
- `vite.config.ts`
- `.replit`

---

## Paso 2: Crear base de datos

1. Abre XAMPP Control Panel
2. Inicia **Apache** y **MySQL**
3. Abre phpMyAdmin: http://localhost/phpmyadmin
4. Clic en **Nueva** (panel izquierdo)
5. Nombre: `mca_database`
6. Cotejamiento: `utf8mb4_unicode_ci`
7. Clic **Crear**

---

## Paso 3: Importar SQL

1. En phpMyAdmin, selecciona `mca_database`
2. Clic en pestaña **Importar**
3. Selecciona: `database/mca_database.sql`
4. Clic **Importar**

Si tienes errores, puedes usar el archivo que ya creaste (el que adjuntaste).

---

## Paso 4: Verificar config/database.php

Abre `C:\xampp\htdocs\mca\config\database.php` y verifica estas líneas:

```php
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'mca_database');  // ← Nombre de tu BD
define('DB_USER', 'root');          // ← Usuario MySQL
define('DB_PASS', '');              // ← Vacío en XAMPP por defecto
```

---

## Paso 5: Crear carpeta uploads

Crea estas carpetas si no existen:
```
C:\xampp\htdocs\mca\uploads\
C:\xampp\htdocs\mca\uploads\publicaciones\
C:\xampp\htdocs\mca\uploads\images\
```

---

## Paso 6: Probar instalación

1. Abre navegador: http://localhost/mca/test.php
2. Verifica que todos los checks estén ✅
3. Si hay errores, revisa los mensajes

---

## Paso 7: Acceder al sitio

| Página | URL |
|--------|-----|
| Inicio | http://localhost/mca/ |
| Login | http://localhost/mca/login.php |
| Test | http://localhost/mca/test.php |

**Credenciales:**
- Usuario: `admin`
- Contraseña: `admin123`

---

## Solución de Problemas

### Página en blanco
1. Abre `config/database.php`
2. Verifica que las primeras líneas tengan:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
3. Recarga la página para ver el error

### Error "Table doesn't exist"
- Importa el archivo SQL en phpMyAdmin

### Error de conexión a BD
- Verifica que MySQL esté corriendo en XAMPP
- Verifica nombre de BD, usuario y contraseña

### CSS no carga
- Verifica que copiaste la carpeta `public/css/`

---

## Después de verificar

1. **Elimina** el archivo `test.php`
2. **Cambia** la contraseña del admin desde el panel

---

## Instagram de MCA

El enlace ya está configurado en la base de datos:
https://www.instagram.com/iglesiamovilizacioncristiana
