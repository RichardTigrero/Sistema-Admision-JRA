<?php
session_start();
include("../conexion/conexion.php");

// Verificar si se ha proporcionado un ID de estudiante
if (!isset($_GET['est_id']) || empty($_GET['est_id'])) {
    echo "<div class='alert alert-danger'>No se proporcionó un ID de estudiante válido</div>";
    exit;
}

$est_id = $_GET['est_id'];

// Consultar información del estudiante y su documento
$sql = "SELECT e.*, d.dtest_id,d.dtest_nombres, d.dtest_apellidos, d.dtest_cedula, d.dtest_documento_adjunto 
        FROM estudiantes e
        INNER JOIN est_datos d ON e.est_id = d.dtest_id
        WHERE e.est_id = '$est_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>No se encontró información del estudiante</div>";
    exit;
}

$row = mysqli_fetch_assoc($result);
$documento_json = $row['dtest_documento_adjunto'];
$nombre_estudiante = $row['dtest_nombres'];
$cedula_estudiante = $row['dtest_cedula'];

// Información de depuración
//echo "<div class='alert alert-info'>ID de estudiante: " . htmlspecialchars($est_id) . "</div>";

if (empty($documento_json)) {
    echo "<div class='alert alert-warning'>El estudiante no tiene documentos adjuntos</div>";
    exit;
}

// Mostrar el JSON recibido para depuración
//echo "<div class='alert alert-info'>JSON recibido: " . htmlspecialchars($documento_json) . "</div>";

// Decodificar la información del documento
$documento_info = json_decode($documento_json, true);

// Comprobar si hay errores de JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<div class='alert alert-danger'>Error al decodificar JSON: " . json_last_error_msg() . "</div>";
    exit;
}

// Verificar la estructura del JSON
//echo "<div class='alert alert-info'>Estructura del documento: <pre>" . htmlspecialchars(print_r($documento_info, true)) . "</pre></div>";

if (!$documento_info || !isset($documento_info['ruta'])) {
    echo "<div class='alert alert-danger'>Información del documento inválida: estructura incorrecta</div>";
    exit;
}

$ruta_documento = $documento_info['ruta'];
$nombre_original = $documento_info['nombre_original'];
$descripcion = isset($documento_info['descripcion']) ? $documento_info['descripcion'] : 'Sin descripción';
$fecha_subida = isset($documento_info['fecha_subida']) ? $documento_info['fecha_subida'] : 'Fecha desconocida';

// Obtener la extensión del archivo
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Documento</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .document-container {
            margin-top: 20px;
        }
        .document-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Documento de Estudiante</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['dtest_nombres'] . ' ' . $row['dtest_apellidos']); ?></p>
            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($cedula_estudiante); ?></p>
        </div>
        
        <div class="document-info">
            <h5>Información del documento</h5>
            <p><strong>Nombre original:</strong> <?php echo htmlspecialchars($nombre_original); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($descripcion); ?></p>
            <p><strong>Fecha de subida:</strong> <?php echo htmlspecialchars($fecha_subida); ?></p>
        </div>
        
        <div class="document-container">
            <?php
            // Mostrar el documento según su tipo
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Es una imagen
                echo '<div class="alert alert-info">Tipo de documento: Imagen</div>';
                echo '<img src="' . htmlspecialchars($ruta_documento) . '" class="img-fluid" alt="Documento">';
            } elseif ($extension === 'pdf') {
                // Es un PDF
                echo '<div class="alert alert-info">Tipo de documento: PDF</div>';
                echo '<div class="ratio ratio-16x9" style="height: 600px;">
                        <iframe src="' . htmlspecialchars($ruta_documento) . '" allowfullscreen></iframe>
                      </div>';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                // Es un documento de Word
                echo '<div class="alert alert-info">Tipo de documento: Word (.'. $extension .')</div>';
                echo '<div class="alert alert-warning">No se puede mostrar el contenido del documento directamente. 
                      <a href="' . htmlspecialchars($ruta_documento) . '" class="btn btn-primary" download>Descargar documento</a></div>';
            } else {
                // Otro tipo de documento
                echo '<div class="alert alert-info">Tipo de documento: '. strtoupper($extension) .'</div>';
                echo '<div class="alert alert-warning">No se puede mostrar el contenido del documento directamente. 
                      <a href="' . htmlspecialchars($ruta_documento) . '" class="btn btn-primary" download>Descargar documento</a></div>';
            }
            ?>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
