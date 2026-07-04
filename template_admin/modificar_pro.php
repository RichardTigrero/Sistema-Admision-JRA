<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $id_doc = mysqli_real_escape_string($conn, $_POST['id_doc_act']);
    $nombre_pro = mysqli_real_escape_string($conn, $_POST['nombre_act']);
    $apellido_pro = mysqli_real_escape_string($conn, $_POST['apellido_act']);
    $cedula_pro = mysqli_real_escape_string($conn, $_POST['cedula_act']);
    $email_pro = mysqli_real_escape_string($conn, $_POST['email_act']);
    $celular_pro = mysqli_real_escape_string($conn, $_POST['celular_act']);
    $password_pro = mysqli_real_escape_string($conn, $_POST['password_act']);

    $sql = "UPDATE docente 
            SET dst_nombres = ?, 
                dst_apellidos = ?, 
                dst_cedula = ?, 
                dst_celular = ?, 
                dst_email = ?, 
                dst_contrasenia = ? 
            WHERE id_doc = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", 
        $nombre_pro, $apellido_pro, $cedula_pro, 
        $celular_pro, $email_pro, $password_pro, $id_doc
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Docente actualizado correctamente'
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
?>