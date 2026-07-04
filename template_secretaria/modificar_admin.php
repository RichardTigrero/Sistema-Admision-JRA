<?php
session_start();
include("../conexion/conexion.php");
$id_adm = $_POST['id_adm_act'];
$nombre_admin = $_POST['nombre_act'];
$apellido_admin = $_POST['apellido_act'];
$cedula_Admin = $_POST['cedula_act'];
$email_admin = $_POST['email_act'];
$celular_admin = $_POST['celular_act'];
//$nombreUser_admin = $_POST['nombre_usuario'];
$password_admin = $_POST['password_act'];


//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

$sql = "UPDATE administrador SET adm_nombres='".$nombre_admin."', adm_apellidos='".$apellido_admin."', adm_cedula='".$cedula_Admin."', adm_celular='".$celular_admin."', adm_email='".$email_admin."', adm_contrasenia='".$password_admin."' WHERE id_adm='".$id_adm."';";



//echo $sql;

if (mysqli_query($conn, $sql)) {
  //echo "Registro actualizado correctamente";
  ?>
    <script> 
        alert("Registro actualizado correctamente")
        location.href="register_admin.php";
        //window.close()
    </script>
  <?php
  
} else {
  ?>
     <script> 
     alert("Error al actualizar registro : " + <?php mysqli_error($conn); ?>);
     location.href="register_admin.php";
     //window.close()
     </script>
  <?php
  //echo "Error al actualizar registro : " . mysqli_error($conn);
}

mysqli_close($conn);



?>