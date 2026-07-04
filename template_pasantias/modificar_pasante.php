<?php
session_start();
include("../conexion/conexion.php");

// Validar que se recibe el id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3>ID de pasante no especificado.</h3>";
    exit;
}
$id = intval($_GET['id']);

// Obtener datos del pasante
$sql = "SELECT * FROM unidad_educativa.pasantes WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pasante = mysqli_fetch_assoc($result);

if (!$pasante) {
    echo "<h3>Pasante no encontrado.</h3>";
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim(mysqli_real_escape_string($conn, $_POST['cedula']));
    $nombres = trim(mysqli_real_escape_string($conn, $_POST['nombres']));
    $apellidos = trim(mysqli_real_escape_string($conn, $_POST['apellidos']));
    $institucion = trim(mysqli_real_escape_string($conn, $_POST['institucion']));
    $carrera = trim(mysqli_real_escape_string($conn, $_POST['carrera']));
    $fecha_inicio = trim(mysqli_real_escape_string($conn, $_POST['fecha_inicio']));
    $fecha_fin = trim(mysqli_real_escape_string($conn, $_POST['fecha_fin']));
    $horario = trim(mysqli_real_escape_string($conn, $_POST['horario']));
    $area_asignada = trim(mysqli_real_escape_string($conn, $_POST['area_asignada']));
    $supervisor = trim(mysqli_real_escape_string($conn, $_POST['supervisor']));
    $estado = trim(mysqli_real_escape_string($conn, $_POST['estado']));
    $observaciones = trim(mysqli_real_escape_string($conn, $_POST['observaciones']));

    // Validar campos obligatorios
    if (empty($cedula) || empty($nombres) || empty($apellidos) || empty($institucion) || empty($carrera)) {
        $error = "Todos los campos obligatorios deben ser completados";
    } else {
        // Verificar si la cédula ya existe en otro registro
        $sql_check = "SELECT id FROM unidad_educativa.pasantes WHERE cedula = ? AND id != ? LIMIT 1";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "si", $cedula, $id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error = "La cédula ya está registrada en otro pasante.";
        } else {
            // Actualizar datos
            $sql_update = "UPDATE unidad_educativa.pasantes SET cedula=?, nombres=?, apellidos=?, institucion=?, carrera=?, fecha_inicio=?, fecha_fin=?, horario=?, area_asignada=?, supervisor=?, estado=?, observaciones=? WHERE id=?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "ssssssssssssi", $cedula, $nombres, $apellidos, $institucion, $carrera, $fecha_inicio, $fecha_fin, $horario, $area_asignada, $supervisor, $estado, $observaciones, $id);
            if (mysqli_stmt_execute($stmt_update)) {
                $success = "Pasante actualizado correctamente.";
                // Recargar datos
                $pasante = [
                    'cedula' => $cedula,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'institucion' => $institucion,
                    'carrera' => $carrera,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'horario' => $horario,
                    'area_asignada' => $area_asignada,
                    'supervisor' => $supervisor,
                    'estado' => $estado,
                    'observaciones' => $observaciones
                ];
            } else {
                $error = "Error al actualizar el pasante.";
            }
            mysqli_stmt_close($stmt_update);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Pasante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Modificar Pasante</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Cédula *</label>
                <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($pasante['cedula']); ?>" required maxlength="20">
            </div>
            <div class="form-group col-md-4">
                <label>Nombres *</label>
                <input type="text" name="nombres" class="form-control" value="<?php echo htmlspecialchars($pasante['nombres']); ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>Apellidos *</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($pasante['apellidos']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Institución *</label>
                <input type="text" name="institucion" class="form-control" value="<?php echo htmlspecialchars($pasante['institucion']); ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>Carrera *</label>
                <input type="text" name="carrera" class="form-control" value="<?php echo htmlspecialchars($pasante['carrera']); ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>Área Asignada</label>
                <input type="text" name="area_asignada" class="form-control" value="<?php echo htmlspecialchars($pasante['area_asignada']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Supervisor</label>
                <input type="text" name="supervisor" class="form-control" value="<?php echo htmlspecialchars($pasante['supervisor']); ?>">
            </div>
            <div class="form-group col-md-4">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="activo" <?php if($pasante['estado']=='activo') echo 'selected'; ?>>Activo</option>
                    <option value="inactivo" <?php if($pasante['estado']=='inactivo') echo 'selected'; ?>>Inactivo</option>
                    <option value="completado" <?php if($pasante['estado']=='completado') echo 'selected'; ?>>Completado</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Horario</label>
                <input type="text" name="horario" class="form-control" value="<?php echo htmlspecialchars($pasante['horario']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($pasante['fecha_inicio']); ?>">
            </div>
            <div class="form-group col-md-6">
                <label>Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($pasante['fecha_fin']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"><?php echo htmlspecialchars($pasante['observaciones']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="register_pasantias.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
