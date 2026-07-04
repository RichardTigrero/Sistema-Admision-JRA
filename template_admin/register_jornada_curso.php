<?php
session_start();
include("../conexion/conexion.php");

$docentes = [];
$periodos_lectivos = [];
$periodo_seleccionado = isset($_GET['periodo']) ? (int) $_GET['periodo'] : 0;
$periodo_estado = '';
$usar_historial = false;

if ($periodo_seleccionado > 0) {
    $sql_periodo_actual = "SELECT estado FROM periodo_lectivo WHERE id_periodo_lectivo = $periodo_seleccionado LIMIT 1";
    $result_periodo_actual = mysqli_query($conn, $sql_periodo_actual);
    if ($result_periodo_actual && mysqli_num_rows($result_periodo_actual) > 0) {
        $row_periodo_actual = mysqli_fetch_assoc($result_periodo_actual);
        $periodo_estado = $row_periodo_actual['estado'];
        $usar_historial = ($periodo_estado === 'CERRADO');
    }
}

$sql_docentes = "SELECT id_doc, CONCAT(dst_nombres, ' ', dst_apellidos) as nombre_completo
                 FROM docente
                 ORDER BY dst_apellidos, dst_nombres";
$result_docentes = mysqli_query($conn, $sql_docentes);
if ($result_docentes && mysqli_num_rows($result_docentes) > 0) {
    while ($row_docente = mysqli_fetch_assoc($result_docentes)) {
        $docentes[] = $row_docente;
    }
}

$sql_periodos = "SELECT id_periodo_lectivo, descripcion, estado
                 FROM periodo_lectivo
                 ORDER BY id_periodo_lectivo DESC";
