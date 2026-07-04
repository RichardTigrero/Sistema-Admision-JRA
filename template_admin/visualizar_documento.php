<?php
session_start();
include("../conexion/conexion.php");

// Verificar si se recibió el ID del estudiante
if (!isset($_GET['est_id']) || empty($_GET['est_id'])) {
    echo "<div class='alert alert-danger'>ID de estudiante no proporcionado</div>";
    exit;
}

$est_id = $_GET['est_id'];

// Obtener información del documento del estudiante
$sql = "SELECT dtest_documento_adjunto, dtest_nombres, dtest_cedula FROM est_datos WHERE dtest_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo "<div class='alert alert-danger'>Error al preparar la consulta: " . mysqli_error($conn) . "</div>";
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $est_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>No se encontró información del estudiante</div>";
    exit;
}

$row = mysqli_fetch_assoc($result);
$documento_json = $row['dtest_documento_adjunto'];
$nombre_estudiante = $row['dtest_nombres'];
$cedula_estudiante = $row['dtest_cedula'];

if (empty($documento_json)) {
    echo "<div class='alert alert-warning'>El estudiante no tiene documentos adjuntos</div>";
    exit;
}

// Decodificar la información del documento
$documento_info = json_decode($documento_json, true);

if (!$documento_info || !isset($documento_info['ruta'])) {
    echo "<div class='alert alert-danger'>Información del documento inválida</div>";
    exit;
}

$ruta_documento = $documento_info['ruta'];
$nombre_original = $documento_info['nombre_original'];
$descripcion = isset($documento_info['descripcion']) ? $documento_info['descripcion'] : 'Sin descripción';
$fecha_subida = isset($documento_info['fecha_subida']) ? $documento_info['fecha_subida'] : 'Fecha desconocida';

// Obtener la extensión del archivo
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

