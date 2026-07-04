<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $id_sec = mysqli_real_escape_string($conn, $_POST['id_sec_act']);
    $nombre_sec = mysqli_real_escape_string($conn, $_POST['nombre_act']);
    $apellido_sec = mysqli_real_escape_string($conn, $_POST['apellido_act']);
    $cedula_sec = mysqli_real_escape_string($conn, $_POST['cedula_act']);
    $email_sec = mysqli_real_escape_string($conn, $_POST['email_act']);
    $celular_sec = mysqli_real_escape_string($conn, $_POST['celular_act']);
    $password_sec = mysqli_real_escape_string($conn, $_POST['password_act']);
    $cargo_sec = mysqli_real_escape_string($conn, $_POST['cargo_act']);

    $sql = "UPDATE secretaria 
            SET sec_nombres = ?, 
                sec_apellidos = ?, 
                sec_cedula = ?, 
                sec_celular = ?, 
                sec_email = ?, 
                sec_contrasenia = ?,
                sec_cargo = ? 
            WHERE id_sec = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", 
        $nombre_sec, $apellido_sec, $cedula_sec, 
        $celular_sec, $email_sec, $password_sec, 
        $cargo_sec, $id_sec
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Secretaria actualizada correctamente'
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