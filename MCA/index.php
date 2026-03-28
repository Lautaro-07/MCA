<?php
$pageTitle = 'Inicio';
require_once __DIR__ . '/includes/header.php';

// Get featured publications (safely)
$featuredPosts = getPublications(null, 3, true);

// Get approved prayer requests (safely)
$prayerRequests = getPrayerRequests(4, true);

// Get hero section configuration
$fondoConfig = getSectionConfig('inicio');

$heroStyle = 'min-height: 100vh; background: var(--gradient-primary);';
if (!empty($fondoConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $fondoConfig['header_image_url'];
    $heroStyle = "min-height: 100vh; background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
?>

<!-- Hero Section -->
<section class="hero-section text-white" style="<?= $heroStyle ?>">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7 hero-content" data-aos="fade-up">
                <span class="badge bg-primary text-white mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-heart-fill me-1"></i> Bienvenidos a MCA
                </span>
                <h1 class="hero-title display-heading">
                    Movilización<br>
                    <span class="text-primary">Cristiana</span>
                </h1>
                <p class="hero-subtitle">
                    Unidos en fe, transformando vidas y comunidades a través del amor de Cristo. 
                    Únete a nuestra familia y sé parte del cambio.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#ministerios" class="btn btn-primary btn-lg btn-icon">
                        <i class="bi bi-arrow-right-circle"></i>
                        Conocer Más
                    </a>
                    <a href="<?= BASE_URL ?>/nosotros.php" class="btn btn-outline-light btn-lg btn-icon">
                        <i class="bi bi-play-circle"></i>
                        Nuestra Historia
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-logo-shell text-center pulse">
                    <img src="<?= BASE_URL ?>/uploads/logos/logomca.png" alt="Logo MCA" class="site-logo hero-logo">
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll indicator -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x pb-4 text-center">
        <a href="#about" class="text-white opacity-75">
            <i class="bi bi-chevron-double-down fs-3"></i>
        </a>
    </div>
</section>

<!-- About Section Preview -->
<section id="about" class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative">
                    <div class="bg-gradient-primary rounded-4 p-5 text-white">
                        <i class="bi bi-quote display-1 opacity-25"></i>
                        <p class="fs-4 mb-4">
                            "Porque donde están dos o tres congregados en mi nombre, allí estoy yo en medio de ellos."
                        </p>
                        <p class="mb-0 fw-bold">— Mateo 18:20</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    Quiénes Somos
                </span>
                <h2 class="display-5 fw-bold mb-4">Una Comunidad de Fe y Amor</h2>
                <p class="lead text-muted mb-4">
                    Somos una iglesia comprometida con el crecimiento espiritual, la comunión fraterna 
                    y el servicio a nuestra comunidad. Creemos en el poder transformador de Dios.
                </p>
                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Grupos Familiares</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Discipulado</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Formación de Niños</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Servicio Social</span>
                        </div>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/nosotros.php" class="btn btn-primary btn-icon">
                    Conoce Nuestra Historia
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="stat-item">
                    <div class="stat-number" data-count="50">0</div>
                    <div class="stat-label">Grupos Familiares</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-number" data-count="500">0</div>
                    <div class="stat-label">Miembros Activos</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-number" data-count="100">0</div>
                    <div class="stat-label">Bautizos Este Año</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-number" data-count="15">0</div>
                    <div class="stat-label">Años de Servicio</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ministries Section -->
<section id="ministerios" class="py-5">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Nuestros Ministerios</h2>
            <p>Espacios dedicados al crecimiento espiritual de cada etapa de la vida</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <a href="<?= BASE_URL ?>/jovenes.php" class="text-decoration-none">
                    <div class="ministry-card">
                        <div class="card-bg" style="background: var(--gradient-primary);"></div>
                        <div class="card-overlay">
                            <div class="card-icon">
                                <img src="<?= BASE_URL ?>/uploads/logos/jovenes.png" alt="Logo Jóvenes" class="ministry-logo">
                            </div>
                            <h3>Jóvenes</h3>
                            <p>Energía, pasión y propósito en Cristo</p>
                            <span class="btn btn-sm btn-outline-light">Ver Más <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <a href="<?= BASE_URL ?>/mujeres.php" class="text-decoration-none">
                    <div class="ministry-card">
                        <div class="card-bg" style="background: linear-gradient(135deg, #ec4899, #f43f5e);"></div>
                        <div class="card-overlay">
                            <div class="card-icon" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
                                <i class="bi bi-flower1"></i>
                            </div>
                            <h3>Mujeres</h3>
                            <p>Fortaleza, gracia y sabiduría divina</p>
                            <span class="btn btn-sm btn-outline-light">Ver Más <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <a href="<?= BASE_URL ?>/varones.php" class="text-decoration-none">
                    <div class="ministry-card">
                        <div class="card-bg" style="background: linear-gradient(135deg, #10b981, #059669);"></div>
                        <div class="card-overlay">
                            <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <h3>Varones</h3>
                            <p>Liderazgo y servicio con integridad</p>
                            <span class="btn btn-sm btn-outline-light">Ver Más <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <a href="<?= BASE_URL ?>/juveniles.php" class="text-decoration-none">
                    <div class="ministry-card">
                        <div class="card-bg" style="background: linear-gradient(135deg, #f59e0b, #f97316);"></div>
                        <div class="card-overlay">
                            <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b, #f97316);">
                                <img src="<?= BASE_URL ?>/uploads/logos/juveniles.png" alt="Logo Juveniles" class="ministry-logo">
                            </div>
                            <h3>Juveniles</h3>
                            <p>Sembrando semillas de fe desde temprano</p>
                            <span class="btn btn-sm btn-outline-light">Ver Más <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Content -->
<?php if (!empty($featuredPosts)): ?>
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Noticias y Recursos</h2>
            <p>Mantente informado de todo lo que sucede en nuestra comunidad</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featuredPosts as $post): ?>
            <div class="col-lg-4" data-aos="fade-up">
                <div class="card blog-card h-100">
                    <?php if (!empty($post['imagen'])): ?>
                    <img src="<?= BASE_URL . $post['imagen'] ?>" class="card-img-top" alt="<?= sanitize($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-newspaper text-white display-4"></i>
                    </div>
                    <?php endif; ?>
                    <span class="badge bg-primary"><?= ucfirst($post['seccion']) ?></span>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['created_at']) ?></span>
                            <span><i class="bi bi-person me-1"></i><?= sanitize($post['autor_nombre'] ?? 'Admin') ?></span>
                        </div>
                        <h5 class="card-title"><?= sanitize($post['titulo']) ?></h5>
                        <p class="card-text"><?= sanitize(substr(strip_tags($post['contenido']), 0, 120)) ?>...</p>
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
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/niños.php" class="btn btn-primary btn-lg">
                Ver Todas las Noticias <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Prayer Requests Section -->
