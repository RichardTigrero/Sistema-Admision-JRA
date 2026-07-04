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
    <title>Registro Estudiantes</title>

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom styles for this page    
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
  
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    
     <!-- Bootstrap core JavaScript 
    <script src="../vendor/jquery/jquery.min.js"></script>-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    -->
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins 
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
     -->
     
    <!-- Page level custom scripts  
    <script src="../js/demo/datatables-demo.js"></script> 
    <script src="https://datatables.net/examples/resources/demo.js"></script>-->
    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
$(document).ready(function() {
var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [  'excel', 'pdf', 'colvis' ]
    } );
    
 table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
 });
 
var table2 = $('#example2').DataTable({
        //scrollY: '200px',
        scrollCollapse: true,
        paging: true
    });


var table3 = $('#example3').DataTable({
        //scrollY: '200px',
        scrollCollapse: true,
        paging: true
    });


function ver_datos_agrupados()
    {
       
        var ModalEdit = new bootstrap.Modal(datos_agrupados, {}).show();
       
    }
    
function ver_datos_jornadas()
    {
       
        var ModalEdit = new bootstrap.Modal(datos_jornadas, {}).show();
       
    }
    
    function carga_masiva()
    {
        window.open("register_est_user.php","_self");
    }
    function carga_unitaria()
    {
        window.open("register_est_one.php","_self");
    }
    function cerrar_sesion()
    {
        //alert("Cerrar Session");
        window.open("logout.php","_self");
    }
    
    /*function recargar() 
    {
         
            window.open("data_id_tmp.php?id_detalle="+param_id_detalle,target='_blank');
            location.reload();
            
    }
    */
    function modificar_estudiante(param_id_detalle)
    {
        //location=0,status=0,scrollbars=1,resizable=0
        window.open("data_estudiantes_udp.php?id_detalle="+param_id_detalle,target='_blank',"height=620,width=1000,left=100,location=no,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,toolbar=no,top=100");
        //location.reload();
        // recargar();   
        //var ModalEdit = new bootstrap.Modal(data_actualizar_estudiantes, {}).show();
        //var data_din = param_id_detalle;
        //id_det_estudiante.value = param_id_detalle;
        //alert(param_id_detalle);
        
         
    }
    
    async function subir_documento(param_id, param_cedula, param_nombres) {
        try {
            // Establecer los valores en el modal
            document.getElementById('est_id').value = param_id;
            document.getElementById('est_cedula').value = param_cedula;
            document.getElementById('est_nombres').textContent = param_nombres;
            
            // Limpiar valores previos
            document.getElementById('documento').value = '';
            document.querySelector('.custom-file-label').textContent = 'Elegir archivo...';
            document.getElementById('descripcion').value = '';
            
            // Mostrar el modal
            $('#documentoModal').modal('show');
            
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al abrir el formulario de carga de documentos'
            });
        }
    }
    
    async function subir_foto(param_id, param_nombres) {
        try {
            // Mostrar indicador de carga
            const loadingAlert = Swal.fire({
                title: 'Cargando formulario de foto...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Configurar características de la ventana
            const width = Math.min(window.innerWidth * 0.9, 900);
            const height = Math.min(window.innerHeight * 0.9, 700);
            const left = (window.innerWidth - width) / 2;
            const top = (window.innerHeight - height) / 2;
            
            const featuresString = [
                `width=${width}`,
                `height=${height}`,
                `left=${left}`,
                `top=${top}`,
                'resizable=yes',
                'scrollbars=yes'
            ].join(',');

            // Abrir nueva ventana con el formulario de subir foto
            const newWindow = window.open(
                `../template_estudiante/subir_foto.php?est_id=${param_id}&nombre=${encodeURIComponent(param_nombres)}`,
                'SubirFotoEstudiante',
                featuresString
            );
            
            if (!newWindow) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo abrir el formulario. Por favor, permita las ventanas emergentes para este sitio.'
                });
                return;
            }
            
            // Cerrar el indicador de carga después de un breve momento
            setTimeout(() => {
                Swal.close();
            }, 1000);
            
            // Detectar cuando se cierra la ventana
            const checkWindow = setInterval(() => {
                if (newWindow.closed) {
                    clearInterval(checkWindow);
                    Swal.close();
                }
            }, 1000);
            
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al abrir el formulario de subida de fotos'
            });
        }
    }
    
    async function visualizar_documento(param_id, param_nombres) {
        try {
            // Mostrar indicador de carga
            const loadingAlert = Swal.fire({
                title: 'Cargando visualizador...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Configurar características de la ventana
            const width = Math.min(window.innerWidth * 0.9, 1200);
            const height = Math.min(window.innerHeight * 0.9, 800);
            const left = (window.innerWidth - width) / 2;
            const top = (window.innerHeight - height) / 2;
            
            const featuresString = [
                `width=${width}`,
                `height=${height}`,
                `left=${left}`,
                `top=${top}`,
                'resizable=yes',
                'scrollbars=yes'
            ].join(',');

            // Abrir nueva ventana con el visualizador
            const newWindow = window.open(
                `visualizar_documento.php?est_id=${param_id}`,
                'VisualizadorDocumento',
                featuresString
            );
            
            if (!newWindow) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo abrir el visualizador. Por favor, permita las ventanas emergentes para este sitio.'
                });
                return;
            }
            
            // Cerrar el indicador de carga después de un breve momento
            setTimeout(() => {
                Swal.close();
            }, 1000);
            
            // Detectar cuando se cierra la ventana
            const checkWindow = setInterval(() => {
                if (newWindow.closed) {
                    clearInterval(checkWindow);
                    Swal.close();
                }
            }, 1000);
            
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al abrir el visualizador de documentos'
            });
        }
    }
    
    function imprimir_reporte(param_detalle)
    {
        window.open("ficha_de_matricula_adm_final.php?id_detalle="+param_detalle, "_blank");
    }
    function eliminar_estudiante(param_id,param_ced,param_det)
    {
        //alert('eliminar'+param_id);
        let text = "Desea eliminar el registro";
        if (confirm(text) == true) {
            window.open("eliminar_estudiante.php?id_estudiante="+param_id+"&cedula="+param_ced+"&det_estudiante="+param_det, "_blank");
            //window.close();
        } else {
            return;
        }
        location.reload();
    }
    
