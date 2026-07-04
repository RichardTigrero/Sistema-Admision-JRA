<?php
// Inicializamos la variable para evitar el warning de variable no definida
$confirma_data = "";
$debug_info = ""; // Variable para almacenar información de depuración
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="../imagenes/Logo_JRA.jpeg">

    <title>Carga Masiva de Estudiantes</title>

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    function cerrar_pantalla()
    {
        window.open('register_est.php',target='_blank');
        window.close();
    }
</script>
<body>
<br>
<br>
<br>
<div class="row"> 
<div class="col-sm-4"></div>
<div class="col-sm-4">
    <h1>
    <?php echo $confirma_data; ?></h1>
    <br>
    <!-- Área para mostrar información de depuración -->
    <div class="alert alert-info">
        <?php echo $debug_info; ?>
    </div>
</div>

<div class="col-sm-4"></div>
</div>

<div class="row">
  <div class="col-sm-2"></div>
  <div class="col-sm-8">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5>Información del curso</h5>
      </div>
      <div class="card-body">
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="curso"><b>Curso:</b></label>
                <select class="form-control" name="curso" id="curso" required>
                  <option value="">Seleccione curso</option>
                  <?php
                  include("../conexion/conexion.php");
                  $sql = "SELECT id_jornada_curso, 
                               nivel, 
                               jornada, 
                               curso, 
                               paralelo,
                               concat(nivel,'-',jornada,'-',curso,'-',paralelo) as nombre_jornada_curso,
                               (select concat(d.dst_nombres,' ',d.dst_apellidos) from docente d where d.id_doc=jc.id_docente) as tutor
                         FROM jornada_curso jc where estado='ACTIVO'";
                  $result = mysqli_query($conn, $sql);
                  
                  if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                      $codigo_pro = $row["id_jornada_curso"];
                      $nombre_pro = $row["nombre_jornada_curso"];
                      echo "<option value=\"$codigo_pro\">$nombre_pro</option>";
                    }
                  } else {
                    echo "<option value=\"\">No hay cursos disponibles</option>";
                  }
                  mysqli_close($conn);
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="excelFile"><b>Archivo Excel/CSV:</b></label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="excelFile" id="excelFile" accept=".csv, .xls, .xlsx" required>
                  <label class="custom-file-label" for="excelFile">Seleccionar archivo...</label>
                </div>
                <small class="form-text text-muted">Formatos permitidos: Excel (.xls, .xlsx) o CSV (.csv)</small>
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <div class="col-md-4">
              <div class="form-group">
                <label for="nivel"><b>Nivel:</b></label>
                <input type="text" class="form-control" id="nivel" name="nivel" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="cur"><b>Curso:</b></label>
                <input type="text" class="form-control" id="cur" name="cur" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="jorna"><b>Jornada:</b></label>
                <input type="text" class="form-control" id="jorna" name="jorna" readonly>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="paralelo"><b>Paralelo:</b></label>
                <input type="text" class="form-control" id="paralelo" name="paralelo" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tutor"><b>Tutor:</b></label>
                <input type="text" class="form-control" id="tutor" name="tutor" readonly>
              </div>
            </div>
          </div>
          
          <div class="alert alert-info mt-3">
            <strong>Nota:</strong> Puede cargar un archivo Excel con solo 3 columnas (Cédula, Nombres, Apellidos). 
            Los demás datos se completarán automáticamente con la información del curso seleccionado.
          </div>
          
          <button type="submit" class="btn btn-primary btn-block mt-3">Cargar estudiantes</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-sm-2"></div>
</div>

<div class="row mt-3">  
<div class="col-sm-4"></div>
     
    <div class="col-sm-4">
     <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
       Ver Datos Cargados
    </button>
    <button type="button" class="btn btn-warning" onclick="cerrar_pantalla()">
       Cerrar
    </button>
    </div>
<div class="col-sm-4"></div>
</div>


<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos Carga Masiva</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="card shadow mb-4">
 <div class="card-body">
  <div class="table-responsive">
<?php
include("../conexion/conexion.php");

