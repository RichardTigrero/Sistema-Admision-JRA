<?php
session_start();
include("../conexion/conexion.php");

// Obtener los datos del formulario
$id_est = $_POST['id_est_act'];
$id_det_estudiante = $_POST['id_det_estudiante'];
$nombres = $_POST['nombre_act'];
$apellidos = $_POST['apellido_act'];
$cedula = $_POST['cedula_act'];
$id_jornada = $_POST['id_jornada_act'];
$nivel = $_POST['nivel_act'];
$curso = $_POST['curso_act'];
$jornada = $_POST['jornada_act'];
$paralelo = $_POST['paralelo_act'];
$tutor = $_POST['tutor_act'];

// Consulta para actualizar la tabla estudiantes
$sql_estudiante = "UPDATE estudiantes SET 
                   est_nombres = '$nombres', 
                   est_apellidos = '$apellidos' 
                   WHERE est_id = '$id_est'";

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
// Actualizar la tabla estudiantes
if (mysqli_query($conn, $sql_estudiante)) {
    
    // Actualizar la tabla est_datos
    $sql_datos = "UPDATE est_datos SET 
                  dtest_nombres = '$nombres',
                  dtest_apellidos = '$apellidos',
                  infaca_jornada_curso = '$id_jornada',
                  infaca_nivel_edu = '$nivel',
                  infaca_curso_act = '$curso',
                  infaca_jornada_archivo = '$jornada',
                  infaca_paralelo = '$paralelo',
                  infaca_tutorcurso = '$tutor'
                  WHERE dtest_id = '$id_det_estudiante'";
    
    if (mysqli_query($conn, $sql_datos)) {
        // Actualización exitosa
        ?>
        <script> 
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Estudiante actualizado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                location.href = "register_est_one.php";
            });
        </script>
        <?php
    } else {
        // Error al actualizar est_datos
        ?>
        <script> 
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al actualizar datos del estudiante: <?php echo mysqli_error($conn); ?>',
                timer: 3000,
                showConfirmButton: false
            }).then(function() {
                location.href = "register_est_one.php";
            });
        </script>
        <?php
    }
} else {
    // Error al actualizar estudiantes
    ?>
    <script> 
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al actualizar estudiante: <?php echo mysqli_error($conn); ?>',
            timer: 3000,
            showConfirmButton: false
        }).then(function() {
            location.href = "register_est_one.php";
        });
    </script>
    <?php
}

mysqli_close($conn);
?>

</body>
</html>