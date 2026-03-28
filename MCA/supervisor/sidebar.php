<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$user = getCurrentUser();
?>
<div class="admin-sidebar">
    <div class="brand">
        <i class="bi bi-heart-fill"></i>
        <span>MCA Supervisor</span>
    </div>
    
    <ul class="nav-menu">
        <li>
            <a href="<?= BASE_URL ?>/supervisor/" class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="nav-section">Líderes</li>
        <li>
            <a href="<?= BASE_URL ?>/supervisor/lideres.php" class="<?= $currentPage === 'lideres' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Mis Líderes</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/supervisor/informe.php" class="<?= $currentPage === 'informe' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-plus"></i>
                <span>Informe Semanal</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/supervisor/informes.php" class="<?= $currentPage === 'informes' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span>Mis Informes</span>
            </a>
        </li>
        
        <li class="nav-section">Estadísticas</li>
        <li>
            <a href="<?= BASE_URL ?>/supervisor/estadisticas.php" class="<?= $currentPage === 'estadisticas' ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span>Estadísticas</span>
            </a>
        </li>
    </ul>
    
    <div class="mt-auto pt-4 border-top border-secondary">
        <div class="d-flex align-items-center px-3 py-2">
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                <i class="bi bi-person text-dark"></i>
            </div>
            <div class="flex-grow-1">
                <small class="text-white d-block"><?= sanitize($user['full_name']) ?></small>
                <small class="text-white-50">Supervisor</small>
            </div>
        </div>
        <div class="px-3 py-2">
            <a href="<?= BASE_URL ?>/" class="btn btn-outline-light btn-sm w-100 mb-2">
                <i class="bi bi-house me-1"></i>Ir al Sitio
            </a>
            <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
            </a>
        </div>
    </div>
</div>
