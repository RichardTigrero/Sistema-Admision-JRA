<?php

include("../conexion/conexion.php");

//$codigo = (int)$_GET['codigo'];
//$nombre_prod = $_POST['nombre'];
//$precio_prod = (float)$_POST['precio'];
//$fabricante = (int)$_POST['fabricante'];//$precio_prod = (float)$_POST['precio'];


$nombre_admin = $_POST['nombre'];
$apellido_admin = $_POST['apellido'];
$cedula_Admin = $_POST['cedula'];
$email_admin = $_POST['email'];
$celular_admin = $_POST['celular'];
$nombreUser_admin = $_POST['nombre_usuario'];
$password_admin = $_POST['password'];

//echo " ---> ".$codigo." ---> ".$nombre_prod." -----> ".$precio_prod." ---> ".$fabricante;

$sql = "INSERT INTO administrador 
            (adm_nombres,
            adm_apellidos,
            adm_cedula,
            adm_celular,
            adm_email,
            adm_usuario,
            adm_contrasenia,
            adm_estado) 
            VALUES ('$nombre_admin','$apellido_admin','$cedula_Admin','$celular_admin','$email_admin','$nombreUser_admin','$password_admin','ACTIVO')";

//echo $sql;

if (mysqli_query($conn, $sql)) {
  
  ?>
    <script> 
        alert("Registro creado correctamente")
        location.href="register_admin.php";
        //window.close()
    </script>
  <?php
  
} else {
  ?>
     <script> 
     alert("Error al crear registro : " + <?php mysqli_error($conn); ?>);
     return false;
     //window.close()
     </script>
  <?php
  //echo "Error al actualizar registro : " . mysqli_error($conn);
}

mysqli_close($conn);



?>