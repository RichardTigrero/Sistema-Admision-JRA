<?php

include("../conexion/conexion.php");

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];


$nombre_pro = $_POST['nombre'];
$apellido_pro = $_POST['apellido'];
$cedula_pro = $_POST['cedula'];
$email_pro = $_POST['email'];
$celular_pro = $_POST['celular'];
$nombreUser_pro = $_POST['nombre_usuario'];
$password_pro = $_POST['password'];
$usuario_pro = $_POST['usuarioc'];
//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

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
            VALUES ('$nombre_pro','$apellido_pro','$cedula_pro','$celular_pro','$email_pro','$nombreUser_pro','$password_pro','$usuario_pro')";

//echo $sql;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Procesando...</title>
    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
<?php
if (mysqli_query($conn, $sql)) {
  
  ?>
    <script> 
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Registro creado correctamente',
            timer: 2000,
            showConfirmButton: false
        }).then(function() {
            location.href="register_pro.php";
        });
    </script>
  <?php
  
} else {
  ?>
     <script> 
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al crear registro: <?php echo mysqli_error($conn); ?>',
            timer: 2000,
            showConfirmButton: false
        });
     </script>
  <?php
  //echo "Error al actualizar registro : " . mysqli_error($conn);
}

mysqli_close($conn);
?>

</body>
</html>