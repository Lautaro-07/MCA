<?php
// Test file for MCA - Movilización Cristiana
// Delete this file after testing

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>MCA - Test de Configuración</h1>";

// Test 1: PHP Version
echo "<h2>1. Versión de PHP</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test 2: Required Extensions
echo "<h2>2. Extensiones Requeridas</h2>";
$extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

// Test 3: Database Connection
echo "<h2>3. Conexión a Base de Datos</h2>";
try {
    require_once __DIR__ . '/config/database.php';
    $pdo = getConnection();
    echo "<p>✅ Conexión exitosa a: " . DB_NAME . " (" . DB_TYPE . ")</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users");
    $result = $stmt->fetch();
    echo "<p>✅ Usuarios en la base de datos: " . $result['cnt'] . "</p>";
    
    // Check tables
    if (DB_TYPE === 'mysql') {
        $stmt = $pdo->query("SHOW TABLES");
    } else {
        $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
    }
    $tables = $stmt->fetchAll();
    echo "<p>✅ Tablas encontradas: " . count($tables) . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<p><strong>Verifica:</strong></p>";
    echo "<ul>";
    echo "<li>Nombre de la base de datos en config/database.php (actual: mca_database)</li>";
    echo "<li>Usuario y contraseña de MySQL</li>";
    echo "<li>Que MySQL esté corriendo en XAMPP</li>";
    echo "</ul>";
}

// Test 4: BASE_URL
echo "<h2>4. URL Base</h2>";
echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'No definido') . "</p>";

// Test 5: Session
echo "<h2>5. Sesiones</h2>";
echo "<p>✅ Sesiones funcionando: " . (session_status() === PHP_SESSION_ACTIVE ? 'Sí' : 'No') . "</p>";

// Test 6: Upload Directory
echo "<h2>6. Directorio de Uploads</h2>";
$uploadDir = __DIR__ . '/uploads/';
$status = is_dir($uploadDir) && is_writable($uploadDir) ? '✅' : '❌';
echo "<p>$status Directorio uploads: " . ($status === '✅' ? 'OK' : 'Crear carpeta /uploads/ con permisos 755') . "</p>";

echo "<hr>";
echo "<h2>🎉 Si todo está ✅, tu aplicación está lista!</h2>";
echo "<p><a href='index.php'>Ir a la página principal</a> | <a href='login.php'>Ir al login</a></p>";
echo "<p><strong>Usuario:</strong> admin | <strong>Contraseña:</strong> admin123</p>";
echo "<p style='color: red;'><strong>⚠️ Elimina este archivo (test.php) después de verificar.</strong></p>";
?>
