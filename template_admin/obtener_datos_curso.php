<?php
// Configuración de cabeceras para respuesta JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Inicializar respuesta
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    // Comprobar que se recibió el ID del curso
    if (!isset($_POST['id_jornada_curso']) || empty($_POST['id_jornada_curso'])) {
        throw new Exception('No se ha proporcionado un ID de curso válido');
    }
    
    // Incluir conexión a la base de datos
    include("../conexion/conexion.php");
    
    $idCurso = mysqli_real_escape_string($conn, $_POST['id_jornada_curso']);
    
    // Consulta para obtener los datos del curso
    $curso_sql = "SELECT 
                    nivel, 
                    jornada, 
                    curso, 
                    paralelo,
                    (SELECT CONCAT(d.dst_nombres,' ',d.dst_apellidos) 
                     FROM docente d 
                     WHERE d.id_doc=jc.id_docente) as tutor
                  FROM jornada_curso jc 
                  WHERE id_jornada_curso='$idCurso' AND estado='ACTIVO'";
    
    $curso_result = mysqli_query($conn, $curso_sql);
    
    if (!$curso_result || mysqli_num_rows($curso_result) == 0) {
        throw new Exception('No se encontró información del curso seleccionado');
    }
    
    // Obtener los datos y preparar la respuesta
    $curso_info = mysqli_fetch_assoc($curso_result);
    
    // Verificar si hay algún valor NULL y convertirlo a cadena vacía
    foreach ($curso_info as $key => $value) {
        if ($value === null) {
            $curso_info[$key] = '';
        }
    }
    
    $response['success'] = true;
    $response['message'] = 'Datos del curso obtenidos correctamente';
    $response['data'] = [
        'nivel' => $curso_info['nivel'],
        'curso' => $curso_info['curso'],
        'jornada' => $curso_info['jornada'],
        'paralelo' => $curso_info['paralelo'],
        'tutor' => $curso_info['tutor'] ?: 'Sin asignar'
    ];
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar conexión si existe
    if (isset($conn)) {
        mysqli_close($conn);
    }
}

// Enviar respuesta
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 