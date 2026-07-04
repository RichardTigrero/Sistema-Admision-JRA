<?php

include("../conexion/conexion.php");

$descr_observa_per = "";
$id_periodo_lectivo_activo = 0;

$sql_periodo_activo = "SELECT id_periodo_lectivo, descripcion, observacion
                       FROM periodo_lectivo
                       WHERE estado = 'ACTIVO'
                       ORDER BY id_periodo_lectivo DESC
                       LIMIT 1";
$result_periodo_activo = mysqli_query($conn, $sql_periodo_activo);

if ($result_periodo_activo && mysqli_num_rows($result_periodo_activo) > 0) {
    $row_periodo_activo = mysqli_fetch_assoc($result_periodo_activo);
    $id_periodo_lectivo_activo = (int) $row_periodo_activo["id_periodo_lectivo"];
    $descr_observa_per = trim((string) $row_periodo_activo["observacion"]);

    if ($descr_observa_per === "") {
        $descr_observa_per = trim((string) $row_periodo_activo["descripcion"]);
    }
}

$sql = "SELECT a.est_id,b.*, concat(c.nivel,'-',c.jornada,'-',c.curso,'-',c.paralelo) as nombre_jornada_curso 
FROM estudiantes a, est_datos b , jornada_curso c
WHERE b.infaca_jornada_curso = c.id_jornada_curso
AND   a.est_cedula = b.dtest_cedula and a.est_usuario='".$_SESSION["cedula_estudiante"]."' and b.dtest_id='".$_SESSION["id_det_estudiante"]."'";
$result = mysqli_query($conn, $sql);
$bdgenero = "0";
$bg_genero_dat = "";
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //echo "id: " . $row["est_id"]. "<br>";
    $bdnacionalidad = strtoupper($row["dtest_nacionalidad"]);
    $bdgenero = strtoupper($row["dtest_genero"]);
    if ($bdgenero == "0")
    {
        $bg_genero_dat = "";
    }
    if ($bdgenero == "1")
    {
        $bg_genero_dat = strtoupper("Masculino");
    }
    if ($bdgenero == "2")
    {
        $bg_genero_dat = strtoupper("Femenino");
    }
    $bdedad = strtoupper($row["dtest_edad"]);
    $bdcelular = strtoupper($row["dtest_celular"]);
    $bddireccion = strtolower($row["dtest_direccion"]);
    $bdfecha_nacimiento = $row["dtest_fnnacimiento"];
    $bdemail = strtolower($row["dtest_gmail"]);
    $bdinstitucion_prev = strtoupper($row["dest_institucion_prev"]);
    

    // data nueva desde el archivo de carga
    $jornada_curso_act = strtoupper($row["infaca_jornada_curso"]);
    $nivel_educacion = strtoupper($row["infaca_nivel_edu"]);
    $curso =  strtoupper($row["infaca_curso_act"]);
    $jornada =  strtoupper($row["infaca_jornada_archivo"]);
    $paralelo =  strtoupper($row["infaca_paralelo"]);
    $repite_anio =  strtoupper($row["infaca_repite"]);
    $bdtutor = strtoupper($row["infaca_tutorcurso"]);
    //
    $bdcedula_rep = strtoupper($row["infrepre_cedula"]);
    $bdnombres_a_rep = strtoupper($row["infrepre_nomape"]);
    $bdcelular_rep = strtoupper($row["infrepre_clular"]);
    $bdconvencional_rep = strtoupper($row["infrepre_convencional"]);
    $bdparentezco_rep = strtoupper($row["infrepre_parentezco"]);
    $bdemail_rep = strtoupper($row["infrepre_gmail"]);

    $bdcedula_m = strtoupper($row["infmadre_cedula"]);
    $bdvive_c_mama = strtoupper($row["infmadre_vivemadre"]);
    $bdnombres_a_m = strtoupper($row["infmadre_nomape"]);
    $bdcelular_m = strtoupper($row["infmadre_celular"]);
    $bdconvencional_mama = strtoupper($row["infmadre_convencional"]);
    $bdemail_m = strtoupper($row["infmadre_gmail"]);

    $bdcedula_p = strtoupper($row["infpadre_cedula"]);
    $bdvive_c_papa = strtoupper($row["infpadre_vivepadre"]);
    $bdnombres_a_p = strtoupper($row["infpadre_nomap"]);
    $bdcelular_p = strtoupper($row["infpadre_celular"]);
    $bdconvencional_papa = strtoupper($row["infpadre_convencional"]);
    $bdemail_p = strtoupper($row["infpadre_gmail"]);

    $bdalergias = strtoupper($row["estsalud_alergias"]);
    $bdtalergias = strtoupper($row["estsalud_tipoalerg"]);
    $bdtdiscapacidad = strtoupper($row["estsalud_discapatipo"]);
    $bdpor_discapacidad = strtoupper($row["estsalud_carnet"]);
    $bdvac_covidv = strtoupper($row["estsalud_vacuna19"]);
    
    $bdncel1 = strtoupper($row["estemergencia_numerocell1"]);
    $bdnomcel1 = strtoupper($row["estemergencia_nombre1"]);
    $bdncel2 = strtoupper($row["estemergencia_numcell2"]);
    $bdnomcel2 = strtoupper($row["estemergencia_nombre2"]);
    $imagen_json =$row["dtest_imagen_usuario"]; 
    $imagen_actual="";
    //Si hay una imagen previa, decodificarla
    if (!empty($imagen_json)) {
        $imagen_info = json_decode($imagen_json, true);
        if ($imagen_info && isset($imagen_info['ruta'])) {
            $imagen_actual = $imagen_info;
        }
    }
    
  }
}

?>
