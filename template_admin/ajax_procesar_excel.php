<?php
// Habilitar mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
include("../conexion/conexion.php");

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

// Función para limpiar los datos de entrada
function limpiarDato($dato) {
    global $conn;
    if (is_string($dato)) {
        return mysqli_real_escape_string($conn, trim($dato));
    } elseif (is_null($dato)) {
        return '';
    } else {
        return $dato;
    }
}

// Inicializar respuesta
$response = [
    'success' => false,
    'message' => '',
    'data' => [],
    'errors' => []
];

try {
    // Validar que se haya enviado un archivo y un curso
    if (!isset($_FILES['excelFile']) || !isset($_POST['curso'])) {
        throw new Exception('No se ha proporcionado un archivo o un curso válido');
    }

    $curso = limpiarDato($_POST['curso']);
    
    // Validar que se seleccionó un curso
    if (empty($curso)) {
        throw new Exception('Debe seleccionar un curso válido');
    }

    // Obtener información del curso seleccionado
    $curso_sql = "SELECT 
                    nivel, 
                    jornada, 
                    curso, 
                    paralelo,
                    (SELECT CONCAT(d.dst_nombres,' ',d.dst_apellidos) 
                     FROM docente d 
                     WHERE d.id_doc=jc.id_docente) as tutor
                    FROM jornada_curso jc 
                    WHERE id_jornada_curso='$curso' AND estado='ACTIVO'";
    
    $curso_result = mysqli_query($conn, $curso_sql);
    
    if (!$curso_result || mysqli_num_rows($curso_result) == 0) {
        throw new Exception('No se encontró información del curso seleccionado');
    }
    
    $curso_info = mysqli_fetch_assoc($curso_result);
    $nivel_educacion = $curso_info['nivel'];
    $curso_nombre = $curso_info['curso'];
    $jornada_nombre = $curso_info['jornada'];
    $paralelo_nombre = $curso_info['paralelo'];
    $tutor_nombre = $curso_info['tutor'];

    $periodo_activo_id = 0;
    $sql_periodo_activo = "SELECT id_periodo_lectivo
                           FROM periodo_lectivo
                           WHERE estado = 'ACTIVO'
                           ORDER BY id_periodo_lectivo DESC
                           LIMIT 1";
    $result_periodo_activo = mysqli_query($conn, $sql_periodo_activo);

    if (!$result_periodo_activo || mysqli_num_rows($result_periodo_activo) === 0) {
        throw new Exception('No existe un período lectivo activo para guardar la carga masiva');
    }

    $row_periodo_activo = mysqli_fetch_assoc($result_periodo_activo);
    $periodo_activo_id = (int) $row_periodo_activo['id_periodo_lectivo'];

    // Validar el archivo
    $allowedExtensions = ['csv', 'xls', 'xlsx'];
    $fileName = $_FILES['excelFile']['name'];
    $fileSize = $_FILES['excelFile']['size'];
    $fileTmpName = $_FILES['excelFile']['tmp_name'];
    
    // Verificar extensión
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Solo se permiten archivos Excel o CSV (.csv, .xls, .xlsx)');
    }
    
    // Verificar tamaño
    if ($fileSize > 5242880) { // 5MB
        throw new Exception('El archivo debe ser menor a 5MB');
    }
    
    // Leer el archivo como CSV o Excel simple
    $data = [];
    
    // Crear directorio temporal si no existe
    $tempDir = 'uploads/temp';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    
    // Si es Excel, convertir a CSV para procesarlo
    $csvFile = $fileTmpName;
    if ($fileExtension !== 'csv') {
        // Si es Excel, lo movemos a una ubicación temporal
        $tempFile = $tempDir . '/' . uniqid() . '.tmp';
        if (!move_uploaded_file($fileTmpName, $tempFile)) {
            throw new Exception('No se pudo mover el archivo a la ubicación temporal');
        }
        
        // El enfoque más simple es intentar abrir el archivo
        // Si es un archivo .xlsx o .xls, simplemente mostramos instrucciones
        throw new Exception(
            'Este sistema requiere un archivo CSV para funcionar correctamente. ' .
            'Por favor, abra su archivo de Excel, vaya a "Guardar como" y seleccione ' .
            'el formato CSV (delimitado por comas) y luego súbalo nuevamente.'
        );
    }
    
    // Si llegamos aquí, el archivo es CSV, así que lo procesamos
    $handle = fopen($csvFile, "r");
    if (!$handle) {
        throw new Exception('No se pudo abrir el archivo para lectura');
    }
    
    // Leer línea por línea
    $rowCount = 0;
    $hasHeaders = false;
    $firstRow = null;
    
    // Intentar determinar si la primera fila es de encabezados
    if (($firstRow = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Verificar si la primera celda es numérica (indica cédula) o textual (indica encabezado)
        if (!empty($firstRow[0]) && !is_numeric($firstRow[0])) {
            $hasHeaders = true;
        } else {
            // No es encabezado, así que guardamos la fila para procesarla
            if (!empty($firstRow[0])) {
                $data[] = $firstRow;
            }
        }
    }
    
    // Leer el resto del archivo
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $rowCount++;
        
        // Verificar que la fila tenga datos (al menos la cédula)
        if (isset($row[0]) && !empty($row[0])) {
            $data[] = $row;
        }
    }
    fclose($handle);
    
    // Verificar que haya datos para procesar
    if (count($data) === 0) {
        throw new Exception('No se encontraron datos para procesar en el archivo');
    }
    
    // Procesar los datos
    $procesados = 0;
    $errores = 0;
    $detallesErrores = [];
    
    foreach ($data as $index => $row) {
        // Verificar que la fila tenga al menos los 3 campos necesarios (cédula, nombres, apellidos)
        if (count($row) < 3) {
            $detallesErrores[] = "Fila " . ($index + 2) . ": No tiene los campos mínimos requeridos (cédula, nombres, apellidos)";
            $errores++;
            continue;
        }
        
        // Limpiar los datos básicos (siempre presentes)
        $cedula = limpiarDato($row[0]);
        $nombres = limpiarDato($row[1]);
        $apellidos = limpiarDato($row[2]);
        
        // Para el resto de campos, usar valores del curso si no están presentes en el CSV
        $nivelEducacion = (isset($row[3]) && !empty($row[3])) ? limpiarDato($row[3]) : $nivel_educacion;
        $cursoAct = (isset($row[4]) && !empty($row[4])) ? limpiarDato($row[4]) : $curso_nombre;
        $jornada = (isset($row[5]) && !empty($row[5])) ? limpiarDato($row[5]) : $jornada_nombre;
        $paralelo = (isset($row[6]) && !empty($row[6])) ? limpiarDato($row[6]) : $paralelo_nombre;
        $repite = (isset($row[7]) && !empty($row[7])) ? limpiarDato($row[7]) : 'NO';
        $tutor = (isset($row[8]) && !empty($row[8])) ? limpiarDato($row[8]) : $tutor_nombre;
        
        // Verificar datos mínimos requeridos
        if (empty($cedula) || empty($nombres) || empty($apellidos)) {
            $detallesErrores[] = "Fila " . ($index + 2) . ": Faltan datos obligatorios (cédula, nombres o apellidos)";
            $errores++;
            continue;
        }
        
        // Iniciar transacción para garantizar integridad
        mysqli_begin_transaction($conn);
        
        try {
            // Verificar si el estudiante ya existe
            $check_sql = "SELECT * FROM estudiantes WHERE est_cedula = '$cedula'";
            $check_result = mysqli_query($conn, $check_sql);
            
            if (mysqli_num_rows($check_result) > 0) {
                $detallesErrores[] = "Fila " . ($index + 2) . ": Estudiante con cédula $cedula ya existe";
                mysqli_rollback($conn);
                $errores++;
                continue;
            }
            
            // Insertar en la tabla estudiantes
            $sql1 = "INSERT INTO estudiantes (
                        est_nombres,
                        est_apellidos,
                        est_cedula,
                        est_usuario,
                        est_password,
                        est_estado
                    ) VALUES (
                        '$nombres',
                        '$apellidos',
                        '$cedula',
                        '$cedula',
                        '$cedula',
                        'ACTIVO'
                    )";
            
            if (!mysqli_query($conn, $sql1)) {
                throw new Exception(mysqli_error($conn));
            }
            
            // Insertar en la tabla est_datos
            $sql2 = "INSERT INTO est_datos (
                        dtest_nombres,
                        dtest_apellidos,
                        dtest_cedula,
                        dtest_ciclo_datos,
                        infaca_jornada_curso,
                        infaca_nivel_edu,
                        infaca_curso_act,
                        infaca_jornada_archivo,
                        infaca_paralelo,
                        infaca_repite,
                        infaca_tutorcurso
                    ) VALUES (
                        '$nombres',
                        '$apellidos',
                        '$cedula',
                        '$periodo_activo_id',
                        '$curso',
                        '$nivelEducacion',
                        '$cursoAct',
                        '$jornada',
                        '$paralelo',
                        '$repite',
                        '$tutor'
                    )";
            
            if (!mysqli_query($conn, $sql2)) {
                throw new Exception(mysqli_error($conn));
            }
            
            // Confirmar la transacción
            mysqli_commit($conn);
            $procesados++;
            
        } catch (Exception $e) {
            // Revertir cambios si hay error
            mysqli_rollback($conn);
            $detallesErrores[] = "Fila " . ($index + 2) . ": " . $e->getMessage();
            $errores++;
        }
    }
    
    // Preparar respuesta
    $response['success'] = ($procesados > 0);
    $response['message'] = "Proceso completado. Registros procesados: $procesados. Errores: $errores";
    $response['data'] = [
        'procesados' => $procesados,
        'errores' => $errores,
        'total_filas' => count($data),
        'tiene_encabezados' => $hasHeaders,
        'formato_simplificado' => true
    ];
    $response['errors'] = $detallesErrores;
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['debug'] = [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
}

// Cerrar conexión
mysqli_close($conn);

// Enviar respuesta
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 
