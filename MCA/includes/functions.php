<?php
// Helper functions for MCA - Movilización Cristiana

require_once __DIR__ . '/../config/database.php';

// CSRF Token functions
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get current user from session
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check user role
function hasRole($roles) {
    $user = getCurrentUser();
    if (!$user) return false;
    if (is_string($roles)) $roles = [$roles];
    return in_array($user['role'], $roles);
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

// Require specific role
function requireRole($roles) {
    requireLogin();
    if (!hasRole($roles)) {
        header('Location: ' . BASE_URL . '/login.php?error=access_denied');
        exit;
    }
}

// Log activity
function logActivity($userId, $action, $description = '') {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO actividad_log (user_id, accion, descripcion, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $description, $_SERVER['REMOTE_ADDR'] ?? '']);
    } catch (Exception $e) {
        // Silently fail
    }
}

// Get site setting
function getSetting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        try {
            $pdo = getConnection();
            $stmt = $pdo->query("SELECT clave, valor FROM configuracion");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['clave']] = $row['valor'];
            }
        } catch (Exception $e) {
            $settings = [];
        }
    }
    return $settings[$key] ?? $default;
}

// Update site setting
function updateSetting($key, $value) {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT clave FROM configuracion WHERE clave = ?");
        $stmt->execute([$key]);
        if ($stmt->fetch()) {
            // Update
            $stmt = $pdo->prepare("UPDATE configuracion SET valor = ? WHERE clave = ?");
            $stmt->execute([$value, $key]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES (?, ?)");
            $stmt->execute([$key, $value]);
        }
        return true;
    } catch (Exception $e) {
        error_log("Error updating setting '$key': " . $e->getMessage());
        return false;
    }
}

// Sanitize input
function sanitize($input) {
    if ($input === null) return '';
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate slug
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    if (function_exists('iconv')) {
        $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// Format date in Spanish
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

// Get week range
function getWeekRange($date = null) {
    if (!$date) $date = date('Y-m-d');
    $timestamp = strtotime($date);
    $dayOfWeek = date('w', $timestamp);
    $monday = date('Y-m-d', strtotime("-$dayOfWeek days +1 day", $timestamp));
    if ($dayOfWeek == 0) $monday = date('Y-m-d', strtotime("-6 days", $timestamp));
    $sunday = date('Y-m-d', strtotime("+6 days", strtotime($monday)));
    return ['inicio' => $monday, 'fin' => $sunday];
}

// Calculate attendance percentage
function calculateAttendancePercentage($attendance) {
    $total = 0;
    $attended = 0;
    $fields = ['grupo_familiar', 'escuela', 'reunion_red', 'culto_domingo', 'actividad_omt'];
    
    foreach ($fields as $field) {
        if (isset($attendance[$field]) && $attendance[$field] !== 'no_hubo') {
            $total++;
            if ($attendance[$field] === 'si') {
                $attended++;
            }
        }
    }
    
    return $total > 0 ? round(($attended / $total) * 100, 2) : 0;
}

// Upload file
function uploadFile($file, $directory = 'images') {
    $targetDir = UPLOAD_DIR . $directory . '/';
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        return ['error' => 'Tipo de archivo no permitido'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['error' => 'El archivo es demasiado grande'];
    }
    
    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $targetFile = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'path' => '/uploads/' . $directory . '/' . $fileName];
    }
    
    return ['error' => 'Error al subir el archivo'];
}

