<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'nombre_iglesia' => $_POST['nombre_iglesia'] ?? '',
        'instagram' => $_POST['instagram'] ?? '',
        'facebook' => $_POST['facebook'] ?? '',
        'youtube' => $_POST['youtube'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'email' => $_POST['email'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'horario_culto' => $_POST['horario_culto'] ?? '',
    ];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
        
        foreach ($settings as $key => $value) {
            $stmt->execute([$key, $value]);
        }
        
        logActivity($user['id'], 'configuracion', 'Configuración actualizada');
        $success = 'Configuración guardada correctamente.';
    } catch (Exception $e) {
        $error = 'Error al guardar: ' . $e->getMessage();
    }
}

// Get current settings
$settings = [];
try {
    $stmt = $pdo->query("SELECT clave, valor FROM configuracion");
    while ($row = $stmt->fetch()) {
        $settings[$row['clave']] = $row['valor'];
    }
} catch (Exception $e) {
    $settings = [];
}

$pageTitle = 'Configuración';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | MCA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Configura los datos de la iglesia</p>
            </div>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i>Información General</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Iglesia</label>
                                <input type="text" name="nombre_iglesia" class="form-control" 
                                    value="<?= sanitize($settings['nombre_iglesia'] ?? 'Movilización Cristiana') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" 
                                    value="<?= sanitize($settings['telefono'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                    value="<?= sanitize($settings['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea name="direccion" class="form-control" rows="2"><?= sanitize($settings['direccion'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Horario de Culto</label>
                                <input type="text" name="horario_culto" class="form-control" 
                                    placeholder="Ej: Domingos 10:00 AM"
                                    value="<?= sanitize($settings['horario_culto'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-share me-2"></i>Redes Sociales</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-instagram me-1"></i>Instagram</label>
                                <input type="url" name="instagram" class="form-control" 
                                    placeholder="https://instagram.com/..."
                                    value="<?= sanitize($settings['instagram'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-facebook me-1"></i>Facebook</label>
                                <input type="url" name="facebook" class="form-control" 
                                    placeholder="https://facebook.com/..."
                                    value="<?= sanitize($settings['facebook'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-youtube me-1"></i>YouTube</label>
                                <input type="url" name="youtube" class="form-control" 
                                    placeholder="https://youtube.com/..."
                                    value="<?= sanitize($settings['youtube'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                </button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
