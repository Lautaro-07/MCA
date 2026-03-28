<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();

// Get all leaders with their stats
$lideres = getAllLeaders();

// Get current week info
$weekRange = getWeekRange();

// Get reports and member counts
$lideresData = [];
foreach ($lideres as $lider) {
    $stmt = $pdo->prepare("SELECT * FROM informes_semanales WHERE lider_id = ? AND semana_inicio = ?");
    $stmt->execute([$lider['id'], $weekRange['inicio']]);
    $informe = $stmt->fetch();
    
    $miembros = getLeaderMembers($lider['id']);
    
    // Get supervisor
    $stmt = $pdo->prepare("SELECT u.full_name FROM supervisor_lideres sl JOIN users u ON sl.supervisor_id = u.id WHERE sl.lider_id = ?");
    $stmt->execute([$lider['id']]);
    $supervisor = $stmt->fetchColumn();
    
    $lideresData[] = [
        'lider' => $lider,
        'miembros_count' => count($miembros),
        'supervisor' => $supervisor,
        'informe' => $informe,
        'informe_estado' => $informe ? $informe['estado'] : 'pendiente'
    ];
}

$pageTitle = 'Gestionar Líderes';
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
                <p class="text-muted mb-0">Semana: <?= formatDate($weekRange['inicio']) ?> - <?= formatDate($weekRange['fin']) ?></p>
            </div>
            <a href="<?= BASE_URL ?>/admin/usuarios.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Crear Líder
            </a>
        </div>
        
        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($lideres) ?></h3>
                        <span>Total Líderes</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="info">
                        <h3><?= count(array_filter($lideresData, fn($l) => $l['informe_estado'] === 'completado')) ?></h3>
                        <span>Informes Completos</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="info">
                        <h3><?= count(array_filter($lideresData, fn($l) => $l['informe_estado'] !== 'completado')) ?></h3>
                        <span>Pendientes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Líder</th>
                        <th>Teléfono</th>
                        <th>Supervisor</th>
                        <th>Miembros</th>
                        <th>Informe Semanal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lideresData as $data): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-badge text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium"><?= sanitize($data['lider']['full_name']) ?></p>
                                    <small class="text-muted"><?= sanitize($data['lider']['email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= sanitize($data['lider']['phone']) ?: '-' ?></td>
                        <td><?= $data['supervisor'] ? sanitize($data['supervisor']) : '<span class="text-muted">Sin asignar</span>' ?></td>
                        <td><span class="badge bg-secondary"><?= $data['miembros_count'] ?></span></td>
                        <td>
                            <?php if ($data['informe_estado'] === 'completado'): ?>
                            <span class="status-badge active"><i class="bi bi-check-circle me-1"></i>Completado</span>
                            <?php elseif ($data['informe_estado'] === 'borrador'): ?>
                            <span class="status-badge" style="background: #fef3c7; color: #92400e;"><i class="bi bi-pencil me-1"></i>Borrador</span>
                            <?php else: ?>
                            <span class="status-badge inactive"><i class="bi bi-clock me-1"></i>Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($data['informe']): ?>
                            <a href="<?= BASE_URL ?>/admin/ver-informe.php?id=<?= $data['informe']['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/admin/lider-stats.php?id=<?= $data['lider']['id'] ?>" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-graph-up"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lideresData)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay líderes registrados. 
                            <a href="<?= BASE_URL ?>/admin/usuarios.php">Crear líder</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
