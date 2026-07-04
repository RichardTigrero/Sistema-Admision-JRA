<?php
include("../conexion/conexion.php");

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

// Inicializar respuesta
$response = [
    'success' => false,
    'message' => '',
    'redirect' => 'formulario_estudiante.php'
];

try {
    // datos de filtro para actualizacion
    $cedula_estudiante = $_GET['cedula'];
    $id_det_estudiante= $_GET['id_det_estudiante'];
    
    // Validar datos requeridos
    if (empty($cedula_estudiante) || empty($id_det_estudiante)) {
        throw new Exception("Faltan datos requeridos (cédula o ID)");
    }
    
    // Datos que se ingresan o actualizan
    $nacionalidad = $_GET['nacionalidad'] ?? '';
    $genero = $_GET['genero'] ?? '';
    $edad = $_GET['edad'] ?? '';
    $celular = $_GET['celular'] ?? '';
    $direccion = $_GET['direccion'] ?? '';
    $fecha_nacimiento = $_GET['fecha_nacimiento'] ?? '';
    $email = $_GET['email'] ?? '';
    $institucion_prev = $_GET['institucion_prev'] ?? '';
    $tutor = $_GET['tutor'] ?? '';
    
    $cedula_rep = $_GET['cedula_rep'] ?? '';
    $nombres_a_rep = $_GET['nombres_a_rep'] ?? '';
    $celular_rep = $_GET['celular_rep'] ?? '';
    $convencional_rep = $_GET['convencional_rep'] ?? '';
    $parentezco_rep = $_GET['parentezco_rep'] ?? '';
    $email_rep = $_GET['email_rep'] ?? '';
    
    $cedula_m = $_GET['cedula_m'] ?? '';
    $vive_c_mama = $_GET['vive_c_mama'] ?? '';
    $nombres_a_m = $_GET['nombres_a_m'] ?? '';
    $celular_m = $_GET['celular_m'] ?? '';
    $convencional_mama = $_GET['convencional_mama'] ?? '';
    $email_m = $_GET['email_m'] ?? '';
    
    $cedula_p = $_GET['cedula_p'] ?? '';
    $vive_c_papa = $_GET['vive_c_papa'] ?? '';
    $nombres_a_p = $_GET['nombres_a_p'] ?? '';
    $celular_p = $_GET['celular_p'] ?? '';
    $convencional_papa = $_GET['convencional_papa'] ?? '';
    $email_p = $_GET['email_p'] ?? '';
    
    $alergias = $_GET['alergias'] ?? '';
    $talergias = $_GET['talergias'] ?? '';
    $tdiscapacidad = $_GET['tdiscapacidad'] ?? '';
    $por_discapacidad = $_GET['por_discapacidad'] ?? '';
    $vac_covid = $_GET['vac_covid'] ?? '';
    $ncel1 = $_GET['ncel1'] ?? '';
    $nomcel1 = $_GET['nomcel1'] ?? '';
    $ncel2 = $_GET['ncel2'] ?? '';
    $nomcel2 = $_GET['nomcel2'] ?? '';
    
    // Escapar datos para prevenir SQL Injection
    $cedula_estudiante = mysqli_real_escape_string($conn, $cedula_estudiante);
    $id_det_estudiante = mysqli_real_escape_string($conn, $id_det_estudiante);
    $nacionalidad = mysqli_real_escape_string($conn, $nacionalidad);
    $genero = mysqli_real_escape_string($conn, $genero);
    $edad = mysqli_real_escape_string($conn, $edad);
    $celular = mysqli_real_escape_string($conn, $celular);
    $direccion = mysqli_real_escape_string($conn, $direccion);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $fecha_nacimiento);
    $email = mysqli_real_escape_string($conn, $email);
    $institucion_prev = mysqli_real_escape_string($conn, $institucion_prev);
    $tutor = mysqli_real_escape_string($conn, $tutor);
    
    $cedula_rep = mysqli_real_escape_string($conn, $cedula_rep);
    $nombres_a_rep = mysqli_real_escape_string($conn, $nombres_a_rep);
    $celular_rep = mysqli_real_escape_string($conn, $celular_rep);
    $convencional_rep = mysqli_real_escape_string($conn, $convencional_rep);
    $parentezco_rep = mysqli_real_escape_string($conn, $parentezco_rep);
    $email_rep = mysqli_real_escape_string($conn, $email_rep);
    
    $cedula_m = mysqli_real_escape_string($conn, $cedula_m);
    $vive_c_mama = mysqli_real_escape_string($conn, $vive_c_mama);
    $nombres_a_m = mysqli_real_escape_string($conn, $nombres_a_m);
    $celular_m = mysqli_real_escape_string($conn, $celular_m);
    $convencional_mama = mysqli_real_escape_string($conn, $convencional_mama);
    $email_m = mysqli_real_escape_string($conn, $email_m);
    
    $cedula_p = mysqli_real_escape_string($conn, $cedula_p);
    $vive_c_papa = mysqli_real_escape_string($conn, $vive_c_papa);
    $nombres_a_p = mysqli_real_escape_string($conn, $nombres_a_p);
    $celular_p = mysqli_real_escape_string($conn, $celular_p);
    $convencional_papa = mysqli_real_escape_string($conn, $convencional_papa);
    $email_p = mysqli_real_escape_string($conn, $email_p);
    
    $alergias = mysqli_real_escape_string($conn, $alergias);
    $talergias = mysqli_real_escape_string($conn, $talergias);
    $tdiscapacidad = mysqli_real_escape_string($conn, $tdiscapacidad);
    $por_discapacidad = mysqli_real_escape_string($conn, $por_discapacidad);
    $vac_covid = mysqli_real_escape_string($conn, $vac_covid);
    $ncel1 = mysqli_real_escape_string($conn, $ncel1);
    $nomcel1 = mysqli_real_escape_string($conn, $nomcel1);
    $ncel2 = mysqli_real_escape_string($conn, $ncel2);
    $nomcel2 = mysqli_real_escape_string($conn, $nomcel2);
    
    $sql = "UPDATE `est_datos`
    SET
    `dtest_nacionalidad` = '$nacionalidad',
    `dtest_genero` = '$genero',
    `dtest_fnnacimiento` = '$fecha_nacimiento',
    `dtest_edad` = '$edad',
    `dtest_celular` = '$celular',
    `dtest_direccion` = '$direccion',
    `dtest_gmail` = '$email',
    `dest_institucion_prev` = '$institucion_prev',
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
    WHERE `dtest_id` = '$id_det_estudiante' AND `dtest_cedula` = '$cedula_estudiante'";
    
    if (mysqli_query($conn, $sql)) {
        $response['success'] = true;
        $response['message'] = "Datos guardados correctamente";
    } else {
        throw new Exception("Error al actualizar el registro: " . mysqli_error($conn));
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar conexión
    mysqli_close($conn);
}

// Enviar respuesta JSON
echo json_encode($response);


?>
