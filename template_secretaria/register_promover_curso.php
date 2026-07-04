<?php
session_start();
include("../conexion/conexion.php");

$periodo_activo = null;
$periodo_activo_id = 0;
$descripcion_periodo_activo = '';
$curso_origen_id = isset($_GET['curso_origen']) ? (int) $_GET['curso_origen'] : 0;
$cursos_activos = [];
$curso_origen = null;
$estudiantes = [];
$mensaje_error = '';

$sql_periodo_activo = "SELECT id_periodo_lectivo, descripcion, observacion
                       FROM periodo_lectivo
                       WHERE estado = 'ACTIVO'
                       ORDER BY id_periodo_lectivo DESC
                       LIMIT 1";
$result_periodo_activo = mysqli_query($conn, $sql_periodo_activo);

if ($result_periodo_activo && mysqli_num_rows($result_periodo_activo) > 0) {
    $periodo_activo = mysqli_fetch_assoc($result_periodo_activo);
    $periodo_activo_id = (int) $periodo_activo['id_periodo_lectivo'];
    $descripcion_periodo_activo = trim((string) ($periodo_activo['observacion'] ?: ''));
    if ($descripcion_periodo_activo === '') {
        $descripcion_periodo_activo = trim((string) ($periodo_activo['descripcion'] ?: ''));
    }
}

if ($periodo_activo_id > 0) {
    $sql_cursos = "SELECT jc.id_jornada_curso,
                          jc.nivel,
                          jc.jornada,
                          jc.curso,
                          jc.paralelo,
                          jc.id_periodo_lectivo,
                          COALESCE(NULLIF(pl.observacion, ''), NULLIF(pl.descripcion, ''), jc.periodo) AS periodo_mostrar,
                          COALESCE(CONCAT(d.dst_nombres, ' ', d.dst_apellidos), 'Sin asignar') AS tutor,
                          CONCAT(jc.nivel, ' ', jc.jornada, ' ', jc.curso, ' ', jc.paralelo) AS nombre_jornada_curso
                   FROM jornada_curso jc
                   LEFT JOIN docente d
                          ON jc.id_docente = d.id_doc
                   LEFT JOIN periodo_lectivo pl
                          ON jc.id_periodo_lectivo = pl.id_periodo_lectivo
                   WHERE jc.estado = 'ACTIVO'
                     AND jc.id_periodo_lectivo = $periodo_activo_id
                   ORDER BY jc.nivel, jc.jornada, jc.curso, jc.paralelo";
    $result_cursos = mysqli_query($conn, $sql_cursos);

    if ($result_cursos) {
        while ($row_curso = mysqli_fetch_assoc($result_cursos)) {
            $row_curso['id_jornada_curso'] = (int) $row_curso['id_jornada_curso'];
            $row_curso['id_periodo_lectivo'] = (int) $row_curso['id_periodo_lectivo'];
            $cursos_activos[] = $row_curso;

            if ($curso_origen_id > 0 && $row_curso['id_jornada_curso'] === $curso_origen_id) {
                $curso_origen = $row_curso;
            }
        }
    }

    if ($curso_origen_id > 0 && $curso_origen !== null) {
        $sql_estudiantes = "SELECT e.est_cedula,
                                   e.est_nombres,
                                   e.est_apellidos,
                                   d.dtest_id,
                                   COALESCE(NULLIF(pl.observacion, ''), NULLIF(pl.descripcion, ''), c.periodo) AS periodo_mostrar,
                                   CONCAT(c.nivel, ' ', c.jornada, ' ', c.curso, ' ', c.paralelo) AS curso_mostrar,
                                   COALESCE(NULLIF(d.infaca_tutorcurso, ''), CONCAT(doc.dst_nombres, ' ', doc.dst_apellidos), 'Sin asignar') AS tutor_mostrar
                            FROM estudiantes e
                            INNER JOIN est_datos d
                                    ON e.est_cedula = d.dtest_cedula
                            INNER JOIN jornada_curso c
                                    ON d.infaca_jornada_curso = c.id_jornada_curso
                            LEFT JOIN periodo_lectivo pl
                                   ON c.id_periodo_lectivo = pl.id_periodo_lectivo
                            LEFT JOIN docente doc
                                   ON c.id_docente = doc.id_doc
                            WHERE d.infaca_jornada_curso = ?
                              AND c.id_periodo_lectivo = ?
                            ORDER BY e.est_apellidos, e.est_nombres";
        $stmt_estudiantes = mysqli_prepare($conn, $sql_estudiantes);

        if ($stmt_estudiantes) {
            mysqli_stmt_bind_param($stmt_estudiantes, "ii", $curso_origen_id, $periodo_activo_id);
            mysqli_stmt_execute($stmt_estudiantes);
            $result_estudiantes = mysqli_stmt_get_result($stmt_estudiantes);

            if ($result_estudiantes) {
                while ($row_estudiante = mysqli_fetch_assoc($result_estudiantes)) {
                    $estudiantes[] = $row_estudiante;
                }
            }

            mysqli_stmt_close($stmt_estudiantes);
        } else {
            $mensaje_error = 'No fue posible consultar los estudiantes del curso seleccionado.';
        }
    } elseif ($curso_origen_id > 0) {
        $mensaje_error = 'El curso seleccionado no pertenece al periodo activo actual.';
    }
} else {
    $mensaje_error = 'No existe un periodo lectivo activo para procesar promociones.';
}

