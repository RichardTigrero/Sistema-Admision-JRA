 <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../cssss/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom styles for this page    
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
  
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    
     <!-- Bootstrap core JavaScript 
    <script src="../vendor/jquery/jquery.min.js"></script>-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    -->
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins 
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
     -->
     
    <!-- Page level custom scripts  
    <script src="../js/demo/datatables-demo.js"></script> -->
    <script src="https://datatables.net/examples/resources/demo.js"></script>
<script language="javascript">
$(document).ready(function(){
    
    
var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [  'excel', 'pdf', 'colvis' ]
    } );
    
 table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
   
       
});

function visualizar_documento(param_id, param_nombres) {
    const featuresString = 'height=800,width=1000,left=100,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,titlebar=no,toolbar=no,top=100';
    const newWindow = window.open(`visualizar_documento.php?est_id=${param_id}`, 'VisualizadorDocumento', featuresString);
    
    if (newWindow) {
        newWindow.focus();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Bloqueador de ventanas detectado',
            text: 'Por favor, permita las ventanas emergentes para este sitio para visualizar el documento.',
            confirmButtonText: 'Entendido'
        });
    }
}
</script>


<?php


session_start();
include("../conexion/conexion.php");


$codigo_curso_est = "";
$codigo_curso_est = $_POST["id_curso_det"];
 

?>


 <!-- Begin Page Content -->
                <div id="area_dinamica" >

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800 d-flex justify-content-center">Registro de Estudiante</h1>

                    <!-- DataTales Example -->
