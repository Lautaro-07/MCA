<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['supervisor']);

$user = getCurrentUser();
$pdo = getConnection();

// Filter
$filterMonth = (int)($_GET['month'] ?? date('n'));
$filterYear = (int)($_GET['year'] ?? date('Y'));

// Get reports for this supervisor
$informes = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM informes_supervisores 
                           WHERE supervisor_id = ? 
                             AND MONTH(semana_inicio) = ? 
                             AND YEAR(semana_inicio) = ? 
                           ORDER BY semana_inicio DESC");
    $stmt->execute([$user['id'], $filterMonth, $filterYear]);
    $informes = $stmt->fetchAll();
} catch (Exception $e) {
    // ignore
}

$pageTitle = 'Mis Informes';
$monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
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
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0"><?= $monthNames[$filterMonth] ?> <?= $filterYear ?></p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                                <div class="col-md-3">
                        <label class="form-label">Mes</label>
                        <select name="month" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $filterMonth == $m ? 'selected' : '' ?>>
                                <?= $monthNames[$m] ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <select name="year" class="form-select">
                            <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                            <option value="<?= $y ?>" <?= $filterYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Semana</th>
                        <th>Miembros</th>
                        <th>Asistentes</th>
                        <th>Evangelizados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($informes as $inf): ?>
                    <tr>
                        <td><?= formatDate($inf['semana_inicio']) ?></td>
                        <td><?= $inf['total_miembros'] ?></td>
                        <td><?= $inf['total_asistentes'] ?></td>
                        <td><?= $inf['nuevos_evangelizados'] ?></td>
                        <td>
                            <span class="status-badge <?= $inf['estado'] === 'completado' ? 'active' : 'inactive' ?>">
                                <?= ucfirst($inf['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/supervisor/ver-informe.php?id=<?= $inf['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($informes)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay informes para el período seleccionado
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
