<?php
// ARCHIVO TEMPORAL - BORRAR DESPUÉS DE USAR
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "<h1>Reset de Usuario Admin</h1>";

try {
    $pdo = getConnection();
    
    // Nueva contraseña
    $newPassword = 'admin123';
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    echo "<p>Generando nuevo hash para: <strong>$newPassword</strong></p>";
    echo "<p>Hash: $hash</p>";
    
    // Actualizar usuario admin
    $stmt = $pdo->prepare("UPDATE users SET password = ?, is_active = 1 WHERE username = 'admin'");
    $result = $stmt->execute([$hash]);
    
    if ($stmt->rowCount() > 0) {
        echo "<h2 style='color: green;'>✅ Contraseña actualizada correctamente!</h2>";
        echo "<p><strong>Usuario:</strong> admin</p>";
        echo "<p><strong>Contraseña:</strong> admin123</p>";
        echo "<p><a href='login.php'>👉 Ir al Login</a></p>";
    } else {
        // Si no existe el admin, crearlo
        echo "<p>Usuario admin no encontrado. Creando...</p>";
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute(['admin', 'admin@mca.org', $hash, 'Administrador', 'admin']);
        
        echo "<h2 style='color: green;'>✅ Usuario admin creado!</h2>";
        echo "<p><strong>Usuario:</strong> admin</p>";
        echo "<p><strong>Contraseña:</strong> admin123</p>";
        echo "<p><a href='login.php'>👉 Ir al Login</a></p>";
    }
    
    // Verificar
    echo "<hr><h3>Verificación:</h3>";
    $stmt = $pdo->query("SELECT id, username, email, role, is_active FROM users WHERE username = 'admin'");
    $user = $stmt->fetch();
    if ($user) {
        echo "<pre>" . print_r($user, true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ IMPORTANTE: Borra este archivo (reset_admin.php) después de usarlo.</strong></p>";
?>
