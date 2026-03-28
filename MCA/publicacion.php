<?php
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT p.*, u.full_name as autor_nombre FROM publicaciones p JOIN users u ON p.autor_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$pageTitle = $post['titulo'];

// Get related posts from same section
$stmt = $pdo->prepare("SELECT * FROM publicaciones WHERE seccion = ? AND id != ? ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$post['seccion'], $id]);
$related = $stmt->fetchAll();

// Section colors
$sectionColors = [
    'jovenes' => ['bg' => 'linear-gradient(135deg, #6366f1, #8b5cf6)', 'icon' => 'bi-lightning-fill'],
    'mujeres' => ['bg' => 'linear-gradient(135deg, #ec4899, #f43f5e)', 'icon' => 'bi-flower1'],
    'varones' => ['bg' => 'linear-gradient(135deg, #10b981, #059669)', 'icon' => 'bi-shield-fill'],
    'juveniles' => ['bg' => 'linear-gradient(135deg, #f59e0b, #f97316)', 'icon' => 'bi-stars'],
    'niños' => ['bg' => 'linear-gradient(135deg, #8b5cf6, #a855f7)', 'icon' => 'bi-stars'],
    'intercesion' => ['bg' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)', 'icon' => 'bi-pray'],
];
$sectionStyle = $sectionColors[$post['seccion']] ?? $sectionColors['jovenes'];
?>

<!-- Hero Section -->
<section class="py-5 text-white" style="background: <?= $sectionStyle['bg'] ?>; margin-top: 70px;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <a href="<?= BASE_URL ?>/<?= $post['seccion'] ?>.php" class="badge bg-white bg-opacity-25 text-white mb-3 px-3 py-2 rounded-pill text-decoration-none">
                    <i class="<?= $sectionStyle['icon'] ?> me-1"></i>
                    <?= ucfirst($post['seccion']) ?>
                </a>
                <h1 class="display-5 fw-bold mb-4"><?= sanitize($post['titulo']) ?></h1>
                <div class="d-flex justify-content-center gap-4 text-white-50">
                    <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['created_at'], 'd M, Y') ?></span>
                    <span><i class="bi bi-person me-1"></i><?= sanitize($post['autor_nombre']) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($post['imagen']): ?>
                <img src="<?= BASE_URL . $post['imagen'] ?>" class="img-fluid rounded-4 mb-4 w-100" alt="<?= sanitize($post['titulo']) ?>">
                <?php endif; ?>
                
                <article class="blog-content">
                    <?= nl2br($post['contenido']) ?>
                </article>
                
                <hr class="my-5">
                
                <!-- Share -->
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted">Compartir:</span>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/publicacion.php?id=' . $post['id']) ?>" 
                            target="_blank" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/publicacion.php?id=' . $post['id']) ?>&text=<?= urlencode($post['titulo']) ?>" 
                            target="_blank" class="btn btn-outline-info btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode($post['titulo'] . ' ' . BASE_URL . '/publicacion.php?id=' . $post['id']) ?>" 
                            target="_blank" class="btn btn-outline-success btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Posts -->
<?php if (!empty($related)): ?>
<section class="py-5 bg-light-gradient">
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Publicaciones Relacionadas</h3>
        <div class="row g-4">
            <?php foreach ($related as $rel): ?>
            <div class="col-lg-4">
                <div class="card blog-card h-100">
                    <?php if ($rel['imagen']): ?>
                    <img src="<?= BASE_URL . $rel['imagen'] ?>" class="card-img-top" alt="<?= sanitize($rel['titulo']) ?>">
                    <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 180px; background: <?= $sectionStyle['bg'] ?>;">
                        <i class="<?= $sectionStyle['icon'] ?> text-white display-4"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($rel['created_at']) ?></span>
                        </div>
                        <h5 class="card-title"><?= sanitize($rel['titulo']) ?></h5>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?= BASE_URL ?>/publicacion.php?id=<?= $rel['id'] ?>" class="btn btn-outline-primary btn-sm">
                            Leer Más <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
