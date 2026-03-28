<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$user = getCurrentUser();
?>
<div class="admin-sidebar">
    <div class="brand">
        <i class="bi bi-people-fill"></i>
        <span>MCA Sub-Admin</span>
    </div>
    
    <ul class="nav-menu">
        <li>
            <a href="<?= BASE_URL ?>/" class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="nav-section">Miembros</li>
        <li>
            <a href="<?= BASE_URL ?>/miembros.php" class="<?= $currentPage === 'miembros' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Gestionar Miembros</span>
            </a>
        </li>
    </ul>
    
    <div class="mt-auto pt-4 border-top border-secondary">
        <div class="d-flex align-items-center px-3 py-2">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                <i class="bi bi-person text-white"></i>
            </div>
            <div class="flex-grow-1">
                <small class="text-white d-block"><?= sanitize($user['full_name']) ?></small>
                <small class="text-white-50"><?= getRoleName($user['role']) ?></small>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline-light btn-sm w-100 mt-2">
            <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
        </a>
          <a href="../" class="btn btn-outline-light btn-sm w-100 mt-2">
            <i class="bi bi-box-arrow-right me-1"></i>Ver Web
        </a>
    </div>
</div>