<section class="py-5">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-heart-pulse me-1"></i> Intercesión
                </span>
                <h2 class="display-5 fw-bold mb-4">Motivos de Oración</h2>
                <p class="lead text-muted mb-4">
                    Únete a nosotros en oración por las necesidades de nuestra comunidad. 
                    Tu oración hace la diferencia.
                </p>
                <a href="<?= BASE_URL ?>/intercesion.php" class="btn btn-primary btn-icon">
                    Ver Todos los Motivos
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <?php if (!empty($prayerRequests)): ?>
                    <?php foreach ($prayerRequests as $prayer): ?>
                    <div class="prayer-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0"><?= sanitize($prayer['nombre']) ?></h5>
                        </div>
                        <p><?= sanitize(substr($prayer['peticion'], 0, 150)) ?>...</p>
                        <div class="meta">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= formatDate($prayer['created_at']) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="prayer-card">
                        <h5>No hay motivos de oración activos</h5>
                        <p>Pronto compartiremos nuevos motivos de oración con la comunidad.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Contenido Section -->
<section id="contenido" class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                <i class="bi bi-play-circle-fill me-1"></i> Contenido
            </span>
            <h2 class="display-5 fw-bold mb-4">Videos Inspiradores</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Disfruta de nuestros videos semanales y contenido especial para edificación espiritual.
                Cada domingo compartimos un mensaje especial.
            </p>
        </div>

        <div class="row g-5">
            <?php
            $videoEspecial = convertYouTubeToEmbed(getSetting('video_especial', ''));
            $videosRandom = [
                convertYouTubeToEmbed(getSetting('video_random_1', '')),
                convertYouTubeToEmbed(getSetting('video_random_2', '')),
                convertYouTubeToEmbed(getSetting('video_random_3', '')),
                convertYouTubeToEmbed(getSetting('video_random_4', '')),
            ];
            $videosRandom = array_filter($videosRandom); // Remove empty
            ?>

            <?php if (!empty($videoEspecial)): ?>
                <div class="col-12 mb-4" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h3 class="h4 text-primary fw-bold">
                            <i class="bi bi-star-fill me-2"></i>Video del Domingo
                        </h3>
                    </div>
                    <div class="ratio ratio-16x9 mx-auto" style="max-width: 800px;">
                        <iframe src="<?= $videoEspecial ?>" title="Video Especial" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($videosRandom)): ?>
                <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center mb-4">
                        <h3 class="h4 text-muted fw-bold">Más Videos</h3>
                    </div>
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($videosRandom as $index => $video): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="ratio ratio-16x9">
                                    <iframe src="<?= $video ?>" title="Video <?= $index + 1 ?>" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($videoEspecial) && empty($videosRandom)): ?>
                <div class="col-12 text-center py-5" data-aos="fade-up">
                    <i class="bi bi-play-circle display-1 text-muted opacity-50 mb-4"></i>
                    <h3 class="h4 text-muted mb-3">Próximamente</h3>
                    <p class="text-muted">Videos inspiradores estarán disponibles pronto.</p>
                </div>
            <?php endif; ?>

            <div class="col-12 text-center mt-4" data-aos="fade-up" data-aos-delay="400">
                <a href="https://www.youtube.com/@iglesiamovilizacioncristia5521" target="_blank" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-youtube me-2"></i>Mira más de nuestro contenido
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Instagram Feed Section -->
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Síguenos en Instagram</h2>
            <p>Mantente conectado con las últimas actividades de MCA</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-12" data-aos="fade-up">
                <div class="text-center">
                    <a href="https://www.instagram.com/iglesiamovilizacioncristiana/" target="_blank" class="btn btn-primary btn-lg mb-4">
                        <i class="bi bi-instagram me-2"></i>Síguenos en Instagram
                    </a>
                    <div class="bg-white rounded-3 p-4 shadow-sm">
                        <iframe src="https://www.instagram.com/iglesiamovilizacioncristiana/embed" width="100%" height="500" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-dark-section">
    <div class="container py-5 text-center">
        <div data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-4">¿Listo para ser parte de nuestra familia?</h2>
            <p class="lead opacity-75 mb-5 mx-auto" style="max-width: 600px;">
                Te invitamos a conocernos y ser parte de esta comunidad de fe que crece cada día.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="<?= BASE_URL ?>/formularios.php" class="btn btn-primary btn-lg btn-icon">
                    <i class="bi bi-pencil-square"></i>
                    Contáctanos
                </a>
                <a href="<?= BASE_URL ?>/nosotros.php" class="btn btn-outline-light btn-lg btn-icon">
                    <i class="bi bi-info-circle"></i>
                    Conoce Más
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
