<?php
session_start();
include("../conexion/conexion.php");

// Verificar si se ha proporcionado un ID de estudiante
if (!isset($_GET['est_id']) || empty($_GET['est_id'])) {
    echo "<div class='alert alert-danger'>No se proporcionó un ID de estudiante válido</div>";
    exit;
}

$est_id = $_GET['est_id'];
$nombre_estudiante = isset($_GET['nombre']) ? $_GET['nombre'] : 'Estudiante';

// Consultar información del estudiante
$sql = "SELECT e.*, d.dtest_nombres, d.dtest_apellidos, d.dtest_cedula, d.dtest_imagen_usuario 
        FROM estudiantes e
        INNER JOIN est_datos d ON e.est_id = d.dtest_id
        WHERE e.est_id = '$est_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>No se encontró información del estudiante</div>";
    exit;
}

$row = mysqli_fetch_assoc($result);
$imagen_json = $row['dtest_imagen_usuario'];
$imagen_actual = null;

// Si hay una imagen previa, decodificarla
if (!empty($imagen_json)) {
    $imagen_info = json_decode($imagen_json, true);
    if ($imagen_info && isset($imagen_info['ruta'])) {
        $imagen_actual = $imagen_info;
    }
}

// Procesar el formulario de carga
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_estudiante"])) {
    $upload_dir = "../uploads/fotos/";
    
    // Crear el directorio si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES["foto_estudiante"];
    $file_name = $file["name"];
    $file_tmp = $file["tmp_name"];
    $file_size = $file["size"];
    $file_error = $file["error"];
    
    // Verificar si se subió un archivo correctamente
    if ($file_error === 0) {
        // Obtener la extensión del archivo
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Verificar el tipo de archivo (solo imágenes)
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed)) {
            // Limitar el tamaño del archivo (5MB)
            if ($file_size <= 5242880) {
                // Crear un nombre único para el archivo
                $file_name_new = "foto_" . $est_id . "_" . uniqid('', true) . '.' . $file_ext;
                $file_destination = $upload_dir . $file_name_new;
                
                // Mover el archivo al directorio de destino
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Crear la estructura JSON para guardar en la base de datos
                    $imagen_info = array(
                        'ruta' => $file_destination,
                        'nombre_original' => $file_name,
                        'extension' => $file_ext,
                        'fecha_subida' => date('Y-m-d H:i:s')
                    );
                    
                    $imagen_json = json_encode($imagen_info);
                    
                    // Actualizar la base de datos
                    $sql_update = "UPDATE est_datos SET dtest_imagen_usuario = '$imagen_json' WHERE dtest_id = '$est_id'";
                    
                    if (mysqli_query($conn, $sql_update)) {
                        echo "<div class='alert alert-success'>La foto se ha subido y asociado correctamente al estudiante.</div>";
                        $imagen_actual = $imagen_info;
                    } else {
                        echo "<div class='alert alert-danger'>Error al actualizar la base de datos: " . mysqli_error($conn) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Error al mover el archivo al directorio de destino.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>El archivo es demasiado grande. El tamaño máximo permitido es 5MB.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Tipo de archivo no permitido. Solo se permiten imágenes (JPG, JPEG, PNG, GIF).</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error al subir el archivo. Código de error: " . $file_error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Foto de Estudiante</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
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
        .upload-container {
            margin-top: 20px;
        }
        .preview-container {
            margin-top: 20px;
            text-align: center;
        }
        .preview-image {
            max-width: 300px;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        .upload-form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Subir Foto de Estudiante</h3>
            <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($nombre_estudiante); ?></p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="upload-form">
                    <h5>Cargar nueva foto</h5>
                    <p class="text-muted">Seleccione una imagen JPG, JPEG, PNG o GIF para subir (tamaño máximo: 5MB)</p>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="foto_estudiante" class="form-label">Seleccionar foto</label>
                            <input class="form-control" type="file" id="foto_estudiante" name="foto_estudiante" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Subir Foto</button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="preview-container">
                    <h5>Foto actual</h5>
                    <?php if ($imagen_actual): ?>
                        <img src="<?php echo htmlspecialchars($imagen_actual['ruta']); ?>" alt="Foto del estudiante" class="preview-image img-fluid">
                        <p class="mt-2"><small>Subida el: <?php echo htmlspecialchars($imagen_actual['fecha_subida']); ?></small></p>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                            <p>No hay una foto asociada actualmente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
