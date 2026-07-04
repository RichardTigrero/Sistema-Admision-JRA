<?php
session_start();
include("../conexion/conexion.php");
$id_estudiante = $_GET['id_estudiante'];
$cedula = $_GET['cedula'];
$det_estudiante = $_GET['det_estudiante'];


// borrado de tabla detalle
$sql0 = "DELETE FROM est_datos WHERE dtest_id='".$det_estudiante."' and dtest_cedula='".$cedula."'";
                
 if ($conn->query($sql0) === TRUE) 
 {

               // borrado de tabla maestro
                $sql = "DELETE FROM estudiantes WHERE est_id='".$id_estudiante."' and est_cedula='".$cedula."'";
                
                if ($conn->query($sql) === TRUE) {
                    
                //echo "Registro borrado correctamente";
                ?>
                    <script>alert("Registro borrado correctamente");
                    window.open('register_est.php',target='_self');
                    //location.reload();
                    window.close();
                    </script>
                <?php
                
                } else {
                
                ?>
                    <script>alert("Error al borrar registro : " + <?php $conn->error; ?>)
                    return;
                    //window.close();
                    </script>
                <?php
                    
                //echo "Error al borrar registro : " . $conn->error;
                
                }
    
    }
    else
    {
        ?>
        <script>alert("Error al borrar registro : " + <?php $conn->error; ?>)
        return;
        //window.close();
        </script>
    <?php 
    }   
    // cerrar conexion         
    $conn->close();


?>