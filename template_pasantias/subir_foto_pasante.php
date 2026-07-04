<?php
session_start();
include("../conexion/conexion.php");

// Validar id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3 class='text-center'>ID de pasante no especificado.</h3>");
}
$id = intval($_GET['id']);

// Obtener datos del pasante
$sql = "SELECT nombres, apellidos FROM unidad_educativa.pasantes WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) die("Error en la preparación: ".mysqli_error($conn));
mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) die("Error al ejecutar: ".mysqli_stmt_error($stmt));
$pasante = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$pasante) die("<h3 class='text-center'>Pasante no encontrado.</h3>");

// Procesar subida de foto
$foto_actual = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($foto['type'], $allowed)) {
        $error = "Solo se permiten imágenes JPG, PNG o GIF.";
    } elseif ($foto['size'] > $max_size) {
        $error = "El archivo excede el límite de 5MB.";
    } else {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $nombre_archivo = 'pasante_'.$id.'_'.time().'.'.$ext;
        $ruta_destino = '../uploads/'.$nombre_archivo;
        
        if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
            $sql_update = "UPDATE unidad_educativa.pasantes SET foto = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "si", $nombre_archivo, $id);
            if (mysqli_stmt_execute($stmt_update)) {
                $foto_actual = $nombre_archivo;
                $success = "¡Foto actualizada correctamente!";
            } else {
                unlink($ruta_destino);
                $error = "Error al guardar en base de datos.";
            }
        } else {
            $error = "Error al subir el archivo.";
        }
    }
}

// Obtener foto actual
$sql_foto = "SELECT foto FROM unidad_educativa.pasantes WHERE id = ?";
$stmt_foto = mysqli_prepare($conn, $sql_foto);
mysqli_stmt_bind_param($stmt_foto, "i", $id);
mysqli_stmt_execute($stmt_foto);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_foto));
$foto_actual = $row['foto'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto de Pasante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-foto {
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .foto-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 2px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .btn-action {
            min-width: 120px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="card card-foto">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Subir Foto de Pasante</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($pasante['nombres'].' '.$pasante['apellidos']) ?></h5>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                    <div class="mt-2">
                        <a href="register_pasantias.php" class="btn btn-sm btn-success">
                            ← Volver al registro
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label">Seleccionar archivo (JPG/PNG, max 5MB)</label>
                        <input class="form-control" type="file" name="foto" accept="image/*" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="bi bi-upload"></i> Subir
                        </button>
                        <a href="register_pasantias.php" class="btn btn-secondary btn-action">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
                
                <div class="text-center">
                    <h6>Foto actual</h6>
                    <?php if ($foto_actual): ?>
                    <img src="../uploads/<?= htmlspecialchars($foto_actual) ?>" 
                         class="foto-preview img-thumbnail mb-2"
                         alt="Foto del pasante">
                    <?php else: ?>
                    <div class="text-muted">No hay foto registrada</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>