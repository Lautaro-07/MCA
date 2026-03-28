<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$user = getCurrentUser();
$pdo = getConnection();

// Get stats
$stats = [
    'usuarios' => $pdo->query("SELECT COUNT(*) FROM users WHERE is_active = 1")->fetchColumn(),
    'lideres' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'lider' AND is_active = 1")->fetchColumn(),
    'supervisores' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'supervisor' AND is_active = 1")->fetchColumn(),
    'miembros' => $pdo->query("SELECT COUNT(*) FROM miembros WHERE is_active = 1")->fetchColumn(),
    'publicaciones' => $pdo->query("SELECT COUNT(*) FROM publicaciones WHERE estado = 'publicado'")->fetchColumn(),
    'intercesion' => $pdo->query("SELECT COUNT(*) FROM intercesion WHERE estado = 'activo'")->fetchColumn(),
];

// Recent activity
$recentActivity = $pdo->query("
    SELECT a.*, u.full_name 
    FROM actividad_log a 
    JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT 10
")->fetchAll();

// Recent users
$recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

$pageTitle = 'Panel de Administración';
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
    <link href="<?= BASE_URL ?>/public/css/diseño.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1>Dashboard</h1>
                <p class="text-muted mb-0">Bienvenido, <?= sanitize($user['full_name']) ?></p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-outline-secondary d-lg-none">
                    <i class="bi bi-list"></i>
                </button>
                <span class="text-muted"><?= date('d M, Y') ?></span>
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
                        <h3><?= $stats['usuarios'] ?></h3>
                        <span>Usuarios Activos</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="info">
                        <h3><?= $stats['lideres'] ?></h3>
                        <span>Líderes</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon warning">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="info">
                        <h3><?= $stats['supervisores'] ?></h3>
                        <span>Supervisores</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="icon info">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="info">
                        <h3><?= $stats['miembros'] ?></h3>
                        <span>Miembros</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Recent Activity -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Actividad Reciente</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentActivity as $activity): ?>
                                    <tr>
                                        <td class="fw-medium"><?= sanitize($activity['full_name']) ?></td>
                                        <td><?= sanitize($activity['accion']) ?></td>
                                        <td class="text-muted"><?= formatDate($activity['created_at'], 'd/m/Y H:i') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($recentActivity)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No hay actividad reciente</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Contenido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Publicaciones</span>
                            <span class="badge bg-primary"><?= $stats['publicaciones'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Motivos de Oración</span>
                            <span class="badge bg-success"><?= $stats['intercesion'] ?></span>
                        </div>
                        <hr>
                        <a href="<?= BASE_URL ?>/admin/publicaciones.php" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i>Nueva Publicación
                        </a>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Usuarios Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($recentUsers as $u): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-medium"><?= sanitize($u['full_name']) ?></p>
                                <small class="text-muted"><?= getRoleName($u['role']) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
