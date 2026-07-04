<?php
include("../conexion/conexion.php");

// Verificar la estructura de la tabla est_datos
$result = mysqli_query($conn, "DESCRIBE est_datos");
if ($result) {
    echo "<h3>Estructura de la tabla est_datos:</h3>";
    echo "<pre>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
    echo "</pre>";
} else {
    echo "Error al consultar la estructura de la tabla: " . mysqli_error($conn);
}

// Verificar si existe el campo dtest_imagen_usuario
$has_image_field = false;
mysqli_data_seek($result, 0); // Resetear el puntero del resultado
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Field'] === 'dtest_imagen_usuario') {
        $has_image_field = true;
        break;
    }
}

// Mostrar una muestra de datos de la tabla
$data_query = mysqli_query($conn, "SELECT dtest_id, dtest_cedula, dtest_nombres, dtest_apellidos, dtest_imagen_usuario FROM est_datos LIMIT 5");
if ($data_query) {
    echo "<h3>Muestra de datos (primeros 5 registros):</h3>";
    echo "<pre>";
    while ($data = mysqli_fetch_assoc($data_query)) {
        print_r($data);
    }
    echo "</pre>";
} else {
    echo "Error al consultar los datos: " . mysqli_error($conn);
}

echo "<p>¿Existe el campo dtest_imagen_usuario? " . ($has_image_field ? "SÍ" : "NO") . "</p>";
?>