$cursos_activos_json = json_encode($cursos_activos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($cursos_activos_json === false) {
    $cursos_activos_json = '[]';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">
    <title>Promoci&oacute;n Masiva de Curso</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="principal_sec.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="bi bi-book"></i></div>
                <div class="sidebar-brand-text mx-3">Menu</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="principal_sec.php">
                    <i class="bi bi-bank"></i>
                    <span>UE Jaime Roldos Aguilera</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Opciones</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Registros</span>
                </a>
                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="register_est.php">Estudiantes</a>
                        <a class="collapse-item" href="register_est_one.php">Estudiantes Individuales</a>
                        <a class="collapse-item" href="register_sec.php">Secretaria</a>
                        <a class="collapse-item" href="register_pro.php">Profesores</a>
                        <a class="collapse-item active" href="register_promover_curso.php">Promover Curso</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Carga masiva</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="register_est_user.php">Cargar estudiantes</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Addons</div>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION["nombres_sec"] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="cerrar_sesion()">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Session
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Promoci&oacute;n Masiva de Curso</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                <div>
                                    <h6 class="m-0 font-weight-bold text-primary">Filtro y promoci&oacute;n del grupo actual</h6>
                                    <small class="text-muted">Selecciona un curso del periodo activo y promueve a todos los estudiantes cargados en ese paralelo.</small>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <button type="button" class="btn btn-outline-success" id="btnPromover" onclick="abrirModalPromocion()" <?php echo ($curso_origen === null || count($estudiantes) === 0) ? 'disabled' : ''; ?>>
                                        <b><i class="bi bi-arrow-up-circle"></i> Promover a&ntilde;o</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($mensaje_error !== '') { ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <?php echo htmlspecialchars($mensaje_error, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($periodo_activo_id > 0) { ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-calendar-check"></i>
                                    Periodo activo actual:
                                    <b><?php echo htmlspecialchars($descripcion_periodo_activo, ENT_QUOTES, 'UTF-8'); ?></b>
                                </div>
                            <?php } ?>
                            <form method="GET" action="register_promover_curso.php" class="mb-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="curso_origen" class="font-weight-bold">Filtrar por curso / paralelo</label>
                                        <select class="form-control" id="curso_origen" name="curso_origen" <?php echo ($periodo_activo_id === 0) ? 'disabled' : ''; ?>>
                                            <option value="">Seleccione un curso activo</option>
                                            <?php foreach ($cursos_activos as $curso_item) { ?>
                                                <option value="<?php echo (int) $curso_item['id_jornada_curso']; ?>" <?php echo ($curso_origen_id === (int) $curso_item['id_jornada_curso']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($curso_item['nombre_jornada_curso'], ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-outline-primary w-100" <?php echo ($periodo_activo_id === 0) ? 'disabled' : ''; ?>>
                                            <b><i class="bi bi-funnel"></i> Consultar grupo</b>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php if ($curso_origen !== null) { ?>
                                <div class="row mb-4">
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3 h-100 bg-light">
                                            <div class="text-xs text-uppercase text-primary font-weight-bold mb-1">Curso actual</div>
                                            <div class="font-weight-bold"><?php echo htmlspecialchars($curso_origen['nombre_jornada_curso'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3 h-100 bg-light">
                                            <div class="text-xs text-uppercase text-primary font-weight-bold mb-1">Periodo</div>
                                            <div class="font-weight-bold"><?php echo htmlspecialchars($curso_origen['periodo_mostrar'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3 h-100 bg-light">
                                            <div class="text-xs text-uppercase text-primary font-weight-bold mb-1">Tutor</div>
                                            <div class="font-weight-bold"><?php echo htmlspecialchars($curso_origen['tutor'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3 h-100 bg-light">
                                            <div class="text-xs text-uppercase text-primary font-weight-bold mb-1">Estudiantes</div>
                                            <div class="font-weight-bold"><?php echo count($estudiantes); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaPromocion" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>C&eacute;dula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Periodo</th>
                                            <th>Curso</th>
                                            <th>Tutor</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>C&eacute;dula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Periodo</th>
                                            <th>Curso</th>
                                            <th>Tutor</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach ($estudiantes as $row_estudiante) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row_estudiante['est_cedula'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row_estudiante['est_nombres'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row_estudiante['est_apellidos'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row_estudiante['periodo_mostrar'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row_estudiante['curso_mostrar'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row_estudiante['tutor_mostrar'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPromocion" tabindex="-1" role="dialog" aria-labelledby="modalPromocionTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center align-items-center">
                    <h5 class="modal-title" id="modalPromocionTitle"><b>Promover Curso del Grupo</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary mb-4">
                        <i class="bi bi-info-circle"></i>
                        Revisa el curso actual del grupo, selecciona el nuevo curso activo del mismo periodo y luego procesa la promoci&oacute;n masiva.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Curso actual</label>
                            <input type="text" class="form-control" id="curso_actual_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Periodo actual</label>
                            <input type="text" class="form-control" id="periodo_actual_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Tutor actual</label>
                            <input type="text" class="form-control" id="tutor_actual_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Total estudiantes</label>
                            <input type="text" class="form-control" id="total_estudiantes_modal" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="curso_destino" class="font-weight-bold">Nuevo curso de destino</label>
                        <select class="form-control" id="curso_destino" onchange="mostrarCursoDestino()">
                            <option value="">Seleccione el curso al que se promover&aacute; el grupo</option>
                        </select>
                    </div>
                    <div id="detalleDestino" class="row d-none">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Curso destino</label>
                            <input type="text" class="form-control" id="curso_destino_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Periodo destino</label>
                            <input type="text" class="form-control" id="periodo_destino_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Tutor destino</label>
                            <input type="text" class="form-control" id="tutor_destino_modal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Paralelo destino</label>
                            <input type="text" class="form-control" id="paralelo_destino_modal" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="btnProcesarPromocion" onclick="procesarPromocion()" disabled>
                        <b><i class="bi bi-check2-circle"></i> Procesar</b>
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const cursosActivos = <?php echo $cursos_activos_json; ?>;
        const cursoOrigenId = <?php echo (int) $curso_origen_id; ?>;
        const totalEstudiantes = <?php echo count($estudiantes); ?>;

        $(document).ready(function () {
            $('#tablaPromocion').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                order: [[2, 'asc'], [1, 'asc']],
                pageLength: 25
            });
        });

        function cerrar_sesion() {
            window.open('logout.php', '_self');
        }

        function obtenerCursoPorId(idCurso) {
            const cursoId = parseInt(idCurso, 10);
            return cursosActivos.find((curso) => parseInt(curso.id_jornada_curso, 10) === cursoId) || null;
        }

        function abrirModalPromocion() {
            if (!cursoOrigenId || totalEstudiantes === 0) {
                Swal.fire({ icon: 'warning', title: 'Grupo no disponible', text: 'Primero consulta un curso que tenga estudiantes cargados para poder promoverlo.' });
                return;
            }

            const cursoActual = obtenerCursoPorId(cursoOrigenId);
            if (!cursoActual) {
                Swal.fire({ icon: 'error', title: 'Curso no v&aacute;lido', text: 'El curso actual ya no est&aacute; disponible dentro del periodo activo.' });
                return;
            }

            document.getElementById('curso_actual_modal').value = cursoActual.nombre_jornada_curso || '';
            document.getElementById('periodo_actual_modal').value = cursoActual.periodo_mostrar || '';
            document.getElementById('tutor_actual_modal').value = cursoActual.tutor || 'Sin asignar';
            document.getElementById('total_estudiantes_modal').value = String(totalEstudiantes);

            const selectDestino = document.getElementById('curso_destino');
            selectDestino.innerHTML = '<option value=\"\">Seleccione el curso al que se promover&aacute; el grupo</option>';

            cursosActivos
                .filter((curso) => parseInt(curso.id_jornada_curso, 10) !== cursoOrigenId)
                .forEach((curso) => {
                    const option = document.createElement('option');
                    option.value = curso.id_jornada_curso;
                    option.textContent = curso.nombre_jornada_curso;
                    selectDestino.appendChild(option);
                });

            selectDestino.value = '';
            document.getElementById('detalleDestino').classList.add('d-none');
            document.getElementById('btnProcesarPromocion').disabled = true;
            $('#modalPromocion').modal('show');
        }

        function mostrarCursoDestino() {
            const cursoDestino = obtenerCursoPorId(document.getElementById('curso_destino').value);
            const detalleDestino = document.getElementById('detalleDestino');
            const btnProcesar = document.getElementById('btnProcesarPromocion');

            if (!cursoDestino) {
                detalleDestino.classList.add('d-none');
                btnProcesar.disabled = true;
                return;
            }

            document.getElementById('curso_destino_modal').value = cursoDestino.nombre_jornada_curso || '';
            document.getElementById('periodo_destino_modal').value = cursoDestino.periodo_mostrar || '';
            document.getElementById('tutor_destino_modal').value = cursoDestino.tutor || 'Sin asignar';
            document.getElementById('paralelo_destino_modal').value = cursoDestino.paralelo || '';
            detalleDestino.classList.remove('d-none');
            btnProcesar.disabled = false;
        }

        async function procesarPromocion() {
            const cursoDestinoId = parseInt(document.getElementById('curso_destino').value, 10);
            if (!cursoOrigenId || !cursoDestinoId) {
                Swal.fire({ icon: 'warning', title: 'Datos incompletos', text: 'Selecciona el curso destino antes de procesar.' });
                return;
            }

            const cursoDestino = obtenerCursoPorId(cursoDestinoId);
            const cursoActual = obtenerCursoPorId(cursoOrigenId);

            const confirmacion = await Swal.fire({
                icon: 'question',
                title: 'Confirmar promoci&oacute;n',
                html: 'Se promover&aacute;n <b>' + totalEstudiantes + '</b> estudiantes desde <b>' + (cursoActual ? cursoActual.nombre_jornada_curso : '') + '</b> hacia <b>' + (cursoDestino ? cursoDestino.nombre_jornada_curso : '') + '</b>.',
                showCancelButton: true,
                confirmButtonText: 'S&iacute;, procesar',
                cancelButtonText: 'Cancelar'
            });

            if (!confirmacion.isConfirmed) {
                return;
            }

            const formData = new FormData();
            formData.append('curso_origen', String(cursoOrigenId));
            formData.append('curso_destino', String(cursoDestinoId));

            try {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Estamos actualizando el curso del grupo seleccionado.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                const response = await fetch('../template_admin/procesar_promocion_curso.php', { method: 'POST', body: formData });
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'No fue posible procesar la promoci&oacute;n.');
                }

                await Swal.fire({ icon: 'success', title: '&Eacute;xito', text: data.message, confirmButtonText: 'Aceptar' });
                window.location.href = 'register_promover_curso.php?curso_origen=' + encodeURIComponent(String(cursoOrigenId));
            } catch (error) {
                await Swal.fire({ icon: 'error', title: 'Error', text: error.message || 'Ocurri&oacute; un error al procesar la promoci&oacute;n.' });
            }
        }
    </script>
</body>
</html>