if(isset($_FILES['excelFile'])){
    // Configuración de los archivos permitidos
    $allowedExtensions = array("xls", "xlsx");
    $uploadDir = "uploads/";
    $curso = $_POST['curso'] ;
    
    // Verificar que se ha seleccionado un curso válido
    if(empty($curso) || $curso == "0") {
        $confirma_data = "Error: Debe seleccionar un curso válido";
        $debug_info = "No se seleccionó un curso válido. Valor recibido: " . $curso;
        echo $debug_info;
    } else {
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
            $confirma_data = "Error: No se encontró información del curso seleccionado";
            $debug_info = "No se encontró información del curso con ID: " . $curso;
            echo $debug_info;
        } else {
            $curso_info = mysqli_fetch_assoc($curso_result);
            $nivel_educacion = $curso_info['nivel'];
            $curso_nombre = $curso_info['curso'];
            $jornada_nombre = $curso_info['jornada'];
            $paralelo_nombre = $curso_info['paralelo'];
            $tutor_nombre = $curso_info['tutor'];
            
            // Información del archivo subido
            $originalFileName = $_FILES['excelFile']['name'];
            $fileSize = $_FILES['excelFile']['size'];
            $fileTmpName = $_FILES['excelFile']['tmp_name'];
            $fileType = $_FILES['excelFile']['type'];
            
            // Verificación del tamaño del archivo
            if($fileSize > 2097152){
                die("Error: El archivo debe ser menor a 2MB.");
            }
            
            // Crear el directorio si no existe
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                // Establecer permisos después de crear
                chmod($uploadDir, 0777);
            }
            
            // Generar un nombre único para el archivo para evitar conflictos
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $uniqueFileName = 'excel_' . uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $uniqueFileName;
            
            // Movemos el archivo subido a la carpeta de destino con mejor manejo de errores
            $maxRetries = 3;
            $retryCount = 0;
            $uploadSuccess = false;
            
            while ($retryCount < $maxRetries && !$uploadSuccess) {
                if (move_uploaded_file($fileTmpName, $uploadFile)) {
                    $uploadSuccess = true;
                } else {
                    // Esperar un poco antes de reintentar
                    $retryCount++;
                    if ($retryCount < $maxRetries) {
                        sleep(1); // Esperar 1 segundo
                    }
                }
            }
            
            if ($uploadSuccess) {
                // Si se sube correctamente, leemos el archivo Excel y mostramos los resultados
                $debug_info .= "Archivo subido correctamente como: $uploadFile<br>";
                
                // Verificar si el archivo existe y es legible
                if (!file_exists($uploadFile) || !is_readable($uploadFile)) {
                    $debug_info .= "Error: El archivo subido no existe o no se puede leer.<br>";
                    $confirma_data = "Error: El archivo no se puede procesar";
                    echo $debug_info;
                }
                
                require_once 'PHPExcel-1.8/Classes/PHPExcel.php';

                try {
                    $objPHPExcel = PHPExcel_IOFactory::load($uploadFile);
                    $sheet = $objPHPExcel->getActiveSheet();
                    $debug_info .= "Archivo Excel cargado correctamente<br>";

                    // Leemos los datos de la hoja de cálculo
                    // El orden esperado de las columnas del Excel debe ser:
                    // 0: Cedula, 1: Nombres, 2: Apellidos, 3: Nivel Educacion, 4: Curso 2025, 
                    // 5: Jornada, 6: Paralelo, 7: Repite(SI/NO), 8: Tutor
                    $data = array();
                    $rowCount = 0;
                    
                    foreach($sheet->getRowIterator() as $row){
                        $rowCount++;
                        // Saltamos la primera fila si contiene encabezados
                        if ($rowCount == 1) {
                            $debug_info .= "Saltando primera fila (encabezados)<br>";
                            continue; // Salta la primera fila que suele contener encabezados
                        }
                        
                        $rowData = array();

                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);

                        foreach($cellIterator as $cell){
                            $rowData[] = $cell->getValue();
                        }
                        
                        // Solo procesar filas con datos (verificar que la cédula no esté vacía)
                        if (!empty($rowData[0])) {
                            $data[] = $rowData;
                        }
                    }
                    
                    $debug_info .= "Se procesaron " . count($data) . " filas de datos<br>";
                    
                    // Verificar si es formato simplificado
                    $isSimplifiedFormat = false;
                    if (count($data) > 0) {
                        $firstRow = $data[0];
                        $isSimplifiedFormat = (count($firstRow) >= 3 && count($firstRow) < 9);
                        if ($isSimplifiedFormat) {
                            $debug_info .= "Se detectó formato simplificado (solo datos básicos)<br>";
                        }
                    }

                    // Mostramos los datos en una tabla HTML
                    echo '<h2>Datos del archivo subido:</h2>';
                    echo '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"><thead>';
                    echo '<tr><th>Cedula</th>
                             <th>Nombres</th>
                             <th>Apellidos</th>
                             <th>Nivel Educacion</th>
                             <th>Curso 2025</th>
                             <th>Jornada</th>
                             <th>Paralelo</th>
                             <th>Repite</th>
                             <th>Tutor</th>
                             <th>Procesado</th>
                             </tr></thead>';
                    $estado_reg="OK";
                    foreach($data as $row){
                        echo '<tbody><tr>';
                        echo '<td>' . $row[0] . '</td>';
                        echo '<td>' . $row[1] . '</td>';
                        echo '<td>' . $row[2] . '</td>';
                        
                        // Para campos adicionales, mostrar los datos del curso si no están en el CSV
                        $nivelEducacionView = isset($row[3]) && !empty($row[3]) ? $row[3] : $nivel_educacion;
                        $cursoActView = isset($row[4]) && !empty($row[4]) ? $row[4] : $curso_nombre;
                        $jornadaView = isset($row[5]) && !empty($row[5]) ? $row[5] : $jornada_nombre;
                        $paraleloView = isset($row[6]) && !empty($row[6]) ? $row[6] : $paralelo_nombre;
                        $repiteView = isset($row[7]) && !empty($row[7]) ? $row[7] : 'NO';
                        $tutorView = isset($row[8]) && !empty($row[8]) ? $row[8] : $tutor_nombre;
                        
                        echo '<td>' . $nivelEducacionView . '</td>';
                        echo '<td>' . $cursoActView . '</td>';
                        echo '<td>' . $jornadaView . '</td>';
                        echo '<td>' . $paraleloView . '</td>';
                        echo '<td>' . $repiteView . '</td>';
                        echo '<td>' . $tutorView . '</td>';
                        echo '<td>' . $estado_reg. '</td>';
                        echo '</tr></tbody>';
                        
                        // Verificar que tengamos todos los datos necesarios
                        if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                            $debug_info .= "Fila saltada por datos incompletos. Cédula: " . (empty($row[0]) ? 'vacía' : $row[0]) . "<br>";
                            continue; // Saltar esta fila si faltan datos esenciales
                        }
                        
                        try {
                            // Verificar si el estudiante ya existe para evitar duplicados
                            $check_sql = "SELECT * FROM estudiantes WHERE est_cedula = '$row[0]'";
                            $check_result = mysqli_query($conn, $check_sql);
                            
                            if (mysqli_num_rows($check_result) > 0) {
                                $debug_info .= "Estudiante con cédula $row[0] ya existe, saltando inserción<br>";
                                continue; // Si ya existe, saltamos a la siguiente iteración
                            }
                            
                            $sql = "INSERT INTO estudiantes (est_nombres,
                                                            est_apellidos,
                                                            est_cedula,
                                                            est_usuario,
                                                            est_password,
                                                            est_estado) 
                                                            VALUES ('$row[1]',
                                                                  '$row[2]',
                                                                  '$row[0]',
                                                                  '$row[0]',
                                                                  '$row[0]',
                                                                  'ACTIVO')";
                            if (mysqli_query($conn, $sql)) {
                                $debug_info .= "Estudiante con cédula $row[0] insertado en tabla estudiantes<br>";
                            } else {
                                echo "Error al crear el registro en tabla estudiantes: " . mysqli_error($conn);
                                $debug_info .= "Error al insertar estudiante $row[0]: " . mysqli_error($conn) . "<br>";
                                $confirma_data = "Error en la carga de datos: " . mysqli_error($conn);
                                continue; // Si hay error en la primera inserción, saltamos a la siguiente iteración
                            }

                            // Obtener los valores para la inserción en est_datos
                            $nivelEducacion = isset($row[3]) && !empty($row[3]) ? $row[3] : $nivel_educacion;
                            $cursoAct = isset($row[4]) && !empty($row[4]) ? $row[4] : $curso_nombre;
                            $jornada = isset($row[5]) && !empty($row[5]) ? $row[5] : $jornada_nombre;
                            $paralelo = isset($row[6]) && !empty($row[6]) ? $row[6] : $paralelo_nombre;
                            $repite = isset($row[7]) && !empty($row[7]) ? $row[7] : 'NO';
                            $tutor = isset($row[8]) && !empty($row[8]) ? $row[8] : $tutor_nombre;

                            $sql = "INSERT INTO est_datos(`dtest_nombres`,
                                                        `dtest_apellidos`,
                                                        `dtest_cedula`,
                                                        `infaca_jornada_curso`,
                                                        `infaca_nivel_edu`,
                                                        `infaca_curso_act`,
                                                        `infaca_jornada_archivo`,
                                                        `infaca_paralelo`,
                                                        `infaca_repite`,
                                                        `infaca_tutorcurso`) 
                                                        VALUES ('$row[1]',
                                                                '$row[2]',
                                                                '$row[0]',
                                                                '$curso',
                                                                '$nivelEducacion',
                                                                '$cursoAct',
                                                                '$jornada',
                                                                '$paralelo',
                                                                '$repite',
                                                                '$tutor')";
                            if (mysqli_query($conn, $sql)) {
                                $debug_info .= "Datos adicionales para estudiante $row[0] insertados en est_datos<br>";
                            } else {
                                echo "Error al crear el registro en tabla est_datos: " . mysqli_error($conn);
                                $debug_info .= "Error al insertar datos adicionales para $row[0]: " . mysqli_error($conn) . "<br>";
                                $confirma_data = "Error en la carga de datos: " . mysqli_error($conn);
                            }
                        } catch (Exception $e) {
                            $debug_info .= "Error en el procesamiento de la fila para cédula $row[0]: " . $e->getMessage() . "<br>";
                        }
                    }

                    echo '</table>';

                    // Establecer el mensaje de confirmación después del procesamiento
                    $confirma_data = "Carga de datos exitosa";
                    if ($isSimplifiedFormat) {
                        $confirma_data .= " (formato simplificado)";
                        $debug_info .= "Se utilizó el formato simplificado. Los datos adicionales se tomaron del curso seleccionado.<br>";
                    }
                } catch (Exception $e) {
                    $confirma_data = "Error al procesar el archivo: " . $e->getMessage();
                    $debug_info = "Error al procesar el archivo: " . $e->getMessage();
                }
            } else {
                // Si no se puede subir el archivo, mostramos un mensaje de error detallado
                $error_message = "Error al subir el archivo después de $maxRetries intentos. ";
                
                // Verificar permisos de la carpeta de destino
                if (!is_writable($uploadDir)) {
                    $error_message .= "La carpeta de destino no tiene permisos de escritura. ";
                }
                
                // Verificar espacio en disco
                if (disk_free_space('/') < $fileSize) {
                    $error_message .= "No hay suficiente espacio en disco. ";
                }
                
                // Mostrar el error del sistema
                $error_message .= "Error del sistema: " . error_get_last()['message'];
                
                echo $error_message;
                $confirma_data = $error_message;
            }
        }
    }
}