$result_periodos = mysqli_query($conn, $sql_periodos);
if ($result_periodos && mysqli_num_rows($result_periodos) > 0) {
    while ($row_periodo = mysqli_fetch_assoc($result_periodos)) {
        $periodos_lectivos[] = $row_periodo;
    }
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

    <title>Registro de Jornada y Cursos</title>

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    function cerrar_sesion()
    {
        window.open("logout.php","_self");
    }
    function filtrarPorPeriodoJornadaCurso()
    {
        const periodo = document.getElementById('filtro_periodo_tabla').value;
        if (periodo) {
            window.location.href = 'register_jornada_curso.php?periodo=' + encodeURIComponent(periodo);
        } else {
            window.location.href = 'register_jornada_curso.php';
        }
    }
    function modificar_jornada_curso(id, nivel, jornada, curso, paralelo, id_periodo_lectivo, id_docente, estado)
    {
        var ModalEdit = new bootstrap.Modal(document.getElementById('exampleModalLong2')).show();
        document.getElementById('id_jornada_curso_act').value = id;
        document.getElementById('nivel_act').value = nivel.replace(/-/g, ' ');
        document.getElementById('jornada_act').value = jornada.replace(/-/g, ' ');
        document.getElementById('curso_act').value = curso.replace(/-/g, ' ');
        document.getElementById('paralelo_act').value = paralelo.replace(/-/g, ' ');
        document.getElementById('id_docente_act').value = id_docente;
        document.getElementById('id_periodo_lectivo_act').value = id_periodo_lectivo;
        
        // Establecer estado
        var selectEstado = document.getElementById('estado_act');
        for (var i = 0; i < selectEstado.options.length; i++) {
            if (selectEstado.options[i].value === estado) {
                selectEstado.selectedIndex = i;
                break;
            }
        }
    }

    // Función para insertar jornada/curso
    async function insertarJornadaCurso(formData) {
        try {
            const response = await fetch('insertar_jornada_curso.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                location.reload();
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        }
    }

    // Función para eliminar jornada/curso
    async function eliminar_jornada_curso(id) {
        try {
            const result = await Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const response = await fetch(`eliminar_jornada_curso.php?id=${id}`);
                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        }
    }

    // Función para modificar jornada/curso
    async function modificarJornadaCurso(formData) {
        try {
            const response = await fetch('modificar_jornada_curso.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                location.reload();
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        }
    }
</script>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="principal_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="bi bi-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Menu</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="principal_admin.php">
                    <i class="bi bi-bank"></i></i>
                    <span>UE Jaime Roldos Aguilera</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Opciones
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Registros</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="register_est.php">Estudiantes Masivos</a>
                        <a class="collapse-item" href="register_est_one.php">Estudiantes Individuales</a>
                        <a class="collapse-item" href="register_admin.php">Administradores</a>
                        <a class="collapse-item" href="register_sec.php">Secretaria</a>
                        <a class="collapse-item" href="register_pro.php">Profesores</a>
                        <a class="collapse-item" href="register_jornada_curso.php">Jornadas y Cursos</a>
                        <a class="collapse-item" href="register_promover_curso.php">Promover Curso</a>
                        <a class="collapse-item" href="register_periodo_lectivo.php">Períodos Lectivos</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Carga masiva</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="register_est_user.php">Cargar estudiantes</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombres_admin"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" onclick="cerrar_sesion()" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Session
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>




                <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Jornadas y Cursos</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button type="button" 
        data-toggle="modal" data-target="#exampleModalLong"
        class="btn btn-outline-primary">
        <b><i class='bi bi-journal-plus'></i> Crear</b>
        </button>
    </div>
    <div class="card-body">
        <?php if ($usar_historial) { ?>
            <div class="alert alert-secondary">
                <i class="bi bi-archive"></i> Estás consultando un período cerrado. Los datos mostrados provienen de la tabla histórica y son solo de lectura.
            </div>
        <?php } ?>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filtro_periodo_tabla" class="font-weight-bold">Filtrar por periodo lectivo</label>
                <select class="form-control" id="filtro_periodo_tabla">
                    <option value="">Todos los periodos</option>
                    <?php foreach ($periodos_lectivos as $periodo_lectivo) { ?>
                        <option value="<?php echo htmlspecialchars($periodo_lectivo["id_periodo_lectivo"]); ?>" <?php echo ($periodo_seleccionado === (int)$periodo_lectivo["id_periodo_lectivo"]) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($periodo_lectivo["descripcion"] . " - " . $periodo_lectivo["estado"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" onclick="filtrarPorPeriodoJornadaCurso()" class="btn btn-outline-info w-100">
                    <b><i class='bi bi-funnel'></i> Consultar</b>
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                      <tr>
                        <th>Nivel</th>
                        <th>Jornada</th>
                        <th>Curso</th>
                        <th>Paralelo</th>
                        <th>Periodo</th>
                        <th>Docente</th>
                        <th>Estado</th>
                        <th>Acciones</th>  
                    </tr>
            </thead>
            <tfoot>
                      <tr>
                        <th>Nivel</th>
                        <th>Jornada</th>
                        <th>Curso</th>
                        <th>Paralelo</th>
                        <th>Periodo</th>
                        <th>Docente</th>
                        <th>Estado</th>
                        <th>Acciones</th>   
                      </tr>
           </tfoot>
                <tbody>
                    <?php
                if ($usar_historial) {
                    $sql = "SELECT jc.id_jornada_curso, jc.nivel, jc.jornada, jc.curso, jc.paralelo, jc.periodo,
                            jc.id_periodo_lectivo, jc.id_docente, jc.estado,
                            COALESCE(pl.descripcion, jc.periodo) as periodo_mostrar,
                            CONCAT(d.dst_nombres, ' ', d.dst_apellidos) as nombre_docente
                            FROM jornada_curso_historial jc
                            LEFT JOIN docente d ON jc.id_docente = d.id_doc
                            LEFT JOIN periodo_lectivo pl ON jc.id_periodo_lectivo = pl.id_periodo_lectivo
                            WHERE jc.id_periodo_lectivo = " . (int)$periodo_seleccionado;
                } else {
                    $sql = "SELECT jc.id_jornada_curso, jc.nivel, jc.jornada, jc.curso, jc.paralelo, jc.periodo,
                            jc.id_periodo_lectivo, jc.id_docente, jc.estado,
                            COALESCE(pl.descripcion, jc.periodo) as periodo_mostrar,
                            CONCAT(d.dst_nombres, ' ', d.dst_apellidos) as nombre_docente
                            FROM jornada_curso jc
                            LEFT JOIN docente d ON jc.id_docente = d.id_doc
                            LEFT JOIN periodo_lectivo pl ON jc.id_periodo_lectivo = pl.id_periodo_lectivo";
                    if ($periodo_seleccionado > 0) {
                        $sql .= " WHERE jc.id_periodo_lectivo = " . (int)$periodo_seleccionado;
                    }
                }
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    $acciones = '';
                    if ($usar_historial) {
                        $acciones = "<span class='badge badge-secondary'>Historial</span>";
                    } else {
                        $acciones = "<button type='button' onclick=\"modificar_jornada_curso('".$row["id_jornada_curso"]."', '".str_replace(" ","-",$row["nivel"])."', '".str_replace(" ","-",$row["jornada"])."', '".str_replace(" ","-",$row["curso"])."', '".str_replace(" ","-",$row["paralelo"])."', '".htmlspecialchars((string)$row["id_periodo_lectivo"], ENT_QUOTES)."', '".$row["id_docente"]."', '".$row["estado"]."')\" class='btn btn-outline-success'><i class='bi bi-pencil-square'></i></button> 
                        <button type='button' onclick=\"eliminar_jornada_curso('".$row["id_jornada_curso"]."')\" class='btn btn-outline-danger'><i class='bi bi-trash-fill'></i></button>";
                    }
                    
                    echo "<tr> 
                    <td>" . $row["nivel"]. "</td> 
                    <td>" . $row["jornada"]. "</td> 
                    <td>" . $row["curso"]. "</td>  
                    <td>" . $row["paralelo"]. "</td> 
                    <td>" . htmlspecialchars($row["periodo_mostrar"]) . "</td> 
                    <td>" . $row["nombre_docente"]. "</td>  
                    <td>" . $row["estado"]. "</td>  
                    <td>" . $acciones . "</td> 
                    </tr>";

                }
                } else {
                echo "0 results";
                }

                ?> 

                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->




<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de Jornada y Curso</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="createForm" onsubmit="event.preventDefault(); insertarJornadaCurso(new FormData(this));">
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="nivel" name="nivel" placeholder="Nivel" required>
                    <input type="text" class="form-control mb-3" id="jornada" name="jornada" placeholder="Jornada" required>
                    <input type="text" class="form-control mb-3" id="curso" name="curso" placeholder="Curso" required>
                    <input type="text" class="form-control mb-3" id="paralelo" name="paralelo" placeholder="Paralelo" required>
                    
                    <select class="form-control mb-3" id="id_periodo_lectivo" name="id_periodo_lectivo" required>
                        <option value="">Seleccione un periodo lectivo</option>
                        <?php foreach ($periodos_lectivos as $periodo_lectivo) { ?>
                            <option value="<?php echo htmlspecialchars($periodo_lectivo["id_periodo_lectivo"]); ?>">
                                <?php echo htmlspecialchars($periodo_lectivo["descripcion"] . " - " . $periodo_lectivo["estado"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <select class="form-control mb-3" id="id_docente" name="id_docente" required>
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente) { ?>
                            <option value="<?php echo htmlspecialchars($docente["id_doc"]); ?>">
                                <?php echo htmlspecialchars($docente["nombre_completo"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <select class="form-control mb-3" id="estado" name="estado" required>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
          </div>
        </div>
      </div>




<div class="modal fade" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de Jornada y Curso (Modificar)</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="updateForm" onsubmit="event.preventDefault(); modificarJornadaCurso(new FormData(this));">
                <div class="modal-body">
                    <input type="hidden" id="id_jornada_curso_act" name="id_jornada_curso_act">
                    <input type="text" class="form-control mb-3" id="nivel_act" name="nivel_act" placeholder="Nivel" required>
                    <input type="text" class="form-control mb-3" id="jornada_act" name="jornada_act" placeholder="Jornada" required>
                    <input type="text" class="form-control mb-3" id="curso_act" name="curso_act" placeholder="Curso" required>
                    <input type="text" class="form-control mb-3" id="paralelo_act" name="paralelo_act" placeholder="Paralelo" required>
                    
                    <select class="form-control mb-3" id="id_periodo_lectivo_act" name="id_periodo_lectivo_act" required>
                        <option value="">Seleccione un periodo lectivo</option>
                        <?php foreach ($periodos_lectivos as $periodo_lectivo) { ?>
                            <option value="<?php echo htmlspecialchars($periodo_lectivo["id_periodo_lectivo"]); ?>">
                                <?php echo htmlspecialchars($periodo_lectivo["descripcion"] . " - " . $periodo_lectivo["estado"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <select class="form-control mb-3" id="id_docente_act" name="id_docente_act" required>
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente) { ?>
                            <option value="<?php echo htmlspecialchars($docente["id_doc"]); ?>">
                                <?php echo htmlspecialchars($docente["nombre_completo"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <select class="form-control mb-3" id="estado_act" name="estado_act" required>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
          </div>
        </div>
      </div>


    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html> 
