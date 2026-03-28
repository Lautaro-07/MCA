    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <img src="<?= BASE_URL ?>/uploads/logos/logomca.png" alt="Logo MCA" class="site-logo footer-logo">
                        <div>
                            <p class="text-white-50 mb-0"><?= getSetting('site_description', 'Movilizando a la iglesia para transformar vidas') ?></p>
                        </div>
                    </div>
                    <div class="social-links mt-3">
                        <?php if ($fb = getSetting('facebook_url')): ?>
                            <a href="<?= $fb ?>" class="me-3 text-white-50 fs-4" target="_blank"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = getSetting('instagram_url')): ?>
                            <a href="<?= $ig ?>" class="me-3 text-white-50 fs-4" target="_blank"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = getSetting('youtube_url')): ?>
                            <a href="<?= $yt ?>" class="me-3 text-white-50 fs-4" target="_blank"><i class="bi bi-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3 text-uppercase">Navegacion</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= BASE_URL ?>">Inicio</a></li>
                        <li><a href="#contenido">Contenido</a></li>
                        <li><a href="<?= BASE_URL ?>/nosotros.php">Sobre Nosotros</a></li>
                        <li><a href="<?= BASE_URL ?>/niños.php">Niños</a></li>
                        <li><a href="<?= BASE_URL ?>/intercesion.php">Intercesion</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-uppercase">Ministerios</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= BASE_URL ?>/jovenes.php">Jovenes</a></li>
                        <li><a href="<?= BASE_URL ?>/mujeres.php">Mujeres</a></li>
                        <li><a href="<?= BASE_URL ?>/varones.php">Varones</a></li>
                        <li><a href="<?= BASE_URL ?>/juveniles.php">Juveniles</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-uppercase">Contacto</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <?= getSetting('site_email', 'contacto@mca.org') ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <?= getSetting('site_phone', '+56 9 1234 5678') ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <?= getSetting('site_address', 'Santiago, Chile') ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-white-50">&copy; <?= date('Y') ?> MCA - MovilizaciÃ³n Cristiana. Todos los derechos reservados.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-white-50">
                        <a href="<?= BASE_URL ?>/login.php" class="text-white-50 text-decoration-none">Acceso LÃ­deres</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Cambiar color del texto en tarjetas de ministerios segÃºn viewport
        function updateMinistryCardColors() {
            const ministryTexts = document.querySelectorAll('.ministry-card .card-overlay h3, .ministry-card .card-overlay p');
            const isMobile = window.innerWidth <= 768;
            
            ministryTexts.forEach(element => {
                if (isMobile) {
                    element.style.color = '#ffffff';
                } else {
                    element.style.color = '#000000';
                }
            });
        }

        // Ejecutar al cargar
        updateMinistryCardColors();

        // Ejecutar cuando se redimensiona la ventana
        window.addEventListener('resize', updateMinistryCardColors);
    </script>
</body>
</html>
