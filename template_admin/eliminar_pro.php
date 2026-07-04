<?php

session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    $id_doc = mysqli_real_escape_string($conn, $_GET['id_doc']);

    $sql = "DELETE FROM docente WHERE id_doc = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id_doc);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Docente eliminado correctamente'
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