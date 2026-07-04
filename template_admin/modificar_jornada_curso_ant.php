<?php
session_start();
include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $id_jornada_curso = mysqli_real_escape_string($conn, $_POST['id_jornada_curso_act']);
    $nivel = mysqli_real_escape_string($conn, $_POST['nivel_act']);
    $jornada = mysqli_real_escape_string($conn, $_POST['jornada_act']);
    $curso = mysqli_real_escape_string($conn, $_POST['curso_act']);
    $paralelo = mysqli_real_escape_string($conn, $_POST['paralelo_act']);
    $id_periodo_lectivo = mysqli_real_escape_string($conn, $_POST['id_periodo_lectivo_act']);
    $id_docente = mysqli_real_escape_string($conn, $_POST['id_docente_act']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado_act']);

    if (empty($id_periodo_lectivo)) {
        throw new Exception("Debe seleccionar un periodo lectivo");
    }

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

    $sql = "UPDATE jornada_curso 
            SET nivel = ?, 
                jornada = ?, 
                curso = ?, 
                paralelo = ?, 
                periodo = ?, 
                id_periodo_lectivo = ?,
                id_docente = ?,
                estado = ?
            WHERE id_jornada_curso = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssissi", 
        $nivel, $jornada, $curso, 
        $paralelo, $periodo, $id_periodo_lectivo, $id_docente,
        $estado, $id_jornada_curso
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Jornada y curso actualizados correctamente'
        ]);
    } else {
        throw new Exception("Error al actualizar el registro: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
?> 
