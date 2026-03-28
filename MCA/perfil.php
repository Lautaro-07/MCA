<?php
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        if (empty($fullName) || empty($email)) {
            $error = 'Nombre y email son obligatorios.';
        } else {
            // Check if email is unique
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user['id']]);
            if ($stmt->fetch()) {
                $error = 'Este email ya está en uso.';
            } else {
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$fullName, $email, $phone, $user['id']]);
                $success = 'Perfil actualizado exitosamente.';
                
                // Refresh user data
                $user = getCurrentUser();
            }
        }
    } elseif ($action === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'Todos los campos de contraseña son obligatorios.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Las contraseñas no coinciden.';
        } elseif (strlen($newPassword) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres.';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $error = 'La contraseña actual es incorrecta.';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            $success = 'Contraseña cambiada exitosamente.';
        }
    }
}

// Determine back URL based on role
$backUrl = BASE_URL . '/';
if ($user['role'] === 'admin') {
    $backUrl = BASE_URL . '/admin/';
} elseif ($user['role'] === 'supervisor') {
    $backUrl = BASE_URL . '/supervisor/';
} elseif ($user['role'] === 'lider') {
    $backUrl = BASE_URL . '/lider/';
} elseif (strpos($user['role'], 'admin_') === 0) {
    $backUrl = BASE_URL . '/admin/secciones.php';
}

// Role display names
$roleNames = [
    'admin' => 'Administrador General',
    'supervisor' => 'Supervisor',
    'lider' => 'Líder',
    'admin_jovenes' => 'Admin Jóvenes',
    'admin_mujeres' => 'Admin Mujeres',
    'admin_varones' => 'Admin Varones',
    'admin_juveniles' => 'Admin Juveniles',
    'admin_niños' => 'Admin Niños',
    'admin_intercesion' => 'Admin Intercesión',
    'sub_administrador' => 'Consolidador',
    'user' => 'Usuario'
];

$pageTitle = 'Mi Perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | MCA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="bi bi-person-circle me-2"></i>Mi Perfil
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= $backUrl ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">Salir</a>
            </div>
        </div>
    </nav>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Profile Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" value="<?= sanitize($user['username']) ?>" disabled>
                                    <small class="text-muted">No se puede cambiar</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <input type="text" class="form-control" value="<?= $roleNames[$user['role']] ?? ucfirst($user['role']) ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo *</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= sanitize($user['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" value="<?= sanitize($user['email']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Change Password Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-lock me-2"></i>Cambiar Contraseña</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Contraseña Actual *</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nueva Contraseña *</label>
                                    <input type="password" name="new_password" class="form-control" minlength="6" required>
                                    <small class="text-muted">Mínimo 6 caracteres</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar Nueva Contraseña *</label>
                                    <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key me-2"></i>Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
