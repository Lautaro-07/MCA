<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'user';
        
        if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
            $error = 'Todos los campos obligatorios son requeridos.';
        } else {
            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'El usuario o email ya existe.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashedPassword, $fullName, $phone, $role]);
                
                logActivity($user['id'], 'crear_usuario', "Usuario creado: $username ($role)");
                $success = 'Usuario creado exitosamente.';
            }
        }
    } elseif ($action === 'update') {
        $userId = (int)$_POST['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, role = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$fullName, $phone, $role, $isActive, $userId]);
        
        // Update password if provided
        if (!empty($_POST['new_password'])) {
            $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);
        }
        
        logActivity($user['id'], 'actualizar_usuario', "Usuario actualizado: ID $userId");
        $success = 'Usuario actualizado exitosamente.';
    } elseif ($action === 'delete') {
        $userId = (int)$_POST['user_id'];
        if ($userId !== $user['id']) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            logActivity($user['id'], 'eliminar_usuario', "Usuario eliminado: ID $userId");
            $success = 'Usuario eliminado exitosamente.';
        } else {
            $error = 'No puedes eliminar tu propio usuario.';
        }
    }
}

// Get all users
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Gestionar Usuarios';
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
                <p class="text-muted mb-0">Administra todos los usuarios del sistema</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
            </button>
        </div>
        
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
        
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="fw-medium"><?= sanitize($u['username']) ?></td>
                        <td><?= sanitize($u['full_name']) ?></td>
                        <td><?= sanitize($u['email']) ?></td>
                        <td><span class="badge bg-primary"><?= getRoleName($u['role']) ?></span></td>
                        <td>
                            <span class="status-badge <?= $u['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $u['is_active'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                data-bs-target="#editUserModal<?= $u['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ($u['id'] !== $user['id']): ?>
                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUserModal<?= $u['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nombre Completo *</label>
                                            <input type="text" name="full_name" class="form-control" value="<?= sanitize($u['full_name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" name="phone" class="form-control" value="<?= sanitize($u['phone']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rol *</label>
                                            <select name="role" class="form-select" required>
                                                <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>Usuario</option>
                                                <option value="lider" <?= $u['role'] === 'lider' ? 'selected' : '' ?>>Líder</option>
                                                <option value="supervisor" <?= $u['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                                <option value="admin_jovenes" <?= $u['role'] === 'admin_jovenes' ? 'selected' : '' ?>>Admin Jóvenes</option>
                                                <option value="admin_mujeres" <?= $u['role'] === 'admin_mujeres' ? 'selected' : '' ?>>Admin Mujeres</option>
                                                <option value="admin_varones" <?= $u['role'] === 'admin_varones' ? 'selected' : '' ?>>Admin Varones</option>
                                                <option value="admin_juveniles" <?= $u['role'] === 'admin_juveniles' ? 'selected' : '' ?>>Admin Juveniles</option>
                                                <option value="admin_niños" <?= $u['role'] === 'admin_niños' ? 'selected' : '' ?>>Admin Niños</option>
                                                <option value="admin_intercesion" <?= $u['role'] === 'admin_intercesion' ? 'selected' : '' ?>>Admin Intercesíon</option>
                                                <option value="consolidador" <?= $u['role'] === 'consolidador' ? 'selected' : '' ?>>Consolidador</option>
                                                <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Administrador General</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive<?= $u['id'] ?>" <?= $u['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="isActive<?= $u['id'] ?>">Usuario Activo</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label class="form-label">Usuario *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol *</label>
                            <select name="role" class="form-select" required>
                                <option value="user">Usuario</option>
                                <option value="lider">Líder</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin_jovenes">Admin Jóvenes</option>
                                <option value="admin_mujeres">Admin Mujeres</option>
                                <option value="admin_varones">Admin Varones</option>
                                <option value="admin_juveniles">Admin Juveniles</option>
                                <option value="admin_niños">Admin Niños</option>
                                <option value="admin_intercesion">Admin Intercesíon</option>
                                <option value="consolidador">Consolidador</option>
                                <option value="admin">Administrador General</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
