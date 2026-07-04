<?php
session_start();
include("../conexion/conexion.php");

// Configuración
$upload_dir = "../uploads/documentos/";
$max_file_size = 5 * 1024 * 1024; // 5MB
$allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
$allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

// Verificar si la carpeta existe, si no, crearla
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Función para responder con JSON
function json_response($success, $message, $data = []) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se proporcionaron los datos necesarios
    if (!isset($_POST['est_id']) || !isset($_POST['est_cedula']) || !isset($_FILES['documento'])) {
        json_response(false, "Faltan datos requeridos");
    }
    
    $est_id = $_POST['est_id'];
    $est_cedula = $_POST['est_cedula'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    
    $file = $_FILES['documento'];
    
    // Verificar si hubo errores en la subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => "El archivo excede el tamaño máximo permitido por el servidor",
            UPLOAD_ERR_FORM_SIZE => "El archivo excede el tamaño máximo permitido por el formulario",
            UPLOAD_ERR_PARTIAL => "El archivo se subió parcialmente",
            UPLOAD_ERR_NO_FILE => "No se seleccionó ningún archivo",
            UPLOAD_ERR_NO_TMP_DIR => "Falta la carpeta temporal",
            UPLOAD_ERR_CANT_WRITE => "No se pudo escribir en el disco",
            UPLOAD_ERR_EXTENSION => "Una extensión PHP detuvo la subida"
        ];
        
        $error_message = isset($error_messages[$file['error']]) 
            ? $error_messages[$file['error']] 
            : "Error desconocido al subir el archivo";
            
        json_response(false, $error_message);
    }
    
    // Verificar el tamaño del archivo
    if ($file['size'] > $max_file_size) {
        json_response(false, "El archivo es demasiado grande. El tamaño máximo permitido es 5MB");
    }
    
    // Verificar el tipo de archivo
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_type = $file['type'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        json_response(false, "Formato de archivo no permitido. Se permiten: PDF, DOC, DOCX, JPG, PNG");
    }
    
    // Generar un nombre único para el archivo
    $timestamp = date('YmdHis');
    $new_filename = $est_cedula . '_' . $timestamp . '.' . $file_extension;
    $file_path = $upload_dir . $new_filename;
    
    // Mover el archivo subido a la ubicación final
    if (!move_uploaded_file($file['tmp_name'], $file_path)) {
        json_response(false, "Error al guardar el archivo");
    }
    
    // Guardar la información en la base de datos
    $documento_info = [
        'ruta' => $file_path,
        'nombre_original' => $file['name'],
        'descripcion' => $descripcion,
        'fecha_subida' => date('Y-m-d H:i:s')
    ];
    
    $documento_json = json_encode($documento_info);
    
    // Actualizar la tabla es_datos
    $sql = "UPDATE est_datos SET dtest_documento_adjunto = ? WHERE dtest_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $documento_json, $est_id);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            json_response(true, "Documento adjuntado correctamente");
        } else {
            json_response(false, "Error al guardar la información en la base de datos: " . mysqli_error($conn));
        }
        
        mysqli_stmt_close($stmt);
    } else {
        json_response(false, "Error al preparar la consulta: " . mysqli_error($conn));
    }
    
} else {
    json_response(false, "Método de solicitud no válido");
}
?>
