<?php
require_once __DIR__ . '/../includes/functions.php';
requireRole(['supervisor']);

$user = getCurrentUser();
$pdo = getConnection();

$lideres = getSupervisorLeaders($user['id']);
$year = (int)($_GET['year'] ?? date('Y'));

$mesSeleccionado = (int)($_GET['mes'] ?? date('n'));
$liderSeleccionado = $_GET['lider'] ?? 'todos';

$monthNames = ['', 'Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
$monthNamesFull = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];


// ================= ESTADÍSTICAS LÍDERES =================
$lideresStats = [];

foreach ($lideres as $lider) {
    $monthlyData = [];
    $monthlyInformes = [];
    $monthlyDetalle = [];
    $mesesConDatos = 0;
    $sumaPromedios = 0;

    for ($m = 1; $m <= 12; $m++) {
        $stats = getMonthlyStats($lider['id'], $m, $year);
        $informes = (int)($stats['informes_enviados'] ?? 0);

        if ($informes > 0) {
            $prom = (float)$stats['promedio_asistencia'];
            $monthlyData[$m] = $prom;
            $sumaPromedios += $prom;
            $mesesConDatos++;
        } else {
            $monthlyData[$m] = null; // 🔹 CLAVE PARA N/H
        }

        $monthlyInformes[$m] = $informes;
        $monthlyDetalle[$m] = $stats;
    }

    $promedioAnual = $mesesConDatos > 0 ? round($sumaPromedios / $mesesConDatos,1) : null;

    $lideresStats[] = [
        'lider'=>$lider,
        'data'=>$monthlyData,
        'informes'=>$monthlyInformes,
        'detalle'=>$monthlyDetalle,
        'promedio_anual'=>$promedioAnual,
        'total_informes'=>array_sum($monthlyInformes)
    ];
}

usort($lideresStats, fn($a,$b)=>($b['promedio_anual']??0)<=>($a['promedio_anual']??0));


// ================= MIEMBROS =================
$miembrosStats=[];
$liderNombre='';

if($liderSeleccionado!=='todos'){
    $miembrosStats=getMemberAttendanceStats($liderSeleccionado,$mesSeleccionado,$year);
    foreach($lideres as $l){
        if($l['id']==$liderSeleccionado){$liderNombre=$l['full_name'];break;}
    }
}

