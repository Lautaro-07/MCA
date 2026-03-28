<?php
$pageTitle = 'Intercesión';
$seccion = 'intercesion';
require_once __DIR__ . '/includes/functions.php';

$pdo = getConnection();
$successMessage = '';
$errorMessage = '';

// Handle prayer request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['peticion'])) {
    $nombre = trim($_POST['nombre'] ?? '') ?: 'Anónimo';
    $email = trim($_POST['email'] ?? '');
    $peticion = trim($_POST['peticion'] ?? '');
    $es_privada = isset($_POST['es_privada']) ? 1 : 0;
    
    if (!empty($peticion)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO intercesion (nombre, email, peticion, es_privada, estado) VALUES (?, ?, ?, ?, 'pendiente')");
            $result = $stmt->execute([$nombre, $email, $peticion, $es_privada]);
            if ($result) {
                $successMessage = '¡Gracias! Tu petición de oración ha sido enviada. Será revisada por nuestro equipo.';
            } else {
                $errorMessage = 'Error al enviar la petición. Por favor intenta de nuevo.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Error al enviar la petición. Por favor intenta de nuevo.';
        }
    } else {
        $errorMessage = 'Por favor escribe tu petición de oración.';
    }
}

require_once __DIR__ . '/includes/header.php';

// Get publications for this section
$sectionConfig = getSectionConfig($seccion);
$publicaciones = getPublications($seccion, 12);

// Get approved prayer requests
$stmt = $pdo->query("SELECT * FROM intercesion WHERE estado = 'aprobada' AND es_privada = 0 ORDER BY created_at DESC LIMIT 10");
$prayerRequests = $stmt->fetchAll();

