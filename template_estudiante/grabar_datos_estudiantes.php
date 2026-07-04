<?php
include("../conexion/conexion.php");
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'redirect' => 'formulario_estudiante.php'
];

try {
    // 1. Validar campos obligatorios
    $cedula_estudiante = trim($_GET['cedula'] ?? '');
    $id_det_estudiante = trim($_GET['id_det_estudiante'] ?? '');

    if (empty($cedula_estudiante) || empty($id_det_estudiante)) {
        throw new Exception("Faltan datos requeridos (cédula o ID)");
    }

    // 2. Funciones auxiliares para normalización
    $toUpper   = fn($val) => strtoupper(trim($val ?? ''));
    $keepAsIs  = fn($val) => trim($val ?? '');
    $toLower   = fn($val) => strtolower(trim($val ?? '')); // Estándar para emails

    // 3. Asignar y transformar variables
    $nacionalidad      = $toUpper($_GET['nacionalidad'] ?? '');
    $genero            = $toUpper($_GET['genero'] ?? '');
    $edad              = $keepAsIs($_GET['edad'] ?? '');
    $celular           = $keepAsIs($_GET['celular'] ?? '');
    $direccion         = $toUpper($_GET['direccion'] ?? '');
    $fecha_nacimiento  = $keepAsIs($_GET['fecha_nacimiento'] ?? '');
    $email             = $toLower($_GET['email'] ?? '');
    $institucion_prev  = $toUpper($_GET['institucion_prev'] ?? '');
    $tutor             = $toUpper($_GET['tutor'] ?? '');
    
    $cedula_rep        = $keepAsIs($_GET['cedula_rep'] ?? '');
    $nombres_a_rep     = $toUpper($_GET['nombres_a_rep'] ?? '');
    $celular_rep       = $keepAsIs($_GET['celular_rep'] ?? '');
    $convencional_rep  = $keepAsIs($_GET['convencional_rep'] ?? '');
    $parentezco_rep    = $toUpper($_GET['parentezco_rep'] ?? '');
    $email_rep         = $toLower($_GET['email_rep'] ?? '');
    
    $cedula_m          = $keepAsIs($_GET['cedula_m'] ?? '');
    $vive_c_mama       = $keepAsIs($_GET['vive_c_mama'] ?? '');
    $nombres_a_m       = $toUpper($_GET['nombres_a_m'] ?? '');
    $celular_m         = $keepAsIs($_GET['celular_m'] ?? '');
    $convencional_mama = $keepAsIs($_GET['convencional_mama'] ?? '');
    $email_m           = $toLower($_GET['email_m'] ?? '');
    
    $cedula_p          = $keepAsIs($_GET['cedula_p'] ?? '');
    $vive_c_papa       = $keepAsIs($_GET['vive_c_papa'] ?? '');
    $nombres_a_p       = $toUpper($_GET['nombres_a_p'] ?? '');
    $celular_p         = $keepAsIs($_GET['celular_p'] ?? '');
    $convencional_papa = $keepAsIs($_GET['convencional_papa'] ?? '');
    $email_p           = $toLower($_GET['email_p'] ?? '');
    
    $alergias          = $toUpper($_GET['alergias'] ?? '');
    $talergias         = $toUpper($_GET['talergias'] ?? '');
    $tdiscapacidad     = $toUpper($_GET['tdiscapacidad'] ?? '');
    $por_discapacidad  = $keepAsIs($_GET['por_discapacidad'] ?? '');
    $vac_covid         = $toUpper($_GET['vac_covid'] ?? '');
    
    $ncel1             = $keepAsIs($_GET['ncel1'] ?? '');
    $nomcel1           = $toUpper($_GET['nomcel1'] ?? '');
    $ncel2             = $keepAsIs($_GET['ncel2'] ?? '');
    $nomcel2           = $toUpper($_GET['nomcel2'] ?? '');

    // 4. Prepared Statement (MÁS SEGURO: elimina necesidad de mysqli_real_escape_string)
    $sql = "UPDATE `est_datos` SET
        `dtest_nacionalidad` = ?, `dtest_genero` = ?, `dtest_fnnacimiento` = ?, 
        `dtest_edad` = ?, `dtest_celular` = ?, `dtest_direccion` = ?, 
        `dtest_gmail` = ?, `dest_institucion_prev` = ?, `infaca_tutorcurso` = ?, 
        `infrepre_cedula` = ?, `infrepre_nomape` = ?, `infrepre_clular` = ?, 
        `infrepre_convencional` = ?, `infrepre_gmail` = ?, `infrepre_parentezco` = ?, 
        `infmadre_vivemadre` = ?, `infmadre_cedula` = ?, `infmadre_nomape` = ?, 
        `infmadre_celular` = ?, `infmadre_convencional` = ?, `infmadre_gmail` = ?, 
        `infpadre_vivepadre` = ?, `infpadre_cedula` = ?, `infpadre_nomap` = ?, 
        `infpadre_celular` = ?, `infpadre_convencional` = ?, `infpadre_gmail` = ?, 
        `estsalud_alergias` = ?, `estsalud_tipoalerg` = ?, `estsalud_vacuna19` = ?, 
        `estsalud_carnet` = ?, `estsalud_discapatipo` = ?, 
        `estemergencia_numerocell1` = ?, `estemergencia_nombre1` = ?, 
        `estemergencia_numcell2` = ?, `estemergencia_nombre2` = ?
        WHERE `dtest_id` = ? AND `dtest_cedula` = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new Exception("Error preparando consulta: " . mysqli_error($conn));

    // 36 campos SET + 2 campos WHERE = 38 parámetros
    $types = str_repeat('s', 38); 
    mysqli_stmt_bind_param($stmt, $types,
        $nacionalidad, $genero, $fecha_nacimiento, $edad, $celular,
        $direccion, $email, $institucion_prev, $tutor,
        $cedula_rep, $nombres_a_rep, $celular_rep, $convencional_rep,
        $email_rep, $parentezco_rep, $vive_c_mama, $cedula_m,
        $nombres_a_m, $celular_m, $convencional_mama, $email_m,
        $vive_c_papa, $cedula_p, $nombres_a_p, $celular_p,
        $convencional_papa, $email_p, $alergias, $talergias,
        $vac_covid, $por_discapacidad, $tdiscapacidad, $ncel1,
        $nomcel1, $ncel2, $nomcel2,
        $id_det_estudiante, $cedula_estudiante
    );

    if (mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
        $response['message'] = "Datos actualizados correctamente (normalizados a mayúsculas)";
    } else {
        throw new Exception("Error en BD: " . mysqli_stmt_error($stmt));
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

echo json_encode($response);
?>