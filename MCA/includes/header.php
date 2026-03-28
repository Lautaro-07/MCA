<?php
require_once __DIR__ . '/functions.php';
$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?><?= getSetting('site_name', 'MCA - Movilización Cristiana') ?></title>
    <meta name="description" content="<?= getSetting('site_description', 'Movilizando a la iglesia para transformar vidas') ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/public/css/diseño.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/uploads/logos/logomca.png" alt="Logo MCA" class="site-logo navbar-logo" style="width: 60px; height: 70px; max-width: none;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" href="<?= BASE_URL ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'nosotros' ? 'active' : '' ?>" href="<?= BASE_URL ?>/nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['jovenes', 'mujeres', 'varones', 'juveniles']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                            Ministerios
                        </a>
                        <ul class="dropdown-menu ministry-dropdown-menu" >
                            <li><a class="dropdown-item ministry-link" href="<?= BASE_URL ?>/jovenes.php"><i class="bi bi-lightning-fill text-primary me-2"></i>Jóvenes</a></li>
                            <li><a class="dropdown-item ministry-link" href="<?= BASE_URL ?>/mujeres.php"><i class="bi bi-flower1 text-danger me-2"></i>Mujeres</a></li>
                            <li><a class="dropdown-item ministry-link" href="<?= BASE_URL ?>/varones.php"><i class="bi bi-shield-fill text-success me-2"></i>Varones</a></li>
                            <li><a class="dropdown-item ministry-link" href="<?= BASE_URL ?>/juveniles.php"><i class="bi bi-stars text-primary me-2"></i>Juveniles</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'niños' ? 'active' : '' ?>" href="<?= BASE_URL ?>/niños.php">Niños</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'intercesion' ? 'active' : '' ?>" href="<?= BASE_URL ?>/intercesion.php">Intercesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contenido">Contenido</a>
                    </li>
                    <?php if ($currentUser && in_array($currentUser['role'], ['admin', 'supervisor', 'lider'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'formularios' ? 'active' : '' ?>" href="<?= BASE_URL ?>/formularios.php">Formularios</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if ($currentUser): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= sanitize($currentUser['full_name']) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($currentUser['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/"><i class="bi bi-speedometer2 me-2"></i>Panel Admin</a></li>
                                <?php elseif ($currentUser['role'] === 'supervisor'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/supervisor/"><i class="bi bi-graph-up me-2"></i>Panel Supervisor</a></li>
                                <?php elseif ($currentUser['role'] === 'consolidador'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/sub-admin/"><i class="bi bi-people-fill me-2"></i>Panel Miembros</a></li>
                                <?php elseif ($currentUser['role'] === 'lider'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/lider/"><i class="bi bi-clipboard-data me-2"></i>Mi Panel</a></li>
                                <?php elseif (strpos($currentUser['role'], 'admin_') === 0): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/secciones.php"><i class="bi bi-file-earmark-text me-2"></i>Gestionar Sección</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/perfil.php"><i class="bi bi-gear me-2"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light rounded-pill me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Ingresar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if ($flash = getFlash()): ?>
    <div class="position-fixed top-0 start-50 translate-middle-x mt-5 pt-4" style="z-index: 9999;">
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show shadow-lg" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Dropdown Ministerios Responsive Colors -->
    <script>
        function updateDropdownColors() {
            const links = document.querySelectorAll('.ministry-link');
            const isResponsive = window.innerWidth <= 991;
            links.forEach(link => {
                link.style.color = isResponsive ? '#ffffff' : '#000000';
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateDropdownColors);
        } else {
            updateDropdownColors();
        }
        window.addEventListener('resize', updateDropdownColors);
    </script>
