<?php
$pageTitle = 'Formularios';
require_once __DIR__ . '/includes/header.php';

$pdo = getConnection();
$currentUser = getCurrentUser();

// Only allow admin, supervisor, and lider roles
if (!$currentUser || !in_array($currentUser['role'], ['admin', 'supervisor', 'lider'])) {
    setFlash('error', 'No tienes permiso para acceder a esta sección. Solo líderes, supervisores y administradores pueden ver los formularios.');
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Get active forms
$sql = "SELECT * FROM formularios WHERE esta_activo = 1 ORDER BY created_at DESC";
$formularios = $pdo->query($sql)->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formulario_id'])) {
    $formularioId = (int)$_POST['formulario_id'];
    $respuestas = json_encode($_POST['respuestas'] ?? []);
    $userId = $currentUser ? $currentUser['id'] : null;
    
    $stmt = $pdo->prepare("INSERT INTO respuestas_formularios (formulario_id, user_id, respuestas, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$formularioId, $userId, $respuestas, $_SERVER['REMOTE_ADDR']]);
    
    setFlash('success', '¡Formulario enviado correctamente! Gracias por tu respuesta.');
    header('Location: ' . BASE_URL . '/formularios.php');
    exit;
}
?>

<!-- Hero Section -->
<section class="hero-section text-white" style="min-height: 50vh; background: linear-gradient(135deg, #1f2937 0%, #374151 100%);">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-center text-center" style="min-height: 50vh;">
            <div class="col-lg-8 hero-content" data-aos="fade-up">
                <div class="mb-4">
                    <i class="bi bi-file-earmark-text-fill display-2 text-warning"></i>
                </div>
                <h1 class="hero-title display-heading">Formularios</h1>
                <p class="hero-subtitle mx-auto">
                    Completa nuestros formularios para inscripciones, contacto y más.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Forms List -->
<section class="py-5">
    <div class="container py-5">
        <?php if (!empty($formularios)): ?>
        <div class="row g-4">
            <?php foreach ($formularios as $form): ?>
            <div class="col-lg-6" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-clipboard-check text-primary fs-5"></i>
                            </div>
                            <?php if ($form['fecha_limite']): ?>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>
                                Hasta <?= formatDate($form['fecha_limite']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold"><?= sanitize($form['titulo']) ?></h5>
                        <p class="text-muted mb-4"><?= sanitize($form['descripcion']) ?></p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal<?= $form['id'] ?>">
                            <i class="bi bi-pencil-square me-2"></i>Completar Formulario
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Modal for each form -->
            <div class="modal fade" id="formModal<?= $form['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold"><?= sanitize($form['titulo']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="">
                            <div class="modal-body">
                                <?php if ($form['descripcion']): ?>
                                <p class="text-muted mb-4"><?= sanitize($form['descripcion']) ?></p>
                                <?php endif; ?>
                                
                                <input type="hidden" name="formulario_id" value="<?= $form['id'] ?>">
                                                                
                                <?php 
                                $campos = json_decode($form['campos'], true) ?? [];
                                foreach ($campos as $index => $campo): 
                                ?>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">
                                        <?= sanitize($campo['name']) ?>
                                        <?php if (!empty($campo['required'])): ?>
                                        <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </label>
                                    
                                    <?php if ($campo['type'] === 'textarea'): ?>
                                    <textarea name="respuestas[<?= $index ?>]" class="form-control" rows="3" 
                                        <?= !empty($campo['required']) ? 'required' : '' ?>></textarea>
                                    
                                    <?php elseif ($campo['type'] === 'select' && !empty($campo['options'])): ?>
                                    <select name="respuestas[<?= $index ?>]" class="form-select" 
                                        <?= !empty($campo['required']) ? 'required' : '' ?>>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($campo['options'] as $opt): ?>
                                        <option value="<?= sanitize($opt) ?>"><?= sanitize($opt) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <?php elseif ($campo['type'] === 'checkbox'): ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="respuestas[<?= $index ?>]" class="form-check-input" value="1"
                                            <?= !empty($campo['required']) ? 'required' : '' ?>>
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    
                                    <?php elseif ($campo['type'] === 'radio' && !empty($campo['options'])): ?>
                                    <?php foreach ($campo['options'] as $opt): ?>
                                    <div class="form-check">
                                        <input type="radio" name="respuestas[<?= $index ?>]" class="form-check-input" 
                                            value="<?= sanitize($opt) ?>" <?= !empty($campo['required']) ? 'required' : '' ?>>
                                        <label class="form-check-label"><?= sanitize($opt) ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <?php else: ?>
                                    <input type="<?= $campo['type'] ?? 'text' ?>" name="respuestas[<?= $index ?>]" 
                                        class="form-control" <?= !empty($campo['required']) ? 'required' : '' ?>>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Enviar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-x display-1 text-muted opacity-25"></i>
            <h4 class="mt-4 text-muted">No hay formularios disponibles</h4>
            <p class="text-muted">Pronto tendremos formularios para que puedas completar.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Contact Info -->
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-envelope me-1"></i> Contáctanos
                </span>
                <h2 class="display-5 fw-bold mb-4">¿Tienes alguna pregunta?</h2>
                <p class="lead text-muted mb-4">
                    Si no encuentras el formulario que necesitas o tienes alguna consulta, 
                    no dudes en contactarnos directamente.
                </p>
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-envelope text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted">Email</small>
                        <p class="mb-0 fw-medium"><?= getSetting('site_email', 'contacto@mca.org') ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-telephone text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted">Teléfono</small>
                        <p class="mb-0 fw-medium"><?= getSetting('site_phone', '+56 9 1234 5678') ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-geo-alt text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted">Dirección</small>
                        <p class="mb-0 fw-medium"><?= getSetting('site_address', 'Santiago, Chile') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h5 class="fw-bold mb-4">Envíanos un mensaje</h5>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Asunto</label>
                                <select name="asunto" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="consulta">Consulta General</option>
                                    <option value="oracion">Petición de Oración</option>
                                    <option value="inscripcion">Inscripción</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea name="mensaje" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
