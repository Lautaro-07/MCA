<?php
$pageTitle = 'Mujeres';
$seccion = 'mujeres';
require_once __DIR__ . '/includes/header.php';
$publicaciones = getPublications($seccion, 12);
$sectionConfig = getSectionConfig($seccion);

$heroStyle = 'min-height: 50vh; background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);';
if (!empty($sectionConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $sectionConfig['header_image_url'];
    $heroStyle = "min-height: 50vh; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
$heroTitle = $sectionConfig['header_title'] ?? 'Ministerio de Mujeres';
$heroSubtitle = $sectionConfig['header_subtitle'] ?? 'Fortaleza, gracia y sabiduría divina. Mujeres que inspiran y transforman su entorno.';
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
            </div>
        </div>
    </div>
</section>

<!-- About Ministry -->
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge mb-3 px-3 py-2 rounded-pill" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                    <i class="bi bi-flower1 me-1"></i> Mujeres MCA
                </span>
                <h2 class="display-5 fw-bold mb-4">Mujeres de Valor</h2>
                <p class="lead text-muted mb-4">
                    Un espacio donde las mujeres encuentran comunidad, crecimiento espiritual y apoyo mutuo. 
                    Juntas caminamos en fe, superando desafíos y celebrando victorias.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Estudios bíblicos</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Retiros espirituales</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Mentoría</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Comunidad de apoyo</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="rounded-4 p-5 text-white text-center" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
                    <i class="bi bi-quote display-4 mb-3 opacity-50"></i>
                    <p class="fs-5 mb-4">
                        "Engañosa es la gracia, y vana la hermosura; la mujer que teme a Jehová, esa será alabada."
                    </p>
                    <p class="fw-bold mb-0">— Proverbios 31:30</p>
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
            <p>Contenido para fortalecer tu vida espiritual</p>
        </div>
        
        <?php if (!empty($publicaciones)): ?>
        <div class="row g-4">
            <?php foreach ($publicaciones as $post): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card blog-card h-100">
                    <?php if ($post['imagen']): ?>
                    <img src="<?= BASE_URL . $post['imagen'] ?>" class="card-img-top" alt="<?= sanitize($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #ec4899, #f43f5e);">
                        <i class="bi bi-flower1 text-white display-4"></i>
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
                        <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $post['id'] ?>" class="btn btn-sm" style="border-color: #ec4899; color: #ec4899;">
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
<section class="py-5 text-white" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
    <div class="container py-4 text-center">
        <h3 class="fw-bold mb-3">¿Quieres ser parte?</h3>
        <p class="opacity-90 mb-4">Únete a nuestra comunidad de mujeres de fe</p>
        <a href="<?= BASE_URL ?>/formularios.php" class="btn btn-light btn-lg">
            <i class="bi bi-envelope me-2"></i>Contáctanos
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
