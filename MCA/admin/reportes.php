<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();

// Get filter
$mes = $_GET['mes'] ?? date('m');
$anio = $_GET['anio'] ?? date('Y');

// Get monthly reports
$informes = [];
try {
    $stmt = $pdo->prepare("
        SELECT i.*, u.full_name,
            (SELECT COUNT(*) FROM miembros m WHERE m.lider_id = i.lider_id AND m.is_active = 1) as total_miembros
        FROM informes_semanales i
        JOIN users u ON i.lider_id = u.id
        WHERE MONTH(i.semana_inicio) = ? AND YEAR(i.semana_inicio) = ?
        ORDER BY i.semana_inicio DESC, u.full_name
    ");
    $stmt->execute([$mes, $anio]);
    $informes = $stmt->fetchAll();
} catch (Exception $e) {
    $informes = [];
}

// Group by leader
$informesPorLider = [];
foreach ($informes as $inf) {
    $liderId = $inf['lider_id'];
    if (!isset($informesPorLider[$liderId])) {
        $informesPorLider[$liderId] = [
            'nombre' => $inf['full_name'],
            'miembros' => $inf['total_miembros'],
            'informes' => []
        ];
    }
    $informesPorLider[$liderId]['informes'][] = $inf;
}

// Get all leaders to show who hasn't reported
$todosLideres = getAllLeaders();

$pageTitle = 'Reportes Mensuales';
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
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
                <p class="text-muted mb-0">Resumen de informes por mes</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Mes</label>
                        <select name="mes" class="form-select">
                            <?php foreach ($meses as $num => $nombre): ?>
                            <option value="<?= $num ?>" <?= $mes == $num ? 'selected' : '' ?>><?= $nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Año</label>
                        <select name="anio" class="form-select">
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?= $y ?>" <?= $anio == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon primary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($informes) ?></h3>
                        <span>Informes del Mes</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($informesPorLider) ?></h3>
                        <span>Líderes Reportaron</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="info">
                        <h3><?= count($todosLideres) - count($informesPorLider) ?></h3>
                        <span>Sin Reportar</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reports by Leader -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Informes por Líder - <?= $meses[(int)$mes] ?> <?= $anio ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Líder</th>
                                <th>Miembros</th>
                                <th>Informes</th>
                                <th>Semanas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todosLideres as $lider): ?>
                            <?php 
                                $data = $informesPorLider[$lider['id']] ?? null;
                                $numInformes = $data ? count($data['informes']) : 0;
                            ?>
                            <tr>
                                <td class="fw-medium"><?= sanitize($lider['full_name']) ?></td>
                                <td><?= $data ? $data['miembros'] : 0 ?></td>
                                <td><?= $numInformes ?></td>
                                <td>
                                    <?php if ($data): ?>
                                    <?php foreach ($data['informes'] as $inf): ?>
                                    <span class="badge bg-secondary me-1">
                                        <?= formatDate($inf['semana_inicio'], 'd/m') ?>
                                    </span>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($numInformes >= 4): ?>
                                    <span class="badge bg-success">Completo</span>
                                    <?php elseif ($numInformes > 0): ?>
                                    <span class="badge bg-warning">Parcial</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">Sin reportes</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($todosLideres)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay líderes registrados</td>
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
