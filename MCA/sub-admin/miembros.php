<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['consolidador']);

$pdo = getConnection();
$user = getCurrentUser();
$error = '';
$success = '';

// Get all leaders for dropdown
$stmt = $pdo->query("SELECT id, full_name, username FROM users WHERE role = 'lider' AND is_active = 1 ORDER BY full_name");
$lideres = $stmt->fetchAll();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $liderId = (int)($_POST['lider_id'] ?? 0);
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        
        if (empty($nombre)) {
            $error = 'El nombre es obligatorio.';
        } else {
            // leader is optional for sub-admin
            if ($liderId === 0) {
                $liderId = null;
            }
            try {
                $stmt = $pdo->prepare("INSERT INTO miembros (lider_id, nombre, telefono, email, is_new) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$liderId, $nombre, $telefono, $email, $is_new]);
                $success = 'Miembro agregado y asignado exitosamente.';
            } catch (PDOException $e) {
                $error = 'Error al crear miembro: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'update') {
        $miembroId = (int)$_POST['miembro_id'];
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $liderId = (int)($_POST['lider_id'] ?? 0);
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        
        // leader optional
        if ($liderId === 0) {
            $liderId = null;
        }
        try {
            $stmt = $pdo->prepare("UPDATE miembros SET nombre = ?, telefono = ?, email = ?, lider_id = ?, is_new = ? WHERE id = ?");
            $stmt->execute([$nombre, $telefono, $email, $liderId, $is_new, $miembroId]);
            $success = 'Miembro actualizado exitosamente.';
        } catch (PDOException $e) {
            $error = 'Error al actualizar miembro: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        $miembroId = (int)$_POST['miembro_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM miembros WHERE id = ?");
            $stmt->execute([$miembroId]);
            $success = 'Miembro eliminado exitosamente.';
        } catch (PDOException $e) {
            $error = 'Error al eliminar miembro: ' . $e->getMessage();
        }
    }
}

// Get all miembros
$miembros = $pdo->query("SELECT m.*, u.full_name as lider_nombre FROM miembros m LEFT JOIN users u ON m.lider_id = u.id ORDER BY m.nombre")->fetchAll();

$pageTitle = 'Gestionar Miembros';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | MCA Sub-Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
    <link href="../public/css/diseño.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Administra todos los miembros del sistema</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMiembroModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Miembro
            </button>
        </div>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-circle me-2"></i>Error:</strong> <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-check-circle me-2"></i>Éxito:</strong> <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lista de Miembros (<?= count($miembros) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Líder Asignado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($miembros)): ?>
                                <?php foreach ($miembros as $m): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <strong><?= sanitize($m['nombre']) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= sanitize($m['telefono'] ?? '-') ?></td>
                                    <td><?= sanitize($m['email'] ?? '-') ?></td>
                                    <td><?= sanitize($m['lider_nombre'] ?? 'Pendiente') ?></td>
                                    <td>
                                        <?php if ($m['is_active']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                        <?php if ($m['is_new']): ?>
                                            <span class="badge bg-info">Nuevo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMiembroModal"
                                            data-id="<?= $m['id'] ?>"
                                            data-nombre="<?= sanitize($m['nombre']) ?>"
                                            data-telefono="<?= sanitize($m['telefono'] ?? '') ?>"
                                            data-email="<?= sanitize($m['email'] ?? '') ?>"
                                            data-lider="<?= $m['lider_id'] ?>"
                                            data-isnew="<?= $m['is_new'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMiembroModal"
                                            data-id="<?= $m['id'] ?>"
                                            data-nombre="<?= sanitize($m['nombre']) ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                        No hay miembros registrados
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Miembro Modal -->
    <div class="modal fade" id="createMiembroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Miembro</h5>
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
                        <div class="mb-3">
                            <label class="form-label">Líder Asignado</label>
                            <select name="lider_id" class="form-select">
                                <option value="0">Pendiente / Sin líder</option>
                                <?php foreach ($lideres as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= sanitize($l['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_new" class="form-check-input" id="isNewCreate">
                            <label class="form-check-label" for="isNewCreate">Es nuevo en la congregación</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Miembro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal (single) -->
    <div class="modal fade" id="editMiembroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Miembro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="miembro_id">
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
                        <div class="mb-3">
                            <label class="form-label">Líder Asignado</label>
                            <select name="lider_id" class="form-select">
                                <option value="0">Pendiente / Sin líder</option>
                                <?php foreach ($lideres as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= sanitize($l['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_new" class="form-check-input" id="isNewEdit">
                            <label class="form-check-label" for="isNewEdit">Es nuevo en la congregación</label>
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

    <!-- Delete Modal (single) -->
    <div class="modal fade" id="deleteMiembroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Miembro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar a <strong id="deleteMiembroName"></strong>?</p>
                    <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="miembro_id" id="deleteMiembroId">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editModal = document.getElementById('editMiembroModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var nombre = button.getAttribute('data-nombre');
                    var telefono = button.getAttribute('data-telefono');
                    var email = button.getAttribute('data-email');
                    var lider = button.getAttribute('data-lider') || 0;
                    var isnew = button.getAttribute('data-isnew') === '1';

                    editModal.querySelector('input[name="miembro_id"]').value = id;
                    editModal.querySelector('input[name="nombre"]').value = nombre;
                    editModal.querySelector('input[name="telefono"]').value = telefono;
                    editModal.querySelector('input[name="email"]').value = email;
                    editModal.querySelector('select[name="lider_id"]').value = lider;
                    editModal.querySelector('#isNewEdit').checked = isnew;
                });
            }

            var deleteModal = document.getElementById('deleteMiembroModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var nombre = button.getAttribute('data-nombre');
                    deleteModal.querySelector('#deleteMiembroId').value = id;
                    deleteModal.querySelector('#deleteMiembroName').textContent = nombre;
                });
            }
        });
    </script>
</body>
</html>
