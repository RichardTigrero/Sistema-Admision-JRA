<?php
session_start();
include("../conexion/conexion.php");
$id_doc = $_POST['id_doc_act'];
$nombre_pro = $_POST['nombre_act'];
$apellido_pro = $_POST['apellido_act'];
$cedula_pro = $_POST['cedula_act'];
$email_pro = $_POST['email_act'];
$celular_pro = $_POST['celular_act'];
//$nombreUser_admin = $_POST['nombre_usuario'];
$password_pro = $_POST['password_act'];


//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

$sql = "UPDATE docente SET dst_nombres='".$nombre_pro."', dst_apellidos='".$apellido_pro."', dst_cedula='".$cedula_pro."', dst_celular='".$celular_pro."', dst_email='".$email_pro."', dst_contrasenia='".$password_pro."' WHERE id_doc='".$id_doc."';";



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
            text: 'Error al actualizar registro: <?php echo mysqli_error($conn); ?>',
            timer: 2000,
            showConfirmButton: false
        }).then(function() {
            location.href="register_pro.php";
        });
     </script>
  <?php
  //echo "Error al actualizar registro : " . mysqli_error($conn);
}

mysqli_close($conn);
?>

</body>
</html>