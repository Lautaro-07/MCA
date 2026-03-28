<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['lider']);

$user = getCurrentUser();
$pdo = getConnection();

// Get all reports for this leader
$stmt = $pdo->prepare("SELECT * FROM informes_semanales WHERE lider_id = ? ORDER BY semana_inicio DESC");
$stmt->execute([$user['id']]);
$informes = $stmt->fetchAll();

$pageTitle = 'Historial de Informes';
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
                <p class="text-muted mb-0">Todos tus informes semanales</p>
            </div>
            <a href="<?= BASE_URL ?>/lider/informe.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Informe
            </a>
        </div>
        
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Semana</th>
                        <th>Fecha Reunión</th>
                        <th>Miembros</th>
                        <th>Asistentes GF</th>
                        <th>Evangelizados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($informes as $inf): ?>
                    <tr>
                        <td class="fw-medium"><?= formatDate($inf['semana_inicio']) ?> - <?= formatDate($inf['semana_fin']) ?></td>
                        <td><?= $inf['fecha_reunion'] ? formatDate($inf['fecha_reunion']) : '-' ?></td>
                        <td><?= $inf['total_miembros'] ?></td>
                        <td><?= $inf['total_asistentes'] ?></td>
                        <td><?= $inf['nuevos_evangelizados'] ?></td>
                        <td>
                            <?php if ($inf['estado'] === 'completado'): ?>
                            <span class="status-badge active">Completado</span>
                            <?php else: ?>
                            <span class="status-badge" style="background: #fef3c7; color: #92400e;">Borrador</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/lider/informe.php?id=<?= $inf['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($informes)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay informes registrados. 
                            <a href="<?= BASE_URL ?>/lider/informe.php">Crear tu primer informe</a>
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
