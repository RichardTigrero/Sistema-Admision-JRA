<?php
session_start();
include("../conexion/conexion.php");

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y sanitizar datos del formulario

$cedula = isset($_POST['cedula']) ? trim(mysqli_real_escape_string($conn, $_POST['cedula'])) : '';
$nombres = isset($_POST['nombres']) ? trim(mysqli_real_escape_string($conn, $_POST['nombres'])) : '';
$apellidos = isset($_POST['apellidos']) ? trim(mysqli_real_escape_string($conn, $_POST['apellidos'])) : '';
$institucion = isset($_POST['institucion']) ? trim(mysqli_real_escape_string($conn, $_POST['institucion'])) : '';
$carrera = isset($_POST['carrera']) ? trim(mysqli_real_escape_string($conn, $_POST['carrera'])) : '';
$fecha_inicio = isset($_POST['fecha_inicio']) ? trim(mysqli_real_escape_string($conn, $_POST['fecha_inicio'])) : null;
$fecha_fin = isset($_POST['fecha_fin']) ? trim(mysqli_real_escape_string($conn, $_POST['fecha_fin'])) : null;
$horario = isset($_POST['horario']) ? trim(mysqli_real_escape_string($conn, $_POST['horario'])) : null;
$area_asignada = isset($_POST['area_asignada']) ? trim(mysqli_real_escape_string($conn, $_POST['area_asignada'])) : null;
$supervisor = isset($_POST['supervisor']) ? trim(mysqli_real_escape_string($conn, $_POST['supervisor'])) : null;
$estado = isset($_POST['estado']) && $_POST['estado'] !== '' ? trim(mysqli_real_escape_string($conn, $_POST['estado'])) : 'active';
$observaciones = isset($_POST['observaciones']) ? trim(mysqli_real_escape_string($conn, $_POST['observaciones'])) : null;

// Validar campos obligatorios
if (empty($cedula) || empty($nombres) || empty($apellidos) || empty($institucion) || empty($carrera)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados']);
    exit;
}

try {
    // Validar si la cédula ya existe
    $sql_check = "SELECT id FROM unidad_educativa.pasantes WHERE cedula = ? LIMIT 1";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $cedula);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'La cédula ya está registrada. No se puede duplicar.']);
        mysqli_stmt_close($stmt_check);
        exit;
    }
    mysqli_stmt_close($stmt_check);

    // Preparar consulta SQL para insertar
    $sql = "INSERT INTO unidad_educativa.pasantes 
            (cedula, nombres, apellidos, institucion, carrera, fecha_inicio, fecha_fin, horario, area_asignada, supervisor, estado, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . mysqli_error($conn));
    }
    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "ssssssssssss", 
        $cedula, $nombres, $apellidos, $institucion, $carrera, 
        $fecha_inicio, $fecha_fin, $horario, $area_asignada, 
        $supervisor, $estado, $observaciones);
    // Ejecutar consulta
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Pasante registrado correctamente']);
    } else {
        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Cerrar conexiones
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>