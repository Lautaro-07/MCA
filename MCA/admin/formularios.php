<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['admin']);

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Create new form
    if ($action === 'create') {
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $fecha_limite = !empty($_POST['fecha_limite']) ? $_POST['fecha_limite'] : null;
        
        // Build fields array from dynamic inputs
        $campos = [];
        if (!empty($_POST['campo_nombre'])) {
            foreach ($_POST['campo_nombre'] as $i => $nombre) {
                if (empty(trim($nombre))) continue;
                
                $campo = [
                    'name' => trim($nombre),
                    'type' => $_POST['campo_tipo'][$i] ?? 'text',
                    'required' => isset($_POST['campo_requerido'][$i]),
                    'options' => []
                ];
                
                // Handle options for select/radio
                if (in_array($campo['type'], ['select', 'radio']) && !empty($_POST['campo_opciones'][$i])) {
                    $opciones = explode(',', $_POST['campo_opciones'][$i]);
                    $campo['options'] = array_map('trim', $opciones);
                }
                
                $campos[] = $campo;
            }
        }
        
        if (empty($titulo)) {
            $error = 'El título es obligatorio.';
        } elseif (empty($campos)) {
            $error = 'Debes agregar al menos un campo.';
        } else {
            $camposJson = json_encode($campos);
            $rolesJson = json_encode(['all']);
            
            $stmt = $pdo->prepare("INSERT INTO formularios (titulo, descripcion, campos, roles_permitidos, es_publico, esta_activo, fecha_limite, created_by) VALUES (?, ?, ?, ?, ?, 1, ?, ?)");
            $result = $stmt->execute([$titulo, $descripcion, $camposJson, $rolesJson, $es_publico, $fecha_limite, $user['id']]);
            
            if ($result) {
                $success = 'Formulario creado exitosamente.';
            } else {
                $error = 'Error al crear el formulario.';
            }
        }
    }
    
    // Update form
    elseif ($action === 'update') {
        $formId = (int)$_POST['form_id'];
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $esta_activo = isset($_POST['esta_activo']) ? 1 : 0;
        $fecha_limite = !empty($_POST['fecha_limite']) ? $_POST['fecha_limite'] : null;
        
        // Build fields array
        $campos = [];
        if (!empty($_POST['campo_nombre'])) {
            foreach ($_POST['campo_nombre'] as $i => $nombre) {
                if (empty(trim($nombre))) continue;
                
                $campo = [
                    'name' => trim($nombre),
                    'type' => $_POST['campo_tipo'][$i] ?? 'text',
                    'required' => isset($_POST['campo_requerido'][$i]),
                    'options' => []
                ];
                
                if (in_array($campo['type'], ['select', 'radio']) && !empty($_POST['campo_opciones'][$i])) {
                    $opciones = explode(',', $_POST['campo_opciones'][$i]);
                    $campo['options'] = array_map('trim', $opciones);
                }
                
                $campos[] = $campo;
            }
        }
        
        if (empty($titulo) || empty($campos)) {
            $error = 'Título y al menos un campo son obligatorios.';
        } else {
            $camposJson = json_encode($campos);
            
            $stmt = $pdo->prepare("UPDATE formularios SET titulo = ?, descripcion = ?, campos = ?, es_publico = ?, esta_activo = ?, fecha_limite = ? WHERE id = ?");
            $result = $stmt->execute([$titulo, $descripcion, $camposJson, $es_publico, $esta_activo, $fecha_limite, $formId]);
            
            if ($result) {
                $success = 'Formulario actualizado exitosamente.';
            } else {
                $error = 'Error al actualizar el formulario.';
            }
        }
    }
    
    // Delete form
    elseif ($action === 'delete') {
        $formId = (int)$_POST['form_id'];
        $pdo->prepare("DELETE FROM respuestas_formularios WHERE formulario_id = ?")->execute([$formId]);
        $pdo->prepare("DELETE FROM formularios WHERE id = ?")->execute([$formId]);
        $success = 'Formulario eliminado.';
    }
    
    // Delete response
    elseif ($action === 'delete_response') {
        $responseId = (int)$_POST['response_id'];
        $pdo->prepare("DELETE FROM respuestas_formularios WHERE id = ?")->execute([$responseId]);
        $success = 'Respuesta eliminada.';
    }
}

