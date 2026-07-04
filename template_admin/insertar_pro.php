<?php

include("../conexion/conexion.php");
header('Content-Type: application/json');

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];


$nombre_pro = mysqli_real_escape_string($conn, $_POST['nombre']);
$apellido_pro = mysqli_real_escape_string($conn, $_POST['apellido']);
$cedula_pro = mysqli_real_escape_string($conn, $_POST['cedula']);
$email_pro = mysqli_real_escape_string($conn, $_POST['email']);
$celular_pro = mysqli_real_escape_string($conn, $_POST['celular']);
$nombreUser_pro = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
$password_pro = mysqli_real_escape_string($conn, $_POST['password']);
$usuario_pro = mysqli_real_escape_string($conn, $_POST['usuarioc']);
//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

try {
    $sql = "INSERT INTO docente
            (dst_nombres,
            dst_apellidos,
            dst_cedula,
            dst_celular,
            dst_email,
            dst_usuario,
            dst_contrasenia,
            adm_usuarioc
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", 
        $nombre_pro, $apellido_pro, $cedula_pro, 
        $celular_pro, $email_pro, $nombreUser_pro, 
        $password_pro, $usuario_pro
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Docente registrado correctamente'
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