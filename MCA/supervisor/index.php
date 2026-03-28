<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['supervisor']);

$user = getCurrentUser();
$pdo = getConnection();

// Get assigned leaders
$lideres = getSupervisorLeaders($user['id']);

// Get current week info
$weekRange = getWeekRange();

// Get reports status for this week
$lideresStats = [];
foreach ($lideres as $lider) {
    $stmt = $pdo->prepare("SELECT * FROM informes_semanales WHERE lider_id = ? AND semana_inicio = ?");
    $stmt->execute([$lider['id'], $weekRange['inicio']]);
    $informe = $stmt->fetch();
    
    $miembros = getLeaderMembers($lider['id']);
    
    $lideresStats[$lider['id']] = [
        'lider' => $lider,
        'miembros_count' => count($miembros),
        'informe' => $informe,
        'informe_estado' => $informe ? $informe['estado'] : 'pendiente'
    ];
}

$pageTitle = 'Panel de Supervisor';
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
                <h1>Bienvenido, <?= sanitize($user['full_name']) ?></h1>
                <p class="text-muted mb-0">Panel de Supervisor</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none">
                    <i class="bi bi-list"></i>
                </button>
                <span class="text-muted"><?= date('d M, Y') ?></span>
            </div>
        </div>
        
        <!-- Week Info -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="bi bi-calendar-week me-3 fs-4"></i>
            <div>
                <strong>Semana Actual:</strong> <?= formatDate($weekRange['inicio']) ?> - <?= formatDate($weekRange['fin']) ?>
            </div>
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
                        <span>Líderes Asignados</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="info">
                        <h3><?= count(array_filter($lideresStats, fn($l) => $l['informe_estado'] === 'completado')) ?></h3>
                        <span>Informes Completados</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="info">
                        <h3><?= count(array_filter($lideresStats, fn($l) => $l['informe_estado'] !== 'completado')) ?></h3>
                        <span>Informes Pendientes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leaders List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Mis Líderes - Estado de Informes Semanales</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Líder</th>
                                <th>Teléfono</th>
                                <th>Miembros</th>
                                <th>Estado Informe</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lideresStats as $stats): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium"><?= sanitize($stats['lider']['full_name']) ?></p>
                                            <small class="text-muted"><?= sanitize($stats['lider']['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= sanitize($stats['lider']['phone']) ?: '-' ?></td>
                                <td><span class="badge bg-secondary"><?= $stats['miembros_count'] ?></span></td>
                                <td>
                                    <?php if ($stats['informe_estado'] === 'completado'): ?>
                                    <span class="status-badge active"><i class="bi bi-check-circle me-1"></i>Completado</span>
                                    <?php elseif ($stats['informe_estado'] === 'borrador'): ?>
                                    <span class="status-badge" style="background: #fef3c7; color: #92400e;"><i class="bi bi-pencil me-1"></i>Borrador</span>
                                    <?php else: ?>
                                    <span class="status-badge inactive"><i class="bi bi-clock me-1"></i>Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($stats['informe']): ?>
                                    <a href="<?= BASE_URL ?>/supervisor/ver-informe.php?id=<?= $stats['informe']['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted small">Sin informe</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($lideres)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No tienes líderes asignados. Contacta al administrador.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
