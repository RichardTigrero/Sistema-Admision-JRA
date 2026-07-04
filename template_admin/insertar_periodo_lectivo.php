<?php

include("../conexion/conexion.php");
header('Content-Type: application/json');

try {
    // Sanitizar entradas
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $observacion = mysqli_real_escape_string($conn, $_POST['observacion']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    // Validar que no estén vacíos
    if (empty($descripcion) || empty($observacion) || empty($estado)) {
        throw new Exception("Todos los campos son obligatorios");
    }

    $sql = "INSERT INTO periodo_lectivo
            (descripcion,
            observacion,
            estado) 
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", 
        $descripcion, $observacion, $estado
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Período lectivo registrado correctamente'
        ]);
    } else {
        throw new Exception("Error al crear el registro: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