mysqli_close($conn);
?>
</div>
</div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrar_pantalla()">Cerrar</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>
         -->  
    </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../js/demo/datatables-demo.js"></script>

<!-- Script para mostrar el modal automáticamente si hay datos procesados -->
<script>
$(document).ready(function() {
    <?php if(isset($_FILES['excelFile'])): ?>
    $('#exampleModal').modal('show');
    <?php endif; ?>
    
    // Mostrar el nombre del archivo cuando se selecciona
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
    
    // Función para actualizar los campos del formulario según el curso seleccionado
    function actualizarCamposFormulario() {
        var cursoId = $('#curso').val();
        
        if (!cursoId) {
            // Si no hay curso seleccionado, limpiar los campos
            $('#nivel').val('');
            $('#cur').val('');
            $('#jorna').val('');
            $('#paralelo').val('');
            $('#tutor').val('');
            return;
        }
        
        // Petición AJAX para obtener los datos del curso
        $.ajax({
            url: 'obtener_datos_curso.php',
            type: 'POST',
            data: { id_jornada_curso: cursoId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Actualizar los campos con los datos recibidos
                    $('#nivel').val(response.data.nivel);
                    $('#cur').val(response.data.curso);
                    $('#jorna').val(response.data.jornada);
                    $('#paralelo').val(response.data.paralelo);
                    $('#tutor').val(response.data.tutor);
                } else {
                    alert('Error al obtener datos del curso: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la conexión al servidor: ' + error);
            }
        });
    }
    
    // Asignar evento change al select de curso
    $('#curso').on('change', actualizarCamposFormulario);
    
    // Actualizar campos al cargar la página si hay un curso seleccionado
    if ($('#curso').val()) {
        actualizarCamposFormulario();
    }
});
</script>
</body>
</html>