<?php

include("../conexion/conexion.php");

if (!function_exists('obtener_detalle_periodo_lectivo')) {
    function obtener_detalle_periodo_lectivo($conn, $id_periodo_lectivo)
    {
        $detalle = array(
            'estado' => '',
            'texto' => ''
        );

        $id_periodo_lectivo = (int) $id_periodo_lectivo;
        if ($id_periodo_lectivo <= 0) {
            return $detalle;
        }

        $sql_periodo = "SELECT estado, descripcion, observacion
                        FROM periodo_lectivo
                        WHERE id_periodo_lectivo = $id_periodo_lectivo
                        LIMIT 1";
        $result_periodo = mysqli_query($conn, $sql_periodo);

        if ($result_periodo && mysqli_num_rows($result_periodo) > 0) {
            $row_periodo = mysqli_fetch_assoc($result_periodo);
            $texto_periodo = trim((string) $row_periodo['observacion']);

            if ($texto_periodo === '') {
                $texto_periodo = trim((string) $row_periodo['descripcion']);
            }

            $detalle['estado'] = strtoupper(trim((string) $row_periodo['estado']));
            $detalle['texto'] = strtoupper($texto_periodo);
        }

        return $detalle;
    }
}

$det_estudiante = isset($det_estudiante) ? (int)$det_estudiante : 0;
$periodo_seleccionado = isset($_GET['periodo']) ? (int)$_GET['periodo'] : 0;
$usar_historial = false;

$descr_periodo="";

if ($periodo_seleccionado > 0) {
    $detalle_periodo = obtener_detalle_periodo_lectivo($conn, $periodo_seleccionado);
    $usar_historial = ($detalle_periodo['estado'] === 'CERRADO');
    $descr_periodo = $detalle_periodo['texto'];
}

if ($usar_historial) {
    $sql = "SELECT a.est_id, a.est_nombres, a.est_apellidos, a.est_cedula,
                   b.*, c.periodo, b.id_periodo_lectivo AS periodo_fuente_id,
                   CONCAT(c.nivel,'-',c.jornada,'-',c.curso,'-',c.paralelo) as nombre_jornada_curso
            FROM estudiantes_historial a
            INNER JOIN est_datos_historial b
                    ON a.est_cedula = b.dtest_cedula
                   AND a.id_periodo_lectivo = b.id_periodo_lectivo
            INNER JOIN jornada_curso_historial c
                    ON b.infaca_jornada_curso = c.id_jornada_curso
                   AND b.id_periodo_lectivo = c.id_periodo_lectivo
            WHERE b.dtest_id = $det_estudiante
              AND b.id_periodo_lectivo = $periodo_seleccionado
            LIMIT 1";
} else {
    $sql = "SELECT a.est_id, a.est_nombres, a.est_apellidos, a.est_cedula,
                   b.*, c.periodo, c.id_periodo_lectivo AS periodo_fuente_id,
                   CONCAT(c.nivel,'-',c.jornada,'-',c.curso,'-',c.paralelo) as nombre_jornada_curso
            FROM estudiantes a
            INNER JOIN est_datos b ON a.est_cedula = b.dtest_cedula
            INNER JOIN jornada_curso c ON b.infaca_jornada_curso = c.id_jornada_curso
            WHERE b.dtest_id = $det_estudiante";

    if ($periodo_seleccionado > 0) {
        $sql .= " AND (b.dtest_ciclo_datos = '$periodo_seleccionado'
                   OR c.id_periodo_lectivo = $periodo_seleccionado)";
    }

    $sql .= " LIMIT 1";
}

$result = mysqli_query($conn, $sql);
$bdgenero = "0";
$bg_genero_dat = "";
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($descr_periodo === '') {
        $periodo_fuente_id = 0;

        if ($periodo_seleccionado > 0) {
            $periodo_fuente_id = $periodo_seleccionado;
        } elseif (!empty($row["periodo_fuente_id"])) {
            $periodo_fuente_id = (int) $row["periodo_fuente_id"];
        } elseif (!empty($row["dtest_ciclo_datos"])) {
            $periodo_fuente_id = (int) $row["dtest_ciclo_datos"];
        }

        if ($periodo_fuente_id > 0) {
            $detalle_periodo = obtener_detalle_periodo_lectivo($conn, $periodo_fuente_id);
            $descr_periodo = $detalle_periodo['texto'];
        }

        if ($descr_periodo === '' && !empty($row["periodo"])) {
            $descr_periodo = strtoupper(trim((string) $row["periodo"]));
        }
    }

    //echo "id: " . $row["est_id"]. "<br>";
    $dat_id_detalle_estudiante = $row["dtest_id"];
    $dat_est_id = $row["est_id"];
    $dat_nombre_jornada_curso = $row["nombre_jornada_curso"];
    
    $nombres_estudiantes = strtoupper($row["est_nombres"]);
    $apellidos_estudiantes = strtoupper($row["est_apellidos"]);
    $cedula_estudiantes = strtoupper($row["est_cedula"]);

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
