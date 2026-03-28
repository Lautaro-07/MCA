<?php
require_once __DIR__ . '/includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $user = getCurrentUser();
    if ($user['role'] === 'admin') {
        header('Location: ' . BASE_URL . '/admin/');
    } elseif ($user['role'] === 'supervisor') {
        header('Location: ' . BASE_URL . '/supervisor/');
    } elseif ($user['role'] === 'lider') {
        header('Location: ' . BASE_URL . '/lider/');
    } else {
        header('Location: ' . BASE_URL . '/');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor ingresa tu usuario y contraseña.';
    } else {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            
            // Log activity
            logActivity($user['id'], 'login', 'Inicio de sesión exitoso');
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: ' . BASE_URL . '/admin/');
            } elseif ($user['role'] === 'supervisor') {
                header('Location: ' . BASE_URL . '/supervisor/');
            } elseif ($user['role'] === 'lider') {
                header('Location: ' . BASE_URL . '/lider/');
            } elseif (strpos($user['role'], 'admin_') === 0) {
                header('Location: ' . BASE_URL . '/admin/secciones.php');
            } else {
                header('Location: ' . BASE_URL . '/');
            }
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | MCA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/diseño.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card" data-aos="fade-up">
        <div class="logo">
            <i class="bi bi-heart-fill text-primary"></i>
            <h4 class="mt-3 fw-bold">MCA</h4>
            <small class="text-muted">Movilización Cristiana</small>
        </div>
        
        <h2>Iniciar Sesión</h2>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Usuario o Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" name="username" class="form-control border-start-0" 
                        placeholder="Ingresa tu usuario" required autofocus
                        value="<?= sanitize($_POST['username'] ?? '') ?>">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control border-start-0" 
                        placeholder="Ingresa tu contraseña" required id="password">
                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Ingresar
            </button>
            
            <div class="text-center">
                <a href="<?= BASE_URL ?>/" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver al inicio
                </a>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
