<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['supervisor']);

$user = getCurrentUser();
$pdo = getConnection();

// Get assigned leaders
$lideres = getSupervisorLeaders($user['id']);

$pageTitle = 'Mis Líderes';
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
                <p class="text-muted mb-0">Líderes asignados a tu supervisión</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($lideres as $lider): 
                $miembros = getLeaderMembers($lider['id']);
                $monthlyStats = getMonthlyStats($lider['id'], date('n'), date('Y'));
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-badge text-success display-5"></i>
                        </div>
                        <h5 class="mb-1 fw-bold"><?= sanitize($lider['full_name']) ?></h5>
                        <p class="text-muted mb-3"><?= sanitize($lider['email']) ?></p>
                        
                        <div class="row g-2 text-center mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="mb-0"><?= count($miembros) ?></h4>
                                    <small class="text-muted">Miembros</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="mb-0"><?= $monthlyStats['informes_enviados'] ?? 0 ?></h4>
                                    <small class="text-muted">Informes</small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($lider['phone']): ?>
                        <a href="tel:<?= $lider['phone'] ?>" class="btn btn-outline-success btn-sm me-1">
                            <i class="bi bi-telephone"></i>
                        </a>
                        <?php endif; ?>
                        <a href="mailto:<?= $lider['email'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($lideres)): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-people display-1 text-muted opacity-25"></i>
                    <h4 class="mt-4 text-muted">No tienes líderes asignados</h4>
                    <p class="text-muted">Contacta al administrador para que te asigne líderes.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
