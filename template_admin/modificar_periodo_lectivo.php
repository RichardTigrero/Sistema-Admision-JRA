<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $id_periodo_lectivo = mysqli_real_escape_string($conn, $_POST['id_periodo_lectivo_act']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion_act']);
    $observacion = mysqli_real_escape_string($conn, $_POST['observacion_act']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado_act']);

    // Validar que no estén vacíos
    if (empty($descripcion) || empty($observacion) || empty($estado)) {
        throw new Exception("Todos los campos son obligatorios");
    }

    $sql = "UPDATE periodo_lectivo 
            SET descripcion = ?, 
                observacion = ?, 
                estado = ?
            WHERE id_periodo_lectivo = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", 
        $descripcion, $observacion, $estado, $id_periodo_lectivo
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Período lectivo actualizado correctamente'
        ]);
    } else {
        throw new Exception("Error al actualizar el registro: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
