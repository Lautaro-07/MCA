<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['lider']);

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $esConsolidacion = isset($_POST['es_consolidacion']) ? 1 : 0;
        $estaBautizado = isset($_POST['esta_bautizado']) ? 1 : 0;
        
        if (empty($nombre)) {
            $error = 'El nombre es obligatorio.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO miembros (lider_id, nombre, telefono, email, es_consolidacion, esta_bautizado) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user['id'], $nombre, $telefono, $email, $esConsolidacion, $estaBautizado]);
            $success = 'Miembro agregado exitosamente.';
        }
    } elseif ($action === 'update') {
        $miembroId = (int)$_POST['miembro_id'];
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $esConsolidacion = isset($_POST['es_consolidacion']) ? 1 : 0;
        $estaBautizado = isset($_POST['esta_bautizado']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE miembros SET nombre = ?, telefono = ?, email = ?, es_consolidacion = ?, esta_bautizado = ? WHERE id = ? AND lider_id = ?");
        $stmt->execute([$nombre, $telefono, $email, $esConsolidacion, $estaBautizado, $miembroId, $user['id']]);
        $success = 'Miembro actualizado.';
    } elseif ($action === 'delete') {
        $miembroId = (int)$_POST['miembro_id'];
        $stmt = $pdo->prepare("UPDATE miembros SET is_active = 0 WHERE id = ? AND lider_id = ?");
        $stmt->execute([$miembroId, $user['id']]);
        $success = 'Miembro eliminado.';
    }
}

$miembros = getLeaderMembers($user['id']);

$pageTitle = 'Mis Miembros';
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
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Gestiona los miembros de tu grupo familiar</p>
            </div>
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
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($miembros as $m): ?>
                    <tr>
                        <td class="fw-medium">
                            <?= sanitize($m['nombre']) ?>
                            <?php if (!empty($m['is_new'])): ?>
                                <span class="badge bg-success ms-1">Nuevo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= sanitize($m['telefono']) ?: '-' ?></td>
                        <td><?= sanitize($m['email']) ?: '-' ?></td>
                        <td>
                            <?php if ($m['esta_bautizado'] ?? false): ?>
                            <span class="badge bg-success">Bautizado</span>
                            <?php elseif ($m['es_consolidacion'] ?? false): ?>
                            <span class="badge bg-warning">Consolidación</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Miembro</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este miembro?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="miembro_id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $m['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Miembro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="miembro_id" value="<?= $m['id'] ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nombre *</label>
                                            <input type="text" name="nombre" class="form-control" value="<?= sanitize($m['nombre']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" value="<?= sanitize($m['telefono']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= sanitize($m['email']) ?>">
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="es_consolidacion" class="form-check-input" id="editConsolidacion<?= $m['id'] ?>" <?= ($m['es_consolidacion'] ?? false) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="editConsolidacion<?= $m['id'] ?>">En Consolidación</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="esta_bautizado" class="form-check-input" id="editBautizado<?= $m['id'] ?>" <?= ($m['esta_bautizado'] ?? false) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="editBautizado<?= $m['id'] ?>">Bautizado</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($miembros)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay miembros registrados</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Miembro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="es_consolidacion" class="form-check-input" id="newConsolidacion">
                            <label class="form-check-label" for="newConsolidacion">En Consolidación</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="esta_bautizado" class="form-check-input" id="newBautizado">
                            <label class="form-check-label" for="newBautizado">Bautizado</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
