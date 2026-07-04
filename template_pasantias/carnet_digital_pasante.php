<?php
session_start();
include("../conexion/conexion.php");

// Validar parámetro ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID de pasante no especificado");
}

$id = intval($_GET['id']);

// Consulta para obtener datos del pasante (usando la base de datos correcta)
$sql = "SELECT * FROM unidad_educativa.pasantes WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
}
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Error: No se encontró información del pasante");
}

$pasante = mysqli_fetch_assoc($result);

// Configuración de la foto
$foto_path = "../img/undraw_profile.svg"; // Imagen por defecto
if (!empty($pasante['foto'])) {
    $foto_path = "../uploads/" . $pasante['foto'];
}

// Generar datos para el QR
$qr_data = "ID:" . $pasante['id'] . "|CEDULA:" . $pasante['cedula'] . "|NOMBRE:" . $pasante['nombres'] . " " . $pasante['apellidos'];
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qr_data) . "&size=200x200";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">
    <title>Carnet Digital Pasante - <?php echo htmlspecialchars($pasante['nombres'] . ' ' . $pasante['apellidos']); ?></title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .carnet-row { justify-content: center !important; }
            .carnet-card { box-shadow: none !important; border: none !important; }
        }
        body { background: #f4f6fb; }
        .carnet-row {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .carnet-card {
            width: 370px;
            min-height: 470px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
            background-color: #fff;
            border: 1px solid #e3e6f0;
            display: flex;
            flex-direction: column;
        }
        .carnet-header, .carnet-back-header {
            background-color: #1c2c5b;
            color: white;
            padding: 18px 15px 12px 15px;
            text-align: center;
        }
        .carnet-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }
        .carnet-logo img {
            height: 80px;
            margin-right: 18px;
        }
        .carnet-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .carnet-subtitle {
            font-size: 15px;
            font-weight: 600;
            margin-top: 6px;
            margin-bottom: 0;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .carnet-body {
            padding: 22px 20px 12px 20px;
            display: flex;
        }
        .carnet-photo {
            width: 110px;
            height: 110px;
            border: 1px solid #ddd;
            margin-right: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #f8f9fc;
        }
        .carnet-photo img {
            width: 100%;
            height: auto;
        }
        .carnet-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .carnet-info p {
            margin: 6px 0;
            font-size: 15px;
            color: #1c2c5b;
        }
        .carnet-info .label {
            font-weight: bold;
            color: #1c2c5b;
        }
        .carnet-footer, .carnet-back-footer {
            background-color: #f8f9fc;
            padding: 8px 10px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #e3e6f0;
            color: #888;
        }
        .carnet-qr {
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
            text-align: center;
            padding: 10px 0 0 0;
        }
        .btn-toolbar {
            margin-top: 24px;
            display: flex;
            justify-content: center;
        }
        
        /* Parte trasera */
        .carnet-back-body {
            padding: 22px 20px 12px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .carnet-back-body h4 {
            color: #1c2c5b;
            font-size: 17px;
            margin-bottom: 12px;
            font-weight: 700;
            text-align: left;
        }
        .carnet-back-body ul {
            font-size: 15px;
            color: #333;
            margin-bottom: 10px;
            padding-left: 22px;
            list-style: none;
        }
        .carnet-back-body ul li {
            margin-bottom: 7px;
            position: relative;
            padding-left: 22px;
        }
        .carnet-back-body ul li i {
            position: absolute;
            left: 0;
            top: 2px;
            color: #1c2c5b;
            font-size: 16px;
        }
        .carnet-back-body p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .frase-educativa {
            font-size: 15px;
            color: #f7ca18;
            font-weight: bold;
            text-align: center;
            margin: 12px 0 0 0;
            font-style: italic;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="carnet-row">
            <!-- Parte frontal -->
            <div class="carnet-card carnet-card-front">
                <div class="carnet-header">
                    <div class="carnet-logo">
                        <img src="../imagenes/Logo_JRA.jpeg" alt="Logo">
                        <div>
                            <div class="carnet-title">UNIDAD EDUCATIVA FISCAL<br>JAIME ROLDÓS AGUILERA</div>
                            <div class="carnet-subtitle">CARNET DE PASANTE</div>
                        </div>
                    </div>
                </div>
                <div class="carnet-body">
                    <div class="carnet-photo">
                        <img src="<?php echo $foto_path; ?>" alt="Foto del pasante">
                    </div>
                    <div class="carnet-info">
                        <p><span class="label">Cédula:</span> <?php echo htmlspecialchars($pasante['cedula']); ?></p>
                        <p><span class="label">Nombre:</span> <?php echo htmlspecialchars($pasante['nombres'] . ' ' . $pasante['apellidos']); ?></p>
                        <p><span class="label">Institución:</span> <?php echo htmlspecialchars($pasante['institucion']); ?></p>
                        <p><span class="label">Carrera:</span> <?php echo htmlspecialchars($pasante['carrera']); ?></p>
                        <p><span class="label">Estado:</span> <?php echo htmlspecialchars($pasante['estado']); ?></p>
                        <p><span class="label">Fecha Inicio:</span> <?php echo htmlspecialchars($pasante['fecha_inicio']); ?></p>
                        <p><span class="label">Fecha Fin:</span> <?php echo htmlspecialchars($pasante['fecha_fin']); ?></p>
                    </div>
                </div>
                <div class="carnet-qr">
                    <img src="<?php echo $qr_url; ?>" alt="Código QR" style="max-width:130px; height:auto;">
                    <p class="mt-2" style="font-size: 11px;">Escanea este código para verificar</p>
                </div>
                <div class="carnet-footer">
                    Este carnet es personal e intransferible.<br>
                    Válido durante el período de pasantía
                </div>
            </div>
            <!-- Parte trasera -->
<!-- Parte trasera -->

            <div class="carnet-card carnet-card-back">
                <div class="carnet-back-header">
                    <div class="carnet-title">UNIDAD EDUCATIVA FISCAL<br>JAIME ROLDÓS AGUILERA</div>
                    <div class="frase-educativa">
                        "La educación es el arma más poderosa que puedes usar para cambiar el mundo."
                    </div>
                </div>
                <div class="carnet-back-body">
                    <h4>INFORMACIÓN IMPORTANTE</h4>
                    <ul>
                        <li><i class="bi bi-exclamation-circle"></i> En caso de emergencia, comuníquese con la institución.</li>
                        <li><i class="bi bi-telephone"></i> Teléfono: 0999999999</li>
                        <li><i class="bi bi-geo-alt"></i> Dirección: Av. Principal y Calle Secundaria, Ciudad</li>
                        <li><i class="bi bi-envelope"></i> Correo: info@unidadeducativajra.edu.ec</li>
                    </ul>
                    <p>
                        Este carnet debe portarse siempre dentro de la institución.<br>
                        La pérdida o daño del carnet debe ser reportada inmediatamente.<br>
                        <span style="color:#1c2c5b;font-weight:bold;">Solo válido para el periodo de pasantía.</span>
                    </p>
                </div>
                <div class="carnet-back-footer">
                    Unidad Educativa Fiscal Jaime Roldós Aguilera &copy; 2025
                </div>
            </div>
        </div>
        <div class="btn-toolbar no-print">
            <button type="button" class="btn btn-primary mr-2" onclick="window.print();">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.close();">
                <i class="fas fa-times"></i> Cerrar
            </button>
        </div>
    </div>
</body>
</html>