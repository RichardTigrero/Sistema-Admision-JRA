<?php
session_start();
include("../conexion/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    
    // Validar que el estado sea uno de los permitidos
    $estados_permitidos = ['ACTIVO', 'INACTIVO', 'CERRADO'];
    
    if (!in_array($estado, $estados_permitidos)) {
        echo json_encode([
            'success' => false,
            'message' => 'Estado no válido'
        ]);
        exit;
    }
    
    // Si el estado es CERRADO, primero verificar que el período esté abierto (ACTIVO)
    if ($estado === 'CERRADO') {
        // Verificar el estado actual del período
        $sql_verify = "SELECT estado FROM periodo_lectivo WHERE id_periodo_lectivo = $id";
        $result_verify = mysqli_query($conn, $sql_verify);
        
        if (mysqli_num_rows($result_verify) === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Período lectivo no encontrado'
            ]);
            mysqli_close($conn);
            exit;
        }
        
        $row_verify = mysqli_fetch_assoc($result_verify);
        $estado_actual = $row_verify['estado'];
        
        // Solo permitir procesar si el período está ACTIVO (abierto)
        if ($estado_actual !== 'ACTIVO') {
            echo json_encode([
                'success' => false,
                'message' => 'Solo se pueden procesar períodos que están en estado ACTIVO. Estado actual: ' . $estado_actual
            ]);
            mysqli_close($conn);
            exit;
        }
        
        // Si está abierto, ejecutar el procedimiento de archivo
        $sql_procedure = "CALL sp_procesar_periodo_lectivo($id, @mensaje, @exito)";
        
        if (mysqli_query($conn, $sql_procedure)) {
            // Obtener los valores de salida del procedimiento
            $result = mysqli_query($conn, "SELECT @mensaje as mensaje, @exito as exito");
            $row = mysqli_fetch_assoc($result);
            
            if ($row['exito']) {
                echo json_encode([
                    'success' => true,
                    'message' => $row['mensaje']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $row['mensaje']
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar el período: ' . mysqli_error($conn)
            ]);
        }
    } else {
        // Para ACTIVO e INACTIVO, solo actualizar el estado
        $sql = "UPDATE periodo_lectivo SET estado = '$estado' WHERE id_periodo_lectivo = '$id'";
        
        if (mysqli_query($conn, $sql)) {
            $mensaje = '';
            switch($estado) {
                case 'ACTIVO':
                    $mensaje = 'Período activado exitosamente';
                    break;
                case 'INACTIVO':
                    $mensaje = 'Período desactivado exitosamente';
                    break;
            }
            
            echo json_encode([
                'success' => true,
                'message' => $mensaje
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el período: ' . mysqli_error($conn)
            ]);
        }
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Parámetros inválidos'
    ]);
}

mysqli_close($conn);
?>
