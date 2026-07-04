<?php
session_start();
include("../conexion/conexion.php");
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

    <title>Registro de Período Lectivo</title>

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

    function modificar_periodo_lectivo(id, descripcion, observacion, estado)
    {
        var ModalEdit = new bootstrap.Modal(document.getElementById('exampleModalLong2')).show();
        document.getElementById('id_periodo_lectivo_act').value = id;
        document.getElementById('descripcion_act').value = descripcion;
        document.getElementById('observacion_act').value = observacion;
        
        // Establecer estado
        var selectEstado = document.getElementById('estado_act');
        for (var i = 0; i < selectEstado.options.length; i++) {
            if (selectEstado.options[i].value === estado) {
                selectEstado.selectedIndex = i;
                break;
            }
        }
    }

    // Función para insertar período lectivo
    async function insertarPeriodoLectivo(formData) {
        try {
            const response = await fetch('insertar_periodo_lectivo.php', {
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

    // Función para eliminar período lectivo
    async function eliminar_periodo_lectivo(id) {
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
                const response = await fetch(`eliminar_periodo_lectivo.php?id=${id}`);
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

    // Función para modificar período lectivo
    async function modificarPeriodoLectivo(formData) {
        try {
            const response = await fetch('modificar_periodo_lectivo.php', {
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

    // Función para procesar período (cambiar a CERRADO)
    async function procesarPeriodo(id) {
        try {
            const result = await Swal.fire({
                title: '¿Procesar Período?',
                text: "Esto cerrará el período lectivo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('estado', 'CERRADO');
                
                const response = await fetch('actualizar_estado_periodo.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Procesado!',
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

    // Función para abrir proceso (cambiar a ACTIVO)
    async function abrirProceso(id) {
        try {
            const result = await Swal.fire({
                title: '¿Abrir Proceso?',
                text: "Esto activará nuevamente el período lectivo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, abrir',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('estado', 'ACTIVO');
                
                const response = await fetch('actualizar_estado_periodo.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Abierto!',
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

    // Función para desactivar período (cambiar a INACTIVO)
    async function desactivarPeriodo(id) {
        try {
            const result = await Swal.fire({
                title: '¿Desactivar Período?',
                text: "Esto desactivará el período lectivo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('estado', 'INACTIVO');
                
                const response = await fetch('actualizar_estado_periodo.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Desactivado!',
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
<h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Períodos Lectivos</h1>

<!-- Información Informativa -->
<div class="card border-left-success shadow mb-4">
    <div class="card-body" style="background-color: #d4edda; border-left: 4px solid #28a745;">
        <h6 class="text-success font-weight-bold mb-2">
            <i class="fas fa-info-circle"></i> Información Importante
        </h6>
        <p class="text-dark mb-0">
            <strong>¿Qué sucede al procesar un período lectivo?</strong><br>
            Cuando procesa un período lectivo, la información de los estudiantes se respalda en una tabla histórica y esta información queda bloqueada sin oportunidad a modificarla. Automáticamente para ese período se genera la misma información pero para el siguiente período en estado ACTIVO para que se use para el nuevo período y esa sí puede ser modificada.
        </p>
    </div>
</div>

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
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                      <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Observación</th>
                        <th>Estado</th>
                        <th>Acciones</th>  
                    </tr>
            </thead>
            <tfoot>
                      <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Observación</th>
                        <th>Estado</th>
                        <th>Acciones</th>   
                      </tr>
           </tfoot>
                <tbody>
                    <?php
                $sql = "SELECT id_periodo_lectivo, descripcion, observacion, estado FROM periodo_lectivo ORDER BY id_periodo_lectivo DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<tr> 
                    <td>" . htmlspecialchars($row["id_periodo_lectivo"]) . "</td> 
                    <td>" . htmlspecialchars($row["descripcion"]) . "</td> 
                    <td>" . htmlspecialchars($row["observacion"]) . "</td>  
                    <td>" . htmlspecialchars($row["estado"]) . "</td>  
                    <td> 
                    <button type='button' onclick=\"modificar_periodo_lectivo('" . htmlspecialchars($row["id_periodo_lectivo"]) . "', '" . htmlspecialchars($row["descripcion"]) . "', '" . htmlspecialchars($row["observacion"]) . "', '" . htmlspecialchars($row["estado"]) . "')\" class='btn btn-outline-success'><i class='bi bi-pencil-square'></i></button> 
                    <button type='button' onclick=\"procesarPeriodo('" . htmlspecialchars($row["id_periodo_lectivo"]) . "')\" class='btn btn-outline-warning'><i class='bi bi-check-circle'></i> Procesar</button> 
                    <button type='button' onclick=\"abrirProceso('" . htmlspecialchars($row["id_periodo_lectivo"]) . "')\" class='btn btn-outline-info'><i class='bi bi-unlock'></i> Abrir</button> 
                    <button type='button' onclick=\"desactivarPeriodo('" . htmlspecialchars($row["id_periodo_lectivo"]) . "')\" class='btn btn-outline-secondary'><i class='bi bi-power'></i> Desactivar</button> 
                    <button type='button' onclick=\"eliminar_periodo_lectivo('" . htmlspecialchars($row["id_periodo_lectivo"]) . "')\" class='btn btn-outline-danger'><i class='bi bi-trash-fill'></i></button>
                    </td> 
                    </tr>";

                }
                } else {
                echo "<tr><td colspan='5' class='text-center'>No hay registros de períodos lectivos</td></tr>";
                }

                mysqli_close($conn);
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
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de Período Lectivo</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="createForm" onsubmit="event.preventDefault(); insertarPeriodoLectivo(new FormData(this));">
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="descripcion" name="descripcion" placeholder="Descripción del Período" required>
                    <input type="text" class="form-control mb-3" id="observacion" name="observacion" placeholder="Observación" required>
                    
                    <select class="form-control mb-3" id="estado" name="estado" required>
                        <option value="">Seleccione un estado</option>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                        <option value="CERRADO">CERRADO</option>
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
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de Período Lectivo (Modificar)</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="updateForm" onsubmit="event.preventDefault(); modificarPeriodoLectivo(new FormData(this));">
                <div class="modal-body">
                    <input type="hidden" id="id_periodo_lectivo_act" name="id_periodo_lectivo_act">
                    <input type="text" class="form-control mb-3" id="descripcion_act" name="descripcion_act" placeholder="Descripción del Período" required>
                    <input type="text" class="form-control mb-3" id="observacion_act" name="observacion_act" placeholder="Observación" required>
                    
                    <select class="form-control mb-3" id="estado_act" name="estado_act" required>
                        <option value="">Seleccione un estado</option>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                        <option value="CERRADO">CERRADO</option>
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
