<?php

session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    $id_periodo_lectivo = mysqli_real_escape_string($conn, $_GET['id']);

    // Primero verificamos si el período está siendo utilizado en jornada_curso
    $sql_check = "SELECT COUNT(*) as count FROM jornada_curso WHERE periodo = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $id_periodo_lectivo);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] > 0) {
        throw new Exception("No se puede eliminar este período porque está siendo utilizado en " . $row['count'] . " jornada(s)/curso(s). Puede cambiar su estado a INACTIVO.");
    }

    // Si no está siendo utilizado, procedemos a eliminar
    $sql = "DELETE FROM periodo_lectivo WHERE id_periodo_lectivo = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_periodo_lectivo);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Período lectivo eliminado correctamente'
        ]);
    } else {
        throw new Exception("Error al eliminar el registro: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
