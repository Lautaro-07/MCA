<?php
$pageTitle = 'Niños';
$seccion = 'niños';
require_once __DIR__ . '/includes/header.php';
$publicaciones = getPublications($seccion, 12);
$sectionConfig = getSectionConfig($seccion);

$heroStyle = 'min-height: 50vh; background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);';
if (!empty($sectionConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $sectionConfig['header_image_url'];
    $heroStyle = "min-height: 50vh; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
$heroTitle = $sectionConfig['header_title'] ?? 'Niños';
$heroSubtitle = $sectionConfig['header_subtitle'] ?? 'Formando desde temprano líderes apasionados por Dios. Un espacio de crecimiento, diversión y fe.';
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
                <div class="section-hero-logo">
                    <img src="<?= BASE_URL ?>/uploads/logos/ni%C3%B1os.png" alt="Logo Niños" class="site-logo section-logo-image">
                </div>
                <h1 class="hero-title display-heading"><?= sanitize($heroTitle) ?></h1>
                <p class="hero-subtitle mx-auto">
                    <?= sanitize($heroSubtitle) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About Ministry -->
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-stars me-1"></i> Ministerio de Niños
                </span>
                <h2 class="display-5 fw-bold mb-4">Los Pequeños Son Importantes</h2>
                <p class="lead text-muted mb-4">
                    Un ministerio dedicado a formar a nuestros niños en la fe y el amor de Dios. 
                    Creemos que nunca es muy temprano para conocer a Jesús y desarrollar una 
                    relación personal con Él.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Escuela Dominical</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Actividades recreativas</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Campamentos</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Enseñanzas bíblicas</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="rounded-4 p-5 text-white text-center" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                    <i class="bi bi-quote display-4 mb-3 opacity-50"></i>
                    <p class="fs-5 mb-4">
                        "Instruye al niño en su camino, y aun cuando fuere viejo no se apartará de él."
                    </p>
                    <p class="fw-bold mb-0">— Proverbios 22:6</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Publications -->
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Noticias y Recursos</h2>
            <p>Contenido educativo y divertido para los niños de la iglesia</p>
        </div>
        
        <?php if (!empty($publicaciones)): ?>
        <div class="row g-4">
            <?php foreach ($publicaciones as $post): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card blog-card h-100">
                    <?php if ($post['imagen']): ?>
                    <img src="<?= BASE_URL . $post['imagen'] ?>" class="card-img-top" alt="<?= sanitize($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                        <i class="bi bi-stars text-white display-4"></i>
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
                        <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm">
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
            <p class="text-muted">Pronto compartiremos contenido de nuestro ministerio de niños.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
