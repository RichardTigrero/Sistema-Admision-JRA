<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Iniciar transacción
    mysqli_begin_transaction($conn);

    // Sanitizar parámetros
    $id_estudiante = mysqli_real_escape_string($conn, $_GET['id_estudiante']);
    $cedula = mysqli_real_escape_string($conn, $_GET['cedula']);
    $det_estudiante = mysqli_real_escape_string($conn, $_GET['det_estudiante']);

    // Borrado de tabla detalle
    $sql0 = "DELETE FROM est_datos WHERE dtest_id = ? AND dtest_cedula = ?";
    $stmt0 = mysqli_prepare($conn, $sql0);
    mysqli_stmt_bind_param($stmt0, "ss", $det_estudiante, $cedula);
                
    if (!mysqli_stmt_execute($stmt0)) {
        throw new Exception("Error al eliminar detalles del estudiante: " . mysqli_error($conn));
    }

    // Borrado de tabla maestro
    $sql = "DELETE FROM estudiantes WHERE est_id = ? AND est_cedula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $id_estudiante, $cedula);
                
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al eliminar estudiante: " . mysqli_error($conn));
    }

    // Confirmar transacción
    mysqli_commit($conn);

    echo json_encode([
        'success' => true,
        'message' => 'Estudiante eliminado correctamente'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conn);
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    // Cerrar statements y conexión
    if (isset($stmt0)) mysqli_stmt_close($stmt0);
    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>