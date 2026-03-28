<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'admin_intercesion']);

$pdo = getConnection();
$user = getCurrentUser();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_estado') {
        $peticionId = (int)$_POST['peticion_id'];
        $estado = $_POST['estado'] ?? 'activo';
        $pdo->prepare("UPDATE intercesion SET estado = ? WHERE id = ?")->execute([$estado, $peticionId]);
        $success = 'Estado actualizado.';
    } elseif ($action === 'delete') {
        $peticionId = (int)$_POST['peticion_id'];
        $pdo->prepare("DELETE FROM intercesion WHERE id = ?")->execute([$peticionId]);
        $success = 'Petición eliminada.';
    }
}

// Get prayer requests
$filter = $_GET['estado'] ?? '';
$sql = "SELECT * FROM intercesion";
$params = [];

if ($filter && in_array($filter, ['activo', 'respondido', 'archivado'])) {
    $sql .= " WHERE estado = ?";
    $params = [$filter];
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$peticiones = $stmt->fetchAll();

$pageTitle = 'Gestionar Intercesión';
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
                <p class="text-muted mb-0">Administra los motivos de oración</p>
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
        
        <!-- Filter -->
        <div class="mb-4">
            <div class="btn-group">
                <a href="<?= BASE_URL ?>/admin/intercesion.php" class="btn btn-outline-primary <?= empty($filter) ? 'active' : '' ?>">Todas</a>
                <a href="<?= BASE_URL ?>/admin/intercesion.php?estado=activo" class="btn btn-outline-success <?= $filter === 'activo' ? 'active' : '' ?>">Activas</a>
                <a href="<?= BASE_URL ?>/admin/intercesion.php?estado=respondido" class="btn btn-outline-info <?= $filter === 'respondido' ? 'active' : '' ?>">Respondidas</a>
                <a href="<?= BASE_URL ?>/admin/intercesion.php?estado=archivado" class="btn btn-outline-secondary <?= $filter === 'archivado' ? 'active' : '' ?>">Archivadas</a>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($peticiones as $pet): ?>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-bold"><?= sanitize($pet['nombre']) ?></h6>
                                <small class="text-muted">
                                    <?php if ($pet['email']): ?>
                                    <?= sanitize($pet['email']) ?> • 
                                    <?php endif; ?>
                                    <?= formatDate($pet['created_at']) ?>
                                </small>
                            </div>
                            <span class="badge <?= $pet['estado'] === 'activo' ? 'bg-success' : ($pet['estado'] === 'respondido' ? 'bg-info' : 'bg-secondary') ?>">
                                <?= ucfirst($pet['estado']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(sanitize($pet['peticion'] ?? '')) ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex gap-2">
                        <form method="POST" class="flex-grow-1">
                            <input type="hidden" name="action" value="update_estado">
                            <input type="hidden" name="peticion_id" value="<?= $pet['id'] ?>">
                            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="activo" <?= $pet['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="respondido" <?= $pet['estado'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                <option value="archivado" <?= $pet['estado'] === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                            </select>
                        </form>
                        <form method="POST" onsubmit="return confirm('¿Eliminar esta petición?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="peticion_id" value="<?= $pet['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($peticiones)): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-heart-pulse display-1 text-muted opacity-25"></i>
                    <h4 class="mt-4 text-muted">No hay peticiones de oración</h4>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
