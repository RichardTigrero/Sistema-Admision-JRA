<?php
session_start();
include("../conexion/conexion.php");

// Verificar si se recibieron los parámetros necesarios
if (!isset($_GET['est_id']) || !isset($_GET['cedula']) || !isset($_GET['nombre'])) {
    die("Error: Parámetros incompletos");
}

$est_id = $_GET['est_id'];
$cedula = $_GET['cedula'];
$nombre = $_GET['nombre'];

// Consultar información adicional del estudiante si es necesario
$sql = "SELECT a.est_id, b.*, 
         concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) AS nombre_jornada_curso 
        FROM estudiantes a, est_datos b, jornada_curso c
        WHERE b.infaca_jornada_curso = c.id_jornada_curso
        AND a.est_cedula = b.dtest_cedula
        AND b.dtest_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $est_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Error: No se encontró información del estudiante");
}

$estudiante = mysqli_fetch_assoc($result);

/* Debugging - Mostrar todos los campos disponibles
$debug_info = "<div class='alert alert-info'><h4>Información de Depuración</h4>";
$debug_info .= "<p><strong>Campo dtest_imagen_usuario existe: </strong>" . (array_key_exists('dtest_imagen_usuario', $estudiante) ? 'SÍ' : 'NO') . "</p>";
$debug_info .= "<p><strong>Ruta de imagen utilizada: </strong>" . (isset($foto_path) ? $foto_path : 'No definida aún') . "</p>";
$debug_info .= "<h5>Campos de imagen encontrados:</h5><pre>";
foreach ($estudiante as $key => $value) {
    if ($key === 'dtest_imagen_usuario' || strpos($key, 'imagen') !== false || strpos($key, 'foto') !== false) {
        $debug_info .= "$key: $value<br>";
    }
}


 También mostrar los primeros caracteres de todos los campos
$debug_info .= "</pre><h5>Todos los campos disponibles:</h5><pre>";
foreach ($estudiante as $key => $value) {
    // Mostrar solo los primeros 50 caracteres si es un valor largo
    $display_value = (strlen($value) > 50) ? substr($value, 0, 50) . '...' : $value;
    $debug_info .= "$key: $display_value<br>";
}
$debug_info .= "</pre></div>";
*/
// Verificar si existe la columna dtest_imagen_usuario
$column_exists = array_key_exists('dtest_imagen_usuario', $estudiante);

// Obtener la ruta de la imagen - primero intentamos con dtest_imagen_usuario
$foto_path = "../img/undraw_profile.svg"; // Imagen por defecto
$imagen_json = $estudiante['dtest_imagen_usuario'];
$imagen_actual = null;

if (!empty($imagen_json)) {
    $imagen_info = json_decode($imagen_json, true);
    if ($imagen_info && isset($imagen_info['ruta'])) {
        $imagen_actual = $imagen_info;
        $foto_path = $imagen_actual['ruta'];    
    }
}


/*if ($column_exists && !empty($estudiante['dtest_imagen_usuario'])) {
    // Si la ruta comienza con http o https, usarla directamente
    if (preg_match('/^(http|https):\/\//i', $estudiante['dtest_imagen_usuario'])) {
        $foto_path = $estudiante['dtest_imagen_usuario'];
    } else {
        // Si es una ruta relativa, asegurarse de que tenga el formato correcto
        $foto_path = $estudiante['dtest_imagen_usuario'];
        // Si no comienza con "/" o "../", añadir "../"
        if (!preg_match('/^\/|^\.\./i', $foto_path)) {
            $foto_path = "../" . $foto_path;
        }
    }
}
*/
// Búsqueda alternativa de foto si no encontramos en dtest_imagen_usuario
// Buscamos campos relacionados con imágenes en el registro
/*if ($foto_path === "../img/undraw_profile.svg") {
    foreach ($estudiante as $key => $value) {
        if ((strpos($key, 'imagen') !== false || strpos($key, 'foto') !== false) && !empty($value)) {
            // Encontramos un campo de imagen con valor
            $foto_path = $value;
            if (!preg_match('/^(http|https):\/\//i', $foto_path) && !preg_match('/^\/|^\.\./i', $foto_path)) {
                $foto_path = "../" . $foto_path;
            }
            break; // Usar el primer campo de imagen que encontremos
        }
    }
}
*/
// Generar datos para el código QR usando la API solicitada
$qr_data = "ID:" . $est_id . "|CEDULA:" . $cedula . "|NOMBRE:" . $nombre;
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qr_data) . "&size=200x200";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">
    <title>Carnet Digital - <?php echo htmlspecialchars($nombre); ?></title>
    
    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
    <!-- Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
        
        .carnet-container {
            max-width: 450px;
            margin: 0 auto;
        }
        
        .carnet {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border: 1px solid #ddd;
        }
        
        .carnet-header {
            background-color: #1c2c5b;
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .carnet-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .carnet-logo img {
            height: 100px;
            margin-right: 20px;
        }
        
        .carnet-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px;
            text-align: center;
        }
        
        .carnet-body {
            padding: 20px;
            display: flex;
        }
        
        .carnet-photo {
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
            margin-right: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .carnet-photo img {
            width: 100%;
            height: auto;
        }
        
        .carnet-info {
            flex: 1;
        }
        
        .carnet-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .carnet-info .label {
            font-weight: bold;
            color: #1c2c5b;
        }
        
        .carnet-footer {
            background-color: #f8f9fc;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }
        
        .carnet-qr {
            background-color: #f8f9fc;
            border-top: 1px solid #ddd;
        }
        
        .btn-toolbar {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php //echo $debug_info; // Mostrar info de debugging ?>
<div class="carnet-container">
                    <div class="card carnet">
                        <div class="carnet-header">
                            <div class="carnet-logo">
                                <img src="../imagenes/Logo_JRA.jpeg" alt="Logo" 
                            >
                                <h5 class="carnet-title">UNIDAD EDUCATIVA FISCAL<br>JAIME ROLDÓS AGUILERA</h5>
                            </div>
                            <div class="text-center">
                                <h6>CARNET ESTUDIANTIL</h6>
                            </div>
                        </div>
                        
                        <div class="carnet-body">
                            <div class="carnet-photo">
                                <img src="<?php echo $foto_path; ?>" alt="Foto del estudiante">
                            </div>
                            <div class="carnet-info">
                                <p><span class="label">Cédula:</span> <?php echo htmlspecialchars($cedula); ?></p>
                                <p><span class="label">Nombre:</span> <?php echo htmlspecialchars($nombre); ?></p>
                                <p><span class="label">Curso:</span> <?php echo htmlspecialchars($estudiante['nombre_jornada_curso']); ?></p>
                                <p><span class="label">Año Lectivo:</span> 2025-2026</p>
                                <?php if (!empty($estudiante['infrepre_nomape'])): ?>
                                <p><span class="label">Representante:</span> <?php echo htmlspecialchars($estudiante['infrepre_nomape']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="carnet-qr text-center p-2">
                            <img src="<?php echo $qr_url; ?>" alt="Código QR" style="max-width:150px; height:auto;">
                            <p class="mt-2" style="font-size: 11px;">Escanea este código para verificar</p>
                        </div>
                        
                        <div class="carnet-footer">
                            <p>Este carnet es personal e intransferible.<br>Válido durante el período académico 2025-2026</p>
                        </div>
                    </div>
                    
                    <div class="btn-toolbar no-print">
                        <button type="button" class="btn btn-primary mr-2" onclick="window.print();">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.close();">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
