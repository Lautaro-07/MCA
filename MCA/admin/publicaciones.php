<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin', 'admin_jovenes', 'admin_mujeres', 'admin_varones', 'admin_juveniles', 'admin_niños', 'admin_intercesion']);

$pdo = getConnection();
$user = getCurrentUser();
$error = '';
$success = '';

// Determine allowed sections based on role
$allowedSections = [];
if ($user['role'] === 'admin') {
    $allowedSections = ['jovenes', 'mujeres', 'varones', 'juveniles', 'niños', 'intercesion'];
} else {
    $sectionMap = [
        'admin_jovenes' => 'jovenes',
        'admin_mujeres' => 'mujeres',
        'admin_varones' => 'varones',
        'admin_juveniles' => 'juveniles',
        'admin_niños' => 'niños',
        'admin_intercesion' => 'intercesion',
    ];
    $allowedSections = [$sectionMap[$user['role']] ?? ''];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');
        $seccion = $_POST['seccion'] ?? '';
                
        if (empty($titulo) || empty($contenido) || empty($seccion)) {
            $error = 'Título, contenido y sección son obligatorios.';
        } elseif (!in_array($seccion, $allowedSections)) {
            $error = 'No tienes permiso para publicar en esta sección.';
        } else {
            // Handle image upload
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
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $titulo)) . '-' . time();
                $sql = "INSERT INTO publicaciones (titulo, contenido, seccion, imagen, autor_id, slug) VALUES (?, ?, ?, ?, ?, ?)";
                $pdo->prepare($sql)->execute([$titulo, $contenido, $seccion, $imagen, $user['id'], $slug]);
                logActivity($user['id'], 'crear_publicacion', "Publicación creada: $titulo");
                $success = 'Publicación creada exitosamente.';
            } else {
                $postId = (int)$_POST['post_id'];
                
                // Keep existing image if no new one uploaded
                if (!$imagen) {
                    $stmt = $pdo->prepare("SELECT imagen FROM publicaciones WHERE id = ?");
                    $stmt->execute([$postId]);
                    $existing = $stmt->fetch();
                    $imagen = $existing['imagen'] ?? null;
                }
                
                $sql = "UPDATE publicaciones SET titulo = ?, contenido = ?, seccion = ?, imagen = ? WHERE id = ?";
                $pdo->prepare($sql)->execute([$titulo, $contenido, $seccion, $imagen, $postId]);
                logActivity($user['id'], 'actualizar_publicacion', "Publicación actualizada: ID $postId");
                $success = 'Publicación actualizada.';
            }
        }
    } elseif ($action === 'delete') {
        $postId = (int)$_POST['post_id'];
        $pdo->prepare("DELETE FROM publicaciones WHERE id = ?")->execute([$postId]);
        logActivity($user['id'], 'eliminar_publicacion', "Publicación eliminada: ID $postId");
        $success = 'Publicación eliminada.';
    }
}

// Get publications
$seccionFilter = $_GET['seccion'] ?? '';
$sql = "SELECT p.*, u.full_name as autor_nombre FROM publicaciones p JOIN users u ON p.autor_id = u.id";
$params = [];

if ($user['role'] !== 'admin') {
    $sql .= " WHERE p.seccion IN (" . implode(',', array_fill(0, count($allowedSections), '?')) . ")";
    $params = $allowedSections;
} elseif ($seccionFilter && in_array($seccionFilter, $allowedSections)) {
    $sql .= " WHERE p.seccion = ?";
    $params = [$seccionFilter];
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$publicaciones = $stmt->fetchAll();

$pageTitle = 'Gestionar Publicaciones';
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
    <link href="<?= BASE_URL ?>/public/css/diseño.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Administra publicaciones del blog</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Publicación
            </button>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Filter -->
        <?php if ($user['role'] === 'admin'): ?>
        <div class="mb-4">
            <div class="btn-group">
                <a href="<?= BASE_URL ?>/admin/publicaciones.php" class="btn btn-outline-primary <?= empty($seccionFilter) ? 'active' : '' ?>">Todas</a>
                <?php foreach ($allowedSections as $sec): ?>
                <a href="<?= BASE_URL ?>/admin/publicaciones.php?seccion=<?= $sec ?>" class="btn btn-outline-primary <?= $seccionFilter === $sec ? 'active' : '' ?>">
                    <?= ucfirst($sec) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Sección</th>
                        <th>Autor</th>
                                                <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publicaciones as $pub): ?>
                    <tr>
                        <td class="fw-medium"><?= sanitize(substr($pub['titulo'], 0, 50)) ?><?= strlen($pub['titulo']) > 50 ? '...' : '' ?></td>
                        <td><span class="badge bg-primary"><?= ucfirst($pub['seccion']) ?></span></td>
                        <td><?= sanitize($pub['autor_nombre']) ?></td>
                                                <td class="text-muted"><?= formatDate($pub['created_at']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $pub['id'] ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $pub['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta publicación?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="post_id" value="<?= $pub['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $pub['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Publicación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="post_id" value="<?= $pub['id'] ?>">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label">Título *</label>
                                                <input type="text" name="titulo" class="form-control" value="<?= sanitize($pub['titulo']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Sección *</label>
                                                <select name="seccion" class="form-select" required>
                                                    <?php foreach ($allowedSections as $sec): ?>
                                                    <option value="<?= $sec ?>" <?= $pub['seccion'] === $sec ? 'selected' : '' ?>><?= ucfirst($sec) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Contenido *</label>
                                                <textarea name="contenido" class="form-control" rows="8" required><?= sanitize($pub['contenido']) ?></textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Imagen</label>
                                                <input type="file" name="imagen" class="form-control" accept="image/*">
                                                <?php if ($pub['imagen']): ?>
                                                <small class="text-muted">Imagen actual: <?= basename($pub['imagen']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($publicaciones)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay publicaciones</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Título *</label>
                                <input type="text" name="titulo" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sección *</label>
                                <select name="seccion" class="form-select" required>
                                    <?php foreach ($allowedSections as $sec): ?>
                                    <option value="<?= $sec ?>"><?= ucfirst($sec) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Contenido *</label>
                                <textarea name="contenido" class="form-control" rows="8" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
