<?php
session_start();
include("../conexion/conexion.php");

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

function respuesta_json($success, $message, $extra = [])
{
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respuesta_json(false, 'Método no permitido.');
}

$curso_origen = isset($_POST['curso_origen']) ? (int) $_POST['curso_origen'] : 0;
$curso_destino = isset($_POST['curso_destino']) ? (int) $_POST['curso_destino'] : 0;

if ($curso_origen <= 0 || $curso_destino <= 0) {
    respuesta_json(false, 'Debes seleccionar un curso origen y un curso destino válidos.');
}

if ($curso_origen === $curso_destino) {
    respuesta_json(false, 'El curso destino debe ser diferente al curso actual.');
}

mysqli_begin_transaction($conn);

try {
    $sql_periodo = "SELECT id_periodo_lectivo
                    FROM periodo_lectivo
                    WHERE estado = 'ACTIVO'
                    ORDER BY id_periodo_lectivo DESC
                    LIMIT 1";
    $result_periodo = mysqli_query($conn, $sql_periodo);

    if (!$result_periodo || mysqli_num_rows($result_periodo) === 0) {
        throw new Exception('No existe un periodo lectivo activo para procesar promociones.');
    }

    $periodo = mysqli_fetch_assoc($result_periodo);
    $periodo_activo_id = (int) $periodo['id_periodo_lectivo'];

    $sql_curso = "SELECT jc.id_jornada_curso,
                         jc.nivel,
                         jc.jornada,
                         jc.curso,
                         jc.paralelo,
                         jc.id_periodo_lectivo,
                         COALESCE(CONCAT(d.dst_nombres, ' ', d.dst_apellidos), '') AS tutor,
                         CONCAT(jc.nivel, ' ', jc.jornada, ' ', jc.curso, ' ', jc.paralelo) AS nombre_jornada_curso
                  FROM jornada_curso jc
                  LEFT JOIN docente d
                         ON jc.id_docente = d.id_doc
                  WHERE jc.id_jornada_curso = ?
                    AND jc.estado = 'ACTIVO'
                    AND jc.id_periodo_lectivo = ?";

    $stmt_origen = mysqli_prepare($conn, $sql_curso);
    if (!$stmt_origen) {
        throw new Exception('No fue posible preparar la consulta del curso origen.');
    }
    mysqli_stmt_bind_param($stmt_origen, "ii", $curso_origen, $periodo_activo_id);
    mysqli_stmt_execute($stmt_origen);
    $result_origen = mysqli_stmt_get_result($stmt_origen);
    $datos_origen = $result_origen ? mysqli_fetch_assoc($result_origen) : null;
    mysqli_stmt_close($stmt_origen);

    if (!$datos_origen) {
        throw new Exception('El curso origen no pertenece al periodo activo o ya no está disponible.');
    }

    $stmt_destino = mysqli_prepare($conn, $sql_curso);
    if (!$stmt_destino) {
        throw new Exception('No fue posible preparar la consulta del curso destino.');
    }
    mysqli_stmt_bind_param($stmt_destino, "ii", $curso_destino, $periodo_activo_id);
    mysqli_stmt_execute($stmt_destino);
    $result_destino = mysqli_stmt_get_result($stmt_destino);
    $datos_destino = $result_destino ? mysqli_fetch_assoc($result_destino) : null;
    mysqli_stmt_close($stmt_destino);

    if (!$datos_destino) {
        throw new Exception('El curso destino no pertenece al periodo activo o ya no está disponible.');
    }

    $sql_contar = "SELECT COUNT(*) AS total
                   FROM est_datos d
                   INNER JOIN jornada_curso jc
                           ON d.infaca_jornada_curso = jc.id_jornada_curso
                   WHERE d.infaca_jornada_curso = ?
                     AND jc.id_periodo_lectivo = ?";
    $stmt_contar = mysqli_prepare($conn, $sql_contar);
    if (!$stmt_contar) {
        throw new Exception('No fue posible consultar la cantidad de estudiantes a promover.');
    }
    mysqli_stmt_bind_param($stmt_contar, "ii", $curso_origen, $periodo_activo_id);
    mysqli_stmt_execute($stmt_contar);
    $result_contar = mysqli_stmt_get_result($stmt_contar);
    $fila_total = $result_contar ? mysqli_fetch_assoc($result_contar) : null;
    mysqli_stmt_close($stmt_contar);

    $total_estudiantes = $fila_total ? (int) $fila_total['total'] : 0;
    if ($total_estudiantes === 0) {
        throw new Exception('No existen estudiantes cargados en el curso origen seleccionado.');
    }

    $sql_update = "UPDATE est_datos d
                   INNER JOIN jornada_curso jc
                           ON d.infaca_jornada_curso = jc.id_jornada_curso
                   SET d.infaca_jornada_curso = ?,
                       d.infaca_nivel_edu = ?,
                       d.infaca_curso_act = ?,
                       d.infaca_jornada_archivo = ?,
                       d.infaca_paralelo = ?,
                       d.infaca_tutorcurso = ?,
                       d.dtest_ciclo_datos = ?
                   WHERE d.infaca_jornada_curso = ?
                     AND jc.id_periodo_lectivo = ?";

    $stmt_update = mysqli_prepare($conn, $sql_update);
    if (!$stmt_update) {
        throw new Exception('No fue posible preparar la actualización masiva.');
    }

    $tutor_destino = $datos_destino['tutor'];
    mysqli_stmt_bind_param(
        $stmt_update,
        "isssssiii",
        $curso_destino,
        $datos_destino['nivel'],
        $datos_destino['curso'],
        $datos_destino['jornada'],
        $datos_destino['paralelo'],
        $tutor_destino,
        $periodo_activo_id,
        $curso_origen,
        $periodo_activo_id
    );
    mysqli_stmt_execute($stmt_update);
    $filas_afectadas = mysqli_stmt_affected_rows($stmt_update);
    mysqli_stmt_close($stmt_update);

    mysqli_commit($conn);

    $mensaje = 'Se promovieron ' . $total_estudiantes . ' estudiantes desde ' .
        $datos_origen['nombre_jornada_curso'] . ' hacia ' . $datos_destino['nombre_jornada_curso'] . '.';

    respuesta_json(true, $mensaje, [
        'updated_rows' => $filas_afectadas,
        'total_estudiantes' => $total_estudiantes
    ]);
} catch (Exception $e) {
    mysqli_rollback($conn);
    respuesta_json(false, $e->getMessage());
}