$heroStyle = 'min-height: 50vh; background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #c084fc 100%);';
if (!empty($sectionConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $sectionConfig['header_image_url'];
    $heroStyle = "min-height: 50vh; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
$heroTitle = $sectionConfig['header_title'] ?? 'Ministerio de Intercesión';
$heroSubtitle = $sectionConfig['header_subtitle'] ?? 'Un ejército de oración que levanta las necesidades de nuestra comunidad ante el trono de la gracia.';
?>

<!-- Hero Section -->
<section class="hero-section text-white" style="<?= $heroStyle ?>">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-center text-center" style="min-height: 50vh;">
            <div class="col-lg-8 hero-content" data-aos="fade-up">
                <h1 class="hero-title display-heading"><?= sanitize($heroTitle) ?></h1>
                <p class="hero-subtitle mx-auto">
                    <?= sanitize($heroSubtitle) ?>
                </p>
                <button class="btn btn-warning btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#prayerModal">
                    <i class="bi bi-plus-circle me-2"></i>Enviar Petición de Oración
                </button>
            </div>
        </div>
    </div>
</section>

<?php if ($successMessage): ?>
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($successMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<?php if ($errorMessage): ?>
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errorMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- About Ministry -->
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-purple bg-opacity-10 text-purple mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-heart-pulse me-1"></i> Intercesión MCA
                </span>
                <h2 class="display-5 fw-bold mb-4">Orando por Nuestra Comunidad</h2>
                <p class="lead text-muted mb-4">
                    Creemos en el poder de la oración. Nuestro ministerio de intercesión se dedica a levantar 
                    las necesidades de nuestra iglesia, familias y comunidad ante Dios cada semana.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Oración semanal</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Cadenas de oración</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Ayuno y oración</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Apoyo espiritual</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="rounded-4 p-5 text-white text-center" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                    <i class="bi bi-quote display-4 mb-3 opacity-50"></i>
                    <p class="fs-5 mb-4">
                        "Por nada estéis afanosos, sino sean conocidas vuestras peticiones delante de Dios 
                        en toda oración y ruego, con acción de gracias."
                    </p>
                    <p class="fw-bold mb-0">— Filipenses 4:6</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Requests Section -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill">
                <i class="bi bi-heart me-1"></i> Peticiones
            </span>
            <h2>Motivos de Oración</h2>
            <p>Únete a nosotros en oración por estas necesidades</p>
        </div>
        
        <?php if (!empty($prayerRequests)): ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ($prayerRequests as $prayer): ?>
            <div class="col-lg-6" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-purple bg-opacity-10 p-3 me-3">
                                <i class="bi bi-person-heart text-purple fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1"><?= sanitize($prayer['nombre'] ?? 'Anónimo') ?></h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i><?= formatDate($prayer['created_at']) ?>
                                </small>
                            </div>
                        </div>
                        <p class="mb-0 text-muted"><?= nl2br(sanitize($prayer['peticion'])) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted opacity-25"></i>
            <h4 class="mt-4 text-muted">No hay peticiones aprobadas actualmente</h4>
            <p class="text-muted">Sé el primero en compartir tu motivo de oración.</p>
            <button class="btn btn-purple btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#prayerModal">
                <i class="bi bi-plus-circle me-2"></i>Enviar Petición
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Publications -->
<section class="py-5">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Reflexiones y Mensajes</h2>
            <p>Contenido para edificar tu vida de oración</p>
        </div>
        
        <?php if (!empty($publicaciones)): ?>
        <div class="row g-4">
            <?php foreach ($publicaciones as $post): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card blog-card h-100">
                    <?php if ($post['imagen']): ?>
                    <img src="<?= BASE_URL . $post['imagen'] ?>" class="card-img-top" alt="<?= sanitize($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                        <i class="bi bi-heart-pulse text-white display-4"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['created_at']) ?></span>
                        </div>
                        <h5 class="card-title"><?= sanitize($post['titulo']) ?></h5>
                        <p class="card-text"><?= sanitize(substr(strip_tags($post['contenido']), 0, 100)) ?>...</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $post['id'] ?>" class="btn btn-outline-purple btn-sm">
                            Leer Más <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-newspaper display-1 text-muted opacity-25"></i>
            <h4 class="mt-4 text-muted">Próximamente</h4>
            <p class="text-muted">Pronto compartiremos contenido de nuestro ministerio.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
    <div class="container py-4 text-center">
        <h3 class="fw-bold mb-3">¿Tienes una necesidad de oración?</h3>
        <p class="opacity-90 mb-4">Compártela con nosotros y oremos juntos</p>
        <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#prayerModal">
            <i class="bi bi-heart me-2"></i>Enviar Petición
        </button>
    </div>
</section>

<!-- Prayer Request Modal -->
<div class="modal fade" id="prayerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-heart-pulse me-2"></i>
                    Enviar Petición de Oración
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/intercesion.php">
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">
                        Comparte tu motivo de oración con nosotros. Nuestro equipo de intercesores orará por ti.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person me-1 text-muted"></i>Tu Nombre
                        </label>
                        <input type="text" name="nombre" class="form-control form-control-lg" placeholder="Opcional - puedes ser anónimo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1 text-muted"></i>Email
                        </label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Opcional - para seguimiento">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-chat-heart me-1 text-muted"></i>Tu Petición <span class="text-danger">*</span>
                        </label>
                        <textarea name="peticion" class="form-control" rows="4" required placeholder="Escribe tu motivo de oración aquí..."></textarea>
                    </div>
                    <div class="form-check bg-light p-3 rounded">
                        <input type="checkbox" name="es_privada" class="form-check-input" id="privada" value="1">
                        <label class="form-check-label" for="privada">
                            <i class="bi bi-lock me-1"></i>
                            Mantener mi petición privada <small class="text-muted">(solo verán los líderes)</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-purple btn-lg">
                        <i class="bi bi-send me-2"></i>Enviar Petición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-purple { background-color: #8b5cf6 !important; }
.text-purple { color: #8b5cf6 !important; }
.btn-purple {
    background-color: #8b5cf6;
    border-color: #8b5cf6;
    color: white;
}
.btn-purple:hover {
    background-color: #7c3aed;
    border-color: #7c3aed;
    color: white;
}
.btn-outline-purple {
    color: #8b5cf6;
    border-color: #8b5cf6;
}
.btn-outline-purple:hover {
    background-color: #8b5cf6;
    border-color: #8b5cf6;
    color: white;
}
.blog-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.blog-card .card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
