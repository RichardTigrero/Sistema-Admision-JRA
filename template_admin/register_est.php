<?php
session_start();
include("../conexion/conexion.php");

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
    <title>Principal Estudiantes</title>

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
    <script src="../js/demo/datatables-demo.js"></script> -->
    <!-- Removed demo.js script that was causing SyntaxHighlighter error -->
   <!-- Agregar SweetAlert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<script  type="text/javascript" class="init">

$(document).ready(function() {
var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [  'excel', 'colvis' ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    } );
    
 table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
 });
 
var table2 = $('#example2').DataTable({
        //scrollY: '200px',
        scrollCollapse: true,
        paging: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });


var table3 = $('#example3').DataTable({
        //scrollY: '200px',
        scrollCollapse: true,
        paging: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });

function ver_datos_agrupados()
    {
       
       // var ModalEdit = new bootstrap.Modal(datos_agrupados, {}).show();
        $('#datos_agrupados').modal('show');
       
    }
    
function ver_datos_jornadas()
    {
       
       // var ModalEdit = new bootstrap.Modal(datos_jornadas, {}).show();
         $('#datos_jornadas').modal('show');
       
    }

    function filtrar_por_periodo() {
        const periodSelect = document.getElementById('filtro_periodo');
        const periodo = periodSelect.value;
        
        if (!periodo) {
            alert('Por favor selecciona un período lectivo');
            return;
        }

        // Recargar la página con el parámetro de período
        window.location.href = 'register_est.php?periodo=' + encodeURIComponent(periodo);
    }

    function carga_masiva()
    {
        window.open("register_est_user.php","_self");
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
    async function modificar_estudiante(param_id_detalle) {
        try {
            // Mostrar indicador de carga
            const loadingAlert = Swal.fire({
                title: 'Cargando datos...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            // Configurar ventana modal
            const windowFeatures = {
                height: 700,
                width: 1000,
                left: (window.screen.width - 1000) / 2,
                top: (window.screen.height - 700) / 2,
                location: 'no',
                menubar: 'yes',
                resizable: 'yes', // Cambiado a 'yes' para mejor compatibilidad
                scrollbars: 'yes',
                status: 'no',
                titlebar: 'yes', // Cambiado a 'yes' para mejor visibilidad
                toolbar: 'no'
            };

            const featuresString = Object.entries(windowFeatures)
                .map(([key, value]) => `${key}=${value}`)
                .join(',');

            const periodo = document.getElementById('filtro_periodo')?.value || '';
            let url = `data_estudiantes_udp.php?id_detalle=${param_id_detalle}`;

            if (periodo) {
                url += `&periodo=${encodeURIComponent(periodo)}`;
            }

            // Abrir nueva ventana
            const newWindow = window.open(
                url,
                'ModificarEstudiante',
                featuresString
            );

            if (!newWindow) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El navegador bloqueó la ventana emergente. Por favor, permita las ventanas emergentes para este sitio.'
                });
                return;
            }

            // Cerrar loading cuando la ventana esté cargada
            newWindow.addEventListener('load', function() {
                loadingAlert.then(result => {
                    Swal.close();
                });
            });

            // Manejar caso de ventana cerrada
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
                text: error.message || 'Error al abrir la ventana de modificación'
            });
        }
    }
    
    function imprimir_reporte(param_detalle)
    {
        const periodo = document.getElementById('filtro_periodo') ? document.getElementById('filtro_periodo').value : '';
        let url = "ficha_de_matricula_adm_final.php?id_detalle=" + encodeURIComponent(param_detalle);
        if (periodo) {
            url += "&periodo=" + encodeURIComponent(periodo);
        }
        window.open(url,target='_blank');
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
    async function generar_carnet(param_id, param_cedula, param_nombres) {
        try {
            // Mostrar indicador de carga
            const loadingAlert = Swal.fire({
                title: 'Generando carnet digital...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Configurar características de la ventana
            const width = Math.min(window.innerWidth * 0.9, 600);
            const height = Math.min(window.innerHeight * 0.9, 500);
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

            // Abrir nueva ventana con el carnet digital
            const newWindow = window.open(
                `carnet_digital.php?est_id=${param_id}&cedula=${param_cedula}&nombre=${encodeURIComponent(param_nombres)}`,
                'CarnetDigital',
                featuresString
            );
            
            if (!newWindow) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo abrir el carnet digital. Por favor, permita las ventanas emergentes para este sitio.'
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
                text: error.message || 'Error al generar el carnet digital'
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
    
    async function eliminar_estudiante(param_id, param_ced, param_det) {
        try {
            const result = await Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                // Mostrar loading mientras se procesa
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                });

                const response = await fetch(`eliminar_estudiante.php?id_estudiante=${param_id}&cedula=${param_ced}&det_estudiante=${param_det}`);
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
                text: error.message || 'Error al eliminar el estudiante'
            });
        }
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombres_admin"]; ?></span>
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
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                                <label for="filtro_periodo" style="margin: 0; font-weight: 500;">Filtrar por Período Lectivo:</label>
                                <select id="filtro_periodo" class="form-control" style="width: auto; display: inline-block;">
                                    <option value="">Todos los períodos</option>
                                    <?php
                                    $sql_periodos = "SELECT id_periodo_lectivo, descripcion FROM periodo_lectivo WHERE estado IN ('ACTIVO', 'CERRADO') ORDER BY id_periodo_lectivo DESC";
                                    $result_periodos = mysqli_query($conn, $sql_periodos);
                                    
                                    if (mysqli_num_rows($result_periodos) > 0) {
                                        while($row_periodo = mysqli_fetch_assoc($result_periodos)) {
                                            $selected = (isset($_GET['periodo']) && $_GET['periodo'] == $row_periodo['id_periodo_lectivo']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row_periodo['id_periodo_lectivo']) . '" ' . $selected . '>' . htmlspecialchars($row_periodo['descripcion']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <button type="button" onclick="filtrar_por_periodo()" class="btn btn-outline-info">
                                    <b><i class='bi bi-funnel'></i> Filtrar</b>
                                </button>
                            </div>
                            <button type="button" 
                                 onclick="carga_masiva()"
                                class="btn btn-outline-primary">
                                <b><i class='bi bi-journal-plus'></i> Crear</b>
                            </button>
                            <div class="alert alert-info mt-2" style="display: inline-block; margin-left: 10px;">
                                <small><i class="fas fa-info-circle"></i> Ahora admite formato simplificado: solo es necesario Cédula, Nombres y Apellidos.</small>
                            </div>
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
                            <?php if ($usar_historial) { ?>
                                <div class="alert alert-secondary">
                                    <i class="bi bi-archive"></i> Estás consultando un período cerrado. Los datos provienen de las tablas históricas y se muestran en modo solo lectura.
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example" width="100%" cellspacing="0">
                                <thead>
                                          <tr>
                                            <th>Acciones</th>  
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
                                            <th>Nacionalidad</th>
                                            <th>Genero</th>
                                            <th>Fecha Nacimiento</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            <th>Email</th>
                                            <th>Direccion</th>
                                            <th>Institucion Proviene</th>
                                            <th>Alergias</th>
                                            <th>Tipo Alergias</th>
                                            <th>Tiene carnet</th>
                                            <th>%Discapacidad</th>
                                            <th>Vacuna Covid</th>
                                            <th>Cedula Representante</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Parentesco</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email Representante</th>
                                            <th>Vive con Madre</th>
                                            <th>Cedula</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email</th>
                                            <th>Vive con el Padre</th>
                                            <th>Cedula</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email</th>
                                            <th>Numero Emergencia #1</th>
                                            <th>Nombre de persona que contesta</th>
                                            <th>Numero Emergencia #2</th>
                                            <th>Nombre de persona que contesta</th>
                                            
                                        </tr>
                                </thead>
                                <tfoot>
                                          <tr>
                                            <th>Acciones</th>
                                            <th>Estado</th>
                                            <th>Cedula</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                           <!-- <th>Nivel Educacion</th>-->
                                            <th>Curso 2025</th>
                                            <th>Jornada</th>
                                            <th>Paralelo</th>
                                            <!--<th>Repite</th>-->
                                            <th>Tutor</th>
                                            <th>Nacionalidad</th>
                                            <th>Genero</th>
                                            <th>Fecha Nacimiento</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            <th>Email</th>
                                            <th>Direccion</th>
                                            <th>Institucion Proviene</th>
                                            <th>Alergias</th>
                                            <th>Tipo Alergias</th>
                                            <th>Tiene carnet</th>
                                            <th>%Discapacidad</th>
                                            <th>Vacuna Covid</th>
                                            <th>Cedula Representante</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Parentesco</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email Representante</th>
                                            <th>Vive con Madre</th>
                                            <th>Cedula</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email</th>
                                            <th>Vive con el Padre</th>
                                            <th>Cedula</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Celular</th>
                                            <th>Convencional</th>
                                            <th>Email</th>
                                            <th>Numero Emergencia #1</th>
                                            <th>Nombre de persona que contesta</th>
                                            <th>Numero Emergencia #2</th>
                                            <th>Nombre de persona que contesta</th>
                                          </tr>
                               </tfoot>
                                    <tbody>
                                        <?php
                                    if ($usar_historial) {
                                        $sql = "SELECT a.est_id, b.*,
                                                 CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) AS nombre_jornada_curso
                                                FROM estudiantes_historial a
                                                INNER JOIN est_datos_historial b
                                                        ON a.est_cedula = b.dtest_cedula
                                                       AND a.id_periodo_lectivo = b.id_periodo_lectivo
                                                INNER JOIN jornada_curso_historial c
                                                        ON b.infaca_jornada_curso = c.id_jornada_curso
                                                       AND b.id_periodo_lectivo = c.id_periodo_lectivo
                                                WHERE b.id_periodo_lectivo = " . (int)$periodo_seleccionado;
                                    } else {
                                        $sql = "SELECT a.est_id, b.*,
                                                 CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) AS nombre_jornada_curso
                                                FROM estudiantes a
                                                INNER JOIN est_datos b ON a.est_cedula = b.dtest_cedula
                                                INNER JOIN jornada_curso c ON b.infaca_jornada_curso = c.id_jornada_curso
                                                WHERE 1 = 1";
                                    }
                                    
                                    // Agregar filtro por período si se ha seleccionado
                                    if (!$usar_historial && $periodo_seleccionado > 0) {
                                        $sql .= " AND (b.dtest_ciclo_datos = '" . (int)$periodo_seleccionado . "'
                                                   OR c.id_periodo_lectivo = " . (int)$periodo_seleccionado . ")";
                                    }
                                    
                                    $sql .= ";";
                                    
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                            $genero_var = "";
                                            if ($row["dtest_genero"]=="1")
                                            {
                                                $genero_var = "MASCULINO";
                                            }else
                                            {
                                                $genero_var = "FEMENINO";
                                            }
                                        //echo "<tr> <td>" . $row["dtest_cedula"]. "</td> <td> " . $row["dtest_apellidos"]. "</td> <td> " . $row["dtest_nombres"]. "</td> <td> " . $row["infaca_nivel_edu"]. "</td> <td> " . $row["infaca_curso_act"]. "</td> <td> " . $row["infaca_jornada_archivo"]. "</td> <td> " . $row["infaca_paralelo"]. "</td> <td> " . $row["infaca_repite"]. "</td> <td> " . $row["infaca_tutorcurso"]. "</td> <td> <button type='button' onclick= modificar_producto('". $row["est_id"]."') class='btn btn-outline-success' value=" . $row["est_id"]. "><i class='bi bi-pencil-square'></i> Modificar</button> <button type='button' onclick=eliminar_producto('". $row["est_id"]."') class='btn btn-outline-danger' value=" . $row["est_id"]. " ><i class='bi bi-trash-fill'></i>Eliminar</button></td> <tr>";
                                        //echo "<tr> <td>" . $row["dtest_cedula"]. "</td> <td> " . $row["dtest_apellidos"]. "</td> <td> " . $row["dtest_nombres"]. "</td>  <td> " . $row["infaca_curso_act"]. "</td> <td> " . $row["infaca_jornada_archivo"]. "</td> <td> " . $row["infaca_paralelo"]. "</td> <td> " . $row["infaca_tutorcurso"]. "</td> <td> <button type='button' onclick= modificar_producto('". $row["est_id"]."') class='btn btn-outline-success' value=" . $row["est_id"]. "><i class='bi bi-pencil-square'></i><b> Modificar</b></button> <button type='button' onclick=eliminar_producto('". $row["est_id"]."') class='btn btn-outline-danger' value=" . $row["est_id"]. " ><i class='bi bi-trash-fill'></i><b>Eliminar</b></button></td> </tr>";
 // First prepare the full name for the student
 $nombreCompleto = htmlspecialchars($row["dtest_nombres"] . ' ' . $row["dtest_apellidos"], ENT_QUOTES, 'UTF-8');
 
 // Build the buttons with proper JavaScript quote escaping
 $editBtn = '<button type="button" onclick="modificar_estudiante(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-success" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-pencil-square"></i></b></button>';
 
 $deleteBtn = '<button type="button" onclick="eliminar_estudiante(\'' . htmlspecialchars($row["est_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-danger" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-trash-fill"></i></b></button>';
 
 $pdfBtn = '<button type="button" onclick="imprimir_reporte(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-secondary" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-file-pdf"></i></b></button>';
 
 $uploadBtn = '<button type="button" onclick="subir_documento(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-primary" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-file-upload"></i></b></button>';
 
 $viewBtn = '<button type="button" onclick="visualizar_documento(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-info" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-eye"></i></b></button>';
  
  $photoBtn = '<button type="button" onclick="subir_foto(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-warning" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-camera"></i></b></button>';
 
 // Crear botón para el carnet digital
  $carnetBtn = '<button type="button" onclick="generar_carnet(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-dark" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="fas fa-id-card"></i></b></button>';
 
 if ($usar_historial) {
 $editBtn = '';
 $deleteBtn = '';
 $uploadBtn = '';
 $viewBtn = '';
 $photoBtn = '';
 }

 // Output the table row using single quotes to avoid escaping issues
                                        echo '<tr>
                                            <td>' . $editBtn . ' ' . $deleteBtn . ' ' . $pdfBtn . ' ' . $uploadBtn . ' ' . $viewBtn . ' ' . $photoBtn . ' ' . $carnetBtn . '</td>
                                            <td><b>' . htmlspecialchars($row["dtest_estado_reg"], ENT_QUOTES, 'UTF-8') . '</b></td>
                                            <td>' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_apellidos"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_nombres"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_curso_act"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_jornada_archivo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_paralelo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_tutorcurso"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_nacionalidad"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . $genero_var . '</td>
                                            <td>' . htmlspecialchars($row["dtest_fnnacimiento"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_edad"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_celular"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_gmail"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_direccion"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dest_institucion_prev"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estsalud_alergias"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estsalud_tipoalerg"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estsalud_discapatipo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estsalud_carnet"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estsalud_vacuna19"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_nomape"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_parentezco"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_clular"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_convencional"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infrepre_gmail"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_vivemadre"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_nomape"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_celular"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_convencional"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infmadre_gmail"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_vivepadre"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_nomap"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_celular"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_convencional"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infpadre_gmail"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estemergencia_numerocell1"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estemergencia_nombre1"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estemergencia_numcell2"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["estemergencia_nombre2"], ENT_QUOTES, 'UTF-8') . '</td>
                                        </tr>';

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
                                    if ($usar_historial) {
                                        $sql = "SELECT COUNT(*) as cantidad,
                                                CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,
                                                b.infaca_jornada_curso,
                                                b.infaca_curso_act
                                                FROM estudiantes_historial a
                                                INNER JOIN est_datos_historial b
                                                        ON a.est_cedula = b.dtest_cedula
                                                       AND a.id_periodo_lectivo = b.id_periodo_lectivo
                                                INNER JOIN jornada_curso_historial c
                                                        ON b.infaca_jornada_curso = c.id_jornada_curso
                                                       AND b.id_periodo_lectivo = c.id_periodo_lectivo
                                                WHERE b.id_periodo_lectivo = " . (int)$periodo_seleccionado . "
                                                GROUP BY b.infaca_jornada_curso, b.infaca_curso_act, c.nivel, c.jornada, c.curso, c.paralelo;";
                                    } else {
                                        $sql = "SELECT COUNT(*) as cantidad,
                                                CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,
                                                b.infaca_jornada_curso,
                                                b.infaca_curso_act
                                                FROM estudiantes a
                                                INNER JOIN est_datos b ON a.est_cedula = b.dtest_cedula
                                                INNER JOIN jornada_curso c ON b.infaca_jornada_curso = c.id_jornada_curso
                                                WHERE 1 = 1";
                                        if ($periodo_seleccionado > 0) {
                                            $sql .= " AND (b.dtest_ciclo_datos = '" . (int)$periodo_seleccionado . "'
                                                       OR c.id_periodo_lectivo = " . (int)$periodo_seleccionado . ")";
                                        }
                                        $sql .= " GROUP BY b.infaca_jornada_curso, b.infaca_curso_act, c.nivel, c.jornada, c.curso, c.paralelo;";
                                    }
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
                                    if ($usar_historial) {
                                        $sql = "SELECT COUNT(*) as cantidad,
                                                CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,
                                                b.infaca_jornada_curso,
                                                b.infaca_curso_act,
                                                c.jornada as jornada
                                                FROM estudiantes_historial a
                                                INNER JOIN est_datos_historial b
                                                        ON a.est_cedula = b.dtest_cedula
                                                       AND a.id_periodo_lectivo = b.id_periodo_lectivo
                                                INNER JOIN jornada_curso_historial c
                                                        ON b.infaca_jornada_curso = c.id_jornada_curso
                                                       AND b.id_periodo_lectivo = c.id_periodo_lectivo
                                                WHERE b.id_periodo_lectivo = " . (int)$periodo_seleccionado . "
                                                GROUP BY c.jornada;";
                                    } else {
                                        $sql = "SELECT COUNT(*) as cantidad,
                                                CONCAT(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso,
                                                b.infaca_jornada_curso,
                                                b.infaca_curso_act,
                                                c.jornada as jornada
                                                FROM estudiantes a
                                                INNER JOIN est_datos b ON a.est_cedula = b.dtest_cedula
                                                INNER JOIN jornada_curso c ON b.infaca_jornada_curso = c.id_jornada_curso
                                                WHERE 1 = 1";
                                        if ($periodo_seleccionado > 0) {
                                            $sql .= " AND (b.dtest_ciclo_datos = '" . (int)$periodo_seleccionado . "'
                                                       OR c.id_periodo_lectivo = " . (int)$periodo_seleccionado . ")";
                                        }
                                        $sql .= " GROUP BY c.jornada;";
                                    }
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
