<?php
$pageTitle = 'Sobre Nosotros';
require_once __DIR__ . '/includes/header.php';
// Cargar la misma imagen de cabecera usada en la página de inicio (si existe)
$fondoConfig = function_exists('getSectionConfig') ? getSectionConfig('inicio') : [];
$heroStyle = 'min-height: 60vh;';
if (!empty($fondoConfig['header_image_url'])) {
    $imageUrl = BASE_URL . $fondoConfig['header_image_url'];
    $heroStyle = "min-height: 60vh; background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url('{$imageUrl}'); background-size: cover; background-position: center;";
}
?>

<!-- Hero Section -->
<section class="hero-section text-white" style="<?= $heroStyle ?>">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-center text-center" style="min-height: 60vh;">
            <div class="col-lg-8 hero-content" data-aos="fade-up">
                <h1 class="hero-title display-heading">Sobre Nosotros</h1>
                <p class="hero-subtitle mx-auto">
                    Conoce nuestra historia, visión y los valores que nos guían en nuestra misión de transformar vidas.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="feature-icon mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-bullseye fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Nuestra Misión</h3>
                        <p class="text-muted mb-0">
                            Somos una iglesia comprometida con ganar almas para Cristo, consolidar la fe de los creyentes, 
                            discipular a los nuevos convertidos y enviar misioneros a todas las naciones. Creemos en el poder 
                            transformador del evangelio y trabajamos incansablemente para que cada persona conozca el amor de Dios.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-lg bg-gradient-primary text-white">
                    <div class="card-body p-5">
                        <div class="feature-icon mb-4" style="width: 70px; height: 70px; background: white;">
                            <i class="bi bi-eye fs-3 text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Nuestra Visión</h3>
                        <p class="opacity-90 mb-0">
                            Ser una iglesia que impacte nuestra ciudad, nación y el mundo entero con el mensaje de salvación. 
                            Soñamos con ver familias restauradas, jóvenes apasionados por Dios, y comunidades transformadas 
                            por el poder del Espíritu Santo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Nuestros Valores</h2>
            <p>Principios que guían todo lo que hacemos</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="feature-box h-100">
                    <div class="feature-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h4>Fe en la Palabra</h4>
                    <p>La Biblia es nuestra autoridad suprema. Cada enseñanza y decisión está fundamentada en las Sagradas Escrituras.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-box h-100">
                    <div class="feature-icon" style="background: var(--gradient-secondary);">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4>Amor Genuino</h4>
                    <p>Practicamos el amor incondicional de Cristo, sirviendo a otros sin esperar nada a cambio.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box h-100">
                    <div class="feature-icon" style="background: var(--gradient-accent);">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Comunidad</h4>
                    <p>Creemos en el poder de la comunión fraterna. Somos una familia que se apoya y crece junta.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box h-100">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4>Excelencia</h4>
                    <p>Servimos a Dios con lo mejor de nosotros, buscando siempre la excelencia en todo lo que hacemos.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-box h-100">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #06b6d4, #0284c7);">
                        <i class="bi bi-hand-index"></i>
                    </div>
                    <h4>Integridad</h4>
                    <p>Vivimos de manera coherente con lo que predicamos, siendo ejemplo en nuestra vida diaria.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-box h-100">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h4>Niños</h4>
                    <p>Formamos a los niños desde temprano en la fe y el amor de Dios, siendo un ministrio dedicado al futuro de la iglesia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- History -->
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    Nuestra Historia
                </span>
                <h2 class="display-5 fw-bold mb-4">15 Años Transformando Vidas</h2>
                <p class="lead text-muted mb-4">
                    MCA nació de un sueño: ver a Chile y las naciones impactadas por el evangelio de Cristo.
                </p>
                <p class="text-muted mb-4">
                    Lo que comenzó como un pequeño grupo de oración en una casa, hoy se ha convertido en una 
                    comunidad vibrante con más de 500 miembros activos, 50 grupos familiares y un impacto 
                    que trasciende fronteras.
                </p>
                <p class="text-muted mb-4">
                    A lo largo de estos años, hemos sido testigos de miles de vidas transformadas, familias 
                    restauradas, jóvenes liberados y comunidades impactadas por el amor de Dios.
                </p>
                <div class="d-flex gap-4">
                    <div class="text-center">
                        <h3 class="fw-bold text-primary mb-0">2010</h3>
                        <small class="text-muted">Fundación</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-primary mb-0">50+</h3>
                        <small class="text-muted">Grupos</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-primary mb-0">500+</h3>
                        <small class="text-muted">Miembros</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <div class="bg-gradient-primary rounded-4 p-5 text-white text-center">
                        <i class="bi bi-heart-fill display-1 mb-4 opacity-75"></i>
                        <h3 class="fw-bold mb-3">"El amor de Cristo nos motiva"</h3>
                        <p class="opacity-90 mb-0">
                            Cada día nos levantamos con la convicción de que Dios tiene un propósito 
                            para cada vida que toca nuestra comunidad.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do -->
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <div class="section-header" data-aos="fade-up">
            <h2>Lo Que Hacemos</h2>
            <p>Nuestras principales áreas de servicio y ministerio</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-house-heart text-primary fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Grupos Familiares</h5>
                        <p class="text-muted small mb-0">Reuniones semanales en hogares para crecer en comunidad y fe.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-mortarboard text-success fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Escuela de Liderazgo</h5>
                        <p class="text-muted small mb-0">Formación integral para desarrollar líderes según el corazón de Dios.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-stars text-warning fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Niños</h5>
                        <p class="text-muted small mb-0">Formación integral de niños en fe, valores y amor de Dios.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-hand-thumbs-up text-danger fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Servicio Social</h5>
                        <p class="text-muted small mb-0">Ayuda a los más necesitados y proyectos de impacto comunitario.</p>
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
            <h2 class="display-5 fw-bold mb-4">Únete a Nuestra Familia</h2>
            <p class="lead opacity-75 mb-5 mx-auto" style="max-width: 600px;">
                Te invitamos a ser parte de esta comunidad que crece cada día en fe y amor.
            </p>
            <a href="<?= BASE_URL ?>/formularios.php" class="btn btn-warning btn-lg btn-icon">
                <i class="bi bi-envelope"></i>
                Contáctanos
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