</script>
 <?php
          $det_estudiante= $_SESSION["Ses_det_estudiante"];
          echo $det_estudiante;
?>
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

            <!-- Nav Item - Pages Collapse Menu 
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="#">Login</a>
                        <a class="collapse-item" href="#">Register</a>
                        <a class="collapse-item" href="#">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="#">Blank Page</a>
                    </div>
                </div>
            </li>
            -->
            <!-- Nav Item - Charts 
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>
             -->
            <!-- Nav Item - Tables 
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>
             -->
            <!-- Divider -->
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
                                <!--<a class="dropdown-item" href="#">
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
                                </a>-->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="cerrar_sesion()" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Session
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Estudiante</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <button type="button" 
                                 onclick="carga_unitaria()"
                                class="btn btn-outline-primary">
                                <b><i class='bi bi-journal-plus'></i> Crear</b>
                            </button>
                            <button type="button" 
                                 onclick="ver_datos_agrupados()"
                                class="btn btn-outline-primary">
                                <b><i class='bi bi-search'></i> Ver datos por curso</b>
                            </button>
                             <button type="button" 
                                 onclick="ver_datos_jornadas()"
                                class="btn btn-outline-primary">
                                <b><i class='bi bi-search'></i> Ver datos por jornadas</b>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                                <thead>
                                          <tr>
                                            <th>Estado</th>
                                            <th>Cedula</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                            <!--<th>Nivel Educacion</th>-->
                                            <th>Curso 2025</th>
                                            <th>Jornada</th>
                                            <th>Paralelo</th>
                                            <!--<th>Repite</th>-->
                                            <th>Tutor</th>
                                            <th>Acciones</th>  
                                        </tr>
                                </thead>
                                <tfoot>
                                          <tr>
                                            <th>Cedula</th>
                                            <th>Cedula</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                           <!-- <th>Nivel Educacion</th>-->
                                            <th>Curso 2025</th>
                                            <th>Jornada</th>
                                            <th>Paralelo</th>
                                            <!--<th>Repite</th>-->
                                            <th>Tutor</th>
                                            <th>Acciones</th>
                                          </tr>
                               </tfoot>
                                    <tbody>
                                        <?php
                                    $sql = "SELECT a.est_id,b.*, concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso 
                                            FROM estudiantes a, est_datos b , jornada_curso c
                                            WHERE b.infaca_jornada_curso = c.id_jornada_curso
                                            AND   a.est_cedula = b.dtest_cedula;";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                        // Establecer la variable de género
                                        $genero_var = "";
                                        if ($row["dtest_genero"]=="1") {
                                            $genero_var = "MASCULINO";
                                        } else {
                                            $genero_var = "FEMENINO";
                                        }
                                        
                                        // Preparar el nombre completo para el estudiante con seguridad
                                        $nombreCompleto = htmlspecialchars($row["dtest_nombres"] . ' ' . $row["dtest_apellidos"], ENT_QUOTES, 'UTF-8');
                                        
                                        // Preparar los botones de acción con seguridad
                                        $editBtn = '<button type="button" onclick="modificar_estudiante(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-success" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-pencil-square"></i></b></button>';
                                        
                                        $deleteBtn = '<button type="button" onclick="eliminar_estudiante(\'' . htmlspecialchars($row["est_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-danger" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-trash-fill"></i></b></button>';
                                        
                                        $pdfBtn = '<button type="button" onclick="imprimir_reporte(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-secondary" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-file-pdf"></i></b></button>';
                                        
                                        $uploadBtn = '<button type="button" onclick="subir_documento(\'' . htmlspecialchars($row["est_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-info" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-cloud-arrow-up-fill"></i></b></button>';
                                        
                                        $viewBtn = '<button type="button" onclick="visualizar_documento(\'' . htmlspecialchars($row["est_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-warning" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-eye-fill"></i></b></button>';
                                         
                                         $photoBtn = '<button type="button" onclick="subir_foto(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-primary" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-camera"></i></b></button>';
                                        
                                        // Generar la fila de la tabla usando comillas simples para evitar problemas de escape
                                        echo '<tr>
                                            <td><b>' . htmlspecialchars($row["dtest_estado_reg"], ENT_QUOTES, 'UTF-8') . '</b></td>
                                            <td>' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_apellidos"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_nombres"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_curso_act"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_jornada_archivo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_paralelo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_tutorcurso"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . $editBtn . ' ' . $deleteBtn . ' ' . $pdfBtn . ' ' . $uploadBtn . ' ' . $viewBtn . ' ' . $photoBtn . '</td>
                                        </tr>';

                                    }
                                    } else {
                                    echo "0 results";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                     <!--   
                                        <tr>
                                            <td>0963213194</td>
                                            <td>ALEJANDRO TORO</td>
                                            <td>AYLEEN ROMINA</td>
                                            <td>INICIAL </td>
                                            <td>INICIAL 1 </td>
                                            <td>MATUTINA </td>
                                            <td>A </td>
                                            <td>NO </td>
                                            <td>YAQUELINE ECHEVERRIA  </td>
                                        </tr>
                                        -->  

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="#">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="datos_agrupados" tabindex="-1" role="dialog" aria-labelledby="data_actualizar_estudiantes" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Cantidad de estudiantes por curso y jornada</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <form method="POST">
                
                <div class="container container-fluid">
                     <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example2"  cellspacing="0">
                                <thead>
                                          <tr>
                                            <th>Cantidad</th>
                                            <th>Curso</th>
                                            <th>Curso Jornada</th>
                                           
                                        </tr>
                                </thead>
                                <tfoot>
                                          <tr>
                                           <th>Cantidad</th>
                                           <th>Curso</th>
                                            <th>Curso Jornada</th>
                                           
                                          </tr>
                               </tfoot>
                                    <tbody>
                                        <?php
                                    include("../conexion/conexion.php");
                                    $sql = "SELECT count(*) as cantidad,
                                    concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,
                                    b.infaca_jornada_curso,infaca_curso_act 
                                    FROM estudiantes a, est_datos b , jornada_curso c 
                                    WHERE b.infaca_jornada_curso = c.id_jornada_curso 
                                    AND a.est_cedula = b.dtest_cedula 
                                    group by b.infaca_jornada_curso;";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                       
                                        echo "<tr><td><b>".$row["cantidad"]."</b></td><td>".$row["infaca_curso_act"]."</td> <td>" . $row["nombre_jornada_curso"]. "</td> </tr>";

                                    }
                                    } else {
                                    echo "0 results";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                    
                </div>
		 </form>
          </div>
        </div>
      </div>


 <div class="modal fade" id="datos_jornadas" tabindex="-1" role="dialog" aria-labelledby="data_jornadas" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Cantidad estudiantes (Jornadas)</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <form method="POST">
                
                <div class="container container-fluid">
                    
                     <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example3"  cellspacing="0">
                                <thead>
                                          <tr>
                                            <th>Cantidad</th>
                                            <th>Jornada</th>
                                            
                                           
                                        </tr>
                                </thead>
                                <tfoot>
                                          <tr>
                                           <th>Cantidad</th>
                                           <th>Jornada</th>
                                           
                                           
                                          </tr>
                               </tfoot>
                                    <tbody>
                                        <?php
                                    include("../conexion/conexion.php");
                                    $sql = "SELECT count(*) as cantidad,
                                    concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,b.infaca_jornada_curso,b.infaca_curso_act,
                                    c.jornada as jornada 
                                    FROM estudiantes a, est_datos b , jornada_curso c 
                                    WHERE b.infaca_jornada_curso = c.id_jornada_curso 
                                    AND a.est_cedula = b.dtest_cedula 
                                    group by c.jornada;";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                       
                                        echo "<tr><td><b>".$row["cantidad"]."</b></td><td>".$row["jornada"]."</td> </tr>";

                                    }
                                    } else {
                                    echo "0 results";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                </div>
		 </form>
          </div>
        </div>
      </div>

<!-- Modal para subir documentos -->
<div class="modal fade" id="documentoModal" tabindex="-1" role="dialog" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel"><b>Adjuntar documento</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="documentoForm" method="POST" enctype="multipart/form-data" action="upload_documento.php">
                    <div class="form-group">
                        <label>Estudiante:</label>
                        <p><b id="est_nombres"></b></p>
                        <input type="hidden" id="est_id" name="est_id">
                        <input type="hidden" id="est_cedula" name="est_cedula">
                    </div>
                    
                    <div class="form-group">
                        <label for="documento">Seleccionar documento:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="documento" name="documento" required>
                            <label class="custom-file-label" for="documento">Elegir archivo...</label>
                        </div>
                        <small class="form-text text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (Máx. 5MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción del documento:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Ingrese una breve descripción del documento"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar documento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Script para mostrar el nombre del archivo seleccionado en el input file
$(document).ready(function() {
    // Cuando se selecciona un archivo, mostrar su nombre en el label
    $('.custom-file-input').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).siblings('.custom-file-label').text(fileName);
    });
    
    // Manejar el envío del formulario con AJAX
    $('#documentoForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Subiendo documento...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $('#documentoModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message
                        }).then(() => {
                            // Opcional: recargar la página para ver cambios
                            // location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la respuesta del servidor'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al comunicarse con el servidor'
                });
            }
        });
    });
});
</script>
</body>

</html>
