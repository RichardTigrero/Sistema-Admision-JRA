<?php

session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    $id_jornada_curso = mysqli_real_escape_string($conn, $_GET['id']);

    // Primero verificamos si el curso está siendo utilizado por estudiantes
    $sql_check = "SELECT COUNT(*) as count FROM est_datos WHERE infaca_jornada_curso = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $id_jornada_curso);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] > 0) {
        throw new Exception("No se puede eliminar esta jornada/curso porque está asignada a " . $row['count'] . " estudiante(s). Puede cambiar su estado a INACTIVO.");
    }

    // Si no está siendo utilizado, procedemos a eliminar
    $sql = "DELETE FROM jornada_curso WHERE id_jornada_curso = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_jornada_curso);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Jornada y curso eliminados correctamente'
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
?> 