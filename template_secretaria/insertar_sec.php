<?php

include("../conexion/conexion.php");

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];


$nombre_sec = $_POST['nombre'];
$apellido_sec = $_POST['apellido'];
$cedula_sec = $_POST['cedula'];
$email_sec = $_POST['email'];
$celular_sec = $_POST['celular'];
$nombreUser_sec = $_POST['nombre_usuario'];
$password_sec = $_POST['password'];
$cargo_sec = $_POST['cargo'];
$usuario_sec = $_POST['usuarioc'];
//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

$sql = "INSERT INTO secretaria 
            (sec_nombres,
            sec_apellidos,
            sec_cedula,
            sec_celular,
            sec_cargo,
            sec_email,
            sec_usuario,
            sec_contrasenia,
            adm_usuarioc
            ) 
            VALUES ('$nombre_sec','$apellido_sec','$cedula_sec','$celular_sec','$cargo_sec','$email_sec','$nombreUser_sec','$password_sec','$usuario_sec')";

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
            location.href="register_sec.php";
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