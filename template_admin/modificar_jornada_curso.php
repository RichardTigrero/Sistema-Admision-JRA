<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Iniciar transacción para garantizar integridad de datos
    $conn->begin_transaction();

    // Obtener y validar datos del POST
    $id_jornada_curso   = $_POST['id_jornada_curso_act'];
    $nivel              = $_POST['nivel_act'];
    $jornada            = $_POST['jornada_act'];
    $curso              = $_POST['curso_act'];
    $paralelo           = $_POST['paralelo_act'];
    $id_periodo_lectivo = $_POST['id_periodo_lectivo_act'];
    $id_docente         = $_POST['id_docente_act'];
    $estado             = $_POST['estado_act'];

    if (empty($id_periodo_lectivo)) {
        throw new Exception("Debe seleccionar un periodo lectivo");
    }

    // 1. Validar que el periodo lectivo exista
    $sql_periodo = "SELECT descripcion FROM periodo_lectivo WHERE id_periodo_lectivo = ?";
    $stmt_periodo = mysqli_prepare($conn, $sql_periodo);
    mysqli_stmt_bind_param($stmt_periodo, "i", $id_periodo_lectivo);
    mysqli_stmt_execute($stmt_periodo);
    $result_periodo = mysqli_stmt_get_result($stmt_periodo);
    $row_periodo = mysqli_fetch_assoc($result_periodo);
    if (!$row_periodo) {
        throw new Exception("El periodo lectivo seleccionado no existe");
    }
    $periodo = $row_periodo['descripcion'];

    // 2. Actualizar tabla jornada_curso
    $sql = "UPDATE jornada_curso 
            SET nivel = ?, jornada = ?, curso = ?, paralelo = ?, periodo = ?, id_periodo_lectivo = ?, id_docente = ?, estado = ? 
            WHERE id_jornada_curso = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssissi", 
        $nivel, $jornada, $curso, $paralelo, $periodo, 
        $id_periodo_lectivo, $id_docente, $estado, $id_jornada_curso
    );
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al actualizar jornada_curso: " . mysqli_error($conn));
    }

    // 3. Obtener nombre completo del docente (tutor)
    $nombre_tutor = "";
    if (!empty($id_docente)) {
        $sql_docente = "SELECT CONCAT(dst_nombres, ' ', dst_apellidos) AS nombre_completo 
                        FROM docente WHERE id_doc = ?";
        $stmt_docente = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_docente, "i", $id_docente);
        mysqli_stmt_execute($stmt_docente);
        $res_docente = mysqli_stmt_get_result($stmt_docente);
        $row_docente = mysqli_fetch_assoc($res_docente);
        if ($row_docente) {
            $nombre_tutor = $row_docente['nombre_completo'];
        }
    }

    // 4. Actualizar datos de los estudiantes en est_datos
    // Se actualizan todos los registros que coincidan con el id_jornada_curso
    $sql_est = "UPDATE est_datos 
                SET infaca_nivel_edu = ?, 
                    infaca_curso_act = ?, 
                    infaca_jornada_archivo = ?, 
                    infaca_paralelo = ?, 
                    infaca_tutorcurso = ? 
                WHERE infaca_jornada_curso = ?";
    $stmt_est = mysqli_prepare($conn, $sql_est);
    // Tipos: s=nivel, s=curso, s=jornada, s=paralelo, s=tutor, i=id_jornada
    mysqli_stmt_bind_param($stmt_est, "sssssi", 
        $nivel, $curso, $jornada, $paralelo, $nombre_tutor, $id_jornada_curso
    );
    mysqli_stmt_execute($stmt_est);
    
    if (mysqli_stmt_errno($stmt_est)) {
        throw new Exception("Error al actualizar datos de estudiantes: " . mysqli_stmt_error($stmt_est));
    }

    // Confirmar transacción
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Jornada, curso y registros de estudiantes actualizados correctamente'
    ]);

} catch (Exception $e) {
    // Revertir cambios si ocurre algún error
    if ($conn) {
        $conn->rollback();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if ($conn) {
        mysqli_close($conn);
    }
}
?>