// Delete file from uploads
function deleteFile($filePath) {
    if (empty($filePath)) {
        return ['error' => 'Ruta de archivo no válida'];
    }
    
    // Remove leading slash if present
    $relativePath = $filePath;
    if (strpos($relativePath, '/') === 0) {
        $relativePath = substr($relativePath, 1);
    }
    
    // Build the full path
    $fullPath = __DIR__ . '/../' . $relativePath;
    
    // Normalize path to prevent directory traversal
    $fullPath = realpath($fullPath);
    $uploadsDir = realpath(__DIR__ . '/../uploads/');
    
    // Verify the file is within uploads directory
    if ($fullPath === false || strpos($fullPath, $uploadsDir) !== 0) {
        return ['error' => 'Ruta de archivo no válida'];
    }
    
    // Delete the file if it exists
    if (file_exists($fullPath) && is_file($fullPath)) {
        if (@unlink($fullPath)) {
            return ['success' => true];
        } else {
            error_log("Error deleting file: {$fullPath}");
            return ['error' => 'Error al eliminar el archivo del servidor'];
        }
    }
    
    // File doesn't exist, but we can still clear the database reference
    return ['success' => true, 'message' => 'Archivo no encontrado en servidor'];
}

// Get publications by section
function getPublications($section = null, $limit = 10, $onlyPublished = false) {
    try {
        $pdo = getConnection();
        $sql = "SELECT p.*, u.full_name as autor_nombre 
                FROM publicaciones p 
                JOIN users u ON p.autor_id = u.id";
        
        $params = [];
        $conditions = [];
        
        if ($section) {
            $conditions[] = "p.seccion = ?";
            $params[] = $section;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT " . intval($limit);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get section configuration
function getSectionConfig($sectionKey, $refresh = false) {
    static $configs = [];
    
    // Clear cache for this section if refresh is requested
    if ($refresh && isset($configs[$sectionKey])) {
        unset($configs[$sectionKey]);
    }
    
    if (isset($configs[$sectionKey]) && !$refresh) {
        return $configs[$sectionKey];
    }
    
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM seccion_config WHERE seccion_key = ?");
        $stmt->execute([$sectionKey]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($config) {
            $configs[$sectionKey] = $config;
            return $config;
        }
    } catch (Exception $e) {
        // Silently fail or log error
    }
    
    // Return a default array if not found
    return [];
}

// Get prayer requests (matches actual database schema)
function getPrayerRequests($limit = 10, $approvedOnly = true) {
    try {
        $pdo = getConnection();
        $sql = "SELECT * FROM intercesion";
        
        if ($approvedOnly) {
            $sql .= " WHERE estado = 'aprobada' AND es_privada = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT " . intval($limit);
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get leader's members
function getLeaderMembers($liderId) {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM miembros WHERE lider_id = ? AND is_active = 1 ORDER BY nombre");
        $stmt->execute([$liderId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get supervisor's leaders
function getSupervisorLeaders($supervisorId) {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            SELECT u.*, sl.created_at as assigned_at 
            FROM users u 
            JOIN supervisor_lideres sl ON u.id = sl.lider_id 
            WHERE sl.supervisor_id = ? AND u.is_active = 1 
            ORDER BY u.full_name
        ");
        $stmt->execute([$supervisorId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get all leaders for admin
function getAllLeaders() {
    try {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'lider' AND is_active = 1 ORDER BY full_name");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Get all supervisors for admin
function getAllSupervisors() {
    try {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'supervisor' AND is_active = 1 ORDER BY full_name");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Return supervisors visible to a given administrator.  At the moment the
// relationship is one‑to‑many so admins see everyone, but the helper exists
// in case filtering is added later.
function getAdminSupervisors($adminId) {
    try {
        // could filter by admin_id if a linking table is added in the future
        return getAllSupervisors();
    } catch (Exception $e) {
        return [];
    }
}

// Flash messages
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Count users by role
function countUsersByRole($role) {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE role = ? AND is_active = 1");
        $stmt->execute([$role]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Count total members
function countAllMembers() {
    try {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM miembros WHERE is_active = 1");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Count weekly reports
function countWeeklyReports($weekStart = null) {
    try {
        $pdo = getConnection();
        if ($weekStart) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM informes_semanales WHERE semana_inicio = ?");
            $stmt->execute([$weekStart]);
        } else {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM informes_semanales");
        }
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Count prayer requests by status
function countPrayerRequests($status = null) {
    try {
        $pdo = getConnection();
        if ($status) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM intercesion WHERE estado = ?");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM intercesion");
        }
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Convert YouTube URL to embed URL
function convertYouTubeToEmbed($url) {
    if (empty($url)) {
        return '';
    }

    // Extract video ID from various YouTube URL formats
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/',
        '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/live\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            $videoId = $matches[1];

            // Build embed URL with parameters to help with regional restrictions
            $params = [
                'enablejsapi' => '1',
                'modestbranding' => '1',
                'rel' => '0',
                'showinfo' => '0',
                'iv_load_policy' => '3',
                'fs' => '1',
                'cc_load_policy' => '0',
                'hl' => 'es',
                'origin' => (!empty($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : '')
            ];

            $queryString = http_build_query($params);
            return 'https://www.youtube.com/embed/' . $videoId . '?' . $queryString;
        }
    }

    // If no pattern matches, return the original URL (might already be embed)
    return $url;
}

// Get role display name
function getRoleName($role) {
    $roles = [
        'admin' => 'Administrador',
        'supervisor' => 'Supervisor',
        'lider' => 'Líder',
        'user' => 'Usuario',
        'admin_jovenes' => 'Admin Jóvenes',
        'admin_mujeres' => 'Admin Mujeres',
        'admin_varones' => 'Admin Varones',
        'admin_juveniles' => 'Admin Juveniles',
        'admin_niños' => 'Admin Niños',
        'admin_intercesion' => 'Admin Intercesión',
        'sub_administrador' => 'Consolidador'
    ];
    return $roles[$role] ?? ucfirst($role);
}

// Generate monthly statistics and save to estadisticas_mensuales
function generateMonthlyStats($liderId, $month, $year) {
    try {
        $pdo = getConnection();
        
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $stmt = $pdo->prepare("
            SELECT i.id FROM informes_semanales i 
            WHERE i.lider_id = ? AND i.semana_inicio >= ? AND i.semana_inicio <= ? AND i.estado = 'completado'
        ");
        $stmt->execute([$liderId, $startDate, $endDate]);
        $informeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($informeIds)) return;
        
        $placeholders = implode(',', array_fill(0, count($informeIds), '?'));
        $stmt = $pdo->prepare("
            SELECT 
                AVG(CASE WHEN grupo_familiar = 'si' THEN 100 WHEN grupo_familiar = 'no' THEN 0 ELSE NULL END) as prom_gf,
                AVG(CASE WHEN escuela = 'si' THEN 100 WHEN escuela = 'no' THEN 0 ELSE NULL END) as prom_esc,
                AVG(CASE WHEN reunion_red = 'si' THEN 100 WHEN reunion_red = 'no' THEN 0 ELSE NULL END) as prom_rr,
                AVG(CASE WHEN culto_domingo = 'si' THEN 100 WHEN culto_domingo = 'no' THEN 0 ELSE NULL END) as prom_cd,
                AVG(CASE WHEN actividad_omt = 'si' THEN 100 WHEN actividad_omt = 'no' THEN 0 ELSE NULL END) as prom_omt
            FROM asistencia_semanal WHERE informe_id IN ($placeholders)
        ");
        $stmt->execute($informeIds);
        $promedios = $stmt->fetch();
        
        // respect nulls from AVG
        $promGF = $promedios['prom_gf'] !== null ? round($promedios['prom_gf'], 2) : null;
        $promEsc = $promedios['prom_esc'] !== null ? round($promedios['prom_esc'], 2) : null;
        $promRR = $promedios['prom_rr'] !== null ? round($promedios['prom_rr'], 2) : null;
        $promCD = $promedios['prom_cd'] !== null ? round($promedios['prom_cd'], 2) : null;
        $promOMT = $promedios['prom_omt'] !== null ? round($promedios['prom_omt'], 2) : null;
        $parts = array_filter([$promGF, $promEsc, $promRR, $promCD, $promOMT], fn($v) => $v !== null);
        $promGeneral = !empty($parts) ? round(array_sum($parts) / count($parts), 2) : null;
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN esta_bautizado = 1 THEN 1 ELSE 0 END) as bautizados, SUM(CASE WHEN es_consolidacion = 1 THEN 1 ELSE 0 END) as consolidacion FROM miembros WHERE lider_id = ? AND is_active = 1");
        $stmt->execute([$liderId]);
        $members = $stmt->fetch();
        
        $stmt = $pdo->prepare("SELECT id FROM estadisticas_mensuales WHERE lider_id = ? AND mes = ? AND anio = ?");
        $stmt->execute([$liderId, $month, $year]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $pdo->prepare("
                UPDATE estadisticas_mensuales SET 
                    promedio_grupo_familiar = ?, promedio_escuela = ?, promedio_reunion_red = ?,
                    promedio_culto_domingo = ?, promedio_actividad_omt = ?, promedio_general = ?,
                    total_miembros = ?, total_bautizados = ?, total_consolidacion = ?
                WHERE id = ?
            ");
            $stmt->execute([$promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $members['total'] ?? 0, $members['bautizados'] ?? 0, $members['consolidacion'] ?? 0, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO estadisticas_mensuales 
                (lider_id, mes, anio, promedio_grupo_familiar, promedio_escuela, promedio_reunion_red,
                 promedio_culto_domingo, promedio_actividad_omt, promedio_general,
                 total_miembros, total_bautizados, total_consolidacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$liderId, $month, $year, $promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $members['total'] ?? 0, $members['bautizados'] ?? 0, $members['consolidacion'] ?? 0]);
        }
    } catch (Exception $e) {
        error_log("Error generating monthly stats: " . $e->getMessage());
    }
}

// generate monthly statistics for a supervisor using the supervisor tables
function generateMonthlyStatsSupervisor($supervisorId, $month, $year) {
    try {
        $pdo = getConnection();
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $stmt = $pdo->prepare("SELECT id FROM informes_supervisores WHERE supervisor_id = ? AND semana_inicio >= ? AND semana_inicio <= ? AND estado = 'completado'");
        $stmt->execute([$supervisorId, $startDate, $endDate]);
        $informeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($informeIds)) return;

        $placeholders = implode(',', array_fill(0, count($informeIds), '?'));
        $stmt = $pdo->prepare("SELECT 
                AVG(CASE WHEN grupo_familiar = 'si' THEN 100 WHEN grupo_familiar = 'no' THEN 0 ELSE NULL END) as prom_gf,
                AVG(CASE WHEN escuela = 'si' THEN 100 WHEN escuela = 'no' THEN 0 ELSE NULL END) as prom_esc,
                AVG(CASE WHEN reunion_red = 'si' THEN 100 WHEN reunion_red = 'no' THEN 0 ELSE NULL END) as prom_rr,
                AVG(CASE WHEN culto_domingo = 'si' THEN 100 WHEN culto_domingo = 'no' THEN 0 ELSE NULL END) as prom_cd,
                AVG(CASE WHEN actividad_omt = 'si' THEN 100 WHEN actividad_omt = 'no' THEN 0 ELSE NULL END) as prom_omt
            FROM asistencia_supervisores WHERE informe_id IN ($placeholders)");
        $stmt->execute($informeIds);
        $promedios = $stmt->fetch();

        $promGF = $promedios['prom_gf'] !== null ? round($promedios['prom_gf'], 2) : null;
        $promEsc = $promedios['prom_esc'] !== null ? round($promedios['prom_esc'], 2) : null;
        $promRR = $promedios['prom_rr'] !== null ? round($promedios['prom_rr'], 2) : null;
        $promCD = $promedios['prom_cd'] !== null ? round($promedios['prom_cd'], 2) : null;
        $promOMT = $promedios['prom_omt'] !== null ? round($promedios['prom_omt'], 2) : null;
        $parts = array_filter([$promGF, $promEsc, $promRR, $promCD, $promOMT], fn($v) => $v !== null);
        $promGeneral = !empty($parts) ? round(array_sum($parts) / count($parts), 2) : null;

        $stmt = $pdo->prepare("SELECT COUNT(lider_id) as total_lideres FROM supervisor_lideres WHERE supervisor_id = ?");
        $stmt->execute([$supervisorId]);
        $counts = $stmt->fetch();
        $totalLideres = $counts['total_lideres'] ?? 0;

        $stmt = $pdo->prepare("SELECT id FROM estadisticas_mensuales_supervisores WHERE supervisor_id = ? AND mes = ? AND anio = ?");
        $stmt->execute([$supervisorId, $month, $year]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $pdo->prepare("UPDATE estadisticas_mensuales_supervisores SET 
                    promedio_grupo_familiar = ?, promedio_escuela = ?, promedio_reunion_red = ?,
                    promedio_culto_domingo = ?, promedio_actividad_omt = ?, promedio_general = ?,
                    total_lideres = ? WHERE id = ?");
            $stmt->execute([$promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $totalLideres, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO estadisticas_mensuales_supervisores 
                    (supervisor_id, mes, anio, promedio_grupo_familiar, promedio_escuela, promedio_reunion_red,
                     promedio_culto_domingo, promedio_actividad_omt, promedio_general, total_lideres)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$supervisorId, $month, $year, $promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $totalLideres]);
        }
    } catch (Exception $e) {
        error_log("Error generating monthly stats for supervisor: " . $e->getMessage());
    }
}

// generate monthly statistics for an administrator
function generateMonthlyStatsAdmin($adminId, $month, $year) {
    try {
        $pdo = getConnection();
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $stmt = $pdo->prepare("SELECT id FROM informes_admin WHERE admin_id = ? AND semana_inicio >= ? AND semana_inicio <= ? AND estado = 'completado'");
        $stmt->execute([$adminId, $startDate, $endDate]);
        $informeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($informeIds)) return;

        $placeholders = implode(',', array_fill(0, count($informeIds), '?'));
        $stmt = $pdo->prepare("SELECT 
                AVG(CASE WHEN grupo_familiar = 'si' THEN 100 WHEN grupo_familiar = 'no' THEN 0 ELSE NULL END) as prom_gf,
                AVG(CASE WHEN escuela = 'si' THEN 100 WHEN escuela = 'no' THEN 0 ELSE NULL END) as prom_esc,
                AVG(CASE WHEN reunion_red = 'si' THEN 100 WHEN reunion_red = 'no' THEN 0 ELSE NULL END) as prom_rr,
                AVG(CASE WHEN culto_domingo = 'si' THEN 100 WHEN culto_domingo = 'no' THEN 0 ELSE NULL END) as prom_cd,
                AVG(CASE WHEN actividad_omt = 'si' THEN 100 WHEN actividad_omt = 'no' THEN 0 ELSE NULL END) as prom_omt
            FROM asistencia_admin WHERE informe_id IN ($placeholders)");
        $stmt->execute($informeIds);
        $promedios = $stmt->fetch();

        $promGF = $promedios['prom_gf'] !== null ? round($promedios['prom_gf'], 2) : null;
        $promEsc = $promedios['prom_esc'] !== null ? round($promedios['prom_esc'], 2) : null;
        $promRR = $promedios['prom_rr'] !== null ? round($promedios['prom_rr'], 2) : null;
        $promCD = $promedios['prom_cd'] !== null ? round($promedios['prom_cd'], 2) : null;
        $promOMT = $promedios['prom_omt'] !== null ? round($promedios['prom_omt'], 2) : null;
        $parts = array_filter([$promGF, $promEsc, $promRR, $promCD, $promOMT], fn($v) => $v !== null);
        $promGeneral = !empty($parts) ? round(array_sum($parts) / count($parts), 2) : null;

        // total supervisors under this admin (could simply be count of role)
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'supervisor' AND is_active = 1");
        $totalSuper = $stmt->fetch()['cnt'] ?? 0;

        $stmt = $pdo->prepare("SELECT id FROM estadisticas_mensuales_admin WHERE admin_id = ? AND mes = ? AND anio = ?");
        $stmt->execute([$adminId, $month, $year]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $pdo->prepare("UPDATE estadisticas_mensuales_admin SET 
                    promedio_grupo_familiar = ?, promedio_escuela = ?, promedio_reunion_red = ?,
                    promedio_culto_domingo = ?, promedio_actividad_omt = ?, promedio_general = ?,
                    total_supervisores = ? WHERE id = ?");
            $stmt->execute([$promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $totalSuper, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO estadisticas_mensuales_admin 
                    (admin_id, mes, anio, promedio_grupo_familiar, promedio_escuela, promedio_reunion_red,
                     promedio_culto_domingo, promedio_actividad_omt, promedio_general, total_supervisores)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$adminId, $month, $year, $promGF, $promEsc, $promRR, $promCD, $promOMT, $promGeneral, $totalSuper]);
        }
    } catch (Exception $e) {
        error_log("Error generating monthly stats for admin: " . $e->getMessage());
    }
}

// Get monthly stats for a leader (calculates directly from attendance data)
function getMonthlyStats($liderId, $month = null, $year = null) {
    if (!$month) $month = date('n');
    if (!$year) $year = date('Y');
    
    $default = [
        'total_miembros' => 0,
        'informes_enviados' => 0,
        'promedio_asistencia' => null,
        'promedio_grupo_familiar' => null,
        'promedio_escuela' => null,
        'promedio_reunion_red' => null,
        'promedio_culto_domingo' => null,
        'promedio_actividad_omt' => null,
        'mes' => $month,
        'anio' => $year
    ];
    
    try {
        $pdo = getConnection();
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM miembros WHERE lider_id = ? AND is_active = 1");
        $stmt->execute([$liderId]);
        $memberCount = $stmt->fetch()['count'] ?? 0;
        
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $stmt = $pdo->prepare("SELECT id FROM informes_semanales WHERE lider_id = ? AND semana_inicio >= ? AND semana_inicio <= ? AND estado = 'completado'");
        $stmt->execute([$liderId, $startDate, $endDate]);
        $informeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $reportCount = count($informeIds);
        
        $default['total_miembros'] = $memberCount;
        $default['informes_enviados'] = $reportCount;
        
        if ($reportCount === 0 || empty($informeIds)) {
            return $default;
        }
        
        $placeholders = implode(',', array_fill(0, count($informeIds), '?'));
        $stmt = $pdo->prepare("
            SELECT 
                AVG(CASE WHEN grupo_familiar = 'si' THEN 100 WHEN grupo_familiar = 'no' THEN 0 ELSE NULL END) as prom_gf,
                AVG(CASE WHEN escuela = 'si' THEN 100 WHEN escuela = 'no' THEN 0 ELSE NULL END) as prom_esc,
                AVG(CASE WHEN reunion_red = 'si' THEN 100 WHEN reunion_red = 'no' THEN 0 ELSE NULL END) as prom_rr,
                AVG(CASE WHEN culto_domingo = 'si' THEN 100 WHEN culto_domingo = 'no' THEN 0 ELSE NULL END) as prom_cd,
                AVG(CASE WHEN actividad_omt = 'si' THEN 100 WHEN actividad_omt = 'no' THEN 0 ELSE NULL END) as prom_omt
            FROM asistencia_semanal WHERE informe_id IN ($placeholders)
        ");
        $stmt->execute($informeIds);
        $promedios = $stmt->fetch();
        
        // SQL AVG returns null when there are no non-null values (e.g. all
        // registros were "no_hubo").  Preserve that null so calling code can
        // treat it as "no data" instead of 0.
        $promGF = $promedios['prom_gf'] !== null ? round((float)$promedios['prom_gf'], 2) : null;
        $promEsc = $promedios['prom_esc'] !== null ? round((float)$promedios['prom_esc'], 2) : null;
        $promRR = $promedios['prom_rr'] !== null ? round((float)$promedios['prom_rr'], 2) : null;
        $promCD = $promedios['prom_cd'] !== null ? round((float)$promedios['prom_cd'], 2) : null;
        $promOMT = $promedios['prom_omt'] !== null ? round((float)$promedios['prom_omt'], 2) : null;

        $parts = array_filter([$promGF, $promEsc, $promRR, $promCD, $promOMT], fn($v) => $v !== null);
        $promGeneral = !empty($parts) ? round(array_sum($parts) / count($parts), 2) : null;
        
        return [
            'total_miembros' => $memberCount,
            'informes_enviados' => $reportCount,
            'promedio_asistencia' => $promGeneral,
            'promedio_grupo_familiar' => $promGF,
            'promedio_escuela' => $promEsc,
            'promedio_reunion_red' => $promRR,
            'promedio_culto_domingo' => $promCD,
            'promedio_actividad_omt' => $promOMT,
            'mes' => $month,
            'anio' => $year
        ];
    } catch (Exception $e) {
        return $default;
    }
}

// Get current week dates
function getCurrentWeekDates() {
    $today = new DateTime();
    $dayOfWeek = $today->format('N');
    
    $monday = clone $today;
    $monday->modify('-' . ($dayOfWeek - 1) . ' days');
    
    $sunday = clone $monday;
    $sunday->modify('+6 days');
    
    return [
        'start' => $monday->format('Y-m-d'),
        'end' => $sunday->format('Y-m-d')
    ];
}

// Check if leader has submitted report this week
function hasSubmittedReportThisWeek($liderId) {
    try {
        $week = getCurrentWeekDates();
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT id FROM informes_semanales WHERE lider_id = ? AND semana_inicio = ?");
        $stmt->execute([$liderId, $week['start']]);
        return $stmt->fetch() !== false;
    } catch (Exception $e) {
        return false;
    }
}

function getMemberAttendanceStats($liderId, $mes = null, $year = null) {
    if (!$mes) $mes = date('n');
    if (!$year) $year = date('Y');

    try {
        $pdo = getConnection();

        // Miembros del líder
        $stmt = $pdo->prepare("SELECT id, nombre, es_consolidacion, is_new FROM miembros WHERE lider_id = ? AND is_active = 1 ORDER BY nombre");
        $stmt->execute([$liderId]);
        $miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$miembros) return [];

        $startDate = sprintf('%04d-%02d-01', $year, $mes);
        $endDate = date('Y-m-t', strtotime($startDate));

        // Informes del mes
        $stmt = $pdo->prepare("
            SELECT id FROM informes_semanales
            WHERE lider_id = ?
            AND semana_inicio BETWEEN ? AND ?
            AND estado = 'completado'
        ");
        $stmt->execute([$liderId, $startDate, $endDate]);
        $informeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($informeIds)) return [];

        $placeholders = implode(',', array_fill(0, count($informeIds), '?'));
        $resultados = [];

        foreach ($miembros as $m) {
            $stmt = $pdo->prepare("
                SELECT grupo_familiar, escuela, reunion_red, culto_domingo, actividad_omt
                FROM asistencia_semanal
                WHERE miembro_id = ? AND informe_id IN ($placeholders)
            ");
            $stmt->execute(array_merge([$m['id']], $informeIds));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) continue;

            $total = count($rows);
            $campos = ['grupo_familiar'=>0,'escuela'=>0,'reunion_red'=>0,'culto_domingo'=>0,'actividad_omt'=>0];

            foreach ($rows as $r) {
                foreach ($campos as $k => $v) {
                    if (($r[$k] ?? '') === 'si') $campos[$k]++;
                }
            }

            foreach ($campos as $k => $v) {
                $campos[$k] = round(($v / $total) * 100, 1);
            }

            $general = round(array_sum($campos) / 5, 1);

            $resultados[] = [
                'miembro' => $m,
                'porcentaje_gf' => $campos['grupo_familiar'],
                'porcentaje_escuela' => $campos['escuela'],
                'porcentaje_red' => $campos['reunion_red'],
                'porcentaje_domingo' => $campos['culto_domingo'],
                'porcentaje_omt' => $campos['actividad_omt'],
                'porcentaje_general' => $general
            ];
        }

        return $resultados;

    } catch (Exception $e) {
        error_log("getMemberAttendanceStats ERROR: " . $e->getMessage());
        return [];
    }
}