<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $id_adm = mysqli_real_escape_string($conn, $_POST['id_adm_act']);
    $nombre_admin = mysqli_real_escape_string($conn, $_POST['nombre_act']);
    $apellido_admin = mysqli_real_escape_string($conn, $_POST['apellido_act']);
    $cedula_Admin = mysqli_real_escape_string($conn, $_POST['cedula_act']);
    $email_admin = mysqli_real_escape_string($conn, $_POST['email_act']);
    $celular_admin = mysqli_real_escape_string($conn, $_POST['celular_act']);
    $password_admin = mysqli_real_escape_string($conn, $_POST['password_act']);

    $sql = "UPDATE administrador 
            SET adm_nombres = ?, 
                adm_apellidos = ?, 
                adm_cedula = ?, 
                adm_celular = ?, 
                adm_email = ?, 
                adm_contrasenia = ? 
            WHERE id_adm = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", 
        $nombre_admin, $apellido_admin, $cedula_Admin, 
        $celular_admin, $email_admin, $password_admin, $id_adm
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Administrador actualizado correctamente'
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