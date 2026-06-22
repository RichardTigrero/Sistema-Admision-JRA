<?php
include("conexion/conexion.php");
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title>Exploración de Base de Datos</title>
<style>
body { font-family: Arial; margin: 20px; }
h2 { background: #333; color: white; padding: 10px; }
table { border-collapse: collapse; width: 100%; margin: 20px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background: #f2f2f2; }
.section { margin: 20px 0; }
</style>
</head>
<body>";

// 1. ESTRUCTURA DE jornada_curso
echo "<h2>1. ESTRUCTURA DE TABLA: jornada_curso</h2>";
$sql = "DESCRIBE jornada_curso";
$result = mysqli_query($conn, $sql);
if ($result) {
    echo "<table>
    <tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>".$row['Field']."</td><td>".$row['Type']."</td><td>".$row['Null']."</td><td>".$row['Key']."</td><td>".$row['Default']."</td><td>".$row['Extra']."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// 2. ESTRUCTURA DE periodo_lectivo
echo "<h2>2. ESTRUCTURA DE TABLA: periodo_lectivo</h2>";
$sql = "DESCRIBE periodo_lectivo";
$result = mysqli_query($conn, $sql);
if ($result) {
    echo "<table>
    <tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>".$row['Field']."</td><td>".$row['Type']."</td><td>".$row['Null']."</td><td>".$row['Key']."</td><td>".$row['Default']."</td><td>".$row['Extra']."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// 3. CONTEO DE REGISTROS
echo "<h2>3. CONTEO DE REGISTROS</h2>";
echo "<table>
<tr><th>Tabla</th><th>Total Registros</th></tr>";

$tablas = array('estudiantes', 'est_datos', 'jornada_curso');
foreach ($tablas as $tabla) {
    $sql = "SELECT COUNT(*) as total FROM $tabla";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<tr><td>".$tabla."</td><td>".$row['total']."</td></tr>";
    } else {
        echo "<tr><td>".$tabla."</td><td>Error: " . mysqli_error($conn) . "</td></tr>";
    }
}
echo "</table>";

// 4. PROCEDIMIENTO ALMACENADO
echo "<h2>4. PROCEDIMIENTOS ALMACENADOS</h2>";
$sql = "SELECT ROUTINE_NAME, ROUTINE_DEFINITION 
        FROM INFORMATION_SCHEMA.ROUTINES 
        WHERE ROUTINE_SCHEMA = 'unidad_educativa'
        AND ROUTINE_TYPE = 'PROCEDURE'
        AND ROUTINE_NAME = 'sp_procesar_periodo_lectivo'";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    echo "<p style='color: green; font-weight: bold;'>✓ El procedimiento sp_procesar_periodo_lectivo EXISTE en la base de datos</p>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p><strong>Nombre:</strong> ".$row['ROUTINE_NAME']."</p>";
        echo "<p><strong>Definición:</strong></p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 400px;'>";
        echo htmlspecialchars($row['ROUTINE_DEFINITION']);
        echo "</pre>";
    }
} else {
    echo "<p style='color: red; font-weight: bold;'>✗ El procedimiento sp_procesar_periodo_lectivo NO existe</p>";
}

// 5. INFORMACIÓN EN crear_tablas_historial.sql
echo "<h2>5. BÚSQUEDA EN ARCHIVO crear_tablas_historial.sql</h2>";
$archivo = "conexion/crear_tablas_historial.sql";
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    if (strpos($contenido, 'sp_procesar_periodo_lectivo') !== false) {
        echo "<p style='color: green; font-weight: bold;'>✓ El procedimiento SÍ está definido en " . $archivo . "</p>";
    } else {
        echo "<p style='color: red;'>✗ El procedimiento NO está en " . $archivo . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Archivo " . $archivo . " no encontrado</p>";
}

mysqli_close($conn);

echo "</body>
</html>";
?>
