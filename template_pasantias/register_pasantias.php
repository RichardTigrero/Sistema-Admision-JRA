<?php
session_start();
include("../conexion/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de registro de pasantes">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">
    <title>Registro de Pasantes</title>

    <!-- Fuentes personalizadas -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Estilos para DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    
    <!-- Scripts JavaScript -->
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
    
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- [Contenido del sidebar...] -->
     
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- [Contenido del topbar...] -->
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Pasantes</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#crearPasanteModal">
                                <b><i class='bi bi-journal-plus'></i> Crear</b>
                            </button>
                            
                            <div class="alert alert-info mt-2" style="display: inline-block; margin-left: 10px;">
                                <small><i class="fas fa-info-circle"></i> Complete los campos obligatorios</small>
                            </div>
                            
                            <button type="button" onclick="ver_datos_agrupados()" class="btn btn-outline-primary">
                                <b><i class='bi bi-search'></i> Ver datos</b>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tablaPasantes" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Acciones</th>
                                            <th>Estado</th>
                                            <th>Cédula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Institución</th>
                                            <th>Carrera</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Horario</th>
                                            <th>Área Asignada</th>
                                            <th>Supervisor</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para obtener todos los pasantes
                                        $sql = "SELECT * FROM unidad_educativa.pasantes";
                                        $result = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                            while($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>
                                                    <td>
                                                        <button type="button" onclick="modificar_pasante(\''.$row["id"].'\')" class="btn btn-outline-success" title="Editar">
                                                            <b><i class="bi bi-pencil-square"></i></b>
                                                        </button>
                                                        <button type="button" onclick="eliminar_pasante(\''.$row["id"].'\')" class="btn btn-outline-danger" title="Eliminar">
                                                            <b><i class="bi bi-trash-fill"></i></b>
                                                        </button>
                                                        <a href="subir_foto_pasante.php?id='.$row["id"].'" class="btn btn-outline-warning" title="Subir Foto">
                                                            <i class="bi bi-camera-fill"></i>
                                                        </a>
                                                        <a href="carnet_digital_pasante.php?id='.$row["id"].'" class="btn btn-outline-dark" title="Carnet Digital" target="_blank">
                                                            <i class="bi bi-person-badge"></i>
                                                        </a>
                                                                     
                                                    </td>
                                                    <td><span class="badge badge-'.($row["estado"] == 'activo' ? 'success' : ($row["estado"] == 'inactivo' ? 'warning' : ($row["estado"] == 'completado' ? 'secondary' : 'info'))).'">'.$row["estado"].'</span></td>
                                                    <td>'.$row["cedula"].'</td>
                                                    <td>'.$row["nombres"].'</td>
                                                    <td>'.$row["apellidos"].'</td>
                                                    <td>'.$row["institucion"].'</td>
                                                    <td>'.$row["carrera"].'</td>
                                                    <td>'.$row["fecha_inicio"].'</td>
                                                    <td>'.$row["fecha_fin"].'</td>
                                                    <td>'.$row["horario"].'</td>
                                                    <td>'.$row["area_asignada"].'</td>
                                                    <td>'.$row["supervisor"].'</td>
                                                    <td>'.$row["observaciones"].'</td>
                                                </tr>';
                                            }
                                        } else {
                                            echo "<tr><td colspan='13' class='text-center'>No se encontraron registros de pasantes</td></tr>";
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
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal para crear nuevo pasante -->
    <div class="modal fade" id="crearPasanteModal" tabindex="-1" role="dialog" aria-labelledby="crearPasanteModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="crearPasanteModalLabel">Registrar Nuevo Pasante</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPasante" method="POST" autocomplete="off">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cédula <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="cedula" required pattern="[0-9]{10}" title="Ingrese 10 dígitos" maxlength="10">
                                </div>
                                <div class="form-group">
                                    <label>Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombres" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="apellidos" required>
                                </div>
                                <div class="form-group">
                                    <label>Institución <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="institucion" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Carrera <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="carrera" required>
                                </div>
                                <div class="form-group">
                                    <label>Área Asignada</label>
                                    <input type="text" class="form-control" name="area_asignada">
                                </div>
                                <div class="form-group">
                                    <label>Supervisor</label>
                                    <input type="text" class="form-control" name="supervisor">
                                </div>
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select class="form-control" name="estado">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                        <option value="completado">Completado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Horario</label>
                                    <input type="text" class="form-control" name="horario" placeholder="Ej: L-V 8am-4pm">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea class="form-control" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Pasante</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "Cerrar sesión" si está listo para finalizar su sesión actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        $(document).ready(function() {
            // Inicialización de DataTable
            var table = $('#tablaPasantes').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columnas',
                        className: 'btn btn-info'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true
            });

            // Manejo del formulario de creación de pasante
            $('#formPasante').on('submit', function(e) {
                e.preventDefault();
                
                // Validación de cédula (10 dígitos)
                if($('[name="cedula"]').val().length !== 10) {
                    Swal.fire('Error', 'La cédula debe tener 10 dígitos', 'error');
                    return false;
                }

                // Mostrar loader mientras se procesa
                Swal.fire({
                    title: 'Guardando pasante...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading() }
                });

                // Enviar datos por AJAX
                $.ajax({
                    url: 'guardar_pasante.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Cerrar modal y recargar la página
                                $('#crearPasanteModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Error al conectar con el servidor: ' + error, 'error');
                    }
                });
            });
        });

        /**
         * Función para mostrar datos agrupados
         */
        function ver_datos_agrupados() {
            // Implementar lógica para mostrar datos agrupados
            $('#datos_agrupados').modal('show');
        }
        
        /**
         * Función para cerrar sesión
         */
        function cerrar_sesion() {
            window.location.href = "logout.php";
        }
        
        /**
         * Función para abrir ventana de modificación de pasante
         * @param {number} pasante_id - ID del pasante a modificar
         */
        async function modificar_pasante(pasante_id) {
            try {
                const loadingAlert = Swal.fire({
                    title: 'Cargando datos...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading() }
                });

                // Configuración de la ventana emergente
                const windowFeatures = {
                    height: 700,
                    width: 1000,
                    left: (window.screen.width - 1000) / 2,
                    top: (window.screen.height - 700) / 2,
                    location: 'no',
                    menubar: 'yes',
                    resizable: 'yes',
                    scrollbars: 'yes',
                    status: 'no',
                    titlebar: 'yes',
                    toolbar: 'no'
                };

                const featuresString = Object.entries(windowFeatures)
                    .map(([key, value]) => `${key}=${value}`)
                    .join(',');

                // Abrir ventana de edición
                const newWindow = window.open(
                    `modificar_pasante.php?id=${pasante_id}`,
                    'ModificarPasante',
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

                // Cuando la ventana se cargue, cerrar el loader
                newWindow.addEventListener('load', function() {
                    loadingAlert.then(result => { Swal.close(); });
                });

                // Verificar si la ventana se cerró para recargar la página
                const checkWindow = setInterval(() => {
                    if (newWindow.closed) {
                        clearInterval(checkWindow);
                        location.reload();
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
        
        /**
         * Función para eliminar un pasante
         * @param {number} pasante_id - ID del pasante a eliminar
         */
        async function eliminar_pasante(pasante_id) {
            try {
                // Confirmar eliminación
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
                    // Mostrar loader durante la eliminación
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => { Swal.showLoading() }
                    });

                    // Enviar solicitud de eliminación
                    const response = await fetch(`eliminar_pasante.php?id=${pasante_id}`);
                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Recargar la página para actualizar la tabla
                        location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                }
            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al eliminar el pasante'
                });
            }
        }
    </script>
</body>
</html>