<?php
// Database Configuration for MCA - Movilización Cristiana
// Supports both PostgreSQL (Replit) and MySQL (XAMPP/Hostinger)

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detect environment
$isReplit = !empty(getenv('DATABASE_URL'));

if ($isReplit) {
    // Replit PostgreSQL configuration
    $databaseUrl = getenv('DATABASE_URL');
    $parsedUrl = parse_url($databaseUrl);
    
    define('DB_TYPE', 'pgsql');
    define('DB_HOST', $parsedUrl['host']);
    define('DB_PORT', $parsedUrl['port'] ?? 5432);
    define('DB_NAME', ltrim($parsedUrl['path'], '/'));
    define('DB_USER', $parsedUrl['user']);
    define('DB_PASS', $parsedUrl['pass']);
} else {
    // XAMPP / Hostinger MySQL configuration
    // CHANGE THESE VALUES FOR YOUR ENVIRONMENT
    define('DB_TYPE', 'mysql');
    define('DB_HOST', 'localhost');
    define('DB_PORT', 3306);
    define('DB_NAME', 'u843239035_mca');  // Your database name
    define('DB_USER', 'u843239035_mca');          // Your MySQL username
    define('DB_PASS', 'Movilizacion_cristiana2025');              // Your MySQL password
}

define('DB_CHARSET', 'utf8mb4');

// Create connection
function getConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            if (DB_TYPE === 'pgsql') {
                $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
            } else {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("<h1>Error de conexión a la base de datos</h1><p>" . $e->getMessage() . "</p><p>Verifica tu configuración en config/database.php</p>");
        }
    }
    
    return $pdo;
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Time zone
date_default_timezone_set('America/Santiago');

// Base URL - Auto-detect or set manually for production
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Detect subdirectory (for XAMPP: /mca)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $baseDir = '';
    
    // Check if we're in a subdirectory
    if ($scriptDir !== '/' && $scriptDir !== '\\') {
        // Get the root directory of the project
        $parts = explode('/', trim($scriptDir, '/'));
        if (!empty($parts[0]) && $parts[0] !== 'admin' && $parts[0] !== 'lider' && $parts[0] !== 'supervisor') {
            $baseDir = '/' . $parts[0];
        } elseif (count($parts) > 1 && $parts[1] !== 'admin' && $parts[1] !== 'lider' && $parts[1] !== 'supervisor') {
            $baseDir = '/' . $parts[0];
        }
    }
    
    define('BASE_URL', rtrim($protocol . $host . $baseDir, '/'));
}

// Upload directories
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
