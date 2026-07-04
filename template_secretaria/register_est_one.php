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

    <title>Registro de Estudiantes</title>

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

    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
    function cerrar_sesion()
    {
        window.open("logout.php","_self");
    }

    // Agregar función JavaScript para actualizar datos del curso
    function actualizarInfoCurso() {
        // Obtener el ID del curso seleccionado
        var cursoId = document.getElementById('id_jornada').value;
        
        if (!cursoId) {
            // Si no hay curso seleccionado, limpiar los campos
            document.getElementById('nivel').value = '';
            document.getElementById('curso').value = '';
            document.getElementById('jornada').value = '';
            document.getElementById('paralelo').value = '';
            document.getElementById('tutor').value = '';
            return;
        }
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo información del curso',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Realizar petición AJAX para obtener los datos del curso
        fetch('../template_admin/obtener_datos_curso.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_jornada_curso=' + encodeURIComponent(cursoId)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la conexión');
            }
            return response.json();
        })
        .then(data => {
            // Cerrar el indicador de carga
            Swal.close();
            
            if (data.success) {
                // Actualizar los campos con los datos recibidos
                document.getElementById('nivel').value = data.data.nivel;
                document.getElementById('curso').value = data.data.curso;
                document.getElementById('jornada').value = data.data.jornada;
                document.getElementById('paralelo').value = data.data.paralelo;
                document.getElementById('tutor').value = data.data.tutor;
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo obtener la información del curso'
                });
            }
        })
        .catch(error => {
            // Cerrar el indicador de carga y mostrar error
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al obtener datos del curso'
            });
        });
    }

    function modificar_estudiante(id_est, nombres, apellidos, cedula, id_jornada, id_detalle)
    {
        var ModalEdit = new bootstrap.Modal(exampleModalLong2, {}).show();
        id_est_act.value = id_est;
        id_det_estudiante.value = id_detalle;
        nombre_act.value = nombres.replace('-', ' ');
        apellido_act.value = apellidos.replace('-', ' ');
        cedula_act.value = cedula.replace('-', ' ');
        id_jornada_act.value = id_jornada;

        // Trigger para actualizar la información del curso
        setTimeout(function() {
            actualizarInfoCursoModificar();
        }, 500);
    }

    function actualizarInfoCursoModificar() {
        // Obtener el ID del curso seleccionado
        var cursoId = document.getElementById('id_jornada_act').value;
        
        if (!cursoId) {
            return;
        }
        
        // Realizar petición AJAX para obtener los datos del curso
        fetch('../template_admin/obtener_datos_curso.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_jornada_curso=' + encodeURIComponent(cursoId)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la conexión');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Actualizar los campos con los datos recibidos
                document.getElementById('nivel_act').value = data.data.nivel;
                document.getElementById('curso_act').value = data.data.curso;
                document.getElementById('jornada_act').value = data.data.jornada;
                document.getElementById('paralelo_act').value = data.data.paralelo;
                document.getElementById('tutor_act').value = data.data.tutor;
            }
        });
    }

    function eliminar_estudiante(id_est, id_detalle)
    {
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea eliminar este estudiante?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("eliminar_est_one.php?id_est=" + id_est + "&id_detalle=" + id_detalle, target="_blank");
                // Recargar después de un breve retraso para asegurar que el proceso termine
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    }
</script>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="principal_sec.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="bi bi-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Menu</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="principal_sec.php">
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
                        <a class="collapse-item" href="register_est.php">Estudiantes</a>
                        <!--<a class="collapse-item" href="register_admin.php">Administradores</a>-->
                        <a class="collapse-item" href="register_sec.php">Secretaria</a>
                        <a class="collapse-item" href="register_pro.php">Profesores</a>
                        <a class="collapse-item" href="register_promover_curso.php">Promover Curso</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Crear estudiantes</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="register_est_one.php">Crear estudiante</a>
                        <!--<a class="collapse-item" href="register_ust_cur.php">Cargar cursos</a>-->
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

                    <!-- Topbar Search -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        


                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombres_sec"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!--
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                -->
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
<h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro Individual de Estudiantes</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button type="button" 
        data-toggle="modal" data-target="#exampleModalLong"
        class="btn btn-outline-primary">
        <b><i class='bi bi-journal-plus'></i> Crear Estudiante</b>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                      <tr>
                        <th>Cédula</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Curso</th>
                        <th>Acciones</th>  
                    </tr>
            </thead>
            <tfoot>
                      <tr>
                        <th>Cédula</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Curso</th>
                        <th>Acciones</th>  
                      </tr>
           </tfoot>
                <tbody>
                    <?php
                $sql = "SELECT e.est_id, e.est_nombres, e.est_apellidos, e.est_cedula, 
                        d.dtest_id, d.infaca_jornada_curso,
                        CONCAT(j.nivel,' ',j.jornada,' ',j.curso,' ',j.paralelo) as nombre_curso
                        FROM estudiantes e 
                        JOIN est_datos d ON e.est_cedula = d.dtest_cedula
                        JOIN jornada_curso j ON d.infaca_jornada_curso = j.id_jornada_curso
                        ORDER BY e.est_apellidos";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<tr> 
                    <td>" . $row["est_cedula"]. "</td> 
                    <td>" . $row["est_apellidos"]. "</td> 
                    <td>" . $row["est_nombres"]. "</td>  
                    <td>" . $row["nombre_curso"]. "</td> 
                    <td> 
                    <button type='button' onclick=\"modificar_estudiante('".$row["est_id"]."','".str_replace(" ","-",$row["est_nombres"])."','".str_replace(" ","-",$row["est_apellidos"])."','".str_replace(" ","-",$row["est_cedula"])."','".$row["infaca_jornada_curso"]."','".$row["dtest_id"]."')\" class='btn btn-outline-success'><i class='bi bi-pencil-square'></i></button>
                    <button type='button' onclick=\"eliminar_estudiante('".$row["est_id"]."','".$row["dtest_id"]."')\" class='btn btn-outline-danger'><i class='bi bi-trash-fill'></i></button>
                    </td> 
                    </tr>";

                }
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
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de Estudiante</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="insertar_est_one.php" method="POST">
                <div class="modal-body">
                          <input type="text" class="form-control mb-3" id="nombres" name="nombres" aria-describedby="emailHelp" placeholder="Nombres" required>
                          <input type="text" class="form-control mb-3" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                          <input type="text" class="form-control mb-3" id="cedula" name="cedula" placeholder="Cédula" required>
                          
                          <div class="form-group">
                              <label for="id_jornada">Curso:</label>
                              <select name="id_jornada" id="id_jornada" class="form-control" onchange="actualizarInfoCurso()" required>
                                  <option value="">Seleccione un curso</option>
                                  <?php
                                    $sqln = "SELECT id_jornada_curso, 
                                           nivel, 
                                           jornada, 
                                           curso, 
                                           paralelo,
                                           concat(nivel,' ',jornada,' ',curso,' ',paralelo) as nombre_jornada_curso , 
                                          (select concat(d.dst_nombres,' ',d.dst_apellidos) from docente d where d.id_doc=id_docente) as tutor
                                           FROM jornada_curso where estado='ACTIVO'";
                                    $result = mysqli_query($conn, $sqln);
                                    
                                    if (mysqli_num_rows($result) > 0) {
                                      while($row = mysqli_fetch_assoc($result)) {
                                        $codigo_cur = $row["id_jornada_curso"];
                                        $nombre_cur = $row["nombre_jornada_curso"];
                                        ?>
                                        <option value="<?php echo $codigo_cur; ?>"><?php echo $nombre_cur; ?></option>
                                        <?php
                                      }
                                    }
                                  ?>
                                </select>  
                          </div>
                          
                          <input type="text" class="form-control mb-3" id="nivel" name="nivel" placeholder="Nivel" readonly>
                          <input type="text" class="form-control mb-3" id="curso" name="curso" placeholder="Curso" readonly>
                          <input type="text" class="form-control mb-3" id="jornada" name="jornada" placeholder="Jornada" readonly>
                          <input type="text" class="form-control mb-3" id="paralelo" name="paralelo" placeholder="Paralelo" readonly>
                          <input type="text" class="form-control mb-3" id="tutor" name="tutor" placeholder="Tutor" readonly>
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
              <h5 class="modal-title" id="exampleModalLongTitle2"><b>Modificar Estudiante</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="modificar_est_one.php" method="POST">
                <div class="modal-body">
                      <input type="hidden" id="id_est_act" name="id_est_act">
                      <input type="hidden" id="id_det_estudiante" name="id_det_estudiante">
                      <input type="text" class="form-control mb-3" id="nombre_act" name="nombre_act" aria-describedby="emailHelp" placeholder="Nombres" required>
                      <input type="text" class="form-control mb-3" id="apellido_act" name="apellido_act" placeholder="Apellidos" required>
                      <input type="text" class="form-control mb-3" id="cedula_act" name="cedula_act" placeholder="Cédula" readonly>
                      
                      <div class="form-group">
                          <label for="id_jornada_act">Curso:</label>
                          <select name="id_jornada_act" id="id_jornada_act" class="form-control" onchange="actualizarInfoCursoModificar()" required>
                              <option value="">Seleccione un curso</option>
                              <?php
                                $sqln = "SELECT id_jornada_curso, 
                                       nivel, 
                                       jornada, 
                                       curso, 
                                       paralelo,
                                       concat(nivel,' ',jornada,' ',curso,' ',paralelo) as nombre_jornada_curso , 
                                      (select concat(d.dst_nombres,' ',d.dst_apellidos) from docente d where d.id_doc=id_docente) as tutor
                                       FROM jornada_curso where estado='ACTIVO'";
                                $result = mysqli_query($conn, $sqln);
                                
                                if (mysqli_num_rows($result) > 0) {
                                  while($row = mysqli_fetch_assoc($result)) {
                                    $codigo_cur = $row["id_jornada_curso"];
                                    $nombre_cur = $row["nombre_jornada_curso"];
                                    ?>
                                    <option value="<?php echo $codigo_cur; ?>"><?php echo $nombre_cur; ?></option>
                                    <?php
                                  }
                                }
                              ?>
                            </select>  
                      </div>
                      
                      <input type="text" class="form-control mb-3" id="nivel_act" name="nivel_act" placeholder="Nivel" readonly>
                      <input type="text" class="form-control mb-3" id="curso_act" name="curso_act" placeholder="Curso" readonly>
                      <input type="text" class="form-control mb-3" id="jornada_act" name="jornada_act" placeholder="Jornada" readonly>
                      <input type="text" class="form-control mb-3" id="paralelo_act" name="paralelo_act" placeholder="Paralelo" readonly>
                      <input type="text" class="form-control mb-3" id="tutor_act" name="tutor_act" placeholder="Tutor" readonly>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-primary">Guardar Cambios</button>
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

</body>

</html>  

</body>

</html>