<!--                    <div class="card shadow mb-4">-->
<!--                        <div class="card-header py-3">-->
<!--                            <button type="button" -->
<!--                             onclick="carga_masiva()"-->
<!--                            class="btn btn-outline-primary">-->
<!--                            <b><i class='bi bi-journal-plus'></i> Crear</b>-->
<!--</button>-->
<!--                        </div>-->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                                <thead>
                                          <tr>
                                            <th>Estado</th>
                                            <th>Cedula</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                            <!--<th>Nivel Educacion</th>-->
                                            <th>Curso 2025</th>
                                            <th>Jornada</th>
                                            <th>Paralelo</th>
                                            <!--<th>Repite</th>-->
                                            <th>Tutor</th>
                                            <th>Acciones</th>  
                                        </tr>
                                </thead>
                                <tfoot>
                                          <tr>
                                            <th>Estado</th>
                                            <th>Cedula</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                           <!-- <th>Nivel Educacion</th>-->
                                            <th>Curso 2025</th>
                                            <th>Jornada</th>
                                            <th>Paralelo</th>
                                            <!--<th>Repite</th>-->
                                            <th>Tutor</th>
                                            <th>Acciones</th>
                                          </tr>
                               </tfoot>
                                    <tbody>
                                        <?php
                                        
                                    if ($codigo_curso_est=="0")
                                    {
                                         $sql = "SELECT a.est_id,b.*, concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso 
                                            FROM estudiantes a, est_datos b , jornada_curso c
                                            WHERE b.infaca_jornada_curso = c.id_jornada_curso
                                            AND   a.est_cedula = b.dtest_cedula ;";
                                    }else
                                    {
                                        $sql = "SELECT a.est_id,b.*,concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso 
                                            FROM estudiantes a, est_datos b , jornada_curso c
                                            WHERE b.infaca_jornada_curso = c.id_jornada_curso
                                            AND   a.est_cedula = b.dtest_cedula
                                            and b.infaca_jornada_curso = '$codigo_curso_est' ;";
                                        
                                    }
                                    
                                            
                                   
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                        //echo "<tr> <td>" . $row["dtest_cedula"]. "</td> <td> " . $row["dtest_apellidos"]. "</td> <td> " . $row["dtest_nombres"]. "</td> <td> " . $row["infaca_nivel_edu"]. "</td> <td> " . $row["infaca_curso_act"]. "</td> <td> " . $row["infaca_jornada_archivo"]. "</td> <td> " . $row["infaca_paralelo"]. "</td> <td> " . $row["infaca_repite"]. "</td> <td> " . $row["infaca_tutorcurso"]. "</td> <td> <button type='button' onclick= modificar_producto('". $row["est_id"]."') class='btn btn-outline-success' value=" . $row["est_id"]. "><i class='bi bi-pencil-square'></i> Modificar</button> <button type='button' onclick=eliminar_producto('". $row["est_id"]."') class='btn btn-outline-danger' value=" . $row["est_id"]. " ><i class='bi bi-trash-fill'></i>Eliminar</button></td> <tr>";
                                        //echo "<tr> <td>" . $row["dtest_cedula"]. "</td> <td> " . $row["dtest_apellidos"]. "</td> <td> " . $row["dtest_nombres"]. "</td>  <td> " . $row["infaca_curso_act"]. "</td> <td> " . $row["infaca_jornada_archivo"]. "</td> <td> " . $row["infaca_paralelo"]. "</td> <td> " . $row["infaca_tutorcurso"]. "</td> <td> <button type='button' onclick= modificar_producto('". $row["est_id"]."') class='btn btn-outline-success' value=" . $row["est_id"]. "><i class='bi bi-pencil-square'></i><b> Modificar</b></button> <button type='button' onclick=eliminar_producto('". $row["est_id"]."') class='btn btn-outline-danger' value=" . $row["est_id"]. " ><i class='bi bi-trash-fill'></i><b>Eliminar</b></button></td> </tr>";
                                        //echo "<tr><td><b>".$row["dtest_estado_reg"]."</b></td> <td>" . $row["dtest_cedula"]. "</td> <td> " . $row["dtest_apellidos"]. "</td> <td> " . $row["dtest_nombres"]. "</td>  <td> " . $row["infaca_curso_act"]. "</td> <td> " . $row["infaca_jornada_archivo"]. "</td> <td> " . $row["infaca_paralelo"]. "</td> <td> " . $row["infaca_tutorcurso"]. "</td> <td> 
                                        //<button type='button' onclick= visualizar_estudiante('". $row["dtest_id"]."') class='btn btn-outline-success' value=" . $row["dtest_id"]. "><b><i class='bi bi-eye'></i></b></button> </td> </tr>";

                                        // Preparar el nombre completo para el estudiante con seguridad
                                        $nombreCompleto = htmlspecialchars($row["dtest_nombres"] . ' ' . $row["dtest_apellidos"], ENT_QUOTES, 'UTF-8');
                                        
                                        // Preparar el botón de visualizar estudiante
                                        $viewStudentBtn = '<button type="button" onclick="visualizar_estudiante(\'' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '\')" class="btn btn-outline-success" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-card-checklist"></i></b></button>';
                                        
                                        // Preparar el botón de visualizar documentos
                                        $viewDocBtn = '<button type="button" onclick="visualizar_documento(\'' . htmlspecialchars($row["est_id"], ENT_QUOTES, 'UTF-8') . '\',\'' . $nombreCompleto . '\')" class="btn btn-outline-warning" value="' . htmlspecialchars($row["dtest_id"], ENT_QUOTES, 'UTF-8') . '"><b><i class="bi bi-eye-fill"></i></b></button>';
                                        
                                        // Generar la fila de la tabla usando comillas simples para evitar problemas de escape
                                        echo '<tr>
                                            <td><b>' . htmlspecialchars($row["dtest_estado_reg"], ENT_QUOTES, 'UTF-8') . '</b></td>
                                            <td>' . htmlspecialchars($row["dtest_cedula"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_apellidos"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["dtest_nombres"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_curso_act"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_jornada_archivo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_paralelo"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row["infaca_tutorcurso"], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . $viewStudentBtn . ' ' . $viewDocBtn . '</td>
                                        </tr>';



                                    }
                                    } else {
                                    echo "0 results";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                     <!--   
                                        <tr>
                                            <td>0963213194</td>
                                            <td>ALEJANDRO TORO</td>
                                            <td>AYLEEN ROMINA</td>
                                            <td>INICIAL </td>
                                            <td>INICIAL 1 </td>
                                            <td>MATUTINA </td>
                                            <td>A </td>
                                            <td>NO </td>
                                            <td>YAQUELINE ECHEVERRIA  </td>
                                        </tr>
                                        -->  

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->




        </div>
        <!-- End of Content Wrapper -->
        
