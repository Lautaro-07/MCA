<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['lider']);

$user = getCurrentUser();
$pdo = getConnection();

// Get leader's members
$miembros = getLeaderMembers($user['id']);

// Get current week info
$weekRange = getWeekRange();

// Check if report exists for this week
$stmt = $pdo->prepare("SELECT * FROM informes_semanales WHERE lider_id = ? AND semana_inicio = ?");
$stmt->execute([$user['id'], $weekRange['inicio']]);
$informeActual = $stmt->fetch();

// Get recent reports
$stmt = $pdo->prepare("SELECT * FROM informes_semanales WHERE lider_id = ? ORDER BY semana_inicio DESC LIMIT 5");
$stmt->execute([$user['id']]);
$informesRecientes = $stmt->fetchAll();

// Get monthly stats
$currentMonth = date('n');
$currentYear = date('Y');
$monthlyStats = getMonthlyStats($user['id'], $currentMonth, $currentYear);

$pageTitle = 'Panel de Líder';
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
                <p class="text-muted mb-0">Panel de Líder de Grupo Familiar</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none">
                    <i class="bi bi-list"></i>
                </button>
                <a href="<?= BASE_URL ?>/lider/informe.php" class="btn btn-primary">
                    <i class="bi bi-clipboard-plus me-2"></i>Nuevo Informe
                </a>
            </div>
        </div>
        
        <!-- Week Info -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="bi bi-calendar-week me-3 fs-4"></i>
            <div>
                <strong>Semana Actual:</strong> <?= formatDate($weekRange['inicio']) ?> - <?= formatDate($weekRange['fin']) ?>
                <?php if ($informeActual && $informeActual['estado'] === 'completado'): ?>
                <span class="badge bg-success ms-2">Informe Completado</span>
                <?php elseif ($informeActual): ?>
                <span class="badge bg-warning ms-2">Informe en Borrador</span>
                <?php else: ?>
                <span class="badge bg-danger ms-2">Informe Pendiente</span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($miembros) ?></h3>
                        <span>Miembros</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="info">
                        <h3><?= $monthlyStats['informes_enviados'] ?? 0 ?></h3>
                        <span>Informes Mes</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="info">
                        <h3><?= $informeActual ? 'Sí' : 'No' ?></h3>
                        <span>Informe Semana</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon info">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($informesRecientes) ?></h3>
                        <span>Últimos Informes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Members List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Mis Miembros</h5>
                        <a href="<?= BASE_URL ?>/lider/miembros.php" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i>Agregar
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($miembros as $m): ?>
                                    <tr>
                                        <td class="fw-medium"><?= sanitize($m['nombre']) ?></td>
                                        <td><?= sanitize($m['telefono']) ?: '-' ?></td>
                                        <td>
                                            <span class="badge bg-secondary">Miembro</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($miembros)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            No hay miembros registrados. 
                                            <a href="<?= BASE_URL ?>/lider/miembros.php">Ver miembros</a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Reports -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Informes Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($informesRecientes as $informe): ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 <?= $informe['estado'] === 'completado' ? 'bg-success' : 'bg-warning' ?> bg-opacity-10" style="width: 45px; height: 45px;">
                                <i class="bi <?= $informe['estado'] === 'completado' ? 'bi-check-circle text-success' : 'bi-pencil text-warning' ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">Semana <?= formatDate($informe['semana_inicio']) ?></p>
                                <small class="text-muted"><?= $informe['estado'] === 'completado' ? 'Completado' : 'Borrador' ?></small>
                            </div>
                            <a href="<?= BASE_URL ?>/lider/informe.php?id=<?= $informe['id'] ?>" class="btn btn-sm btn-outline-primary">
                                Ver
                            </a>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($informesRecientes)): ?>
                        <p class="text-muted text-center mb-0">No hay informes registrados</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?= BASE_URL ?>/lider/informe.php" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-clipboard-plus me-2"></i>Crear Informe Semanal
                        </a>
                        <a href="<?= BASE_URL ?>/lider/miembros.php" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-person-plus me-2"></i>Ver Miembros
                        </a>
                        <a href="<?= BASE_URL ?>/lider/estadisticas.php" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-graph-up me-2"></i>Ver Estadísticas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
