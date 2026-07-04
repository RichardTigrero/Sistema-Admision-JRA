<?php
session_start();
include("../conexion/conexion.php");

// Datos del estudiante 
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$cedula = $_POST['cedula'];
$id_jornada = $_POST['id_jornada'];
$nivel = $_POST['nivel'];
$curso = $_POST['curso'];
$jornada = $_POST['jornada'];
$paralelo = $_POST['paralelo'];
$tutor = $_POST['tutor'];

// Primera consulta: insertar en la tabla estudiantes
$sql_estudiante = "INSERT INTO estudiantes (est_nombres, est_apellidos, est_cedula, est_usuario, est_password, est_estado) 
                   VALUES ('$nombres', '$apellidos', '$cedula', '$cedula', '$cedula', 'ACTIVO')";

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
// Intentar insertar en la tabla estudiantes
if (mysqli_query($conn, $sql_estudiante)) {
    // Obtener el ID del estudiante insertado
    $id_estudiante = mysqli_insert_id($conn);
    
    // Segunda consulta: insertar en la tabla est_datos
    $sql_datos = "INSERT INTO est_datos (
        dtest_cedula, 
        dtest_nombres, 
        dtest_apellidos,
        infaca_jornada_curso,
        infaca_nivel_edu,
        infaca_curso_act,
        infaca_jornada_archivo,
        infaca_paralelo,
        infaca_tutorcurso
    ) VALUES (
        '$cedula',
        '$nombres',
        '$apellidos',
        '$id_jornada',
        '$nivel',
        '$curso',
        '$jornada',
        '$paralelo',
        '$tutor'
    )";
    
    if (mysqli_query($conn, $sql_datos)) {
        // Inserción exitosa en ambas tablas
        ?>
        <script> 
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Estudiante registrado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                location.href = "register_est_one.php";
            });
        </script>
        <?php
    } else {
        // Error al insertar en est_datos, eliminamos el registro en estudiantes para mantener la integridad
        $sql_delete = "DELETE FROM estudiantes WHERE est_id = '$id_estudiante'";
        mysqli_query($conn, $sql_delete);
        
        ?>
        <script> 
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al registrar datos del estudiante: <?php echo mysqli_error($conn); ?>',
                timer: 3000,
                showConfirmButton: false
            }).then(function() {
                location.href = "register_est_one.php";
            });
        </script>
        <?php
    }
} else {
    // Error al insertar en estudiantes
    ?>
    <script> 
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al registrar estudiante: <?php echo mysqli_error($conn); ?>',
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