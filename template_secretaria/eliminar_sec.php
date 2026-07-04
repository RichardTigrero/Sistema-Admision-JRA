<?php

session_start();
include("../conexion/conexion.php");

$id_sec = $_GET['id_sec'];


//echo " Codigo----> ".$codigo;

// sql to delete a record
    $sql = "DELETE FROM secretaria WHERE id_sec='".$id_sec."'";
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
    if ($conn->query($sql) === TRUE) {
        
      //echo "Registro borrado correctamente";
      ?>
          <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'Registro borrado correctamente',
                  timer: 2000,
                  showConfirmButton: false
              }).then(function() {
                  window.close();
              });
          </script>
      <?php
      
    } else {
    
       ?>
          <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Error al borrar registro: <?php echo $conn->error; ?>',
                  timer: 2000,
                  showConfirmButton: false
              }).then(function() {
                  window.close();
              });
          </script>
      <?php
        
      //echo "Error al borrar registro : " . $conn->error;
      
    }
    
    $conn->close();
?>

</body>
</html>