<?php

include("../conexion/conexion.php");
header('Content-Type: application/json');

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];


$nombre_admin = mysqli_real_escape_string($conn, $_POST['nombre']);
$apellido_admin = mysqli_real_escape_string($conn, $_POST['apellido']);
$cedula_Admin = mysqli_real_escape_string($conn, $_POST['cedula']);
$email_admin = mysqli_real_escape_string($conn, $_POST['email']);
$celular_admin = mysqli_real_escape_string($conn, $_POST['celular']);
$nombreUser_admin = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
$password_admin = mysqli_real_escape_string($conn, $_POST['password']);

//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

try {
    $sql = "INSERT INTO administrador 
            (adm_nombres, adm_apellidos, adm_cedula, adm_celular, adm_email, 
             adm_usuario, adm_contrasenia, adm_estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'ACTIVO')";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", 
        $nombre_admin, $apellido_admin, $cedula_Admin, 
        $celular_admin, $email_admin, $nombreUser_admin, $password_admin
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Administrador registrado correctamente'
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









