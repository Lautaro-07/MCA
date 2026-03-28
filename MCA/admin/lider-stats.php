<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'supervisor']);

$pdo = getConnection();
$user = getCurrentUser();

$liderId = (int)($_GET['id'] ?? 0);

if (!$liderId) {
    header('Location: ' . BASE_URL . '/admin/lideres.php');
    exit;
}

// Get leader info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'lider'");
$stmt->execute([$liderId]);
$lider = $stmt->fetch();

if (!$lider) {
    header('Location: ' . BASE_URL . '/admin/lideres.php');
    exit;
}

// Get members - handle missing columns gracefully
try {
    $stmt = $pdo->prepare("SELECT * FROM miembros WHERE lider_id = ? AND is_active = 1 ORDER BY nombre ASC");
    $stmt->execute([$liderId]);
    $miembros = $stmt->fetchAll();
} catch (PDOException $e) {
    $miembros = [];
}

// Get recent reports - handle missing table gracefully
try {
    $stmt = $pdo->prepare("
        SELECT * FROM informes_semanales 
        WHERE lider_id = ? 
        ORDER BY semana_inicio DESC 
        LIMIT 12
    ");
    $stmt->execute([$liderId]);
    $informes = $stmt->fetchAll();
} catch (PDOException $e) {
    $informes = [];
}

// Calculate stats - handle missing columns gracefully
$totalMiembros = count(array_filter($miembros, fn($m) => empty($m['es_consolidacion'] ?? 0)));
$enConsolidacion = count(array_filter($miembros, fn($m) => !empty($m['es_consolidacion'] ?? 0)));
$bautizados = count(array_filter($miembros, fn($m) => !empty($m['esta_bautizado'] ?? 0)));
$totalInformes = count($informes);

$pageTitle = 'Estadísticas del Líder';
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
                <a href="<?= BASE_URL ?>/admin/lideres.php" class="text-muted text-decoration-none">
                    <i class="bi bi-arrow-left me-2"></i>Volver a Líderes
                </a>
                <h1 class="mt-2"><?= sanitize($lider['full_name']) ?></h1>
                <p class="text-muted mb-0"><?= sanitize($lider['email']) ?></p>
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
                        <h3><?= $totalMiembros ?></h3>
                        <span>Miembros</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div class="info">
                        <h3><?= $enConsolidacion ?></h3>
                        <span>En Consolidación</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-water"></i>
                    </div>
                    <div class="info">
                        <h3><?= $bautizados ?></h3>
                        <span>Bautizados</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon info">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="info">
                        <h3><?= $totalInformes ?></h3>
                        <span>Informes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Members List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Miembros del Grupo</h5>
                        <span class="badge bg-primary"><?= count($miembros) ?> total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Tipo</th>
                                        <th>Bautizado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($miembros as $m): ?>
                                    <tr>
                                        <td class="fw-medium"><?= sanitize($m['nombre']) ?></td>
                                        <td class="text-muted"><?= sanitize($m['telefono'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($m['es_consolidacion'] ?? 0)): ?>
                                            <span class="badge bg-warning">En Consolidación</span>
                                            <?php else: ?>
                                            <span class="badge bg-success">Miembro</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($m['esta_bautizado'] ?? 0)): ?>
                                            <i class="bi bi-check-circle text-success"></i>
                                            <?php else: ?>
                                            <i class="bi bi-x-circle text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($miembros)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No hay miembros registrados</td>
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Informes Recientes</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Semana</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($informes as $inf): ?>
                                    <tr>
                                        <td><?= formatDate($inf['semana_inicio'], 'd/m') ?> - <?= formatDate($inf['semana_fin'], 'd/m') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $inf['estado'] === 'completado' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($inf['estado']) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted"><?= formatDate($inf['created_at'], 'd/m/Y') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($informes)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No hay informes</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
