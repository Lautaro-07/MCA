<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['consolidador']);

$pdo = getConnection();
$user = getCurrentUser();

// Get statistics for miembros
$totalMiembros = $pdo->query("SELECT COUNT(*) as count FROM miembros WHERE is_active = 1")->fetch()['count'];
$totalMiembrosPorLider = $pdo->query("SELECT COUNT(*) as count FROM miembros WHERE lider_id IS NOT NULL AND is_active = 1")->fetch()['count'];
$nuevosMiembros = $pdo->query("SELECT COUNT(*) as count FROM miembros WHERE DATE(created_at) >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND is_active = 1")->fetch()['count'];

$pageTitle = 'Panel Consolidador';
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
    <link href="/public/css/diseño.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
    <style>
        .stat-card-premium {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4599FE, #6FB8FF);
        }

        .stat-card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-icon-box {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: white;
        }

        .stat-icon-box.primary {
            background: linear-gradient(135deg, #4599FE, #6FB8FF);
        }

        .stat-icon-box.success {
            background: linear-gradient(135deg, #10b981, #06b6d4);
        }

        .stat-icon-box.info {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
        }

        .stat-number-premium {
            font-size: 2.8rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label-premium {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
            margin: 0;
        }

        .dashboard-header {
            margin-bottom: 3rem;
        }

        .access-card {
            border-left: 5px solid #4599FE;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .cta-button {
            background: linear-gradient(135deg, #4599FE, #2f7CE6);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateX(4px);
            box-shadow: 0 8px 20px rgba(69, 153, 254, 0.3);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header dashboard-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0"><i class="bi bi-briefcase me-2"></i>Gestión limitada al módulo de miembros</p>
            </div>
        </div>
        
        <?php if ($flash = getFlash()): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="stat-card-premium">
                    <div class="stat-icon-box primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="stat-number-premium"><?= $totalMiembros ?></h3>
                    <p class="stat-label-premium">
                        <i class="bi bi-person-circle me-1"></i>Total de Miembros
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="stat-card-premium">
                    <div class="stat-icon-box success">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <h3 class="stat-number-premium"><?= $totalMiembrosPorLider ?></h3>
                    <p class="stat-label-premium">
                        <i class="bi bi-check-circle me-1"></i>Miembros Asignados
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="stat-card-premium">
                    <div class="stat-icon-box info">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h3 class="stat-number-premium"><?= $nuevosMiembros ?></h3>
                    <p class="stat-label-premium">
                        <i class="bi bi-calendar-week me-1"></i>Nuevos (Esta Semana)
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card access-card border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2"><i class="bi bi-shield-check me-2" style="color: #4599FE;"></i>Acceso Restringido</h5>
                        <p class="text-muted mb-2">
                            Como Consolidador, tienes acceso exclusivamente a:
                        </p>
                        <ul class="mb-0">
                            <li><strong>Gestión de Miembros</strong> - Ver, crear, editar y eliminar miembros del sistema</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="/sub-admin/miembros.php" class="btn cta-button text-white">
                            <i class="bi bi-people me-2"></i>Gestionar Miembros
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/main.js"></script>
</body>
</html>
