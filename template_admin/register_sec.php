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

    <title>Registro de Secretaria</title>

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
    function modificar_secretaria(param1, param2, param3, param4, param5, param6, param7, param8, param9) {
        var ModalEdit = new bootstrap.Modal(document.getElementById('exampleModalLong2')).show();
        document.getElementById('id_sec_act').value = param1;
        document.getElementById('nombre_act').value = param2.replace(/-/g, ' ');
        document.getElementById('apellido_act').value = param3.replace(/-/g, ' ');
        document.getElementById('cedula_act').value = param4.replace(/-/g, ' ');
        document.getElementById('email_act').value = param5.replace(/-/g, ' ');
        document.getElementById('celular_act').value = param6.replace(/-/g, ' ');
        document.getElementById('nombre_usuario_act').value = param7.replace(/-/g, ' ');
        document.getElementById('password_act').value = param8.replace(/-/g, ' ');
        document.getElementById('cargo_act').value = param9.replace(/-/g, ' ');
    }
    function eliminar_secretaria(param)
    {
        
        let text = "Desea eliminar el registro";
        if (confirm(text) == true) {
            window.open("eliminar_sec.php?id_sec="+param,target="_blank");
        } else {
            return;
        }
        location.reload();
       
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




                <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Secretaria</h1>

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
                        <th>Cedula</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Nombre usuario</th>
                        <th>Acciones</th>  
                    </tr>
            </thead>
            <tfoot>
                      <tr>
                      <th>Cedula</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Nombre usuario</th> 
                        <th>Acciones</th> 
                      </tr>
           </tfoot>
                <tbody>
                    <?php
                $sql = "SELECT id_sec,sec_cedula,sec_apellidos,sec_nombres,sec_email,sec_celular,sec_usuario,sec_contrasenia,sec_cargo 
                        FROM secretaria";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<tr> 
                    <td>" . $row["sec_cedula"]. "</td> 
                    <td> " . $row["sec_apellidos"]. "</td> 
                    <td> " . $row["sec_nombres"]. "</td>  
                    <td> " . $row["sec_email"]. "</td> 
                    <td> " . $row["sec_celular"]. "</td> 
                    <td> " . $row["sec_usuario"]. "</td>  
                    <td> 
                    <button type='button'  onclick=modificar_secretaria('". $row["id_sec"]."','".str_replace(" ","-",$row["sec_nombres"])."','".str_replace(" ","-",$row["sec_apellidos"])."','".str_replace(" ","-",$row["sec_cedula"])."','".str_replace(" ","-",$row["sec_email"])."','".str_replace(" ","-",$row["sec_celular"])."','".str_replace(" ","-",$row["sec_usuario"])."','".str_replace(" ","-",$row["sec_contrasenia"])."','".str_replace(" ","-",$row["sec_cargo"])."') class='btn btn-outline-success' value=" . $row["id_sec"]. "><i class='bi bi-pencil-square'></i></button> <button type='button' onclick=eliminar_secretaria('". $row["id_sec"]."') class='btn btn-outline-danger' value=" . $row["id_sec"]. " ><i class='bi bi-trash-fill'></i></button>
                    </td> 
                    </tr>";

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




<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
                <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de secretaria</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createForm" onsubmit="event.preventDefault(); insertarSec(new FormData(this));">
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="nombre" name="nombre" placeholder="Nombres" required>
                    <input type="text" class="form-control mb-3" id="apellido" name="apellido" placeholder="Apellidos" required>
                    <input type="text" class="form-control mb-3" id="cedula" name="cedula" placeholder="Cedula" required>
                    <input type="email" class="form-control mb-3" id="email" name="email" placeholder="info@example.com" required>
                    <input type="text" class="form-control mb-3" id="celular" name="celular" placeholder="Celular" required>
                    <input type="text" class="form-control mb-3" id="nombre_usuario" name="nombre_usuario" placeholder="Username" required>
                    <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Password" required>
                    <input type="text" class="form-control mb-3" id="cargo" name="cargo" placeholder="Cargo" required>
                    <input type="text" class="form-control mb-3" id="usuarioc" name="usuarioc" placeholder="Creado por" readonly value="<?php echo $_SESSION["nombres_admin"]." ".$_SESSION["apellidos_admin"]; ?>">
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
                <h5 class="modal-title"><b>Formulario de secretaria (Modificar)</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateForm" onsubmit="event.preventDefault(); modificarSec(new FormData(this));">
                <div class="modal-body">
                    <input type="hidden" id="id_sec_act" name="id_sec_act">
                    <input type="text" class="form-control mb-3" id="nombre_act" name="nombre_act" placeholder="Nombres" required>
                    <input type="text" class="form-control mb-3" id="apellido_act" name="apellido_act" placeholder="Apellidos" required>
                    <input type="text" class="form-control mb-3" id="cedula_act" name="cedula_act" placeholder="Cedula" required>
                    <input type="email" class="form-control mb-3" id="email_act" name="email_act" placeholder="info@example.com" required>
                    <input type="text" class="form-control mb-3" id="celular_act" name="celular_act" placeholder="Celular" required>
                    <input type="text" class="form-control mb-3" readonly id="nombre_usuario_act" name="nombre_usuario_act" placeholder="Username" required>
                    <input type="password" class="form-control mb-3" id="password_act" name="password_act" placeholder="Password" required>
                    <input type="text" class="form-control mb-3" id="cargo_act" name="cargo_act" placeholder="Cargo" required>
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
    // Función para insertar secretaria
    <script>
    async function insertarSec(formData) {
        try {
            const response = await fetch('insertar_sec.php', {
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

// Función para eliminar secretaria
async function eliminar_secretaria(id_sec) {
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
            const response = await fetch(`eliminar_sec.php?id_sec=${id_sec}`);
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

// Función para modificar secretaria
async function modificarSec(formData) {
    try {
        const response = await fetch('modificar_sec.php', {
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
</body>

</html>  
