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
    
    if ($action === 'assign') {
        $supervisorId = (int)$_POST['supervisor_id'];
        $liderIds = $_POST['lider_ids'] ?? [];
        
        // Remove existing assignments for this supervisor
        $pdo->prepare("DELETE FROM supervisor_lideres WHERE supervisor_id = ?")->execute([$supervisorId]);
        
        // Add new assignments
        if (!empty($liderIds)) {
            $stmt = $pdo->prepare("INSERT INTO supervisor_lideres (supervisor_id, lider_id) VALUES (?, ?)");
            foreach ($liderIds as $liderId) {
                $stmt->execute([$supervisorId, (int)$liderId]);
            }
        }
        
        logActivity($user['id'], 'asignar_lideres', "Líderes asignados al supervisor ID: $supervisorId");
        $success = 'Líderes asignados correctamente.';
    }
}

// Get supervisors
$supervisores = getAllSupervisors();
$lideres = getAllLeaders();

// Get current assignments
$assignments = [];
$stmt = $pdo->query("SELECT * FROM supervisor_lideres");
while ($row = $stmt->fetch()) {
    if (!isset($assignments[$row['supervisor_id']])) {
        $assignments[$row['supervisor_id']] = [];
    }
    $assignments[$row['supervisor_id']][] = $row['lider_id'];
}

$pageTitle = 'Gestionar Supervisores';
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
                <p class="text-muted mb-0">Asigna líderes a cada supervisor</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/usuarios.php" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle me-2"></i>Crear Supervisor
            </a>
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
        
        <?php if (empty($supervisores)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-person-check display-1 text-muted opacity-25"></i>
                <h4 class="mt-4 text-muted">No hay supervisores</h4>
                <p class="text-muted">Crea un supervisor desde la sección de usuarios.</p>
                <a href="<?= BASE_URL ?>/admin/usuarios.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Supervisor
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($supervisores as $sup): ?>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person-check text-warning fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold"><?= sanitize($sup['full_name']) ?></h5>
                                <small class="text-muted"><?= sanitize($sup['email']) ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="assign">
                            <input type="hidden" name="supervisor_id" value="<?= $sup['id'] ?>">
                            
                            <label class="form-label fw-medium">Líderes Asignados</label>
                            <div class="border rounded p-3 mb-3" style="max-height: 200px; overflow-y: auto;">
                                <?php if (empty($lideres)): ?>
                                <p class="text-muted mb-0 small">No hay líderes disponibles</p>
                                <?php else: ?>
                                <?php foreach ($lideres as $lid): ?>
                                <div class="form-check">
                                    <input type="checkbox" name="lider_ids[]" value="<?= $lid['id'] ?>" 
                                        class="form-check-input" id="lider<?= $sup['id'] ?>_<?= $lid['id'] ?>"
                                        <?= in_array($lid['id'], $assignments[$sup['id']] ?? []) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="lider<?= $sup['id'] ?>_<?= $lid['id'] ?>">
                                        <?= sanitize($lid['full_name']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Guardar Asignaciones
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <small class="text-muted">
                            <i class="bi bi-people me-1"></i>
                            <?= count($assignments[$sup['id']] ?? []) ?> líderes asignados
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
