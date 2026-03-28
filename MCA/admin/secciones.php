<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin_jovenes', 'admin_mujeres', 'admin_varones', 'admin_juveniles', 'admin_niños', 'admin_intercesion']);

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Determine section based on role
$sectionMap = [
    'admin_jovenes' => ['name' => 'Jóvenes', 'key' => 'jovenes', 'icon' => 'bi-lightning-fill', 'color' => 'primary', 'gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)'],
    'admin_mujeres' => ['name' => 'Mujeres', 'key' => 'mujeres', 'icon' => 'bi-heart-fill', 'color' => 'danger', 'gradient' => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)'],
    'admin_varones' => ['name' => 'Varones', 'key' => 'varones', 'icon' => 'bi-shield-fill', 'color' => 'info', 'gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%)'],
    'admin_juveniles' => ['name' => 'Juveniles', 'key' => 'juveniles', 'icon' => 'bi-emoji-smile-fill', 'color' => 'warning', 'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #eab308 100%)'],
    'admin_niños' => ['name' => 'Niños', 'key' => 'niños', 'icon' => 'bi-stars', 'color' => 'success', 'gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%)'],
    'admin_intercesion' => ['name' => 'Intercesión', 'key' => 'intercesion', 'icon' => 'bi-heart-pulse-fill', 'color' => 'purple', 'gradient' => 'linear-gradient(135deg, #7c3aed 0%, #a855f7 100%)'],
];

$section = $sectionMap[$user['role']] ?? null;

