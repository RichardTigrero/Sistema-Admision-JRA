<?php
session_start();
include("../conexion/conexion.php");
// datos de filtro para actualizacion
$nombres_est = $_GET['nombres'];
$apellidos_est = $_GET['apellidos'];
$id_est_estudiante = $_GET['id_est_estudiante'];
$id_jornada = $_GET['id_jornada'];

$cedula_estudiante = $_GET['cedula'];
$id_det_estudiante= $_GET['id_det_estudiante'];
// Datos que se ingresan o actualizan
$nacionalidad = $_GET['nacionalidad'];
$genero = $_GET['genero'];
$edad = $_GET['edad'];
$celular = $_GET['celular'];
$direccion = $_GET['direccion'];
$fecha_nacimiento = $_GET['fecha_nacimiento'];
$email = $_GET['email'];
$institucion_prev = $_GET['institucion_prev'];

$nivel = $_GET['nivel'];
$cur = $_GET['cur'];
$jorna = $_GET['jorna'];
$paralelo = $_GET['paralelo'];
$repite = $_GET['repite'];
$tutor = $_GET['tutor'];

$cedula_rep = $_GET['cedula_rep'];
$nombres_a_rep = $_GET['nombres_a_rep'];
$celular_rep = $_GET['celular_rep'];
$convencional_rep = $_GET['convencional_rep'];
$parentezco_rep = $_GET['parentezco_rep'];
$email_rep = $_GET['email_rep'];

$cedula_m = $_GET['cedula_m'];
$vive_c_mama = $_GET['vive_c_mama'];
$nombres_a_m = $_GET['nombres_a_m'];
$celular_m = $_GET['celular_m'];
$convencional_mama = $_GET['convencional_mama'];
$email_m = $_GET['email_m'];

$cedula_p = $_GET['cedula_p'];
$vive_c_papa = $_GET['vive_c_papa'];
$nombres_a_p = $_GET['nombres_a_p'];
$celular_p = $_GET['celular_p'];
$convencional_papa = $_GET['convencional_papa'];
$email_p = $_GET['email_p'];

$alergias = $_GET['alergias'];
$talergias = $_GET['talergias'];
$tdiscapacidad = $_GET['tdiscapacidad'];
$por_discapacidad = $_GET['por_discapacidad'];
$vac_covid = $_GET['vac_covid'];
$ncel1 = $_GET['ncel1'];
$nomcel1 = $_GET['nomcel1'];
$ncel2 = $_GET['ncel2'];
$nomcel2 = $_GET['nomcel2'];

//$foto_est = $_GET[''];
//echo "Tutor -->".$tutor;
$sql = "UPDATE `est_datos`
SET

`dtest_nombres` = '$nombres_est',
`dtest_apellidos` = '$apellidos_est',

`dtest_nacionalidad` = '$nacionalidad',
`dtest_genero` = '$genero',
`dtest_fnnacimiento` = '$fecha_nacimiento',
`dtest_edad` = '$edad',
`dtest_celular` = '$celular',
`dtest_direccion` = '$direccion',
`dtest_gmail` = '$email',
`dest_institucion_prev` = '$institucion_prev',

`infaca_jornada_curso` = '$id_jornada',
`infaca_nivel_edu`='$nivel',
`infaca_curso_act`='$cur',
`infaca_jornada_archivo`= '$jorna',
`infaca_paralelo`= '$paralelo',
`infaca_repite`= '$repite',
`infaca_tutorcurso` = '$tutor',

`infrepre_cedula` = '$cedula_rep',
`infrepre_nomape` = '$nombres_a_rep',
`infrepre_clular` = '$celular_rep',
`infrepre_convencional` = '$convencional_rep',
`infrepre_gmail` = '$email_rep',
`infrepre_parentezco` = '$parentezco_rep',
`infmadre_vivemadre` = '$vive_c_mama',
`infmadre_cedula` = '$cedula_m',
`infmadre_nomape` = '$nombres_a_m',
`infmadre_celular` = '$celular_m',
`infmadre_convencional` = '$convencional_mama',
`infmadre_gmail` = '$email_m',
`infpadre_vivepadre` = '$vive_c_papa',
`infpadre_cedula` = '$cedula_p',
`infpadre_nomap` = '$nombres_a_p',
`infpadre_celular` = '$celular_p',
`infpadre_convencional` = '$convencional_papa',
`infpadre_gmail` = '$email_p',
`estsalud_alergias` = '$alergias',
`estsalud_tipoalerg` = '$talergias',
`estsalud_vacuna19` = '$vac_covid',
`estsalud_carnet` = '$por_discapacidad',
`estsalud_discapatipo` = '$tdiscapacidad',
`estemergencia_numerocell1` = '$ncel1',
`estemergencia_nombre1` = '$nomcel1',
`estemergencia_numcell2` = '$ncel2',
`estemergencia_nombre2` = '$nomcel2'
WHERE `dtest_id` = '$id_det_estudiante' and `dtest_cedula` = '$cedula_estudiante';
";

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
  //echo "Record updated successfully";
  
         //actualizar tabla estudiantes
         $sql2="UPDATE `estudiantes` SET `est_nombres`='$nombres_est',`est_apellidos`='$apellidos_est' WHERE `est_id`='$id_est_estudiante'";
         if (mysqli_query($conn, $sql2)) {
             // actualiza tabla estudiante
             ?>
             <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Datos guardados correctamente',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.close();
                });
             </script>
             <?php
         }
         
         
  
} else {
  echo "Error updating record: " . mysqli_error($conn);
  ?>
  <script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error al guardar datos',
        timer: 2000,
        showConfirmButton: false
    }).then(function() {
        window.close();
    });
  </script>
  <?php
}

mysqli_close($conn);
?>

</body>
</html>
