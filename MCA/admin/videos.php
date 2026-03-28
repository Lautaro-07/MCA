<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$user = getCurrentUser();
$pdo = getConnection();

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $videos = [
        'video_especial' => trim($_POST['video_especial'] ?? ''),
        'video_random_1' => trim($_POST['video_random_1'] ?? ''),
        'video_random_2' => trim($_POST['video_random_2'] ?? ''),
        'video_random_3' => trim($_POST['video_random_3'] ?? ''),
        'video_random_4' => trim($_POST['video_random_4'] ?? ''),
    ];

    // Convert YouTube URLs to embed format
    foreach ($videos as $key => $url) {
        $videos[$key] = convertYouTubeToEmbed($url);
    }

    foreach ($videos as $key => $url) {
        updateSetting($key, $url);
    }

    $message = 'Videos actualizados exitosamente.';
    $messageType = 'success';

    // Log activity
    logActivity($user['id'], 'actualizar_videos', 'Videos de contenido actualizados');
}

$pageTitle = 'Gestión de Videos';
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
                <h1><i class="bi bi-play-circle me-2"></i>Gestión de Videos</h1>
                <p class="text-muted mb-0">Administra los videos que se muestran en la sección de Contenido</p>
            </div>
        </div>

        <div class="container-fluid">
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Configurar Videos</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="video_especial" class="form-label fw-bold">
                                            <i class="bi bi-star-fill text-warning me-1"></i>Video Especial (Domingos)
                                        </label>
                                        <input type="url" class="form-control" id="video_especial" name="video_especial"
                                               value="<?= getSetting('video_especial', '') ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                        <div class="form-text">URL del video destacado que se muestra en la sección de Contenido</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="video_random_1" class="form-label">Video 1</label>
                                        <input type="url" class="form-control" id="video_random_1" name="video_random_1"
                                               value="<?= getSetting('video_random_1', '') ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="video_random_2" class="form-label">Video 2</label>
                                        <input type="url" class="form-control" id="video_random_2" name="video_random_2"
                                               value="<?= getSetting('video_random_2', '') ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="video_random_3" class="form-label">Video 3</label>
                                        <input type="url" class="form-control" id="video_random_3" name="video_random_3"
                                               value="<?= getSetting('video_random_3', '') ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="video_random_4" class="form-label">Video 4</label>
                                        <input type="url" class="form-control" id="video_random_4" name="video_random_4"
                                               value="<?= getSetting('video_random_4', '') ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>Guardar Cambios
                                    </button>
                                    <a href="<?= BASE_URL ?>/admin/" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información</h5>
                        </div>
                        <div class="card-body">
                            <h6>¿Cómo obtener URLs de YouTube?</h6>
                            <ol class="small">
                                <li>Ve al video en YouTube</li>
                                <li>Haz clic en "Compartir"</li>
                                <li>Selecciona "Insertar"</li>
                                <li>Copia la URL del src del iframe</li>
                            </ol>
                            <div class="alert alert-info small">
                                <i class="bi bi-lightbulb me-1"></i>
                                Puedes pegar cualquier enlace de YouTube (watch, live, shorts, etc.) y se convertirá automáticamente al formato embed.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>