// Get all forms
$formularios = $pdo->query("SELECT f.*, u.full_name as autor_nombre, 
    (SELECT COUNT(*) FROM respuestas_formularios WHERE formulario_id = f.id) as total_respuestas
    FROM formularios f 
    LEFT JOIN users u ON f.created_by = u.id 
    ORDER BY f.created_at DESC")->fetchAll();

// Get specific form for editing or viewing responses
$formEdit = null;
$formResponses = [];
$viewMode = $_GET['view'] ?? 'list';
$formId = (int)($_GET['id'] ?? 0);

if ($formId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM formularios WHERE id = ?");
    $stmt->execute([$formId]);
    $formEdit = $stmt->fetch();
    
    if ($formEdit && $viewMode === 'responses') {
        $stmt = $pdo->prepare("SELECT r.*, u.full_name as usuario_nombre 
            FROM respuestas_formularios r 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE r.formulario_id = ? 
            ORDER BY r.created_at DESC");
        $stmt->execute([$formId]);
        $formResponses = $stmt->fetchAll();
    }
}

$pageTitle = 'Gestión de Formularios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | MCA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f1f5f9; }
        .navbar-brand { font-weight: 600; }
        .gradient-header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .card { border: none; border-radius: 16px; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .form-card { transition: all 0.2s; }
        .form-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .field-item { background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; }
        .nav-tabs .nav-link { border: none; color: #64748b; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #4f46e5; border-bottom: 3px solid #4f46e5; background: transparent; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .table-responses th { background: #f8fafc; font-weight: 600; font-size: 0.85rem; }
        .response-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .empty-state { padding: 60px 20px; }
        .empty-state i { font-size: 4rem; opacity: 0.15; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-header shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/admin/formularios.php">
                <i class="bi bi-ui-checks-grid me-2"></i>Gestión de Formularios
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL ?>/admin/index.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Panel Admin
                </a>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <!-- Alerts -->
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="formTabs">
            <li class="nav-item">
                <a class="nav-link <?= $viewMode === 'list' && !$formId ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/formularios.php">
                    <i class="bi bi-list-ul me-2"></i>Mis Formularios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $viewMode === 'create' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/formularios.php?view=create">
                    <i class="bi bi-plus-circle me-2"></i>Crear Formulario
                </a>
            </li>
            <?php if ($formEdit && $viewMode === 'edit'): ?>
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-pencil me-2"></i>Editar: <?= sanitize(substr($formEdit['titulo'], 0, 20)) ?>...
                </a>
            </li>
            <?php endif; ?>
            <?php if ($formEdit && $viewMode === 'responses'): ?>
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-table me-2"></i>Respuestas: <?= sanitize(substr($formEdit['titulo'], 0, 20)) ?>...
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <?php if ($viewMode === 'list' && !$formId): ?>
        <!-- Forms List View -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="bi bi-ui-checks fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= count($formularios) ?></h3>
                            <small class="text-muted">Total Formularios</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= count(array_filter($formularios, fn($f) => $f['esta_activo'])) ?></h3>
                            <small class="text-muted">Activos</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-info text-white me-3">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= array_sum(array_column($formularios, 'total_respuestas')) ?></h3>
                            <small class="text-muted">Total Respuestas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($formularios)): ?>
        <div class="row g-4">
            <?php foreach ($formularios as $form): 
                $campos = json_decode($form['campos'], true) ?? [];
            ?>
            <div class="col-lg-6">
                <div class="card form-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                                    <i class="bi bi-ui-checks text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?= sanitize($form['titulo']) ?></h6>
                                    <small class="text-muted"><?= count($campos) ?> campos</small>
                                </div>
                            </div>
                            <span class="badge <?= $form['esta_activo'] ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $form['esta_activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </div>
                        
                        <?php if ($form['descripcion']): ?>
                        <p class="text-muted small mb-3"><?= sanitize(substr($form['descripcion'], 0, 100)) ?>...</p>
                        <?php endif; ?>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-file-text me-1"></i><?= $form['total_respuestas'] ?> respuestas
                            </span>
                            <?php if ($form['es_publico']): ?>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="bi bi-globe me-1"></i>Público
                            </span>
                            <?php endif; ?>
                            <?php if ($form['fecha_limite']): ?>
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-calendar me-1"></i>Hasta <?= formatDate($form['fecha_limite']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="?view=responses&id=<?= $form['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-table me-1"></i>Ver Respuestas
                            </a>
                            <a href="?view=edit&id=<?= $form['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este formulario y todas sus respuestas?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="form_id" value="<?= $form['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0">
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i><?= sanitize($form['autor_nombre'] ?? 'Admin') ?> · 
                            <i class="bi bi-calendar ms-2 me-1"></i><?= formatDate($form['created_at']) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body empty-state text-center">
                <i class="bi bi-ui-checks-grid text-muted"></i>
                <h5 class="mt-3 text-muted">No hay formularios</h5>
                <p class="text-muted mb-3">Crea tu primer formulario para comenzar a recopilar datos.</p>
                <a href="?view=create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Crear Formulario
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php elseif ($viewMode === 'create' || ($viewMode === 'edit' && $formEdit)): ?>
        <!-- Create/Edit Form View -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-<?= $viewMode === 'create' ? 'plus-circle' : 'pencil' ?> me-2 text-primary"></i>
                    <?= $viewMode === 'create' ? 'Crear Nuevo Formulario' : 'Editar Formulario' ?>
                </h5>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $viewMode === 'create' ? 'create' : 'update' ?>">
                <?php if ($formEdit): ?>
                <input type="hidden" name="form_id" value="<?= $formEdit['id'] ?>">
                <?php endif; ?>
                
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Título del Formulario *</label>
                            <input type="text" name="titulo" class="form-control form-control-lg" required
                                value="<?= sanitize($formEdit['titulo'] ?? '') ?>" placeholder="Ej: Inscripción Retiro 2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Límite</label>
                            <input type="date" name="fecha_limite" class="form-control form-control-lg"
                                value="<?= $formEdit['fecha_limite'] ?? '' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" 
                                placeholder="Describe el propósito del formulario..."><?= sanitize($formEdit['descripcion'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch me-4 d-inline-block">
                                <input type="checkbox" name="es_publico" class="form-check-input" id="esPublico"
                                    <?= ($formEdit['es_publico'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="esPublico">Formulario Público</label>
                            </div>
                            <?php if ($formEdit): ?>
                            <div class="form-check form-switch d-inline-block">
                                <input type="checkbox" name="esta_activo" class="form-check-input" id="estaActivo"
                                    <?= ($formEdit['esta_activo'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="estaActivo">Activo</label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-list-check me-2 text-primary"></i>Campos del Formulario
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addField()">
                            <i class="bi bi-plus-lg me-1"></i>Agregar Campo
                        </button>
                    </div>
                    
                    <div id="fieldsContainer">
                        <?php 
                        $campos = $formEdit ? (json_decode($formEdit['campos'], true) ?? []) : [];
                        if (empty($campos)) $campos = [['name' => '', 'type' => 'text', 'required' => false, 'options' => []]];
                        foreach ($campos as $i => $campo): 
                        ?>
                        <div class="field-item p-3 mb-3" id="field_<?= $i ?>">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small fw-medium">Nombre del Campo</label>
                                    <input type="text" name="campo_nombre[]" class="form-control" 
                                        value="<?= sanitize($campo['name'] ?? '') ?>" placeholder="Ej: Nombre Completo" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">Tipo</label>
                                    <select name="campo_tipo[]" class="form-select" onchange="toggleOptions(this)">
                                        <option value="text" <?= ($campo['type'] ?? '') === 'text' ? 'selected' : '' ?>>Texto</option>
                                        <option value="number" <?= ($campo['type'] ?? '') === 'number' ? 'selected' : '' ?>>Número</option>
                                        <option value="email" <?= ($campo['type'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                        <option value="tel" <?= ($campo['type'] ?? '') === 'tel' ? 'selected' : '' ?>>Teléfono</option>
                                        <option value="date" <?= ($campo['type'] ?? '') === 'date' ? 'selected' : '' ?>>Fecha</option>
                                        <option value="textarea" <?= ($campo['type'] ?? '') === 'textarea' ? 'selected' : '' ?>>Texto Largo</option>
                                        <option value="select" <?= ($campo['type'] ?? '') === 'select' ? 'selected' : '' ?>>Lista Desplegable</option>
                                        <option value="radio" <?= ($campo['type'] ?? '') === 'radio' ? 'selected' : '' ?>>Opción Única</option>
                                        <option value="checkbox" <?= ($campo['type'] ?? '') === 'checkbox' ? 'selected' : '' ?>>Casilla Sí/No</option>
                                    </select>
                                </div>
                                <div class="col-md-3 options-container" style="<?= in_array($campo['type'] ?? '', ['select', 'radio']) ? '' : 'display:none' ?>">
                                    <label class="form-label small fw-medium">Opciones (separar con coma)</label>
                                    <input type="text" name="campo_opciones[]" class="form-control" 
                                        value="<?= sanitize(implode(', ', $campo['options'] ?? [])) ?>" placeholder="Opción1, Opción2">
                                </div>
                                <div class="col-md-3 no-options-container" style="<?= in_array($campo['type'] ?? '', ['select', 'radio']) ? 'display:none' : '' ?>">
                                    <input type="hidden" name="campo_opciones[]" value="">
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="campo_requerido[<?= $i ?>]" class="form-check-input" 
                                            <?= ($campo['required'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label small">Obligatorio</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField(<?= $i ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/admin/formularios.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= $viewMode === 'create' ? 'Crear Formulario' : 'Guardar Cambios' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php elseif ($viewMode === 'responses' && $formEdit): ?>
        <!-- View Responses -->
        <?php $campos = json_decode($formEdit['campos'], true) ?? []; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><?= sanitize($formEdit['titulo']) ?></h4>
                <p class="text-muted mb-0"><?= count($formResponses) ?> respuestas recibidas</p>
            </div>
            <div class="d-flex gap-2">
                <a href="?view=edit&id=<?= $formEdit['id'] ?>" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Editar Formulario
                </a>
                <button type="button" class="btn btn-success" onclick="exportToCSV()">
                    <i class="bi bi-download me-2"></i>Exportar CSV
                </button>
            </div>
        </div>
        
        <?php if (!empty($formResponses)): ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="responsesTable">
                        <thead class="table-responses">
                            <tr>
                                <th class="ps-4">#</th>
                                <?php foreach ($campos as $campo): ?>
                                <th><?= sanitize($campo['name']) ?></th>
                                <?php endforeach; ?>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th class="pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formResponses as $idx => $resp): 
                                $respuestas = json_decode($resp['respuestas'], true) ?? [];
                            ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= $idx + 1 ?></td>
                                <?php foreach ($campos as $i => $campo): ?>
                                <td class="response-cell" title="<?= sanitize($respuestas[$i] ?? '-') ?>">
                                    <?= sanitize($respuestas[$i] ?? '-') ?>
                                </td>
                                <?php endforeach; ?>
                                <td>
                                    <?php if ($resp['usuario_nombre']): ?>
                                    <span class="badge bg-light text-dark"><?= sanitize($resp['usuario_nombre']) ?></span>
                                    <?php else: ?>
                                    <span class="text-muted small">Anónimo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?= formatDate($resp['created_at']) ?></td>
                                <td class="pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                        onclick="viewResponse(<?= htmlspecialchars(json_encode($respuestas)) ?>, <?= htmlspecialchars(json_encode(array_column($campos, 'name'))) ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta respuesta?')">
                                        <input type="hidden" name="action" value="delete_response">
                                        <input type="hidden" name="response_id" value="<?= $resp['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body empty-state text-center">
                <i class="bi bi-inbox text-muted"></i>
                <h5 class="mt-3 text-muted">Sin respuestas</h5>
                <p class="text-muted">Este formulario aún no ha recibido respuestas.</p>
                <a href="<?= BASE_URL ?>/formularios.php" class="btn btn-primary" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Ver Formulario Público
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- View Response Modal -->
        <div class="modal fade" id="viewResponseModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Detalle de Respuesta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="responseDetail"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let fieldIndex = <?= count($campos ?? []) ?>;
        
        function addField() {
            const container = document.getElementById('fieldsContainer');
            const html = `
                <div class="field-item p-3 mb-3" id="field_${fieldIndex}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Nombre del Campo</label>
                            <input type="text" name="campo_nombre[]" class="form-control" placeholder="Ej: Nombre Completo" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Tipo</label>
                            <select name="campo_tipo[]" class="form-select" onchange="toggleOptions(this)">
                                <option value="text">Texto</option>
                                <option value="number">Número</option>
                                <option value="email">Email</option>
                                <option value="tel">Teléfono</option>
                                <option value="date">Fecha</option>
                                <option value="textarea">Texto Largo</option>
                                <option value="select">Lista Desplegable</option>
                                <option value="radio">Opción Única</option>
                                <option value="checkbox">Casilla Sí/No</option>
                            </select>
                        </div>
                        <div class="col-md-3 options-container" style="display:none">
                            <label class="form-label small fw-medium">Opciones (separar con coma)</label>
                            <input type="text" name="campo_opciones[]" class="form-control" placeholder="Opción1, Opción2">
                        </div>
                        <div class="col-md-3 no-options-container">
                            <input type="hidden" name="campo_opciones[]" value="">
                        </div>
                        <div class="col-auto">
                            <div class="form-check">
                                <input type="checkbox" name="campo_requerido[${fieldIndex}]" class="form-check-input">
                                <label class="form-check-label small">Obligatorio</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField(${fieldIndex})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            fieldIndex++;
        }
        
        function removeField(id) {
            const fields = document.querySelectorAll('.field-item');
            if (fields.length > 1) {
                document.getElementById('field_' + id)?.remove();
            } else {
                alert('El formulario debe tener al menos un campo.');
            }
        }
        
        function toggleOptions(select) {
            const row = select.closest('.row');
            const optionsContainer = row.querySelector('.options-container');
            const noOptionsContainer = row.querySelector('.no-options-container');
            const needsOptions = ['select', 'radio'].includes(select.value);
            
            optionsContainer.style.display = needsOptions ? '' : 'none';
            noOptionsContainer.style.display = needsOptions ? 'none' : '';
        }
        
        function viewResponse(respuestas, campos) {
            let html = '<dl class="row mb-0">';
            campos.forEach((campo, i) => {
                html += `<dt class="col-sm-5 text-muted">${campo}</dt>`;
                html += `<dd class="col-sm-7 fw-medium">${respuestas[i] || '-'}</dd>`;
            });
            html += '</dl>';
            document.getElementById('responseDetail').innerHTML = html;
            new bootstrap.Modal(document.getElementById('viewResponseModal')).show();
        }
        
        function exportToCSV() {
            const table = document.getElementById('responsesTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const rowData = [];
                cols.forEach((col, idx) => {
                    if (idx < cols.length - 1) { // Skip actions column
                        let text = col.innerText.replace(/"/g, '""');
                        rowData.push('"' + text + '"');
                    }
                });
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'respuestas_<?= $formEdit['id'] ?? 'form' ?>_<?= date('Y-m-d') ?>.csv';
            link.click();
        }
    </script>
</body>
</html>
