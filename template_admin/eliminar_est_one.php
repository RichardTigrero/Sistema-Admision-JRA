<?php

session_start();
include("../conexion/conexion.php");

// Obtener los IDs a eliminar
$id_est = $_GET['id_est'];
$id_detalle = $_GET['id_detalle'];

// Primero obtenemos la cédula del estudiante para asegurar que eliminamos los registros correctos
$sql_get_cedula = "SELECT est_cedula FROM estudiantes WHERE est_id = '$id_est'";
$result = mysqli_query($conn, $sql_get_cedula);
$row = mysqli_fetch_assoc($result);
$cedula = $row['est_cedula'];

// Preparar consulta para eliminar de est_datos
$sql_delete_datos = "DELETE FROM est_datos WHERE dtest_id = '$id_detalle' AND dtest_cedula = '$cedula'";
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
    // Eliminar primero de est_datos
    if ($conn->query($sql_delete_datos) === TRUE) {
        
        // Ahora eliminar de estudiantes
        $sql_delete_estudiante = "DELETE FROM estudiantes WHERE est_id = '$id_est' AND est_cedula = '$cedula'";
        
        if ($conn->query($sql_delete_estudiante) === TRUE) {
            // Eliminación exitosa en ambas tablas
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Estudiante eliminado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.close();
                });
            </script>
            <?php
        } else {
            // Error al eliminar de estudiantes
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar estudiante: <?php echo $conn->error; ?>',
                    timer: 3000,
                    showConfirmButton: false
                }).then(function() {
                    window.close();
                });
            </script>
            <?php
        }
    } else {
        // Error al eliminar de est_datos
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al eliminar datos del estudiante: <?php echo $conn->error; ?>',
                timer: 3000,
                showConfirmButton: false
            }).then(function() {
                window.close();
            });
        </script>
        <?php
    }
    
    $conn->close();
?>

</body>
</html>