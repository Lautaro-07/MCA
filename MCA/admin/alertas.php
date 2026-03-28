<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();

$alertas = [];

try {
    // 1. Leaders who haven't submitted reports this week
    $weekRange = getWeekRange();
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name, u.email, 'lider_sin_informe' as tipo
        FROM users u 
        WHERE u.role = 'lider' AND u.is_active = 1
        AND u.id NOT IN (
            SELECT lider_id FROM informes_semanales 
            WHERE semana_inicio = ?
        )
    ");
    $stmt->execute([$weekRange['inicio']]);
    while ($row = $stmt->fetch()) {
        $alertas[] = [
            'tipo' => 'warning',
            'icono' => 'bi-exclamation-triangle',
            'titulo' => 'Líder sin informe esta semana',
            'mensaje' => sanitize($row['full_name']) . ' no ha enviado su informe semanal.',
            'usuario_id' => $row['id']
        ];
    }
    
    // 2. Leaders with no members
    $stmt = $pdo->query("
        SELECT u.id, u.full_name 
        FROM users u 
        LEFT JOIN miembros m ON u.id = m.lider_id AND m.is_active = 1
        WHERE u.role = 'lider' AND u.is_active = 1
        GROUP BY u.id, u.full_name
        HAVING COUNT(m.id) = 0
    ");
    while ($row = $stmt->fetch()) {
        $alertas[] = [
            'tipo' => 'info',
            'icono' => 'bi-people',
            'titulo' => 'Líder sin miembros',
            'mensaje' => sanitize($row['full_name']) . ' no tiene miembros asignados.',
            'usuario_id' => $row['id']
        ];
    }
    
    // 3. Supervisors with no leaders assigned
    $stmt = $pdo->query("
        SELECT u.id, u.full_name 
        FROM users u 
        LEFT JOIN supervisor_lideres sl ON u.id = sl.supervisor_id
        WHERE u.role = 'supervisor' AND u.is_active = 1
        GROUP BY u.id, u.full_name
        HAVING COUNT(sl.lider_id) = 0
    ");
    while ($row = $stmt->fetch()) {
        $alertas[] = [
            'tipo' => 'info',
            'icono' => 'bi-person-check',
            'titulo' => 'Supervisor sin líderes',
            'mensaje' => sanitize($row['full_name']) . ' no tiene líderes asignados.',
            'usuario_id' => $row['id']
        ];
    }
    
    // 4. Urgent prayer requests
    $stmt = $pdo->query("SELECT COUNT(*) FROM intercesion WHERE es_urgente = 1 AND estado = 'activo'");
    $urgentes = $stmt->fetchColumn();
    if ($urgentes > 0) {
        $alertas[] = [
            'tipo' => 'danger',
            'icono' => 'bi-heart-pulse',
            'titulo' => 'Peticiones urgentes',
            'mensaje' => "Hay $urgentes peticiones de oración marcadas como urgentes.",
            'link' => BASE_URL . '/admin/intercesion.php'
        ];
    }
    
    // 5. Leaders without reports in the last 2 weeks
    $doseSemanasAtras = date('Y-m-d', strtotime('-14 days'));
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name, MAX(i.created_at) as ultimo_informe
        FROM users u 
        LEFT JOIN informes_semanales i ON u.id = i.lider_id
        WHERE u.role = 'lider' AND u.is_active = 1
        GROUP BY u.id, u.full_name
        HAVING ultimo_informe IS NULL OR ultimo_informe < ?
    ");
    $stmt->execute([$doseSemanasAtras]);
    while ($row = $stmt->fetch()) {
        $alertas[] = [
            'tipo' => 'danger',
            'icono' => 'bi-calendar-x',
            'titulo' => 'Sin informes en 2+ semanas',
            'mensaje' => sanitize($row['full_name']) . ' no ha reportado en más de 2 semanas.',
            'usuario_id' => $row['id']
        ];
    }
    
} catch (Exception $e) {
    // Silently fail
}

// Group alerts by type
$alertasPorTipo = ['danger' => [], 'warning' => [], 'info' => []];
foreach ($alertas as $alerta) {
    $alertasPorTipo[$alerta['tipo']][] = $alerta;
}

$pageTitle = 'Alertas del Sistema';
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
                <h1><i class="bi bi-bell me-2"></i><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Revisa las alertas y pendientes del sistema</p>
            </div>
            <span class="badge bg-<?= count($alertas) > 0 ? 'danger' : 'success' ?> fs-6">
                <?= count($alertas) ?> alertas
            </span>
        </div>
        
        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(220, 53, 69, 0.1);">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($alertasPorTipo['danger']) ?></h3>
                        <span>Críticas</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(255, 193, 7, 0.1);">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($alertasPorTipo['warning']) ?></h3>
                        <span>Advertencias</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(13, 202, 240, 0.1);">
                        <i class="bi bi-info-circle text-info"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($alertasPorTipo['info']) ?></h3>
                        <span>Información</span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (empty($alertas)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle display-1 text-success opacity-50"></i>
                <h4 class="mt-4 text-success">¡Todo en orden!</h4>
                <p class="text-muted">No hay alertas pendientes en el sistema.</p>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Critical Alerts -->
        <?php if (!empty($alertasPorTipo['danger'])): ?>
        <div class="card border-0 shadow-sm mb-4 border-start border-danger border-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-exclamation-circle me-2"></i>Alertas Críticas</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($alertasPorTipo['danger'] as $alerta): ?>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="<?= $alerta['icono'] ?> text-danger fs-4 me-3"></i>
                        <div class="flex-grow-1">
                            <strong><?= $alerta['titulo'] ?></strong>
                            <p class="mb-0 text-muted small"><?= $alerta['mensaje'] ?></p>
                        </div>
                        <?php if (isset($alerta['link'])): ?>
                        <a href="<?= $alerta['link'] ?>" class="btn btn-sm btn-outline-danger">Ver</a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Warnings -->
        <?php if (!empty($alertasPorTipo['warning'])): ?>
        <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Advertencias</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($alertasPorTipo['warning'] as $alerta): ?>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="<?= $alerta['icono'] ?> text-warning fs-4 me-3"></i>
                        <div class="flex-grow-1">
                            <strong><?= $alerta['titulo'] ?></strong>
                            <p class="mb-0 text-muted small"><?= $alerta['mensaje'] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Info -->
        <?php if (!empty($alertasPorTipo['info'])): ?>
        <div class="card border-0 shadow-sm mb-4 border-start border-info border-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-info"><i class="bi bi-info-circle me-2"></i>Información</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($alertasPorTipo['info'] as $alerta): ?>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="<?= $alerta['icono'] ?> text-info fs-4 me-3"></i>
                        <div class="flex-grow-1">
                            <strong><?= $alerta['titulo'] ?></strong>
                            <p class="mb-0 text-muted small"><?= $alerta['mensaje'] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
