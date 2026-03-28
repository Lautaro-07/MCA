<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$pdo = getConnection();
$user = getCurrentUser();

$filterSupervisor = (int)($_GET['supervisor'] ?? 0);
$filterMonth = (int)($_GET['month'] ?? date('n'));
$filterYear = (int)($_GET['year'] ?? date('Y'));

// get supervisors for dropdown
$supervisores = getAllSupervisors();

$informes = [];
try {
    $sql = "SELECT i.*, u.full_name as supervisor_nombre 
            FROM informes_supervisores i 
            JOIN users u ON i.supervisor_id = u.id 
            WHERE MONTH(i.semana_inicio) = ? AND YEAR(i.semana_inicio) = ?";
    $params = [$filterMonth, $filterYear];
    if ($filterSupervisor > 0) {
        $sql .= " AND i.supervisor_id = ?";
        $params[] = $filterSupervisor;
    }
    $sql .= " ORDER BY i.semana_inicio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $informes = $stmt->fetchAll();
} catch (Exception $e) {
    // ignore
}

$pageTitle = 'Informe Supervisor';
$monthNames = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
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
                <p class="text-muted mb-0"><?= $monthNames[$filterMonth] ?> <?= $filterYear ?></p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Supervisor</label>
                        <select name="supervisor" class="form-select">
                            <option value="0">Todos los supervisores</option>
                            <?php foreach ($supervisores as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $filterSupervisor == $s['id'] ? 'selected' : '' ?>>
                                <?= sanitize($s['full_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                        <th>Supervisor</th>
                        <th>Semana</th>
                        <th>Anfitrión</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($informes as $inf): ?>
                    <tr>
                        <td><?= sanitize($inf['supervisor_nombre']) ?></td>
                        <td><?= formatDate($inf['semana_inicio']) ?></td>
                        <td><?= sanitize($inf['anfitrion'] ?? '') ?></td>
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
                        <td colspan="5" class="text-center text-muted py-4">
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
