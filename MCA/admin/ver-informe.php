<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$user = getCurrentUser();
$pdo = getConnection();

$informeId = (int)($_GET['id'] ?? 0);
if (!$informeId) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

// Get report (no supervisor filtering, admin can see all)
$stmt = $pdo->prepare("SELECT i.*, u.full_name as lider_nombre FROM informes_semanales i JOIN users u ON i.lider_id = u.id WHERE i.id = ?");
$stmt->execute([$informeId]);
$informe = $stmt->fetch();

if (!$informe) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

// Get attendance records
$stmt = $pdo->prepare(
    "SELECT a.*, m.nombre as miembro_nombre, m.es_consolidacion, m.esta_bautizado 
    FROM asistencia_semanal a 
    JOIN miembros m ON a.miembro_id = m.id 
    WHERE a.informe_id = ?"
);
$stmt->execute([$informeId]);
$asistencias = $stmt->fetchAll();

$pageTitle = 'Ver Informe Semanal';
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
    <style>
        @media print {
            .admin-sidebar, .no-print { display: none !important; }
            .admin-content { margin-left: 0 !important; padding: 20px !important; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header no-print">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Líder: <?= sanitize($informe['lider_nombre']) ?></p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
                <a href="<?= BASE_URL ?>/admin/" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
        
        <!-- Report Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold">Informe Grupo Familiar</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="text-muted small">Semana</label>
                        <p class="fw-medium mb-0"><?= formatDate($informe['semana_inicio']) ?> - <?= formatDate($informe['semana_fin']) ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Anfitrión</label>
                        <p class="fw-medium mb-0"><?= sanitize($informe['anfitrion']) ?: '-' ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Fecha de Reunión</label>
                        <p class="fw-medium mb-0"><?= $informe['fecha_reunion'] ? formatDate($informe['fecha_reunion']) : '-' ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Horario</label>
                        <p class="fw-medium mb-0"><?= $informe['hora_inicio'] ?> - <?= $informe['hora_termino'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Dirección</label>
                        <p class="fw-medium mb-0"><?= sanitize($informe['direccion']) ?: '-' ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Total Miembros</label>
                        <p class="fw-medium mb-0"><?= $informe['total_miembros'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Asistentes</label>
                        <p class="fw-medium mb-0"><?= $informe['total_asistentes'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Registro de Asistencia</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Miembro</th>
                                <th class="text-center">GF</th>
                                <th class="text-center">Escuela</th>
                                <th class="text-center">Red</th>
                                <th class="text-center">Domingo</th>
                                <th class="text-center">OMT</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistencias as $att): ?>
                            <tr>
                                <td>
                                    <?= sanitize($att['miembro_nombre']) ?>
                                    <?php if ($att['es_consolidacion']): ?>
                                    <span class="badge bg-warning ms-1">C</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $icon = $att['grupo_familiar'] === 'si' ? 'check-circle text-success' : ($att['grupo_familiar'] === 'no_hubo' ? 'dash-circle text-warning' : 'x-circle text-danger');
                                    ?>
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </td>
                                <td class="text-center">
                                    <?php $icon = $att['escuela'] === 'si' ? 'check-circle text-success' : ($att['escuela'] === 'no_hubo' ? 'dash-circle text-warning' : 'x-circle text-danger'); ?>
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </td>
                                <td class="text-center">
                                    <?php $icon = $att['reunion_red'] === 'si' ? 'check-circle text-success' : ($att['reunion_red'] === 'no_hubo' ? 'dash-circle text-warning' : 'x-circle text-danger'); ?>
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </td>
                                <td class="text-center">
                                    <?php $icon = $att['culto_domingo'] === 'si' ? 'check-circle text-success' : ($att['culto_domingo'] === 'no_hubo' ? 'dash-circle text-warning' : 'x-circle text-danger'); ?>
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </td>
                                <td class="text-center">
                                    <?php $icon = $att['actividad_omt'] === 'si' ? 'check-circle text-success' : ($att['actividad_omt'] === 'no_hubo' ? 'dash-circle text-warning' : 'x-circle text-danger'); ?>
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </td>
                                <td class="text-center fw-bold"><?= isset($att['porcentaje_asistencia']) ? round($att['porcentaje_asistencia']) . '%' : '0%' ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($asistencias)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Sin registros de asistencia</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">Evangelismo</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Nuevos Evangelizados:</span>
                            <strong><?= $informe['nuevos_evangelizados'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Confesiones de Fe:</span>
                            <strong><?= $informe['confesiones_fe'] ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">Información Adicional</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tema Expuesto:</span>
                            <strong><?= sanitize($informe['tema_expuesto']) ?: '-' ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ofrenda:</span>
                            <strong>$<?= number_format($informe['ofrenda'], 2) ?></strong>
                        </div>
                        <?php if ($informe['observaciones']): ?>
                        <hr>
                        <p class="mb-0 small"><strong>Observaciones:</strong> <?= nl2br(sanitize($informe['observaciones'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>