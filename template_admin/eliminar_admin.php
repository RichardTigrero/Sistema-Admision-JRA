<?php

session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    $id_adm = mysqli_real_escape_string($conn, $_GET['id_adm']);

    $sql = "DELETE FROM administrador WHERE id_adm = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id_adm);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Administrador eliminado correctamente'
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