if (!$section) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$sectionConfig = getSectionConfig($section['key']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Publication actions
    if ($action === 'create' || $action === 'update') {
        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');
                
        if (empty($titulo) || empty($contenido)) {
            $error = 'Título y contenido son obligatorios.';
        } else {
            $imagen = null;
            if (!empty($_FILES['imagen']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/publicaciones/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $filename)) {
                        $imagen = '/uploads/publicaciones/' . $filename;
                    }
                }
            }
            
            if ($action === 'create') {
                $seccionKey = '';
                switch ($user['role']) {
                    case 'admin_jovenes': $seccionKey = 'jovenes'; break;
                    case 'admin_mujeres': $seccionKey = 'mujeres'; break;
                    case 'admin_varones': $seccionKey = 'varones'; break;
                    case 'admin_juveniles': $seccionKey = 'juveniles'; break;
                    case 'admin_niños': $seccionKey = 'niños'; break;
                    case 'admin_intercesion': $seccionKey = 'intercesion'; break;
                    default: $seccionKey = 'general';
                }
                
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $titulo)) . '-' . time();
                
                $sql = "INSERT INTO publicaciones (titulo, contenido, seccion, imagen, autor_id, slug) VALUES (:titulo, :contenido, :seccion, :imagen, :autor_id, :slug)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
                $stmt->bindValue(':contenido', $contenido, PDO::PARAM_STR);
                $stmt->bindValue(':seccion', $seccionKey, PDO::PARAM_STR);
                $stmt->bindValue(':imagen', $imagen, PDO::PARAM_STR);
                $stmt->bindValue(':autor_id', $user['id'], PDO::PARAM_INT);
                $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
                
                if ($stmt->execute() && $pdo->lastInsertId()) {
                    $success = 'Publicación creada exitosamente.';
                } else {
                    $error = 'Error al crear la publicación.';
                }
            } else {
                $postId = (int)$_POST['post_id'];
                
                if (!$imagen) {
                    $stmt = $pdo->prepare("SELECT imagen FROM publicaciones WHERE id = ? AND seccion = ?");
                    $stmt->execute([$postId, $section['key']]);
                    $existing = $stmt->fetch();
                    $imagen = $existing['imagen'] ?? null;
                }
                
                $sql = "UPDATE publicaciones SET titulo = ?, contenido = ?, imagen = ? WHERE id = ? AND seccion = ?";
                $pdo->prepare($sql)->execute([$titulo, $contenido, $imagen, $postId, $section['key']]);
                $success = 'Publicación actualizada.';
            }
        }
    } elseif ($action === 'delete') {
        $postId = (int)$_POST['post_id'];
        $pdo->prepare("DELETE FROM publicaciones WHERE id = ? AND seccion = ?")->execute([$postId, $section['key']]);
        $success = 'Publicación eliminada.';
    } elseif ($action === 'update_section_config') {
        $titulo = trim($_POST['header_title'] ?? '');
        $subtitulo = trim($_POST['header_subtitle'] ?? '');
        $seccionKey = $section['key'];
        
        $imagen = $sectionConfig['header_image_url'] ?? null;

        if (!empty($_FILES['header_image']['name'])) {
            // Delete old image if it exists
            if (!empty($imagen)) {
                deleteFile($imagen);
            }
            
            $uploadResult = uploadFile($_FILES['header_image'], 'headers');
            if (isset($uploadResult['success'])) {
                $imagen = $uploadResult['path'];
            } else {
                $error = $uploadResult['error'];
            }
        }

        if (!$error) {
            try {
                $stmt = $pdo->prepare("UPDATE seccion_config SET header_title = ?, header_subtitle = ?, header_image_url = ? WHERE seccion_key = ?");
                $stmt->execute([$titulo, $subtitulo, $imagen, $seccionKey]);
                $success = 'La configuración de la sección ha sido actualizada.';
                // Refresh configuration data with force refresh to clear cache
                $sectionConfig = getSectionConfig($section['key'], true);
            } catch (Exception $e) {
                $error = 'Error al actualizar la configuración: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'delete_header_image') {
        $seccionKey = $section['key'];
        $currentImage = $sectionConfig['header_image_url'] ?? null;
        
        if (!empty($currentImage)) {
            // Delete the file from server
            $deleteResult = deleteFile($currentImage);
            
            if (isset($deleteResult['success']) || isset($deleteResult['error'])) {
                // Proceed to clear from database regardless of file deletion status
                try {
                    $stmt = $pdo->prepare("UPDATE seccion_config SET header_image_url = NULL WHERE seccion_key = ?");
                    $stmt->execute([$seccionKey]);
                    $success = 'Imagen eliminada. Se restauró el color de fondo original.';
                    // Refresh configuration data with force refresh
                    $sectionConfig = getSectionConfig($section['key'], true);
                } catch (Exception $e) {
                    $error = 'Error al eliminar la imagen: ' . $e->getMessage();
                }
            }
        } else {
            $error = 'No hay imagen para eliminar.';
        }
    }
    
    // Prayer request actions (only for intercesion)
    if ($section['key'] === 'intercesion') {
        if ($action === 'update_estado_peticion') {
            $peticionId = (int)$_POST['peticion_id'];
            $estado = $_POST['estado'] ?? 'pendiente';
            $pdo->prepare("UPDATE intercesion SET estado = ? WHERE id = ?")->execute([$estado, $peticionId]);
            $success = 'Estado de petición actualizado.';
        } elseif ($action === 'delete_peticion') {
            $peticionId = (int)$_POST['peticion_id'];
            $pdo->prepare("DELETE FROM intercesion WHERE id = ?")->execute([$peticionId]);
            $success = 'Petición eliminada.';
        }
    }
}

// Get publications for this section
$stmt = $pdo->prepare("SELECT * FROM publicaciones WHERE seccion = ? ORDER BY created_at DESC");
$stmt->execute([$section['key']]);
$publicaciones = $stmt->fetchAll();
$pubCount = count($publicaciones);

// For intercesion, get prayer requests
$peticiones = [];
$peticionesCount = 0;
$peticionesPendientes = 0;
if ($section['key'] === 'intercesion') {
    $stmt = $pdo->query("SELECT * FROM intercesion ORDER BY created_at DESC");
    $peticiones = $stmt->fetchAll();
    $peticionesCount = count($peticiones);
    $peticionesPendientes = count(array_filter($peticiones, fn($p) => $p['estado'] === 'pendiente'));
}

$pageTitle = 'Panel de ' . $section['name'];
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
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background-color: #f8fafc; }
        .navbar-brand { font-weight: 600; }
        .stat-card { border: none; border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .pub-card { border: none; border-radius: 12px; transition: all 0.2s; overflow: hidden; }
        .pub-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .pub-card .card-img-top { height: 180px; object-fit: cover; }
        .pub-placeholder { height: 180px; display: flex; align-items: center; justify-content: center; }
        .pet-card { border: none; border-radius: 12px; border-left: 4px solid; }
        .pet-pending { border-left-color: #f59e0b; }
        .pet-approved { border-left-color: #10b981; }
        .pet-rejected { border-left-color: #ef4444; }
        .section-title { font-weight: 600; color: #1e293b; }
        .btn-action { border-radius: 8px; }
        .empty-state { padding: 60px 20px; }
        .empty-state i { font-size: 4rem; opacity: 0.2; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-approved { background-color: #d1fae5; color: #065f46; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .nav-pills .nav-link { border-radius: 10px; font-weight: 500; }
        .nav-pills .nav-link.active { background: <?= $section['gradient'] ?>; }
        .gradient-bg { background: <?= $section['gradient'] ?>; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/admin/secciones.php">
                <i class="bi <?= $section['icon'] ?> me-2"></i>Panel <?= $section['name'] ?>
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white-50 me-2 d-none d-md-inline">
                    <i class="bi bi-person-circle me-1"></i><?= sanitize($user['full_name']) ?>
                </span>
                <a href="<?= BASE_URL ?>/perfil.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-gear"></i>
                </a>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <!-- Alerts -->
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="section-title mb-1">
                    <i class="bi <?= $section['icon'] ?> me-2" style="color: #7c3aed;"></i><?= $pageTitle ?>
                </h2>
                <p class="text-muted mb-0">Gestiona el contenido de tu ministerio</p>
            </div>
            <button class="btn btn-lg gradient-bg text-white btn-action" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i>Nueva Publicación
            </button>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-<?= $section['key'] === 'intercesion' ? '4' : '6' ?>">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon gradient-bg text-white me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= $pubCount ?></h3>
                            <p class="text-muted mb-0 small">Publicaciones</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($section['key'] === 'intercesion'): ?>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= $peticionesPendientes ?></h3>
                            <p class="text-muted mb-0 small">Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="bi bi-heart-pulse fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= $peticionesCount ?></h3>
                            <p class="text-muted mb-0 small">Total Peticiones</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="bi bi-eye fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Activo</h3>
                            <p class="text-muted mb-0 small">Estado del ministerio</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Tabs -->
        <ul class="nav nav-pills mb-4" id="contentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="publicaciones-tab" data-bs-toggle="pill" data-bs-target="#publicaciones" type="button">
                    <i class="bi bi-file-text me-2"></i>Publicaciones
                </button>
            </li>
            <?php if ($section['key'] === 'intercesion'): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="peticiones-tab" data-bs-toggle="pill" data-bs-target="#peticiones" type="button">
                    <i class="bi bi-heart-pulse me-2"></i>Peticiones de Oración
                    <?php if ($peticionesPendientes > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?= $peticionesPendientes ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <?php endif; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="config-tab" data-bs-toggle="pill" data-bs-target="#configuracion" type="button">
                    <i class="bi bi-sliders me-2"></i>Configuración
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="contentTabsContent">
            <!-- Publicaciones Tab -->
            <div class="tab-pane fade show active" id="publicaciones" role="tabpanel">
        
        <!-- Publications Section -->
        <?php if (!empty($publicaciones)): ?>
        <div class="row g-4">
            <?php foreach ($publicaciones as $pub): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card pub-card shadow-sm h-100">
                    <?php if ($pub['imagen']): ?>
                    <img src="<?= BASE_URL . $pub['imagen'] ?>" class="card-img-top" alt="">
                    <?php else: ?>
                    <div class="pub-placeholder gradient-bg">
                        <i class="bi <?= $section['icon'] ?> text-white display-3 opacity-50"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><?= sanitize($pub['titulo']) ?></h6>
                        <p class="text-muted small mb-0">
                            <?= sanitize(substr(strip_tags($pub['contenido']), 0, 80)) ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i><?= formatDate($pub['created_at']) ?>
                            </small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-action" onclick="editPost(<?= htmlspecialchars(json_encode($pub)) ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta publicación?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="post_id" value="<?= $pub['id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-action">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body empty-state text-center">
                <i class="bi bi-file-earmark-plus text-muted"></i>
                <h5 class="mt-3 text-muted">No hay publicaciones</h5>
                <p class="text-muted mb-3">Crea tu primera publicación para comenzar</p>
                <button class="btn gradient-bg text-white" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg me-2"></i>Crear Publicación
                </button>
            </div>
        </div>
        <?php endif; ?>
        
            </div>
            
        <?php if ($section['key'] === 'intercesion'): ?>
            <!-- Peticiones Tab -->
            <div class="tab-pane fade" id="peticiones" role="tabpanel">
                <?php if (!empty($peticiones)): ?>
                <div class="row g-3">
                    <?php foreach ($peticiones as $pet): 
                        $estadoClass = $pet['estado'] === 'aprobada' ? 'pet-approved' : ($pet['estado'] === 'rechazada' ? 'pet-rejected' : 'pet-pending');
                        $badgeClass = $pet['estado'] === 'aprobada' ? 'badge-approved' : ($pet['estado'] === 'rechazada' ? 'badge-rejected' : 'badge-pending');
                    ?>
                    <div class="col-lg-6">
                        <div class="card pet-card shadow-sm <?= $estadoClass ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            <i class="bi bi-person-circle me-1 text-muted"></i>
                                            <?= sanitize($pet['nombre'] ?? 'Anónimo') ?>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i><?= formatDate($pet['created_at']) ?>
                                            <?php if (!empty($pet['email'])): ?>
                                            <span class="ms-2"><i class="bi bi-envelope me-1"></i><?= sanitize($pet['email']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($pet['es_privada'] ?? false): ?>
                                            <span class="ms-2"><i class="bi bi-lock-fill text-warning"></i> Privada</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($pet['estado'] ?? 'pendiente') ?></span>
                                </div>
                                <p class="mb-3"><?= nl2br(sanitize($pet['peticion'] ?? '')) ?></p>
                                <div class="d-flex gap-2">
                                    <form method="POST" class="flex-grow-1">
                                        <input type="hidden" name="action" value="update_estado_peticion">
                                        <input type="hidden" name="peticion_id" value="<?= $pet['id'] ?>">
                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pendiente" <?= ($pet['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="aprobada" <?= ($pet['estado'] ?? '') === 'aprobada' ? 'selected' : '' ?>>Aprobada</option>
                                            <option value="rechazada" <?= ($pet['estado'] ?? '') === 'rechazada' ? 'selected' : '' ?>>Rechazada</option>
                                        </select>
                                    </form>
                                    <form method="POST" onsubmit="return confirm('¿Eliminar esta petición?')">
                                        <input type="hidden" name="action" value="delete_peticion">
                                        <input type="hidden" name="peticion_id" value="<?= $pet['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body empty-state text-center">
                        <i class="bi bi-heart-pulse text-muted"></i>
                        <h5 class="mt-3 text-muted">No hay peticiones de oración</h5>
                        <p class="text-muted">Las peticiones enviadas desde el sitio público aparecerán aquí</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

            <!-- Configuración Tab -->
            <div class="tab-pane fade" id="configuracion" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Configuración de la Página de <?= $section['name'] ?></h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update_section_config">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Título Principal</label>
                                        <input type="text" name="header_title" class="form-control form-control-lg" value="<?= sanitize($sectionConfig['header_title'] ?? '') ?>" placeholder="Título que aparecerá en la cabecera">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Subtítulo</label>
                                        <textarea name="header_subtitle" class="form-control" rows="4" placeholder="Texto descriptivo corto bajo el título"><?= sanitize($sectionConfig['header_subtitle'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Imagen de Cabecera</label>
                                    <input type="file" name="header_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Recomendado: 1920x1080px. Si no se sube una, se usará el color de fondo.</small>
                                    
                                    <?php if (!empty($sectionConfig['header_image_url'])): ?>
                                    <div class="mt-3">
                                        <p class="mb-2 small">Imagen actual:</p>
                                        <img src="<?= BASE_URL . $sectionConfig['header_image_url'] ?>" alt="Imagen de cabecera" class="img-fluid rounded" style="max-height: 150px;">
                                        <form method="POST" class="mt-2" onsubmit="return confirm('¿Eliminar la imagen de encabezado? Se restaurará el color de fondo original.')">
                                            <input type="hidden" name="action" value="delete_header_image">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i>Eliminar Imagen
                                            </button>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn gradient-bg text-white btn-lg"><i class="bi bi-save me-2"></i>Guardar Configuración</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header gradient-bg text-white border-0">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Publicación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título *</label>
                            <input type="text" name="titulo" class="form-control form-control-lg" required placeholder="Título de la publicación">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contenido *</label>
                            <textarea name="contenido" class="form-control" rows="6" required placeholder="Escribe el contenido..."></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Imagen (opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn gradient-bg text-white">
                            <i class="bi bi-check-lg me-2"></i>Publicar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header gradient-bg text-white border-0">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Editar Publicación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="post_id" id="edit_post_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título *</label>
                            <input type="text" name="titulo" id="edit_titulo" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contenido *</label>
                            <textarea name="contenido" id="edit_contenido" class="form-control" rows="6" required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Nueva Imagen (opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn gradient-bg text-white">
                            <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editPost(post) {
            document.getElementById('edit_post_id').value = post.id;
            document.getElementById('edit_titulo').value = post.titulo;
            document.getElementById('edit_contenido').value = post.contenido;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>
