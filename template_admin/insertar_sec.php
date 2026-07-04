<?php

include("../conexion/conexion.php");
header('Content-Type: application/json');

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];

try {
    // Sanitizar entradas
    $nombre_sec = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido_sec = mysqli_real_escape_string($conn, $_POST['apellido']);
    $cedula_sec = mysqli_real_escape_string($conn, $_POST['cedula']);
    $email_sec = mysqli_real_escape_string($conn, $_POST['email']);
    $celular_sec = mysqli_real_escape_string($conn, $_POST['celular']);
    $nombreUser_sec = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
    $password_sec = mysqli_real_escape_string($conn, $_POST['password']);
    $cargo_sec = mysqli_real_escape_string($conn, $_POST['cargo']);
    $usuario_sec = mysqli_real_escape_string($conn, $_POST['usuarioc']);

    $sql = "INSERT INTO secretaria 
            (sec_nombres, sec_apellidos, sec_cedula, sec_celular, sec_cargo,
             sec_email, sec_usuario, sec_contrasenia, adm_usuarioc) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssss", 
        $nombre_sec, $apellido_sec, $cedula_sec, $celular_sec, 
        $cargo_sec, $email_sec, $nombreUser_sec, $password_sec, $usuario_sec
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Secretaria registrada correctamente'
        ]);
    } else {
        throw new Exception("Error al crear el registro: " . mysqli_error($conn));
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