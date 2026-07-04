<?php
session_start();
include("../conexion/conexion.php");

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');

// Deshabilitar la salida de errores PHP como HTML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Función para manejar errores y convertirlos a JSON
function handleError($errno, $errstr, $errfile, $errline) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $errstr
    ]);
    exit;
}

// Establecer el manejador de errores
set_error_handler('handleError');

try {
    // Iniciar transacción
    mysqli_begin_transaction($conn);

    // Validar que los datos necesarios estén presentes
    $requiredFields = ['nombres', 'apellidos', 'cedula', 'id_est_estudiante', 'id_det_estudiante'];
    foreach ($requiredFields as $field) {
        if (!isset($_GET[$field]) || empty($_GET[$field])) {
            throw new Exception("Campo requerido faltante: $field");
        }
    }

    // Sanitizar y procesar los datos
    $nombres_est = mysqli_real_escape_string($conn, $_GET['nombres']);
    $apellidos_est = mysqli_real_escape_string($conn, $_GET['apellidos']);
    $id_est_estudiante = mysqli_real_escape_string($conn, $_GET['id_est_estudiante']);
    $id_jornada = mysqli_real_escape_string($conn, $_GET['id_jornada']);
    $cedula_estudiante = mysqli_real_escape_string($conn, $_GET['cedula']);
    $id_det_estudiante = mysqli_real_escape_string($conn, $_GET['id_det_estudiante']);
    
    // Sanitizar datos personales
    $nacionalidad = mysqli_real_escape_string($conn, $_GET['nacionalidad']);
    $genero = mysqli_real_escape_string($conn, $_GET['genero']);
    $edad = mysqli_real_escape_string($conn, $_GET['edad']);
    $celular = mysqli_real_escape_string($conn, $_GET['celular']);
    $direccion = mysqli_real_escape_string($conn, $_GET['direccion']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_GET['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $institucion_prev = mysqli_real_escape_string($conn, $_GET['institucion_prev']);

    // Sanitizar información académica
    $nivel = mysqli_real_escape_string($conn, $_GET['nivel']);
    $cur = mysqli_real_escape_string($conn, $_GET['cur']);
    $jorna = mysqli_real_escape_string($conn, $_GET['jorna']);
    $paralelo = mysqli_real_escape_string($conn, $_GET['paralelo']);
    $repite = mysqli_real_escape_string($conn, $_GET['repite']);
    $tutor = mysqli_real_escape_string($conn, $_GET['tutor']);

    // Sanitizar datos del representante
    $cedula_rep = mysqli_real_escape_string($conn, $_GET['cedula_rep']);
    $nombres_a_rep = mysqli_real_escape_string($conn, $_GET['nombres_a_rep']);
    $celular_rep = mysqli_real_escape_string($conn, $_GET['celular_rep']);
    $convencional_rep = mysqli_real_escape_string($conn, $_GET['convencional_rep']);
    $parentezco_rep = mysqli_real_escape_string($conn, $_GET['parentezco_rep']);
    $email_rep = mysqli_real_escape_string($conn, $_GET['email_rep']);

    // Sanitizar datos de la madre
    $cedula_m = mysqli_real_escape_string($conn, $_GET['cedula_m']);
    $vive_c_mama = mysqli_real_escape_string($conn, $_GET['vive_c_mama']);
    $nombres_a_m = mysqli_real_escape_string($conn, $_GET['nombres_a_m']);
    $celular_m = mysqli_real_escape_string($conn, $_GET['celular_m']);
    $convencional_mama = mysqli_real_escape_string($conn, $_GET['convencional_mama']);
    $email_m = mysqli_real_escape_string($conn, $_GET['email_m']);

    // Sanitizar datos del padre
    $cedula_p = mysqli_real_escape_string($conn, $_GET['cedula_p']);
    $vive_c_papa = mysqli_real_escape_string($conn, $_GET['vive_c_papa']);
    $nombres_a_p = mysqli_real_escape_string($conn, $_GET['nombres_a_p']);
    $celular_p = mysqli_real_escape_string($conn, $_GET['celular_p']);
    $convencional_papa = mysqli_real_escape_string($conn, $_GET['convencional_papa']);
    $email_p = mysqli_real_escape_string($conn, $_GET['email_p']);

    // Sanitizar información de salud
    $alergias = mysqli_real_escape_string($conn, $_GET['alergias']);
    $talergias = mysqli_real_escape_string($conn, $_GET['talergias']);
    $tdiscapacidad = mysqli_real_escape_string($conn, $_GET['tdiscapacidad']);
    $por_discapacidad = mysqli_real_escape_string($conn, $_GET['por_discapacidad']);
    $vac_covid = mysqli_real_escape_string($conn, $_GET['vac_covid']);

    // Sanitizar contactos de emergencia
    $ncel1 = mysqli_real_escape_string($conn, $_GET['ncel1']);
    $nomcel1 = mysqli_real_escape_string($conn, $_GET['nomcel1']);
    $ncel2 = mysqli_real_escape_string($conn, $_GET['ncel2']);
    $nomcel2 = mysqli_real_escape_string($conn, $_GET['nomcel2']);

    // Preparar la consulta SQL principal
    $sql = "UPDATE est_datos SET
            dtest_nombres = ?,
            dtest_apellidos = ?,
            dtest_nacionalidad = ?,
            dtest_genero = ?,
            dtest_fnnacimiento = ?,
            dtest_edad = ?,
            dtest_celular = ?,
            dtest_direccion = ?,
            dtest_gmail = ?,
            dest_institucion_prev = ?,
            infaca_jornada_curso = ?,
            infaca_nivel_edu = ?,
            infaca_curso_act = ?,
            infaca_jornada_archivo = ?,
            infaca_paralelo = ?,
            infaca_repite = ?,
            infaca_tutorcurso = ?,
            infrepre_cedula = ?,
            infrepre_nomape = ?,
            infrepre_clular = ?,
            infrepre_convencional = ?,
            infrepre_gmail = ?,
            infrepre_parentezco = ?,
            infmadre_vivemadre = ?,
            infmadre_cedula = ?,
            infmadre_nomape = ?,
            infmadre_celular = ?,
            infmadre_convencional = ?,
            infmadre_gmail = ?,
            infpadre_vivepadre = ?,
            infpadre_cedula = ?,
            infpadre_nomap = ?,
            infpadre_celular = ?,
            infpadre_convencional = ?,
            infpadre_gmail = ?,
            estsalud_alergias = ?,
            estsalud_tipoalerg = ?,
            estsalud_vacuna19 = ?,
            estsalud_carnet = ?,
            estsalud_discapatipo = ?,
            estemergencia_numerocell1 = ?,
            estemergencia_nombre1 = ?,
            estemergencia_numcell2 = ?,
            estemergencia_nombre2 = ?
            WHERE dtest_id = ? AND dtest_cedula = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssssssssssssssssssssss", 
        $nombres_est, $apellidos_est, $nacionalidad, $genero, $fecha_nacimiento,
        $edad, $celular, $direccion, $email, $institucion_prev,
        $id_jornada, $nivel, $cur, $jorna, $paralelo,
        $repite, $tutor,
        $cedula_rep, $nombres_a_rep, $celular_rep, $convencional_rep,
        $email_rep, $parentezco_rep,
        $vive_c_mama, $cedula_m, $nombres_a_m, $celular_m,
        $convencional_mama, $email_m,
        $vive_c_papa, $cedula_p, $nombres_a_p, $celular_p,
        $convencional_papa, $email_p,
        $alergias, $talergias, $vac_covid, $por_discapacidad,
        $tdiscapacidad, $ncel1, $nomcel1, $ncel2, $nomcel2,
        $id_det_estudiante, $cedula_estudiante
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error en actualización de datos principales: " . mysqli_error($conn));
    }

    // Actualizar tabla estudiantes
    $sql2 = "UPDATE estudiantes SET 
             est_nombres = ?,
             est_apellidos = ? 
             WHERE est_id = ?";

    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "sss", 
        $nombres_est, 
        $apellidos_est, 
        $id_est_estudiante
    );

    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception("Error en actualización de estudiante: " . mysqli_error($conn));
    }

    // Confirmar transacción
    mysqli_commit($conn);

    echo json_encode([
        'success' => true,
        'message' => 'Datos actualizados correctamente'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conn);
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (isset($stmt2)) mysqli_stmt_close($stmt2);
    mysqli_close($conn);
}
?>