$pageTitle="Estadísticas";
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
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <div>
            <h1><?= $pageTitle ?></h1>
            <p class="text-muted mb-0">Año <?= $year ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="?year=<?= $year - 1 ?>" class="btn btn-outline-secondary">
                <i class="bi bi-chevron-left"></i> <?= $year - 1 ?>
            </a>
            <?php if ($year < date('Y')): ?>
            <a href="?year=<?= $year + 1 ?>" class="btn btn-outline-secondary">
                <?= $year + 1 ?> <i class="bi bi-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Comparison Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Comparación de Líderes</h5>
                </div>
                <div class="card-body">
                    <canvas id="comparisonChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Leader Rankings -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Ranking Anual</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($lideresStats as $index => $ls): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge <?= $index === 0 ? 'bg-warning text-dark' : 'bg-primary' ?> rounded-circle me-3" style="width: 30px; height: 30px; line-height: 20px;">
                                    <?= $index + 1 ?>
                                </span>
                                <span><?= sanitize($ls['lider']['full_name']) ?></span>
                            </div>
                            <span class="fw-bold"><?= $ls['promedio_anual'] !== null ? round($ls['promedio_anual']) . '%' : '-' ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($lideresStats)): ?>
                        <div class="list-group-item text-center text-muted">Sin datos</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table: Attendance -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-bold">Asistencia Promedio por Mes</h5>
            <button class="btn btn-outline-danger btn-sm" onclick="downloadPdfAnual()">
                <i class="bi bi-file-earmark-pdf"></i> Descargar PDF Anual
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Líder</th>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <th class="text-center"><?= $monthNames[$m] ?></th>
                            <?php endfor; ?>
                            <th class="text-center">Prom.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lideresStats as $ls): ?>
                        <tr>
                            <td class="fw-medium"><?= sanitize($ls['lider']['full_name']) ?></td>
                            <?php for ($m = 1; $m <= 12; $m++):
                                $val = $ls['data'][$m];
                                $inf = $ls['informes'][$m];
                                if ($inf == 0 || $val === null) {
                                    $colorClass = 'text-muted';
                                } else {
                                    $colorClass = $val >= 80 ? 'text-success' : ($val >= 50 ? 'text-warning' : 'text-danger');
                                }
                                $display = ($inf > 0 && $val !== null) ? round($val) . '%' : '-';
                            ?>
                            <td class="text-center <?= $colorClass ?>"><?= $display ?></td>
                            <?php endfor; ?>
                            <td class="text-center fw-bold <?= $ls['promedio_anual'] >= 80 ? 'text-success' : ($ls['promedio_anual'] >= 50 ? 'text-warning' : 'text-danger') ?>"><?= $ls['promedio_anual'] ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly PDF Downloads -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Descargar Estadísticas Mensuales en PDF</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php for ($m = 1; $m <= 12; $m++):
                    $tieneDatos = false;
                    foreach ($lideresStats as $ls) {
                        if (($ls['informes'][$m] ?? 0) > 0) { $tieneDatos = true; break; }
                    }
                ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <button class="btn <?= $tieneDatos ? 'btn-outline-danger' : 'btn-outline-secondary' ?> w-100"
                            onclick="downloadPdfMensual(<?= $m ?>)" <?= !$tieneDatos ? 'disabled' : '' ?>>
                        <i class="bi bi-file-earmark-pdf"></i><br>
                        <small><?= $monthNamesFull[$m] ?></small>
                    </button>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Detailed Table: Reports Count -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Informes Enviados por Mes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Líder</th>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <th class="text-center"><?= $monthNames[$m] ?></th>
                            <?php endfor; ?>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lideresStats as $ls): ?>
                        <tr>
                            <td class="fw-medium"><?= sanitize($ls['lider']['full_name']) ?></td>
                            <?php for ($m = 1; $m <= 12; $m++):
                                $inf = $ls['informes'][$m];
                            ?>
                            <td class="text-center <?= $inf > 0 ? 'fw-medium' : 'text-muted' ?>"><?= $inf > 0 ? $inf : '-' ?></td>
                            <?php endfor; ?>
                            <td class="text-center fw-bold text-primary"><?= $ls['total_informes'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- NUEVA SECCIÓN: REGISTRO DE ASISTENCIA DETALLADO POR MIEMBRO -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-check"></i> Registro de Asistencia Detallado por Miembro</h5>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="row g-3 mb-4">
                <input type="hidden" name="year" value="<?= $year ?>">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Seleccionar Mes:</label>
                    <select name="mes" class="form-select" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $mesSeleccionado ? 'selected' : '' ?>>
                            <?= $monthNamesFull[$m] ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Seleccionar Líder:</label>
                    <select name="lider" class="form-select" onchange="this.form.submit()">
                        <option value="todos" <?= $liderSeleccionado === 'todos' ? 'selected' : '' ?>>-- Selecciona un líder --</option>
                        <?php foreach ($lideres as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= $l['id'] == $liderSeleccionado ? 'selected' : '' ?>>
                            <?= sanitize($l['full_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>

            <!-- Tabla de Miembros -->
            <?php if (!empty($miembrosStats)): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <strong>Líder:</strong> <?= sanitize($liderNombre) ?> | 
                    <strong>Mes:</strong> <?= $monthNamesFull[$mesSeleccionado] ?> <?= $year ?>
                </h6>
                <button class="btn btn-danger btn-sm" onclick="downloadPdfMiembros()">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-primary">
                        <tr class="text-center">
                            <th>Miembro</th>
                            <th>GF</th>
                            <th>Escuela</th>
                            <th>Red</th>
                            <th>Domingo</th>
                            <th>OMT</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($miembrosStats as $ms): 
                            $m = $ms['miembro'];
                            $colorGeneral = $ms['porcentaje_general'] >= 80 ? 'text-success' : ($ms['porcentaje_general'] >= 50 ? 'text-warning' : 'text-danger');
                        ?>
                        <tr>
                            <td class="fw-medium">
                                <?= sanitize($m['nombre']) ?>
                                <?php if ($m['es_consolidacion']): ?>
                                <span class="badge bg-warning text-dark">Consolidación</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center <?= $ms['porcentaje_gf'] >= 80 ? 'text-success' : ($ms['porcentaje_gf'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                <?= $ms['porcentaje_gf'] ?>%
                            </td>
                            <td class="text-center <?= $ms['porcentaje_escuela'] >= 80 ? 'text-success' : ($ms['porcentaje_escuela'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                <?= $ms['porcentaje_escuela'] ?>%
                            </td>
                            <td class="text-center <?= $ms['porcentaje_red'] >= 80 ? 'text-success' : ($ms['porcentaje_red'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                <?= $ms['porcentaje_red'] ?>%
                            </td>
                            <td class="text-center <?= $ms['porcentaje_domingo'] >= 80 ? 'text-success' : ($ms['porcentaje_domingo'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                <?= $ms['porcentaje_domingo'] ?>%
                            </td>
                            <td class="text-center <?= $ms['porcentaje_omt'] >= 80 ? 'text-success' : ($ms['porcentaje_omt'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                <?= $ms['porcentaje_omt'] ?>%
                            </td>
                            <td class="text-center fw-bold <?= $colorGeneral ?>">
                                <?= $ms['porcentaje_general'] ?>%
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    <span class="text-success">Verde (≥80%)</span> | 
                    <span class="text-warning">Amarillo (50-79%)</span> | 
                    <span class="text-danger">Rojo (<50%)</span>
                </small>
            </div>
            <?php elseif ($liderSeleccionado !== 'todos'): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay datos de asistencia para este líder en el mes seleccionado.
            </div>
            <?php else: ?>
            <div class="alert alert-secondary">
                <i class="bi bi-arrow-up"></i> Selecciona un líder y mes para ver el registro detallado de asistencia.
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script id="pdfDataAdmin" type="application/json"><?php
        echo json_encode([
            'year' => (int)$year,
            'lideres' => array_values(array_map(function($ls) {
                return [
                    'nombre' => $ls['lider']['full_name'] ?? 'Sin nombre',
                    'data' => $ls['data'] ?? [],
                    'informes' => $ls['informes'] ?? [],
                    'detalle' => $ls['detalle'] ?? [],
                    'promedio_anual' => $ls['promedio_anual'] ?? 0,
                    'total_informes' => $ls['total_informes'] ?? 0
                ];
            }, $lideresStats ?? []))
        ], JSON_UNESCAPED_UNICODE);
    ?></script>

    <script id="pdfDataMiembros" type="application/json"><?php
        // Ordenar para que los miembros nuevos aparezcan primero en el PDF
        if (!empty($miembrosStats) && is_array($miembrosStats)) {
            usort($miembrosStats, function($a, $b) {
                $ai = $a['miembro']['is_new'] ?? $a['is_new'] ?? 0;
                $bi = $b['miembro']['is_new'] ?? $b['is_new'] ?? 0;
                return $bi <=> $ai;
            });
        }

        echo json_encode([
            'year' => (int)$year,
            'mes' => (int)$mesSeleccionado,
            'mesNombre' => $monthNamesFull[$mesSeleccionado] ?? '',
            'liderNombre' => $liderNombre ?? '',
            'miembrosStats' => $miembrosStats ?? []
        ], JSON_UNESCAPED_UNICODE);
    ?></script>

    <!-- Gráfico (independiente) -->
    <?php if (!empty($lideresStats)): ?>
    <script>
    try {
        var chartEl = document.getElementById('comparisonChart');
        if (chartEl) {
            var ctx = chartEl.getContext('2d');
            var chartColors = [
                'rgb(99, 102, 241)', 'rgb(16, 185, 129)', 'rgb(245, 158, 11)',
                'rgb(239, 68, 68)', 'rgb(139, 92, 246)', 'rgb(6, 182, 212)',
                'rgb(236, 72, 153)', 'rgb(34, 197, 94)', 'rgb(251, 146, 60)',
                'rgb(59, 130, 246)'
            ];
            var chartData = JSON.parse(document.getElementById('pdfDataAdmin').textContent || '{}');
            var datasets = (chartData.lideres || []).slice(0, 10).map(function(l, i) {
                return {
                    label: l.nombre,
                    data: Object.values(l.data),
                    borderColor: chartColors[i % chartColors.length],
                    backgroundColor: chartColors[i % chartColors.length] + '20',
                    tension: 0.4
                };
            });
            new Chart(ctx, {
                type: 'line',
                data: { labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'], datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 100, ticks: { callback: function(v){return v+'%';} } } }
                }
            });
        }
    } catch(e) { console.warn('Error grafico:', e); }
    </script>
    <?php endif; ?>

    <!-- Funciones PDF (independiente - SIEMPRE se carga) -->
    <script>
    function downloadPdfAnual() {
        if (typeof window.jspdf === 'undefined') {
            alert('La libreria jsPDF no se ha cargado. Verifica tu conexion a internet y recarga la pagina.');
            return;
        }
        try {
            var rawData = document.getElementById('pdfDataAdmin');
            var pdfData = JSON.parse(rawData ? rawData.textContent : '{}');
            var year = pdfData.year || new Date().getFullYear();
            var lideresData = pdfData.lideres || [];

            var jsPDF = window.jspdf.jsPDF;
            var doc = new jsPDF('landscape', 'mm', 'a4');

            doc.setFontSize(18);
            doc.setTextColor(40, 40, 40);
            doc.text('MCA - Movilizacion Cristiana', 148, 15, { align: 'center' });
            doc.setFontSize(14);
            doc.text('Estadisticas de Asistencia - Ano ' + year, 148, 23, { align: 'center' });
            doc.setFontSize(9);
            doc.setTextColor(120, 120, 120);
            doc.text('Generado: ' + new Date().toLocaleDateString('es-CL'), 148, 29, { align: 'center' });

            var headers = [['Lider', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Prom.']];
            var rows = lideresData.map(function(l) {
                var row = [l.nombre];
                for (var m = 1; m <= 12; m++) {
                    row.push(l.informes[m] > 0 ? Math.round(l.data[m]) + '%' : '-');
                }
                row.push(l.promedio_anual + '%');
                return row;
            });

            doc.autoTable({
                head: headers,
                body: rows,
                startY: 34,
                theme: 'grid',
                styles: { fontSize: 8, cellPadding: 2, halign: 'center' },
                headStyles: { fillColor: [99, 102, 241], textColor: 255, fontStyle: 'bold' },
                columnStyles: { 0: { halign: 'left', cellWidth: 40 } },
                didParseCell: function(data) {
                    if (data.section === 'body' && data.column.index > 0) {
                        var val = parseFloat(data.cell.text[0]);
                        if (!isNaN(val)) {
                            if (val >= 80) data.cell.styles.textColor = [22, 163, 74];
                            else if (val >= 50) data.cell.styles.textColor = [202, 138, 4];
                            else data.cell.styles.textColor = [220, 38, 38];
                        }
                    }
                }
            });

            var finalY = doc.lastAutoTable.finalY + 10;
            doc.setFontSize(12);
            doc.setTextColor(40, 40, 40);
            doc.text('Informes Enviados por Mes', 14, finalY);
            finalY += 4;

            var headers2 = [['Lider', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Total']];
            var rows2 = lideresData.map(function(l) {
                var row = [l.nombre];
                for (var m = 1; m <= 12; m++) {
                    row.push(l.informes[m] > 0 ? l.informes[m] : '-');
                }
                row.push(l.total_informes);
                return row;
            });

            doc.autoTable({
                head: headers2,
                body: rows2,
                startY: finalY,
                theme: 'grid',
                styles: { fontSize: 8, cellPadding: 2, halign: 'center' },
                headStyles: { fillColor: [16, 185, 129], textColor: 255, fontStyle: 'bold' },
                columnStyles: { 0: { halign: 'left', cellWidth: 40 } }
            });

            doc.save('MCA_Estadisticas_Anual_' + year + '.pdf');
        } catch(e) {
            alert('Error al generar PDF: ' + e.message);
            console.error('PDF Error:', e);
        }
    }

    function downloadPdfMensual(mes) {
        if (typeof window.jspdf === 'undefined') {
            alert('La libreria jsPDF no se ha cargado. Verifica tu conexion a internet y recarga la pagina.');
            return;
        }
        try {
            var monthNamesFull = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            var rawData = document.getElementById('pdfDataAdmin');
            var pdfData = JSON.parse(rawData ? rawData.textContent : '{}');
            var year = pdfData.year || new Date().getFullYear();
            var lideresData = pdfData.lideres || [];

            var jsPDF = window.jspdf.jsPDF;
            var doc = new jsPDF('portrait', 'mm', 'a4');

            doc.setFontSize(18);
            doc.setTextColor(40, 40, 40);
            doc.text('MCA - Movilizacion Cristiana', 105, 15, { align: 'center' });
            doc.setFontSize(14);
            doc.text('Estadisticas - ' + monthNamesFull[mes] + ' ' + year, 105, 23, { align: 'center' });
            doc.setFontSize(9);
            doc.setTextColor(120, 120, 120);
            doc.text('Generado: ' + new Date().toLocaleDateString('es-CL'), 105, 29, { align: 'center' });

            var headers = [['Lider', 'G.F.', 'Escuela', 'Red', 'Domingo', 'OMT', 'Promedio', 'Informes']];
            var rows = [];

            lideresData.forEach(function(l) {
                if (l.informes[mes] > 0) {
                    var d = l.detalle[mes] || {};
                    rows.push([
                        l.nombre,
                        Math.round(d.promedio_grupo_familiar || 0) + '%',
                        Math.round(d.promedio_escuela || 0) + '%',
                        Math.round(d.promedio_reunion_red || 0) + '%',
                        Math.round(d.promedio_culto_domingo || 0) + '%',
                        Math.round(d.promedio_actividad_omt || 0) + '%',
                        Math.round(d.promedio_asistencia || 0) + '%',
                        l.informes[mes]
                    ]);
                }
            });

            if (rows.length === 0) {
                doc.setFontSize(12);
                doc.setTextColor(150, 150, 150);
                doc.text('No hay datos para este mes.', 105, 50, { align: 'center' });
            } else {
                doc.autoTable({
                    head: headers,
                    body: rows,
                    startY: 34,
                    theme: 'grid',
                    styles: { fontSize: 9, cellPadding: 3, halign: 'center' },
                    headStyles: { fillColor: [99, 102, 241], textColor: 255, fontStyle: 'bold' },
                    columnStyles: { 0: { halign: 'left', cellWidth: 45 } },
                    didParseCell: function(data) {
                        if (data.section === 'body' && data.column.index >= 1 && data.column.index <= 6) {
                            var val = parseFloat(data.cell.text[0]);
                            if (!isNaN(val)) {
                                if (val >= 80) data.cell.styles.textColor = [22, 163, 74];
                                else if (val >= 50) data.cell.styles.textColor = [202, 138, 4];
                                else data.cell.styles.textColor = [220, 38, 38];
                            }
                        }
                    }
                });

                var finalY = doc.lastAutoTable.finalY + 10;
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Resumen:', 14, finalY);
                finalY += 6;
                doc.setFontSize(9);
                doc.text('Total de lideres con informes: ' + rows.length, 14, finalY);
                finalY += 5;
                var promGeneral = rows.reduce(function(sum, r) { return sum + parseFloat(r[6]); }, 0) / rows.length;
                doc.text('Promedio general de asistencia: ' + Math.round(promGeneral) + '%', 14, finalY);
                finalY += 5;
                var totalInformes = rows.reduce(function(sum, r) { return sum + parseInt(r[7]); }, 0);
                doc.text('Total de informes enviados: ' + totalInformes, 14, finalY);
            }

            doc.save('MCA_Estadisticas_' + monthNamesFull[mes] + '_' + year + '.pdf');
        } catch(e) {
            alert('Error al generar PDF: ' + e.message);
            console.error('PDF Error:', e);
        }
    }

    function downloadPdfMiembros() {
        if (typeof window.jspdf === 'undefined') {
            alert('La librería jsPDF no se ha cargado. Verifica tu conexión a internet y recarga la página.');
            return;
        }
        try {
            const rawData = document.getElementById('pdfDataMiembros');
            if (!rawData) {
                alert('No se encontraron datos para generar el PDF.');
                return;
            }
            const pdfData = JSON.parse(rawData.textContent || '{}');
            const { year, mesNombre, liderNombre, miembrosStats } = pdfData;

            if (!miembrosStats || miembrosStats.length === 0) {
                alert('No hay estadísticas de miembros para descargar.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('portrait', 'mm', 'a4');

            // Header
            doc.setFontSize(18);
            doc.setTextColor(40, 40, 40);
            doc.text('MCA - Movilización Cristiana', 105, 15, { align: 'center' });
            doc.setFontSize(14);
            doc.text('Asistencia Detallada por Miembro', 105, 23, { align: 'center' });
            doc.setFontSize(11);
            doc.setTextColor(80, 80, 80);
            doc.text(`Líder: ${liderNombre}`, 105, 30, { align: 'center' });
            doc.text(`Mes: ${mesNombre} ${year}`, 105, 36, { align: 'center' });
            doc.setFontSize(9);
            doc.setTextColor(120, 120, 120);
            doc.text('Generado: ' + new Date().toLocaleDateString('es-CL'), 105, 42, { align: 'center' });

            // Table: mostrar primero miembros nuevos y marcarlos
            const headers = [['Miembro', 'GF %', 'Escuela %', 'Red %', 'Domingo %', 'OMT %', 'General %']];
            const nuevos = [];
            const otros = [];

            miembrosStats.forEach(ms => {
                let nombreMiembro = (ms.miembro && ms.miembro.nombre) || ms.miembro_nombre || '';
                if (ms.miembro && ms.miembro.es_consolidacion) nombreMiembro += ' (C)';
                const isNew = (ms.miembro && (ms.miembro.is_new === 1 || ms.miembro.is_new === '1')) || (ms.is_new === 1 || ms.is_new === '1');
                if (isNew) nombreMiembro += ' (Nuevo)';
                const row = [
                    nombreMiembro,
                    `${ms.porcentaje_gf}%`,
                    `${ms.porcentaje_escuela}%`,
                    `${ms.porcentaje_red}%`,
                    `${ms.porcentaje_domingo}%`,
                    `${ms.porcentaje_omt}%`,
                    `${ms.porcentaje_general}%`
                ];
                if (isNew) nuevos.push(row); else otros.push(row);
            });

            let cursorY = 50;
            if (nuevos.length > 0) {
                doc.setFontSize(12);
                doc.setTextColor(40, 40, 40);
                doc.text('-ESTADISTICAS NUEVOS MIEMBROS-', 105, cursorY, { align: 'center' });
                cursorY += 6;
                doc.autoTable({
                    head: headers,
                    body: nuevos,
                    startY: cursorY,
                    theme: 'grid',
                    styles: { fontSize: 9, cellPadding: 2, halign: 'center', valign: 'middle' },
                    headStyles: { fillColor: [23, 162, 184], textColor: 255, fontStyle: 'bold' },
                    columnStyles: { 0: { halign: 'left', cellWidth: 60, fontStyle: 'bold' } },
                    didParseCell: function(data) {
                        if (data.section === 'body' && data.column.index > 0) {
                            const val = parseFloat(data.cell.text[0]);
                            if (!isNaN(val)) {
                                if (val >= 80) data.cell.styles.textColor = [22, 163, 74];
                                else if (val >= 50) data.cell.styles.textColor = [255, 193, 7];
                                else data.cell.styles.textColor = [220, 53, 69];
                            }
                        }
                        if (data.section === 'body' && data.column.index === 6) { data.cell.styles.fontStyle = 'bold'; }
                    }
                });
                cursorY = doc.lastAutoTable.finalY + 10;
            }

            if (otros.length > 0) {
                doc.setFontSize(12);
                doc.setTextColor(40, 40, 40);
                doc.text('-ESTADISTICAS MIEMBROS-', 105, cursorY, { align: 'center' });
                cursorY += 6;
                doc.autoTable({
                    head: headers,
                    body: otros,
                    startY: cursorY,
                    theme: 'grid',
                    styles: { fontSize: 9, cellPadding: 2, halign: 'center', valign: 'middle' },
                    headStyles: { fillColor: [23, 162, 184], textColor: 255, fontStyle: 'bold' },
                    columnStyles: { 0: { halign: 'left', cellWidth: 60 } },
                    didParseCell: function(data) {
                        if (data.section === 'body' && data.column.index > 0) {
                            const val = parseFloat(data.cell.text[0]);
                            if (!isNaN(val)) {
                                if (val >= 80) data.cell.styles.textColor = [22, 163, 74];
                                else if (val >= 50) data.cell.styles.textColor = [255, 193, 7];
                                else data.cell.styles.textColor = [220, 53, 69];
                            }
                        }
                        if (data.section === 'body' && data.column.index === 6) { data.cell.styles.fontStyle = 'bold'; }
                    }
                });
                cursorY = doc.lastAutoTable.finalY + 10;
            }

            if (nuevos.length === 0 && otros.length === 0) {
                alert('No hay estadísticas de miembros para descargar.');
                return;
            }

            let finalY = doc.lastAutoTable ? doc.lastAutoTable.finalY + 10 : cursorY;
            doc.setFontSize(9);
            doc.setTextColor(100, 100, 100);
            doc.text('Leyenda: (C) = Miembro en Consolidación. (Nuevo) = Miembro recién agregado.', 14, finalY);

            const safeLiderNombre = liderNombre.replace(/[^a-z0-9]/gi, '_').toLowerCase();
            doc.save(`MCA_Asistencia_Miembros_${safeLiderNombre}_${mesNombre}_${year}.pdf`);

        } catch(e) {
            alert('Error al generar PDF: ' + e.message);
            console.error('PDF Error:', e);
        }
    }
    </script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
