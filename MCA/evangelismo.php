<?php
$pageTitle = 'Evangelismo';
$seccion = 'evangelismo';
require_once __DIR__ . '/includes/header.php';
$publicaciones = getPublications($seccion, 12);
$sectionConfig = getSectionConfig($seccion);

$heroStyle = 'min-height: 50vh; background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);';
if (!empty($sectionConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $sectionConfig['header_image_url'];
    $heroStyle = "min-height: 50vh; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
$heroTitle = $sectionConfig['header_title'] ?? 'Evangelismo';
$heroSubtitle = $sectionConfig['header_subtitle'] ?? 'Noticias, testimonios y recursos para alcanzar a los perdidos con el mensaje de salvación.';
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

<!-- Publications -->
<section class="py-5">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Noticias y Testimonios</h2>
            <p>Lo que Dios está haciendo a través de nuestra comunidad</p>
        </div>
        
        <?php if (!empty($publicaciones)): ?>
        <div class="row g-4">
            <?php foreach ($publicaciones as $post): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card blog-card h-100">
                    <?php if ($post['imagen']): ?>
                    <img src="<?= BASE_URL . $post['imagen'] ?>" class="card-img-top" alt="<?= sanitize($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #06b6d4, #0284c7);">
                        <i class="bi bi-megaphone-fill text-white display-4"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['created_at']) ?></span>
                            <span><i class="bi bi-person me-1"></i><?= sanitize($post['autor_nombre']) ?></span>
                        </div>
                        <h5 class="card-title"><?= sanitize($post['titulo']) ?></h5>
                        <p class="card-text"><?= sanitize(substr(strip_tags($post['contenido']), 0, 120)) ?>...</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $post['id'] ?>" class="btn btn-outline-info btn-sm">
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
            <p class="text-muted">Pronto compartiremos testimonios y noticias de evangelismo.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Stats -->
<section class="py-5" style="background: linear-gradient(135deg, #06b6d4, #0284c7);">
    <div class="container">
        <div class="row g-4 text-center text-white">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Decisiones de Fe</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-number">25</div>
                    <div class="stat-label">Campañas Este Año</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Personas Alcanzadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Barrios Visitados</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-dark-section">
    <div class="container py-4 text-center">
        <h3 class="fw-bold mb-3">¿Quieres participar en evangelismo?</h3>
        <p class="opacity-75 mb-4">Únete a nuestros equipos de alcance y sé parte del movimiento</p>
        <a href="<?= BASE_URL ?>/formularios.php" class="btn btn-warning btn-lg">
            <i class="bi bi-hand-thumbs-up me-2"></i>Quiero Participar
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
