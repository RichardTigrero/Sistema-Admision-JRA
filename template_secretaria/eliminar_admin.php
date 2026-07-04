<?php

session_start();
include("../conexion/conexion.php");

$id_adm = $_GET['id_adm'];


//echo " Codigo----> ".$codigo;

// sql to delete a record
    $sql = "DELETE FROM administrador WHERE id_adm='".$id_adm."'";
    
    if ($conn->query($sql) === TRUE) {
        
      //echo "Registro borrado correctamente";
      ?>
          <script>alert("Registro borrado correctamente")
          window.close()
          </script>
      <?php
      
    } else {
    
       ?>
          <script>alert("Error al borrar registro : " + <?php $conn->error; ?>)
          window.close()
          </script>
      <?php
        
      //echo "Error al borrar registro : " . $conn->error;
      
    }
    
    $conn->close();


?>