// Verificar si el archivo existe
if (!file_exists($ruta_documento)) {
    echo "<div class='alert alert-danger'>El archivo no existe en el servidor</div>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">
    <title>Visualizador de Documentos - JRA</title>

    <!-- Custom fonts -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- PDF.js para visualizar PDFs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    
    <style>
        .document-container {
            background-color: #f8f9fc;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 30px auto;
            max-width: 90%;
            position: relative;
        }
        
        .document-header {
            background-color: #4e73df;
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .document-body {
            padding: 20px;
            text-align: center;
        }
        
        .document-info {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .document-info dl {
            display: grid;
            grid-template-columns: 1fr 2fr;
            grid-gap: 10px;
            margin: 0;
        }
        
        .document-info dt {
            text-align: right;
            font-weight: bold;
            color: #4e73df;
        }
        
        .document-info dd {
            text-align: left;
            margin-left: 0;
        }
        
        .document-viewer {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin: 0 auto;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .document-viewer iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
        
        .document-viewer img {
            max-width: 100%;
            max-height: 600px;
            object-fit: contain;
        }
        
        #pdf-viewer {
            width: 100%;
            height: 600px;
            overflow: auto;
            background-color: #525659;
            padding: 10px 0;
        }
        
        #pdf-viewer canvas {
            margin: 0 auto;
            display: block;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .controls {
            background-color: #f1f1f1;
            padding: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
            border-radius: 0 0 8px 8px;
        }
        
        @media (max-width: 768px) {
            .document-info dl {
                grid-template-columns: 1fr;
            }
            
            .document-info dt {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="document-container">
            <div class="document-header">
                <h3 class="m-0"><i class="fas fa-file-alt mr-2"></i>Visualizador de Documento</h3>
                <button class="btn btn-light btn-sm" onclick="window.close()">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
            
            <div class="document-body">
                <div class="document-info">
                    <dl>
                        <dt>Estudiante:</dt>
                        <dd><?php echo htmlspecialchars($nombre_estudiante); ?></dd>
                        
                        <dt>Cédula:</dt>
                        <dd><?php echo htmlspecialchars($cedula_estudiante); ?></dd>
                        
                        <dt>Documento:</dt>
                        <dd><?php echo htmlspecialchars($nombre_original); ?></dd>
                        
                        <dt>Descripción:</dt>
                        <dd><?php echo htmlspecialchars($descripcion); ?></dd>
                        
                        <dt>Fecha de subida:</dt>
                        <dd><?php echo htmlspecialchars($fecha_subida); ?></dd>
                    </dl>
                </div>
                
                <div class="document-viewer">
                    <?php
                    // Mostrar el documento según su tipo
                    switch ($extension) {
                        case 'pdf':
                            echo '<div id="pdf-viewer"></div>';
                            break;
                            
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            echo '<img src="' . htmlspecialchars($ruta_documento) . '" alt="Documento">';
                            break;
                            
                        case 'doc':
                        case 'docx':
                            // Para documentos de Word, ofrecer opción de descarga
                            echo '<div class="text-center">';
                            echo '<div class="mb-4"><i class="fas fa-file-word fa-5x text-primary"></i></div>';
                            echo '<p class="mb-4">Los documentos de Word no se pueden previsualizar directamente.</p>';
                            echo '<a href="' . htmlspecialchars($ruta_documento) . '" class="btn btn-primary" download>';
                            echo '<i class="fas fa-download mr-2"></i>Descargar documento';
                            echo '</a>';
                            echo '</div>';
                            break;
                            
                        default:
                            echo '<div class="alert alert-warning">Formato no compatible para visualización</div>';
                    }
                    ?>
                </div>
                
                <?php if ($extension === 'pdf'): ?>
                <div class="controls">
                    <button id="prev" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Anterior
                    </button>
                    <span id="page-info" class="btn btn-light">Página <span id="page-num">1</span> de <span id="page-count">?</span></span>
                    <button id="next" class="btn btn-secondary">
                        Siguiente <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                    <button id="zoom-in" class="btn btn-info">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button id="zoom-out" class="btn btn-info">
                        <i class="fas fa-search-minus"></i>
                    </button>
                </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="<?php echo htmlspecialchars($ruta_documento); ?>" class="btn btn-success" download>
                        <i class="fas fa-download mr-2"></i>Descargar documento
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($extension === 'pdf'): ?>
    <script>
        // Configuración para PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        
        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.2,
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext('2d');
            
        document.getElementById('pdf-viewer').appendChild(canvas);
        
        // Cargar el PDF
        const loadingTask = pdfjsLib.getDocument('<?php echo $ruta_documento; ?>');
        loadingTask.promise.then(function(pdf) {
            pdfDoc = pdf;
            document.getElementById('page-count').textContent = pdf.numPages;
            
            // Renderizar la primera página
            renderPage(pageNum);
        }).catch(function(error) {
            console.error('Error durante la carga del documento:', error);
            document.getElementById('pdf-viewer').innerHTML = '<div class="alert alert-danger">Error al cargar el PDF: ' + error.message + '</div>';
        });
        
        function renderPage(num) {
            pageRendering = true;
            
            // Obtener la página
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                // Renderizar PDF página en canvas
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                const renderTask = page.render(renderContext);
                
                // Cuando la página termine de renderizarse
                renderTask.promise.then(function() {
                    pageRendering = false;
                    
                    if (pageNumPending !== null) {
                        // Nueva página en espera
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
            
            // Actualizar número de página
            document.getElementById('page-num').textContent = num;
        }
        
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }
        
        // Ir a la página anterior
        document.getElementById('prev').addEventListener('click', function() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        });
        
        // Ir a la página siguiente
        document.getElementById('next').addEventListener('click', function() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        });
        
        // Zoom
        document.getElementById('zoom-in').addEventListener('click', function() {
            scale += 0.2;
            queueRenderPage(pageNum);
        });
        
        document.getElementById('zoom-out').addEventListener('click', function() {
            if (scale <= 0.5) return;
            scale -= 0.2;
            queueRenderPage(pageNum);
        });
    </script>
    <?php endif; ?>
    
    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
