<?php
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    if ($user) {
        logActivity($user['id'], 'logout', 'Cierre de sesión');
    }
}

session_destroy();
header('Location: ' . BASE_URL . '/');
exit;
