<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['lider']);

$user = getCurrentUser();
$pdo = getConnection();

// Get monthly stats for current year
$year = (int)($_GET['year'] ?? date('Y'));
$monthlyStats = [];

for ($month = 1; $month <= 12; $month++) {
    $stats = getMonthlyStats($user['id'], $month, $year);
    $monthlyStats[$month] = $stats;
}

// Get members
$miembros = getLeaderMembers($user['id']);

// Calculate member attendance (count of reports where they attended)
$memberAverages = [];
foreach ($miembros as $m) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_reportes,
               SUM(CASE WHEN a.grupo_familiar = 'si' THEN 1 ELSE 0 END) as asistencias
        FROM asistencia_semanal a
        WHERE a.miembro_id = ?
    ");
    $stmt->execute([$m['id']]);
    $result = $stmt->fetch();
    $total = (int)($result['total_reportes'] ?? 0);
    $asistencias = (int)($result['asistencias'] ?? 0);
    $promedio = $total > 0 ? round(($asistencias / $total) * 100) : 0;
    
    $memberAverages[] = [
        'nombre' => $m['nombre'],
        'promedio' => $promedio
    ];
}

// Sort by average descending
usort($memberAverages, fn($a, $b) => $b['promedio'] <=> $a['promedio']);

$pageTitle = 'Estadísticas';
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Año <?= $year ?></p>
            </div>
            <div class="d-flex gap-2">
                <a href="?year=<?= $year - 1 ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-chevron-left"></i> <?= $year - 1 ?>
                </a>
                <?php if ($year < date('Y')): ?>
                <a href="?year=<?= $year + 1 ?>" class="btn btn-outline-secondary">
                    <?= $year + 1 ?> <i class="bi bi-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="row g-4 mb-4">
            <!-- Monthly Chart -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Asistencia Promedio por Mes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Member Rankings -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Ranking de Miembros</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($memberAverages, 0, 10) as $index => $m): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle me-3" style="width: 25px; height: 25px; line-height: 16px;">
                                        <?= $index + 1 ?>
                                    </span>
                                    <span><?= sanitize($m['nombre']) ?></span>
                                </div>
                                <span class="fw-bold <?= $m['promedio'] >= 80 ? 'text-success' : ($m['promedio'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                    <?= $m['promedio'] ?>%
                                </span>
                            </div>
                            <?php endforeach; ?>
                            <?php if (empty($memberAverages)): ?>
                            <div class="list-group-item text-center text-muted">
                                Sin datos
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Stats Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Detalle Mensual</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Mes</th>
                                <th class="text-center">Informes</th>
                                <th class="text-center">G.F.</th>
                                <th class="text-center">Escuela</th>
                                <th class="text-center">Red</th>
                                <th class="text-center">Domingo</th>
                                <th class="text-center">OMT</th>
                                <th class="text-center">Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            foreach ($monthlyStats as $month => $stats): 
                            ?>
                            <tr class="<?= $month === (int)date('n') && $year === (int)date('Y') ? 'table-active' : '' ?>">
                                <td class="fw-medium"><?= $monthNames[$month] ?></td>
                                <?php if ($stats && ($stats['informes_enviados'] ?? 0) > 0): ?>
                                <td class="text-center"><?= $stats['informes_enviados'] ?></td>
                                <td class="text-center"><?= round($stats['promedio_grupo_familiar'] ?? 0) ?>%</td>
                                <td class="text-center"><?= round($stats['promedio_escuela'] ?? 0) ?>%</td>
                                <td class="text-center"><?= round($stats['promedio_reunion_red'] ?? 0) ?>%</td>
                                <td class="text-center"><?= round($stats['promedio_culto_domingo'] ?? 0) ?>%</td>
                                <td class="text-center"><?= round($stats['promedio_actividad_omt'] ?? 0) ?>%</td>
                                <td class="text-center fw-bold <?= ($stats['promedio_asistencia'] ?? 0) >= 80 ? 'text-success' : (($stats['promedio_asistencia'] ?? 0) >= 50 ? 'text-warning' : 'text-danger') ?>">
                                    <?= round($stats['promedio_asistencia'] ?? 0) ?>%
                                </td>
                                <?php else: ?>
                                <td colspan="7" class="text-center text-muted">Sin datos</td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        const data = <?= json_encode(array_map(fn($s) => (float)($s['promedio_asistencia'] ?? 0), $monthlyStats)) ?>;
        const dataValues = Object.values(data);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Asistencia Promedio %',
                    data: dataValues,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: 'rgb(99, 102, 241)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });
    </script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
