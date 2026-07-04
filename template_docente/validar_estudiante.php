<?php
session_start();
include("../conexion/conexion.php");

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

// Inicializar respuesta
$response = [
    'success' => false,
    'message' => ''
];

try {
    $id_det_estudiante = $_GET['id_det_estudiante'];
    $cedula_estudiante = $_GET['cedula'];
    
    // Validar datos requeridos
    if (empty($id_det_estudiante) || empty($cedula_estudiante)) {
        throw new Exception("Faltan datos requeridos (ID o cédula del estudiante)");
    }
    
    // Escapar datos para prevenir SQL Injection
    $id_det_estudiante = mysqli_real_escape_string($conn, $id_det_estudiante);
    $cedula_estudiante = mysqli_real_escape_string($conn, $cedula_estudiante);
    $estado_est = "VALIDADO";
    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    
    $sql = "UPDATE `est_datos`
    SET
    `dtest_estado_reg` = '$estado_est',
    `dtest_usuario_reg` = '$usuario'
    WHERE `dtest_id` = '$id_det_estudiante' AND `dtest_cedula` = '$cedula_estudiante'";
    
    if (mysqli_query($conn, $sql)) {
        $response['success'] = true;
        $response['message'] = "Datos validados correctamente";
    } else {
        throw new Exception("Error al actualizar el registro: " . mysqli_error($conn));
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar conexión
    mysqli_close($conn);
}

// Enviar respuesta JSON
echo json_encode($response);
