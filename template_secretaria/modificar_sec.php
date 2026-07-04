<?php
session_start();
include("../conexion/conexion.php");
$id_sec = $_POST['id_sec_act'];
$nombre_sec = $_POST['nombre_act'];
$apellido_sec = $_POST['apellido_act'];
$cedula_sec = $_POST['cedula_act'];
$email_sec = $_POST['email_act'];
$celular_sec = $_POST['celular_act'];
//$nombreUser_admin = $_POST['nombre_usuario'];
$password_sec = $_POST['password_act'];
$cargo_sec = $_POST['cargo_act'];



//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

$sql = "UPDATE secretaria SET sec_nombres='".$nombre_sec."', sec_apellidos='".$apellido_sec."', sec_cedula='".$cedula_sec."', sec_celular='".$celular_sec."', sec_email='".$email_sec."', sec_contrasenia='".$password_sec."',sec_cargo='".$cargo_sec."' WHERE id_sec='".$id_sec."';";



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
  //echo "Registro actualizado correctamente";
  ?>
    <script> 
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Registro actualizado correctamente',
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
            text: 'Error al actualizar registro: <?php echo mysqli_error($conn); ?>',
            timer: 2000,
            showConfirmButton: false
        }).then(function() {
            location.href="register_sec.php";
        });
     </script>
  <?php
  //echo "Error al actualizar registro : " . mysqli_error($conn);
}

mysqli_close($conn);
?>

</body>
</html>