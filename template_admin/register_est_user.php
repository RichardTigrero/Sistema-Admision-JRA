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

    <title>Registro Carga Estudiantes</title>

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
        //alert("Cerrar Session");
        window.open("logout.php","_self");
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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <br>
                    <div class="row d-flex justify-content-center">
                        <h1>Carga masiva de estudiantes por cursos</h1>
                    </div>
                    <br>
                    <div class="row">
                     
            
                    <div class="col-sm-6">
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                          
                        <label><b>Curso:</b></label>
                        <select class="form-control form-control-md" name="curso" id="curso" required>
                            <option value="" >Seleccione curso</option>
                            <?php
                            //include("../conexion/conexion.php");
                            $sql = "SELECT id_jornada_curso, 
                                           nivel, 
                                           jornada, 
                                           curso, 
                                           paralelo,
                                     concat(nivel,'-',jornada,'-',curso,'-',paralelo) as nombre_jornada_curso , 
                                    (select concat(d.dst_nombres,' ',d.dst_apellidos) from docente d where d.id_doc=id_docente) as tutor
                                     FROM jornada_curso where estado='ACTIVO'";
                            $result = mysqli_query($conn, $sql);
                            
                            
                            if (mysqli_num_rows($result) > 0) {
                              // output data of each row
                              while($row = mysqli_fetch_assoc($result)) {
                                $codigo_pro = $row["id_jornada_curso"];
                                $nivel_pro = $row["nivel"];
                                $jornada_pro = $row["jornada"];
                                $curso_pro = $row["curso"];
                                $paralelo_pro = $row["paralelo"];
                                $nombre_pro = $row["nombre_jornada_curso"];
                                $tutor_pro = $row["tutor"];
                                
                                ?>
                                   <option value="<?php echo  $codigo_pro; ?>"><?php echo $nombre_pro; ?></option>
                                   
                                <?php
                                
                              }
                            } else {
                              echo "0 results";
                            }
                            
                             mysqli_close($conn);
                          ?>
                          </select>
                    
                         <div class="form-group mt-4">
                              <label for="excelFile"><b>Seleccione un archivo en formato CSV (.csv)</b></label>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" name="excelFile" id="excelFile" accept=".csv, .xls, .xlsx" required>
                                <label class="custom-file-label" for="excelFile">Seleccionar archivo...</label>
                              </div>
                              <small class="form-text text-muted">El archivo puede ser un CSV con <strong>solo 3 columnas obligatorias</strong>: Cédula, Nombres, Apellidos</small>
                              <small class="form-text text-muted">El resto de datos (Nivel Educación, Curso, Jornada, Paralelo, etc.) se tomarán automáticamente del curso seleccionado</small>
                              <small class="form-text text-muted">También puede incluir todas las columnas en este orden: Cédula, Nombres, Apellidos, Nivel Educación, Curso 2025, Jornada, Paralelo, Repite (SI/NO), Tutor</small>
                              <small class="form-text text-muted">Para convertir un Excel a CSV: abra su archivo en Excel, vaya a "Guardar como" y seleccione formato "CSV (delimitado por comas)"</small>
                              <div class="progress mt-3" style="display: none;" id="progressContainer">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                              </div>
                         </div>
                         <div class="alert alert-info" role="alert" id="statusMessage" style="display: none;">
                            Preparando carga...
                         </div>
                         <button type="submit" class="btn btn-primary w-100 mt-3" id="submitBtn">Subir Archivo</button>
                    </div>
                    </form>
                    </div>
                    <div class="col-sm-6">
                       <!-- Área para previsualización -->
                       <div class="card">
                          <div class="card-header bg-primary text-white">
                             <h5>Previsualización de datos</h5>
                          </div>
                          <div class="card-body">
                             <div id="previewArea" class="table-responsive">
                                <p class="text-muted">Seleccione un archivo Excel para previsualizar los datos antes de cargarlos.</p>
                             </div>
                          </div>
                       </div>
                    </div>
                     <br>
                    
                 
                    </div>
                    <div class="row d-flex justify-content-center">
                        
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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
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

    <!-- Scripts personalizados para la carga de archivos -->
    <script>
        $(document).ready(function() {
            // Mostrar nombre del archivo seleccionado
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
                previewExcel(this);
            });
            
            // Manejar el envío del formulario
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validar que se haya seleccionado un curso
                var curso = $('#curso').val();
                if (!curso) {
                    showAlert('Por favor, seleccione un curso', 'danger');
                    return false;
                }
                
                // Validar que se haya seleccionado un archivo
                var fileInput = $('#excelFile')[0];
                if (fileInput.files.length === 0) {
                    showAlert('Por favor, seleccione un archivo Excel', 'danger');
                    return false;
                }
                
                // Preparar formulario para envío AJAX
                var formData = new FormData(this);
                
                // Mostrar indicadores de progreso
                $('#progressContainer').show();
                $('#statusMessage').show().text('Subiendo archivo...');
                $('#submitBtn').prop('disabled', true);
                
                // Enviar formulario vía AJAX
                $.ajax({
                    url: 'ajax_procesar_excel.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                var percent = Math.round((e.loaded / e.total) * 100);
                                $('.progress-bar').attr('aria-valuenow', percent).css('width', percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        $('#progressContainer').hide();
                        
                        if (response.success) {
                            // Mostrar resultados exitosos
                            var message = 'Carga exitosa. ' + 
                                'Se procesaron ' + response.data.procesados + ' registros de ' + 
                                response.data.total_filas + ' filas con datos. ' + 
                                (response.data.errores > 0 ? 'Errores: ' + response.data.errores : 'Sin errores.');
                            
                            // Si se usó el formato simplificado, añadir información
                            if (response.data.formato_simplificado) {
                                message += '<br><strong>Nota:</strong> Se utilizó el formato simplificado. Los datos adicionales se tomaron automáticamente del curso seleccionado.';
                            }
                            
                            showAlert(message, 'success');
                            
                            // Si hay errores, mostrarlos también
                            if (response.errors.length > 0) {
                                var errorList = $('<ul class="mt-2"></ul>');
                                response.errors.forEach(function(error) {
                                    errorList.append($('<li></li>').text(error));
                                });
                                
                                $('#statusMessage').removeClass('alert-success')
                                    .addClass('alert-warning')
                                    .html('Se completó la carga con algunas advertencias:')
                                    .append(errorList);
                            }
                            
                            // Resetear el formulario después de 5 segundos si todo fue exitoso
                            if (response.data.errores === 0) {
                                setTimeout(function() {
                                    $('#uploadForm')[0].reset();
                                    $('.custom-file-label').html('Seleccionar archivo...');
                                    $('#previewArea').html('<p class="text-muted">Seleccione un archivo Excel para previsualizar los datos antes de cargarlos.</p>');
                                }, 5000);
                            }
                        } else {
                            // Mostrar mensaje de error
                            showAlert('Error: ' + response.message, 'danger');
                        }
                        
                        // Rehabilitar botón de envío
                        $('#submitBtn').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        $('#progressContainer').hide();
                        $('#submitBtn').prop('disabled', false);
                        
                        try {
                            var response = JSON.parse(xhr.responseText);
                            showAlert('Error en el servidor: ' + response.message, 'danger');
                        } catch (e) {
                            showAlert('Error en el servidor: ' + error, 'danger');
                        }
                    }
                });
            });
            
            // Función para mostrar alertas
            function showAlert(message, type) {
                $('#statusMessage')
                    .removeClass('alert-info alert-success alert-warning alert-danger')
                    .addClass('alert-' + type)
                    .html(message)
                    .show();
            }
            
            // Función para previsualizar el archivo Excel
            function previewExcel(input) {
                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    
                    // Verificar extensión
                    var fileExt = file.name.split('.').pop().toLowerCase();
                    if (fileExt !== 'csv' && fileExt !== 'xlsx' && fileExt !== 'xls') {
                        $('#previewArea').html('<div class="alert alert-danger">Formato de archivo no válido. Solo se permiten archivos CSV (.csv) o Excel (.xlsx, .xls)</div>');
                        return;
                    }
                    
                    if (fileExt !== 'csv') {
                        $('#previewArea').html(
                            '<div class="alert alert-warning">' +
                            '<strong>Se recomienda usar archivo CSV:</strong><br>' +
                            'Para convertir su Excel a CSV:<br>' +
                            '1. Abra el archivo en Excel<br>' +
                            '2. Vaya a "Archivo" > "Guardar como"<br>' +
                            '3. Seleccione formato "CSV (delimitado por comas)"<br>' +
                            '4. Guarde y suba el nuevo archivo CSV<br><br>' +
                            'El sistema intentará procesar su archivo Excel, pero podría fallar.' +
                            '</div>'
                        );
                        return;
                    }
                    
                    // Mostrar mensaje de carga
                    $('#previewArea').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando previsualización...</div>');
                    
                    // Crear un objeto FileReader
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        try {
                            // Previsualizar CSV
                            var csv = e.target.result;
                            var lines = csv.split('\n');
                            var previewHtml = '<table class="table table-striped table-sm">';
                            
                            // Leer encabezados y datos
                            var headers = ['Cédula', 'Nombres', 'Apellidos', 'Nivel Educación', 'Curso', 'Jornada', 'Paralelo', 'Repite', 'Tutor'];
                            var hasHeaders = false;
                            var startRow = 0;
                            
                            // Comprobar si la primera fila podría ser encabezados
                            if (lines.length > 0) {
                                var firstRowCells = lines[0].split(',');
                                if (firstRowCells.length > 0 && isNaN(firstRowCells[0].trim())) {
                                    hasHeaders = true;
                                    startRow = 1;
                                }
                            }
                            
                            previewHtml += '<thead><tr>';
                            
                            // Mostrar encabezados (solo los existentes en el CSV)
                            if (lines.length > 0) {
                                var sampleRow = lines[startRow] ? lines[startRow].split(',') : [];
                                var isSimplifiedFormat = sampleRow.length >= 3 && sampleRow.length < 9;
                                
                                // Si es formato simplificado, mostrar solo las columnas presentes
                                var visibleHeaders = isSimplifiedFormat ? headers.slice(0, sampleRow.length) : headers;
                                
                                visibleHeaders.forEach(function(header) {
                                    previewHtml += '<th>' + header + '</th>';
                                });
                                
                                previewHtml += '</tr></thead><tbody>';
                                
                                // Mostrar datos (máximo 5 filas)
                                var dataTotalRows = lines.length - startRow;
                                var maxRows = Math.min(lines.length, startRow + 5);
                                for (var i = startRow; i < maxRows; i++) {
                                    if (!lines[i].trim()) continue; // Saltar líneas vacías
                                    
                                    var cells = lines[i].split(',');
                                    previewHtml += '<tr>';
                                    
                                    // Mostrar celdas
                                    cells.forEach(function(cell, index) {
                                        if (index < visibleHeaders.length) {
                                            previewHtml += '<td>' + cell + '</td>';
                                        }
                                    });
                                    
                                    previewHtml += '</tr>';
                                }
                                
                                if (lines.length > startRow + 5) {
                                    previewHtml += '<tr><td colspan="' + visibleHeaders.length + '" class="text-center">... ' + (lines.length - startRow - 5) + ' filas más ...</td></tr>';
                                }
                                
                                previewHtml += '</tbody></table>';
                                
                                // Contar filas no vacías que realmente contienen datos
                                var dataRows = 0;
                                for (var i = startRow; i < lines.length; i++) {
                                    if (lines[i].trim() && lines[i].split(',')[0].trim()) {
                                        dataRows++;
                                    }
                                }
                                
                                var formatoMsg = '';
                                if (isSimplifiedFormat) {
                                    formatoMsg = '<div class="alert alert-info mb-2">' +
                                        '<strong>Formato simplificado detectado</strong><br>' +
                                        'Solo se encontraron ' + sampleRow.length + ' columnas. Los datos faltantes se tomarán automáticamente del curso seleccionado.' +
                                        '</div>';
                                }
                                
                                $('#previewArea').html(
                                    '<div class="alert alert-success mb-2">' +
                                    '<strong>Archivo cargado:</strong> ' + file.name + '<br>' +
                                    '<strong>Tamaño:</strong> ' + Math.round(file.size / 1024) + ' KB<br>' +
                                    '<strong>Filas con datos:</strong> ' + dataRows + '<br>' +
                                    (hasHeaders ? '<strong>Nota:</strong> Se detectó una fila de encabezados (será omitida durante el procesamiento)<br>' : '') +
                                    '</div>' +
                                    formatoMsg +
                                    '<h5>Vista previa:</h5>' +
                                    previewHtml
                                );
                            } else {
                                $('#previewArea').html('<div class="alert alert-danger">El archivo no contiene datos</div>');
                            }
                        } catch (e) {
                            $('#previewArea').html('<div class="alert alert-danger">No se pudo leer el archivo: ' + e.message + '</div>');
                        }
                    };
                    
                    reader.onerror = function() {
                        $('#previewArea').html('<div class="alert alert-danger">Error al leer el archivo</div>');
                    };
                    
                    reader.readAsText(file);
                }
            }
        });
    </script>

</body>

</html>
