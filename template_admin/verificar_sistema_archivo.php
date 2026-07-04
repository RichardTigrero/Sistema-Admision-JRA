<?php
session_start();
include("../conexion/conexion.php");

// Permitir acceso solo a administradores
if (!isset($_SESSION["nombres_admin"])) {
    header("Location: ../login.php");
    exit;
}

$verificaciones = [
    'tablas_historial' => false,
    'procedimiento' => false,
    'datos_estudiantes' => false,
    'mensaje_general' => ''
];

// Verificar tablas históricas
$tablas_requeridas = ['estudiantes_historial', 'est_datos_historial'];
$tablas_existentes = [];

$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE()";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $tablas_existentes[] = $row['TABLE_NAME'];
}

$verificaciones['tablas_historial'] = (in_array('estudiantes_historial', $tablas_existentes) && 
                                        in_array('est_datos_historial', $tablas_existentes));

// Verificar procedimiento almacenado
$sql_proc = "SELECT ROUTINE_NAME FROM INFORMATION_SCHEMA.ROUTINES 
             WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_NAME = 'sp_procesar_periodo_lectivo'";
$result_proc = mysqli_query($conn, $sql_proc);
$verificaciones['procedimiento'] = (mysqli_num_rows($result_proc) > 0);

// Contar registros
$sql_est = "SELECT COUNT(*) as total FROM estudiantes";
$sql_dat = "SELECT COUNT(*) as total FROM est_datos";
$result_est = mysqli_query($conn, $sql_est);
$result_dat = mysqli_query($conn, $sql_dat);

$est_count = mysqli_fetch_assoc($result_est)['total'];
$dat_count = mysqli_fetch_assoc($result_dat)['total'];

$verificaciones['datos_estudiantes'] = ($est_count > 0 && $dat_count > 0);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verificación Sistema de Archivo</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="principal_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="bi bi-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Menu</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="principal_admin.php">
                    <i class="bi bi-bank"></i>
                    <span>UE Jaime Roldos Aguilera</span>
                </a>
            </li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombres_admin"]; ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Verificación del Sistema de Archivo de Períodos</h1>

                    <div class="row">
                        <!-- Componente 1: Tablas Históricas -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-<?php echo $verificaciones['tablas_historial'] ? 'success' : 'danger'; ?> shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-left text-<?php echo $verificaciones['tablas_historial'] ? 'success' : 'danger'; ?> font-weight-bold text-uppercase mb-1">
                                        <i class="fas fa-<?php echo $verificaciones['tablas_historial'] ? 'check-circle' : 'times-circle'; ?>"></i> 
                                        Tablas Históricas
                                    </div>
                                    <div class="h5 mb-0 text-gray-800">
                                        <?php 
                                        if ($verificaciones['tablas_historial']) {
                                            echo "✓ Tablas creadas correctamente";
                                        } else {
                                            echo "✗ Tablas no encontradas";
                                        }
                                        ?>
                                    </div>
                                    <small class="text-muted">
                                        Tabla: estudiantes_historial<br>
                                        Tabla: est_datos_historial
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Componente 2: Procedimiento Almacenado -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-<?php echo $verificaciones['procedimiento'] ? 'success' : 'danger'; ?> shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-left text-<?php echo $verificaciones['procedimiento'] ? 'success' : 'danger'; ?> font-weight-bold text-uppercase mb-1">
                                        <i class="fas fa-<?php echo $verificaciones['procedimiento'] ? 'check-circle' : 'times-circle'; ?>"></i> 
                                        Procedimiento Almacenado
                                    </div>
                                    <div class="h5 mb-0 text-gray-800">
                                        <?php 
                                        if ($verificaciones['procedimiento']) {
                                            echo "✓ Procedimiento creado";
                                        } else {
                                            echo "✗ Procedimiento no encontrado";
                                        }
                                        ?>
                                    </div>
                                    <small class="text-muted">
                                        Procedimiento: sp_procesar_periodo_lectivo
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Disponibles -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold">Información de la Base de Datos</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%">
                                            <tr>
                                                <td><strong>Registros en tabla 'estudiantes':</strong></td>
                                                <td><?php echo number_format($est_count); ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Registros en tabla 'est_datos':</strong></td>
                                                <td><?php echo number_format($dat_count); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado General -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="alert alert-<?php 
                                if ($verificaciones['tablas_historial'] && $verificaciones['procedimiento']) {
                                    echo 'success';
                                } else {
                                    echo 'danger';
                                }
                            ?>" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-<?php echo ($verificaciones['tablas_historial'] && $verificaciones['procedimiento']) ? 'check' : 'exclamation-triangle'; ?>"></i>
                                    Estado General del Sistema
                                </h5>
                                <p class="mb-0">
                                    <?php 
                                    if ($verificaciones['tablas_historial'] && $verificaciones['procedimiento']) {
                                        echo "✓ El sistema está correctamente instalado y listo para usar.";
                                    } else {
                                        echo "✗ El sistema no está completamente instalado. Por favor, ejecuta el script SQL";
                                        echo " (conexion/crear_tablas_historial.sql) en tu base de datos antes de usar el sistema.";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Instrucciones -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="m-0 font-weight-bold">Próximos Pasos</h6>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li>Si algún componente no está instalado, abre el archivo <code>conexion/crear_tablas_historial.sql</code></li>
                                        <li>Copia su contenido y pégalo en phpMyAdmin en la pestaña SQL</li>
                                        <li>Ejecuta el script</li>
                                        <li>Recarga esta página para verificar</li>
                                        <li>¡El sistema estará listo para procesar períodos lectivos!</li>
                                    </ol>
                                    <p class="mt-3 text-muted">
                                        <strong>Para instrucciones detalladas:</strong> Abre el archivo 
                                        <code>conexion/INSTRUCCIONES_ARCHIVO_PERIODOS.txt</code>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>
