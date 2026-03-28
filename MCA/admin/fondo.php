<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Get or create fondo config
$sectionKey = 'inicio';
$fondoConfig = getSectionConfig($sectionKey, true);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_fondo') {
        $currentImage = $fondoConfig['header_image_url'] ?? null;
        
        if (!empty($_FILES['fondo_image']['name'])) {
            // Delete old image if it exists
            if (!empty($currentImage)) {
                deleteFile($currentImage);
            }
            
            $uploadResult = uploadFile($_FILES['fondo_image'], 'fondo');
            if (isset($uploadResult['success'])) {
                $imagenPath = $uploadResult['path'];
                
                try {
                    $stmt = $pdo->prepare("UPDATE seccion_config SET header_image_url = ? WHERE seccion_key = ?");
                    $stmt->execute([$imagenPath, $sectionKey]);
                    $success = 'Imagen de fondo actualizada correctamente.';
                    $fondoConfig = getSectionConfig($sectionKey, true);
                } catch (Exception $e) {
                    $error = 'Error al guardar en la base de datos: ' . $e->getMessage();
                }
            } else {
                $error = $uploadResult['error'];
            }
        } else {
            $error = 'Por favor selecciona una imagen.';
        }
    } elseif ($action === 'delete_fondo') {
        $currentImage = $fondoConfig['header_image_url'] ?? null;
        
        if (!empty($currentImage)) {
            deleteFile($currentImage);
            
            try {
                $stmt = $pdo->prepare("UPDATE seccion_config SET header_image_url = NULL WHERE seccion_key = ?");
                $stmt->execute([$sectionKey]);
                $success = 'Imagen de fondo eliminada. Se restauró el color de fondo original.';
                $fondoConfig = getSectionConfig($sectionKey, true);
            } catch (Exception $e) {
                $error = 'Error al eliminar la imagen: ' . $e->getMessage();
            }
        } else {
            $error = 'No hay imagen para eliminar.';
        }
    }
}

$pageTitle = 'Configurar Fondo - Inicio';
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
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background-color: #f8fafc; }
        .navbar-brand { font-weight: 600; }
        .preview-container { 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
            text-align: center;
            padding: 20px;
        }
        .preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upload-box {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: rgba(102, 126, 234, 0.05);
        }
        .upload-box:hover {
            border-color: #764ba2;
            background-color: rgba(118, 75, 162, 0.05);
        }
        .upload-box i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 10px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }
        .btn-primary:hover {
            background-color: #764ba2;
            border-color: #764ba2;
        }
        .section-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .file-input {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/admin/">
                <i class="bi bi-image me-2"></i>Administración - Fondo de Inicio
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
    
    <div class="container py-5">
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
        <div class="mb-5">
            <h2 class="section-title">
                <i class="bi bi-image me-2" style="color: #667eea;"></i>Fondo de la Sección de Inicio
            </h2>
            <p class="text-muted">Personaliza la imagen de fondo de la sección principal (hero) del sitio</p>
        </div>
        
        <!-- Main Content -->
        <div class="row g-4">
            <!-- Preview -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Vista Previa</h5>
                    </div>
                    <div class="card-body">
                        <div class="preview-container">
                            <?php if (!empty($fondoConfig['header_image_url'])): ?>
                                <img src="<?= BASE_URL . htmlspecialchars($fondoConfig['header_image_url']) ?>" alt="Fondo de inicio">
                            <?php else: ?>
                                <div>
                                    <i class="bi bi-image me-2"></i><br>
                                    Gradiente por defecto
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($fondoConfig['header_image_url'])): ?>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Esta es la imagen que se mostrará en la sección de bienvenida del sitio público
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Upload Form -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Gestionar Imagen</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update_fondo">
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Selecciona una imagen</label>
                                <div class="upload-box" onclick="document.getElementById('fondo_image').click()">
                                    <input type="file" id="fondo_image" name="fondo_image" class="file-input" accept="image/*">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p class="mb-0 mt-2"><strong>Haz clic o arrastra una imagen</strong></p>
                                    <small class="text-muted d-block mt-2">
                                        Formatos: JPG, PNG, GIF, WebP<br>
                                        Tamaño máximo: 5MB
                                    </small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    Recomendaciones de imagen:
                                </small>
                                <ul class="small text-muted mb-0">
                                    <li>Resolución: 1920x1080px o superior</li>
                                    <li>Formato: JPG o PNG</li>
                                    <li>Asegúrate que sea legible con texto blanco sobre ella</li>
                                </ul>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cloud-arrow-up me-2"></i>Subir Imagen
                            </button>
                        </form>
                        
                        <?php if (!empty($fondoConfig['header_image_url'])): ?>
                        <hr class="my-4">
                        <form method="POST" onsubmit="return confirm('¿Eliminar la imagen de fondo? Se restaurará el gradiente original.')">
                            <input type="hidden" name="action" value="delete_fondo">
                            <button type="submit" class="btn btn-outline-danger btn-lg w-100">
                                <i class="bi bi-trash me-2"></i>Eliminar Imagen Actual
                            </button>
                        </form>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Última actualización: <?= formatDate($fondoConfig['updated_at']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info border-0" role="alert">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Nota:</strong> La imagen de fondo se mostrará en la sección "Bienvenidos a MCA" del sitio público. 
                    Si no subes una imagen, se mostrará un gradiente de colores por defecto.
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Drag and drop
        const uploadBox = document.querySelector('.upload-box');
        const fileInput = document.getElementById('fondo_image');
        
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = '#764ba2';
            uploadBox.style.backgroundColor = 'rgba(118, 75, 162, 0.1)';
        });
        
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.borderColor = '#667eea';
            uploadBox.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
        });
        
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = '#667eea';
            uploadBox.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const fileName = e.dataTransfer.files[0].name;
                const uploadText = uploadBox.querySelector('p');
                uploadText.textContent = 'Archivo seleccionado: ' + fileName;
            }
        });
        
        // Show filename when selected
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                const fileName = fileInput.files[0].name;
                const uploadText = uploadBox.querySelector('p');
                uploadText.textContent = 'Archivo seleccionado: ' + fileName;
            }
        });
    </script>
</body>
</html>
