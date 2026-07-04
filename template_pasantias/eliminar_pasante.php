<?php
session_start();
include("../conexion/conexion.php");

header('Content-Type: application/json');

try {
    if(!isset($_GET['id'])) {
        throw new Exception('ID de pasante no proporcionado');
    }

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM unidad_educativa.pasantes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Pasante eliminado correctamente']);
    } else {
        throw new Exception('Error al eliminar pasante: ' . mysqli_error($conn));
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if(isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>