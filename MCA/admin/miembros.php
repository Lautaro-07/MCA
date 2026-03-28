<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

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
            // leader is optional for admin
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
            $success = 'Miembro actualizado.';
        } catch (PDOException $e) {
            $error = 'Error al actualizar: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        $miembroId = (int)$_POST['miembro_id'];
        $stmt = $pdo->prepare("UPDATE miembros SET is_active = 0 WHERE id = ?");
        $stmt->execute([$miembroId]);
        $success = 'Miembro eliminado.';
    } elseif ($action === 'reassign') {
        $miembroId = (int)$_POST['miembro_id'];
        $nuevoLiderId = (int)$_POST['nuevo_lider_id'];
        
        if ($nuevoLiderId === 0) {
            $nuevoLiderId = null;
        }
        $stmt = $pdo->prepare("UPDATE miembros SET lider_id = ? WHERE id = ?");
        $stmt->execute([$nuevoLiderId, $miembroId]);
        $success = 'Miembro reasignado exitosamente.';
    }
}

// Filter by leader
$filterLider = (int)($_GET['lider'] ?? 0);

// Get members with leader info
$sql = "SELECT m.*, u.full_name as lider_nombre, u.username as lider_username 
        FROM miembros m 
        LEFT JOIN users u ON m.lider_id = u.id 
        WHERE m.is_active = 1";
if ($filterLider > 0) {
    $sql .= " AND m.lider_id = " . $filterLider;
}
$sql .= " ORDER BY u.full_name, m.nombre";
$miembros = $pdo->query($sql)->fetchAll();

$pageTitle = 'Gestión de Miembros';
$currentPage = 'miembros';
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
    <link href="<?= BASE_URL ?>/public/css/diseño.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Asigna y administra miembros de los grupos familiares</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-2"></i>Agregar Miembro
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
        
        <!-- Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Filtrar por Líder</label>
                        <select name="lider" class="form-select" onchange="this.form.submit()">
                            <option value="0">-- Todos los líderes --</option>
                            <?php foreach ($lideres as $l): ?>
                            <option value="<?= $l['id'] ?>" <?= $filterLider == $l['id'] ? 'selected' : '' ?>>
                                <?= sanitize($l['full_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= BASE_URL ?>/admin/miembros.php" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($miembros) ?></h3>
                        <span>Total Miembros</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($lideres) ?></h3>
                        <span>Líderes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Members Table -->
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Líder Asignado</th>
                        <th>Teléfono</th>
                        <th>Email</th>
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
                        <td>
                            <span class="badge bg-primary"><?= sanitize($m['lider_nombre'] ?? 'Sin asignar') ?></span>
                        </td>
                        <td><?= sanitize($m['telefono']) ?: '-' ?></td>
                        <td><?= sanitize($m['email'] ?? '') ?: '-' ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $m['id'] ?>" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reassignModal<?= $m['id'] ?>" title="Reasignar">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este miembro?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="miembro_id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
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
                                        <div class="mb-3 form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="isNewCheck<?= $m['id'] ?>" name="is_new" <?= !empty($m['is_new']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="isNewCheck<?= $m['id'] ?>">Marcar como nuevo miembro</label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Líder Asignado</label>
                                            <select name="lider_id" class="form-select">
                                                <option value="0">Pendiente / Sin líder</option>
                                                <?php foreach ($lideres as $l): ?>
                                                <option value="<?= $l['id'] ?>" <?= $m['lider_id'] == $l['id'] ? 'selected' : '' ?>>
                                                    <?= sanitize($l['full_name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" value="<?= sanitize($m['telefono'] ?? '') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= sanitize($m['email'] ?? '') ?>">
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
                    
                    <!-- Reassign Modal -->
                    <div class="modal fade" id="reassignModal<?= $m['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reasignar Miembro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="reassign">
                                        <input type="hidden" name="miembro_id" value="<?= $m['id'] ?>">
                                        
                                        <p class="mb-3">Reasignar a <strong><?= sanitize($m['nombre']) ?></strong> al líder:</p>
                                        
                                        <div class="mb-3">
                                            <select name="nuevo_lider_id" class="form-select">
                                                <option value="0">Pendiente / Sin líder</option>
                                                <?php foreach ($lideres as $l): ?>
                                                <option value="<?= $l['id'] ?>" <?= $m['lider_id'] == $l['id'] ? 'selected' : '' ?>>
                                                    <?= sanitize($l['full_name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Reasignar</button>
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
                            <label class="form-label">Asignar a Líder</label>
                            <select name="lider_id" class="form-select">
                                <option value="0">Pendiente / Sin líder</option>
                                <?php foreach ($lideres as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= sanitize($l['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="isNewCreate" name="is_new">
                            <label class="form-check-label" for="isNewCreate">Marcar como nuevo miembro</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
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
