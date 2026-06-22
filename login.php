<?php
// Asegurarse de que no haya salida antes del header
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

include("./conexion/conexion.php");
//include("validacion_login.php")

// Función para logging (debugging)
function debug_to_file($data) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ': ' . print_r($data, true) . "\n", FILE_APPEND);
}

// Función para generar respuesta JSON
function sendJsonResponse($success, $message, $redirect = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
}

try {
    // Validar método y parámetros
    if (!isset($_GET['usuario']) || !isset($_GET['password']) || !isset($_GET['t_usu'])) {
        sendJsonResponse(false, 'Parámetros incompletos', null);
        exit;
    }

    // Sanitizar entradas
    $usuario = mysqli_real_escape_string($conn, $_GET['usuario']);
    $clave = mysqli_real_escape_string($conn, $_GET['password']);
    $t_usu = mysqli_real_escape_string($conn, $_GET['t_usu']);

    if ($t_usu == "1") {
        $sql = "SELECT id_adm, adm_usuario, adm_nombres, adm_apellidos, adm_cedula 
                FROM administrador 
                WHERE adm_estado = 'ACTIVO' 
                AND adm_usuario = ? 
                AND adm_contrasenia = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Guardar datos en sesión
            $_SESSION["id_administrador"] = $row["id_adm"];
            $_SESSION["usuario"] = $row["adm_usuario"];
            $_SESSION["nombres_admin"] = $row["adm_nombres"];
            $_SESSION["apellidos_admin"] = $row["adm_apellidos"];
            $_SESSION["cedula_admin"] = $row["adm_cedula"];
            $_SESSION["Ses_det_estudiante"] = "";

            sendJsonResponse(true, 'Login correcto', './template_admin/principal_admin.php');
        } else {
            sendJsonResponse(false, 'Usuario/Clave Incorrecto', 'loginAdmin.html');
        }
    }
    else if ($t_usu == "2") {
        $sql = "SELECT id_sec, sec_usuario, sec_nombres, sec_apellidos, sec_cedula 
                FROM secretaria 
                WHERE sec_usuario = ? 
                AND sec_contrasenia = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["id_secretaria"] = $row["id_sec"];
            $_SESSION["usuario"] = $row["sec_usuario"];
            $_SESSION["nombres_sec"] = $row["sec_nombres"];
            $_SESSION["apellidos_sec"] = $row["sec_apellidos"];
            $_SESSION["cedula_sec"] = $row["sec_cedula"];
            $_SESSION["Ses_det_estudiante"] = "";

            sendJsonResponse(true, 'Login correcto', './template_secretaria/principal_sec.php');
        } else {
            sendJsonResponse(false, 'Usuario/Clave Incorrecto', 'loginSecre.html');
        }
    }
    else if ($t_usu == "3") {
        $sql = "SELECT id_doc, dst_usuario, dst_nombres, dst_apellidos, dst_cedula 
                FROM docente 
                WHERE dst_usuario = ? 
                AND dst_contrasenia = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["id_docente"] = $row["id_doc"];
            $_SESSION["usuario"] = $row["dst_usuario"];
            $_SESSION["nombres_doc"] = $row["dst_nombres"];
            $_SESSION["apellidos_doc"] = $row["dst_apellidos"];
            $_SESSION["cedula_doc"] = $row["dst_cedula"];
            $_SESSION["Ses_det_estudiante"] = "";

            sendJsonResponse(true, 'Login correcto', './template_docente/principal_doc.php');
        } else {
            sendJsonResponse(false, 'Usuario/Clave Incorrecto', 'login_pro.html');
        }
    }
    else if ($t_usu == "4") {
        $sql = "SELECT a.est_id, b.dtest_id, a.est_nombres, a.est_apellidos, a.est_cedula,
                       b.infaca_jornada_curso, concat(c.nivel,'-',c.jornada,'-',c.curso,'-',c.paralelo) as nombre_jornada_curso 
                FROM estudiantes a 
                JOIN est_datos b ON a.est_cedula = b.dtest_cedula
                JOIN jornada_curso c ON b.infaca_jornada_curso = c.id_jornada_curso
                WHERE a.est_usuario = ? 
                AND a.est_password = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["id_det_estudiante"] = $row["dtest_id"];
            $_SESSION["id_estudiante"] = $row["est_id"];
            $_SESSION["nombres_estudiante"] = $row["est_nombres"];
            $_SESSION["apellidos_estudiante"] = $row["est_apellidos"];
            $_SESSION["cedula_estudiante"] = $row["est_cedula"];
            $_SESSION["codigo_jornada_curso"] = $row["infaca_jornada_curso"];
            $_SESSION["nombre_jornada_curso"] = $row["nombre_jornada_curso"];
            $_SESSION["Ses_det_estudiante"] = "";

            sendJsonResponse(true, 'Login correcto', './template_estudiante/formulario_estudiante.php');
        } else {
            sendJsonResponse(false, 'Usuario/Clave Incorrecto', 'loginEst.html');
        }
    }
     else if ($t_usu == "5") {
        $sql = "SELECT id_pasan, pasan_usuario, pasan_nombres, pasan_apellidos, pasan_cedula 
                FROM pasantias 
                WHERE pasan_usuario = ? 
                AND pasan_contrasenia = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["id_pasantias"] = $row["id_pasan"];
            $_SESSION["usuario"] = $row["pasan_usuario"];
            $_SESSION["nombres_pasantias"] = $row["pasan_nombres"];
            $_SESSION["apellidos_pasantias"] = $row["pasan_apellidos"];
            $_SESSION["cedula_pasantias"] = $row["pasan_cedula"];
            $_SESSION["Ses_det_estudiante"] = "";

            sendJsonResponse(true, 'Login correcto', './template_pasantias/principal_pasantias.php');
        } else {
            sendJsonResponse(false, 'Usuario/Clave Incorrecto', 'loginPasan.html');
        }
    }
    else {
        sendJsonResponse(false, 'Tipo de usuario no válido', null);
    }

} catch (Exception $e) {
    debug_to_file($e->getMessage()); // Para debugging
    sendJsonResponse(false, 'Error en el servidor: ' . $e->getMessage(), null);
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?>