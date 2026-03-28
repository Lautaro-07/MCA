<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['supervisor']);

$user = getCurrentUser();
$pdo = getConnection();
$error = '';
$success = '';

// Get supervisor's leaders
$lideres = getSupervisorLeaders($user['id']);

// Get current week info
$weekRange = getWeekRange();

// Check if editing existing report
$informeId = (int)($_GET['id'] ?? 0);
$informe = null;
$asistencias = [];

if ($informeId) {
    $stmt = $pdo->prepare("SELECT * FROM informes_supervisores WHERE id = ? AND supervisor_id = ?");
    $stmt->execute([$informeId, $user['id']]);
    $informe = $stmt->fetch();
    
    if ($informe) {
        // Get attendance records
        $stmt = $pdo->prepare("SELECT * FROM asistencia_supervisores WHERE informe_id = ?");
        $stmt->execute([$informeId]);
        while ($row = $stmt->fetch()) {
            $asistencias[$row['lider_id']] = $row;
        }
    }
} else {
    // Check for existing report this week
    $stmt = $pdo->prepare("SELECT * FROM informes_supervisores WHERE supervisor_id = ? AND semana_inicio = ?");
    $stmt->execute([$user['id'], $weekRange['inicio']]);
    $informe = $stmt->fetch();
    
    if ($informe) {
        $informeId = $informe['id'];
        $stmt = $pdo->prepare("SELECT * FROM asistencia_supervisores WHERE informe_id = ?");
        $stmt->execute([$informeId]);
        while ($row = $stmt->fetch()) {
            $asistencias[$row['lider_id']] = $row;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'guardar';
    
    // Report header data
    $anfitrion = trim($_POST['anfitrion'] ?? '');
    $fechaReunion = $_POST['fecha_reunion'] ?? null;
    $direccion = trim($_POST['direccion'] ?? '');
    $horaInicio = $_POST['hora_inicio'] ?? null;
    $horaTermino = $_POST['hora_termino'] ?? null;
    $nuevosEvangelizados = (int)($_POST['nuevos_evangelizados'] ?? 0);
    $confesionesFe = (int)($_POST['confesiones_fe'] ?? 0);
    $temaExpuesto = trim($_POST['tema_expuesto'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');
    $ofrenda = floatval($_POST['ofrenda'] ?? 0);
    $proximaFechaOmt = $_POST['proxima_fecha_omt'] ?? null;
    $estado = $action === 'completar' ? 'completado' : 'borrador';
    
    // Counts
    $totalLeaders = count($lideres);
    $totalAsistentes = 0;
    
    foreach ($lideres as $l) {
        $gf = $_POST['asistencia'][$l['id']]['grupo_familiar'] ?? 'no';
        if ($gf === 'si') $totalAsistentes++;
    }
    
    if ($informeId) {
        // Update existing report
        $stmt = $pdo->prepare("
            UPDATE informes_supervisores SET 
                anfitrion = ?, fecha_reunion = ?, direccion = ?, hora_inicio = ?, hora_termino = ?,
                total_miembros = ?, total_asistentes = ?, nuevos_evangelizados = ?,
                confesiones_fe = ?, tema_expuesto = ?, observaciones = ?, ofrenda = ?, proxima_fecha_omt = ?,
                estado = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $anfitrion, $fechaReunion, $direccion, $horaInicio, $horaTermino,
            $totalLeaders, $totalAsistentes, $nuevosEvangelizados,
            $confesionesFe, $temaExpuesto, $observaciones, $ofrenda, $proximaFechaOmt,
            $estado, $informeId
        ]);
    } else {
        // Create new report
        $stmt = $pdo->prepare("
            INSERT INTO informes_supervisores 
            (supervisor_id, semana_inicio, semana_fin, anfitrion, fecha_reunion, direccion, hora_inicio, hora_termino,
             total_miembros, total_asistentes, nuevos_evangelizados, confesiones_fe,
             tema_expuesto, observaciones, ofrenda, proxima_fecha_omt, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user['id'], $weekRange['inicio'], $weekRange['fin'],
            $anfitrion, $fechaReunion, $direccion, $horaInicio, $horaTermino,
            $totalLeaders, $totalAsistentes, $nuevosEvangelizados,
            $confesionesFe, $temaExpuesto, $observaciones, $ofrenda, $proximaFechaOmt, $estado
        ]);
        $informeId = $pdo->lastInsertId();
    }
    
    // Save attendance records
    foreach ($lideres as $l) {
        $grupoFamiliar = $_POST['asistencia'][$l['id']]['grupo_familiar'] ?? 'no';
        $escuela = $_POST['asistencia'][$l['id']]['escuela'] ?? 'no';
        $reunionRed = $_POST['asistencia'][$l['id']]['reunion_red'] ?? 'no';
        $cultoDomingo = $_POST['asistencia'][$l['id']]['culto_domingo'] ?? 'no';
        $actividadOmt = $_POST['asistencia'][$l['id']]['actividad_omt'] ?? 'no';
        $fechaLlamada = $_POST['asistencia'][$l['id']]['fecha_llamada'] ?? null;
        $notaConsolidacion = (int)($_POST['asistencia'][$l['id']]['nota_consolidacion'] ?? 0);
        
        $attendance = [
            'grupo_familiar' => $grupoFamiliar,
            'escuela' => $escuela,
            'reunion_red' => $reunionRed,
            'culto_domingo' => $cultoDomingo,
            'actividad_omt' => $actividadOmt
        ];
        $porcentaje = calculateAttendancePercentage($attendance);
        
        $stmt = $pdo->prepare("SELECT id FROM asistencia_supervisores WHERE informe_id = ? AND lider_id = ?");
        $stmt->execute([$informeId, $l['id']]);
        
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare("                UPDATE asistencia_supervisores SET 
                    grupo_familiar = ?, escuela = ?, reunion_red = ?, culto_domingo = ?, 
                    actividad_omt = ?, porcentaje_asistencia = ?, fecha_llamada_visita = ?, nota_consolidacion = ?
                WHERE informe_id = ? AND lider_id = ?
            ");
            $stmt->execute([
                $grupoFamiliar, $escuela, $reunionRed, $cultoDomingo, $actividadOmt,
                $porcentaje, $fechaLlamada, $notaConsolidacion, $informeId, $l['id']
            ]);
        } else {
            $stmt = $pdo->prepare("                INSERT INTO asistencia_supervisores 
                (informe_id, lider_id, grupo_familiar, escuela, reunion_red, culto_domingo, 
                 actividad_omt, porcentaje_asistencia, fecha_llamada_visita, nota_consolidacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $informeId, $l['id'], $grupoFamiliar, $escuela, $reunionRed, $cultoDomingo,
                $actividadOmt, $porcentaje, $fechaLlamada, $notaConsolidacion
            ]);
        }
    }
    
    if ($estado === 'completado') {
        generateMonthlyStatsSupervisor($user['id'], date('n'), date('Y'));
    }
    
    logActivity($user['id'], 'informe_supervisor', "Informe supervisor " . ($estado === 'completado' ? 'completado' : 'guardado'));
    
    $success = $estado === 'completado' ? '¡Informe completado exitosamente!' : 'Informe guardado como borrador.';
    
    // Refresh data
    $stmt = $pdo->prepare("SELECT * FROM informes_supervisores WHERE id = ?");
    $stmt->execute([$informeId]);
    $informe = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT * FROM asistencia_supervisores WHERE informe_id = ?");
    $stmt->execute([$informeId]);
    $asistencias = [];
    while ($row = $stmt->fetch()) {
        $asistencias[$row['lider_id']] = $row;
    }
}

$pageTitle = 'Informe Semanal de Supervisores';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | MCA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <h1><?= $pageTitle ?></h1>
                <p class="text-muted mb-0">Semana: <?= formatDate($weekRange['inicio']) ?> - <?= formatDate($weekRange['fin']) ?></p>
            </div>
            <?php if ($informe && $informe['estado'] === 'completado'): ?>
            <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Completado</span>
            <?php endif; ?>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (empty($lideres)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No tienes líderes asignados. <a href="<?= BASE_URL ?>/supervisor/lideres.php">Agregar líderes</a> antes de crear un informe.
        </div>
        <?php else: ?>
        
        <form method="POST" class="report-form">
            <!-- Report Header -->
            <div class="report-section">
                <h4><i class="bi bi-info-circle"></i> Información General</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Supervisor</label>
                        <input type="text" class="form-control" value="<?= sanitize($user['full_name']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anfitrión</label>
                        <input type="text" name="anfitrion" class="form-control" value="<?= sanitize($informe['anfitrion'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de Reunión</label>
                        <input type="date" name="fecha_reunion" class="form-control" value="<?= $informe['fecha_reunion'] ?? '' ?>">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?= sanitize($informe['direccion'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hora Inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" value="<?= $informe['hora_inicio'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hora Término</label>
                        <input type="time" name="hora_termino" class="form-control" value="<?= $informe['hora_termino'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nuevos Evangelizados</label>
                        <input type="number" name="nuevos_evangelizados" class="form-control" min="0" value="<?= $informe['nuevos_evangelizados'] ?? 0 ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Confesiones de Fe</label>
                        <input type="number" name="confesiones_fe" class="form-control" min="0" value="<?= $informe['confesiones_fe'] ?? 0 ?>">
                    </div>
                </div>
            </div>
            
            <!-- Attendance Table -->
            <div class="report-section">
                <h4><i class="bi bi-people"></i> Asistencia (SI/NO/No Hubo)</h4>
                <div class="table-responsive">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 200px;">Líder</th>
                                <th>Grupo Familiar</th>
                                <th>Escuela</th>
                                <th>Reunión Red</th>
                                <th>Culto Domingo</th>
                                <th>Actividad OMT</th>
                                <th>Bautizado</th>
                                <th>% Informe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lideres as $l): 
                                $att = $asistencias[$l['id']] ?? [];
                            ?>
                            <tr>
                                <td>
                                    <?= sanitize($l['full_name']) ?>
                                </td>
                                <td>
                                    <div class="attendance-toggle">
                                        <input type="hidden" name="asistencia[<?= $l['id'] ?>][grupo_familiar]" value="<?= $att['grupo_familiar'] ?? 'no' ?>">
                                        <button type="button" class="btn btn-sm <?= ($att['grupo_familiar'] ?? 'no') === 'si' ? 'btn-success active' : 'btn-outline-secondary' ?>" data-value="si">Sí</button>
                                        <button type="button" class="btn btn-sm <?= ($att['grupo_familiar'] ?? 'no') === 'no' ? 'btn-danger active' : 'btn-outline-secondary' ?>" data-value="no">No</button>
                                        <button type="button" class="btn btn-sm <?= ($att['grupo_familiar'] ?? 'no') === 'no_hubo' ? 'btn-warning active' : 'btn-outline-secondary' ?>" data-value="no_hubo">N/H</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="attendance-toggle">
                                        <input type="hidden" name="asistencia[<?= $l['id'] ?>][escuela]" value="<?= $att['escuela'] ?? 'no' ?>">
                                        <button type="button" class="btn btn-sm <?= ($att['escuela'] ?? 'no') === 'si' ? 'btn-success active' : 'btn-outline-secondary' ?>" data-value="si">Sí</button>
                                        <button type="button" class="btn btn-sm <?= ($att['escuela'] ?? 'no') === 'no' ? 'btn-danger active' : 'btn-outline-secondary' ?>" data-value="no">No</button>
                                        <button type="button" class="btn btn-sm <?= ($att['escuela'] ?? 'no') === 'no_hubo' ? 'btn-warning active' : 'btn-outline-secondary' ?>" data-value="no_hubo">N/H</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="attendance-toggle">
                                        <input type="hidden" name="asistencia[<?= $l['id'] ?>][reunion_red]" value="<?= $att['reunion_red'] ?? 'no' ?>">
                                        <button type="button" class="btn btn-sm <?= ($att['reunion_red'] ?? 'no') === 'si' ? 'btn-success active' : 'btn-outline-secondary' ?>" data-value="si">Sí</button>
                                        <button type="button" class="btn btn-sm <?= ($att['reunion_red'] ?? 'no') === 'no' ? 'btn-danger active' : 'btn-outline-secondary' ?>" data-value="no">No</button>
                                        <button type="button" class="btn btn-sm <?= ($att['reunion_red'] ?? 'no') === 'no_hubo' ? 'btn-warning active' : 'btn-outline-secondary' ?>" data-value="no_hubo">N/H</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="attendance-toggle">
                                        <input type="hidden" name="asistencia[<?= $l['id'] ?>][culto_domingo]" value="<?= $att['culto_domingo'] ?? 'no' ?>">
                                        <button type="button" class="btn btn-sm <?= ($att['culto_domingo'] ?? 'no') === 'si' ? 'btn-success active' : 'btn-outline-secondary' ?>" data-value="si">Sí</button>
                                        <button type="button" class="btn btn-sm <?= ($att['culto_domingo'] ?? 'no') === 'no' ? 'btn-danger active' : 'btn-outline-secondary' ?>" data-value="no">No</button>
                                        <button type="button" class="btn btn-sm <?= ($att['culto_domingo'] ?? 'no') === 'no_hubo' ? 'btn-warning active' : 'btn-outline-secondary' ?>" data-value="no_hubo">N/H</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="attendance-toggle">
                                        <input type="hidden" name="asistencia[<?= $l['id'] ?>][actividad_omt]" value="<?= $att['actividad_omt'] ?? 'no' ?>">
                                        <button type="button" class="btn btn-sm <?= ($att['actividad_omt'] ?? 'no') === 'si' ? 'btn-success active' : 'btn-outline-secondary' ?>" data-value="si">Sí</button>
                                        <button type="button" class="btn btn-sm <?= ($att['actividad_omt'] ?? 'no') === 'no' ? 'btn-danger active' : 'btn-outline-secondary' ?>" data-value="no">No</button>
                                        <button type="button" class="btn btn-sm <?= ($att['actividad_omt'] ?? 'no') === 'no_hubo' ? 'btn-warning active' : 'btn-outline-secondary' ?>" data-value="no_hubo">N/H</button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">-</span>
                                </td>
                                <td class="percentage fw-bold"><?= isset($att['porcentaje_asistencia']) ? round($att['porcentaje_asistencia']) . '%' : '0%' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="report-section">
                <h4><i class="bi bi-file-text"></i> Información Adicional</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tema Expuesto</label>
                        <input type="text" name="tema_expuesto" class="form-control" value="<?= sanitize($informe['tema_expuesto'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ofrenda</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="ofrenda" class="form-control" min="0" step="0.01" value="<?= $informe['ofrenda'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Próxima Fecha OMT</label>
                        <input type="date" name="proxima_fecha_omt" class="form-control" value="<?= $informe['proxima_fecha_omt'] ?? '' ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3"><?= sanitize($informe['observaciones'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-flex gap-3 justify-content-end">
                <button type="submit" name="action" value="guardar" class="btn btn-outline-primary">
                    <i class="bi bi-save me-2"></i>Guardar Borrador
                </button>
                <button type="submit" name="action" value="completar" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Completar Informe
                </button>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
