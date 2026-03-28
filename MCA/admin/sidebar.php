<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$user = getCurrentUser();
?>
<div class="admin-sidebar">
    <div class="brand">
        <i class="bi bi-heart-fill"></i>
        <span>MCA Admin</span>
    </div>
    
    <ul class="nav-menu">
        <li>
            <a href="<?= BASE_URL ?>/admin/" class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="nav-section">Usuarios</li>
        <li>
            <a href="<?= BASE_URL ?>/admin/usuarios.php" class="<?= $currentPage === 'usuarios' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Gestionar Usuarios</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/supervisores.php" class="<?= $currentPage === 'supervisores' ? 'active' : '' ?>">
                <i class="bi bi-person-check"></i>
                <span>Supervisores</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/lideres.php" class="<?= $currentPage === 'lideres' ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i>
                <span>Líderes</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/miembros.php" class="<?= $currentPage === 'miembros' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Miembros</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/informe.php" class="<?= $currentPage === 'informe' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-plus"></i>
                <span>Informe Semanal</span>
            </a>
        </li>
        
        <li class="nav-section">Contenido</li>
        <li>
            <a href="<?= BASE_URL ?>/admin/publicaciones.php" class="<?= $currentPage === 'publicaciones' ? 'active' : '' ?>">
                <i class="bi bi-newspaper"></i>
                <span>Publicaciones</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/intercesion.php" class="<?= $currentPage === 'intercesion' ? 'active' : '' ?>">
                <i class="bi bi-heart-pulse"></i>
                <span>Intercesión</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/formularios.php" class="<?= $currentPage === 'formularios' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Formularios</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/fondo.php" class="<?= $currentPage === 'fondo' ? 'active' : '' ?>">
                <i class="bi bi-image"></i>
                <span>Fondo Inicio</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/videos.php" class="<?= $currentPage === 'videos' ? 'active' : '' ?>">
                <i class="bi bi-play-circle"></i>
                <span>Videos</span>
            </a>
        </li>
        
        <li class="nav-section">Estadísticas</li>
        <li>
            <a href="<?= BASE_URL ?>/admin/estadisticas.php" class="<?= $currentPage === 'estadisticas' ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span>Ver Estadísticas</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/reportes.php" class="<?= $currentPage === 'reportes' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Reportes Mensuales</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/informe_supervisor.php" class="<?= $currentPage === 'informe_supervisor' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Informe Supervisor</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/mis_informes.php" class="<?= $currentPage === 'mis_informes' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span>Mis Informes</span>
            </a>
        </li>
        <li class="nav-section">Sistema</li>
        <li>
            <a href="<?= BASE_URL ?>/admin/alertas.php" class="<?= $currentPage === 'alertas' ? 'active' : '' ?>">
                <i class="bi bi-bell"></i>
                <span>Alertas</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/configuracion.php" class="<?= $currentPage === 'configuracion' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i>
                <span>Configuración</span>
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
        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm w-100 mt-2">
            <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
        </a>
         <a href="../" class="btn btn-outline-light btn-sm w-100 mt-2">
            <i class="bi bi-box-arrow-right me-1"></i>Volver
        </a>
    </div>
</div>
