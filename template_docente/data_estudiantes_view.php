<?php
session_start();
include("../conexion/conexion.php");

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
    <title>Principal Estudiantes</title>

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

    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<script>
function carga_data()
{
    
    var ModalEdit = new bootstrap.Modal(data_visualizar_estudiantes, {}).show();
    //window.resizeTo(640,480);
}
function cerrar_pantalla()
{
    window.close();
}

function cerrar_sesion()
{
    $('#logoutModal').modal('show');
}

function validar_udp_est()
{
    // Mostrar confirmación antes de validar
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Desea validar los datos de este estudiante?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, validar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Validando...',
                text: 'Procesando información',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            var id_det_estudiante = document.getElementById('id_det_estudiante').value;
            var cedula = document.getElementById('cedula').value;
            
            // Crear una solicitud fetch para llamar a la API
            fetch("validar_estudiante.php?id_det_estudiante=" + id_det_estudiante + "&cedula=" + cedula)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    // Cerrar el indicador de carga
                    Swal.close();
                    
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            showConfirmButton: true,
                            timer: 2000
                        }).then(() => {
                            // Cerrar la ventana después de mostrar el mensaje
                            window.close();
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ocurrió un error al validar los datos'
                        });
                    }
                })
                .catch(error => {
                    // Cerrar el indicador de carga y mostrar error
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error en la comunicación con el servidor: ' + error.message
                    });
                });
        }
    });
}

</script>


<body onload="carga_data();">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo isset($_SESSION["nombres_doc"]) ? $_SESSION["nombres_doc"] : 'Usuario'; ?></span>
                            <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="cerrar_sesion()">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->

<?php

$det_estudiante = $_GET['id_detalle'];
$descr_observa_per = "";

$sql_periodo_activo = "SELECT descripcion, observacion
                       FROM periodo_lectivo
                       WHERE estado = 'ACTIVO'
                       ORDER BY id_periodo_lectivo DESC
                       LIMIT 1";
$result_periodo_activo = mysqli_query($conn, $sql_periodo_activo);

if ($result_periodo_activo && mysqli_num_rows($result_periodo_activo) > 0) {
    $row_periodo_activo = mysqli_fetch_assoc($result_periodo_activo);
    $descr_observa_per = trim((string) $row_periodo_activo["observacion"]);

    if ($descr_observa_per === "") {
        $descr_observa_per = trim((string) $row_periodo_activo["descripcion"]);
    }
}

$sql = "SELECT a.est_id,a.est_nombres,a.est_apellidos,a.est_cedula
       ,b.*, concat(c.nivel,' ',c.jornada,' ',c.curso,' ',c.paralelo) as nombre_jornada_curso 
FROM estudiantes a, est_datos b , jornada_curso c
WHERE b.infaca_jornada_curso = c.id_jornada_curso
AND   a.est_cedula = b.dtest_cedula and  b.dtest_id='".$det_estudiante."'";
$result = mysqli_query($conn, $sql);
$bdgenero = "0";
$bg_genero_dat = "";
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //echo "id: " . $row["est_id"]. "<br>";
    $dat_id_detalle_estudiante = $row["dtest_id"];
    $dat_est_id = $row["est_id"];
    $dat_nombre_jornada_curso = $row["nombre_jornada_curso"];
    
    $nombres_estudiantes = strtoupper($row["est_nombres"]);
    $apellidos_estudiantes = strtoupper($row["est_apellidos"]);
    $cedula_estudiantes = strtoupper($row["est_cedula"]);

    $bdnacionalidad = strtoupper($row["dtest_nacionalidad"]);
    $bdgenero = strtoupper($row["dtest_genero"]);
    if ($bdgenero == "0")
    {
        $bg_genero_dat = "";
    }
    if ($bdgenero == "1")
    {
        $bg_genero_dat = strtoupper("Masculino");
    }
    if ($bdgenero == "2")
    {
        $bg_genero_dat = strtoupper("Femenino");
    }
    $bdedad = strtoupper($row["dtest_edad"]);
    $bdcelular = strtoupper($row["dtest_celular"]);
    $bddireccion = strtoupper($row["dtest_direccion"]);
    $bdfecha_nacimiento = $row["dtest_fnnacimiento"];
    $bdemail = strtoupper($row["dtest_gmail"]);
    $bdinstitucion_prev = strtoupper($row["dest_institucion_prev"]);
    

    // data nueva desde el archivo de carga
    $jornada_curso_act = strtoupper($row["infaca_jornada_curso"]);
    $nivel_educacion = strtoupper($row["infaca_nivel_edu"]);
    $curso =  strtoupper($row["infaca_curso_act"]);
    $jornada =  strtoupper($row["infaca_jornada_archivo"]);
    $paralelo =  strtoupper($row["infaca_paralelo"]);
    $repite_anio =  strtoupper($row["infaca_repite"]);
    $bdtutor = strtoupper($row["infaca_tutorcurso"]);
    //
    $bdcedula_rep = strtoupper($row["infrepre_cedula"]);
    $bdnombres_a_rep = strtoupper($row["infrepre_nomape"]);
    $bdcelular_rep = strtoupper($row["infrepre_clular"]);
    $bdconvencional_rep = strtoupper($row["infrepre_convencional"]);
    $bdparentezco_rep = strtoupper($row["infrepre_parentezco"]);
    $bdemail_rep = strtoupper($row["infrepre_gmail"]);

    $bdcedula_m = strtoupper($row["infmadre_cedula"]);
    $bdvive_c_mama = strtoupper($row["infmadre_vivemadre"]);
    $bdnombres_a_m = strtoupper($row["infmadre_nomape"]);
    $bdcelular_m = strtoupper($row["infmadre_celular"]);
    $bdconvencional_mama = strtoupper($row["infmadre_convencional"]);
    $bdemail_m = strtoupper($row["infmadre_gmail"]);

    $bdcedula_p = strtoupper($row["infpadre_cedula"]);
    $bdvive_c_papa = strtoupper($row["infpadre_vivepadre"]);
    $bdnombres_a_p = strtoupper($row["infpadre_nomap"]);
    $bdcelular_p = strtoupper($row["infpadre_celular"]);
    $bdconvencional_papa = strtoupper($row["infpadre_convencional"]);
    $bdemail_p = strtoupper($row["infpadre_gmail"]);

    $bdalergias = strtoupper($row["estsalud_alergias"]);
    $bdtalergias = strtoupper($row["estsalud_tipoalerg"]);
    $bdtdiscapacidad = strtoupper($row["estsalud_discapatipo"]);
    $bdpor_discapacidad = strtoupper($row["estsalud_carnet"]);
    $bdvac_covidv = strtoupper($row["estsalud_vacuna19"]);
    
    $bdncel1 = strtoupper($row["estemergencia_numerocell1"]);
    $bdnomcel1 = strtoupper($row["estemergencia_nombre1"]);
    $bdncel2 = strtoupper($row["estemergencia_numcell2"]);
    $bdnomcel2 = strtoupper($row["estemergencia_nombre2"]);
    
  }
}

?>



<div class="modal fade" id="data_visualizar_estudiantes" tabindex="-1" role="dialog" aria-labelledby="data_visualizar_estudiantes" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de estudiante (Visualizar)   </b></h5>
              <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>-->
              <button type="button" class="btn btn-outline-success" onclick="validar_udp_est()"><i class="bi bi-calendar-check-fill"></i> Validar
                                        </a>
              </button>
              <button type="button" class="btn btn-outline-danger" data-dismiss="modal" onclick="cerrar_pantalla()"><i class="bi bi-archive"></i> Cerrar Pantalla</button>
            </div>
            
            <form method="POST">
                
                <div class="container container-fluid">
                   <!-- <div class="d-flex justify-content-center row"><h1>Formulario de estudiante</h1></div>-->
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Información del estudiante</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Información académica</a>
                                </li> 
                                <li class="nav-item">
                                  <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Información del representante</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-x-tab" data-toggle="pill" href="#pills-x" role="tab" aria-controls="pills-contact" aria-selected="false">Información de la madre </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-d-tab" data-toggle="pill" href="#pills-d" role="tab" aria-controls="pills-contact" aria-selected="false">Información del padre</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-a-tab" data-toggle="pill" href="#pills-a" role="tab" aria-controls="pills-contact" aria-selected="false">Información de salud</a>
                                </li>
                              </ul>
                              <form>
                              <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                   <div class="row">
                                   
                                   </div> 
                                   <div class="row">
                                   <div class="form-group col-lg-4 col-md-6">
                                        <label for="nombre_jornada">Jornada - Curso:</label>
                                       <input id="id_jornada_est" hidden="hidden" value="<?php echo $jornada_curso_act;?>"/>
                                        <input type="text" class="form-control " 
                                               id="nombre_jornada" readonly="true"  hidden="hidden" value="<?php echo  $dat_nombre_jornada_curso;?>">
                                               
                                              <select name="id_jornada" readonly="true" id="id_jornada" class="form-control">
                                                  <?php
                                                  
                                                    $sqln = "SELECT id_jornada_curso, concat(nivel,' ',jornada,' ',curso,' ',paralelo) as nombre_jornada_curso FROM jornada_curso";
                                                    $result = mysqli_query($conn, $sqln);
                                                    
                                                    if (mysqli_num_rows($result) > 0) {
                                                      // output data of each row
                                                      while($row = mysqli_fetch_assoc($result)) {
                                                        $codigo_cur = $row["id_jornada_curso"];
                                                        $nombre_cur = $row["nombre_jornada_curso"];
                                                        
                                                        if ($codigo_cur == $jornada_curso_act){
                                                         ?>
                                                            <option selected value="<?php echo  $codigo_cur; ?>"><?php echo $nombre_cur; ?></option>
                                                         <?php 
                                                        }else{
                                                        
                                                        ?>
                                                           <option value="<?php echo  $codigo_cur; ?>"><?php echo $nombre_cur; ?></option>
                                                           
                                                        <?php
                                                        }
                                                      }
                                                    } else {
                                                      echo "0 results";
                                                    }
                                                    
                                                    
                                                    
                                                     //mysqli_close($conn);
                                                  ?>
                                                </select>  
                                        </div>
                                        <!--
                                        <div class="form-group ">
                                            <label for="File1">Foto del estudiante</label>
                                            <input type="file" class="form-control-file" 
                                                   id="File1">
                                        </div>
                                        -->
                                    </div>
                                    <div class="row">       
                                        <div class="form-group col-lg-4 col-md-6">
                                        <label for="nombres">Nombres:</label>
                                        <input type="text" class="form-control " 
                                               readonly="true"
                                               id="nombres" placeholder="Nombres" value="<?php echo $nombres_estudiantes;?>">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6">
                                        <label for="apellidos">Apellidos:</label>
                                        <input type="text" class="form-control " 
                                               readonly="true"
                                               id="apellidos" placeholder="Apellidos" value="<?php echo $apellidos_estudiantes;?>">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6">
                                            <label for="cedula">Cedula:</label>
                                            <input type="text" class="form-control " 
                                            id="cedula" readonly="true" placeholder="cedula" value="<?php echo $cedula_estudiantes;?>">
                                            <input id="id_det_estudiante" hidden="hidden" value="<?php echo $det_estudiante; ?>"/>
                                            <input id="id_est_estudiante" hidden="hidden" value="<?php echo $dat_est_id;?>"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-6">
                                                <label for="exampleInputEmail1">Nacionalidad:</label>
                                                <input type="text" class="form-control " 
                                                       id="nacionalidad" 
                                                       readonly="true"
                                                       placeholder="Nacionalidad"
                                                       value="<?php echo $bdnacionalidad; ?>"
                                                       >
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="genero">Género</label>
                                                <select id="genero"  readonly="true" class="form-control">
                                                            <?php 

                                                               if ($bdgenero=="1")
                                                               {
                                                                    ?>
                                                                        <option value="1" selected>Masculino</option>
                                                                        <option value="2" >Femenino</option>
                                                                        <option value="0" >Seleccione</option>
                                                                    <?php
                                                               }

                                                               if ($bdgenero=="2")
                                                               {
                                                                ?>
                                                                     <option value="2" selected>Femenino</option>
                                                                     <option value="1" >Masculino</option>
                                                                     <option value="0" >Seleccione</option>
                                                                <?php
                                                               }

                                                               if ($bdgenero=="" or $bdgenero=="0")
                                                               {
                                                                ?>
                                                                        <option value="0" selected>Seleccione</option> 
                                                                        <option value="1" >Masculino</option>
                                                                        <option value="2" >Femenino</option>
                                                             <?php

                                                               }
                                                            
                                                            ?>
                                                  
                                                  
                                                  
                                                </select>
                                            </div>      
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="edad">Edad:</label>
                                                <input type="text" class="form-control " 
                                                  id="edad" 
                                                  readonly="true"
                                                  placeholder="Edad"
                                                  value="<?php echo $bdedad; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-6">
                                                <label for="celular">Celular:</label>
                                                <input type="text" class="form-control " 
                                                     id="celular"  
                                                     readonly="true"
                                                     placeholder="celular"
                                                     value="<?php echo $bdcelular; ?>">
                                            </div>     
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="direccion">Dirección:</label>
                                                <input type="text" class="form-control " 
                                                    id="direccion" 
                                                    readonly="true"
                                                    placeholder="Direccion"
                                                    value="<?php echo $bddireccion; ?>">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                                <input type="date" class="form-control " 
                                                    id="fecha_nacimiento"
                                                    readonly="true"
                                                    value="<?php echo $bdfecha_nacimiento; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-4">
                                                <label for="email">Correo Electrónico:</label>
                                                <input type="text" class="form-control " 
                                                       id="email"  
                                                       readonly="true"
                                                       placeholder="Correo Electronico"
                                                       value="<?php echo $bdemail; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-6">
                                                <label for="institucion_prev">Institución Educativa que proviene:</label>
                                                <input type="text" class="form-control " 
                                                       id="institucion_prev" 
                                                       readonly="true"
                                                       placeholder="Institucion de donde proviene"
                                                       value="<?php echo $bdinstitucion_prev; ?>">
                                            </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="row d-flex justify-content-center align-content-center">
                                        <h3>Información Académica <?php echo htmlspecialchars($descr_observa_per !== "" ? $descr_observa_per : "PERIODO ACTIVO", ENT_QUOTES, 'UTF-8'); ?></h3>
                                    </div>
                                    <div class="row">
                                           <div class="form-group col-lg-4 col-md-6">
                                                <label for="nivel">Nivel de educación:</label>
                                                <input type="text" class="form-control " 
                                                  id="nivel"  
                                                  placeholder="Nivel"
                                                  readonly="true"
                                                  value="<?php echo $nivel_educacion; ?>"
                                                  >
                                            </div>     
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="cur">Curso <?php echo htmlspecialchars($descr_observa_per !== "" ? $descr_observa_per : "PERIODO ACTIVO", ENT_QUOTES, 'UTF-8'); ?>:</label>
                                                <input type="text" class="form-control " 
                                                   id="cur" 
                                                   placeholder="Curso"
                                                   readonly="true"
                                                   value="<?php echo $curso; ?>"
                                                   >
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="jorna">Jornada:</label>
                                                <input type="text" class="form-control " 
                                                   id="jorna" 
                                                   placeholder="Matutina o Vespertina"
                                                   readonly="true"
                                                   value="<?php echo $jornada; ?>"
                                                   >
                                            </div>
                                      </div>
                                      <div class="row">
                                            <div class="form-group col-lg-4 col-md-4">
                                                <label for="paralelo">Paralelo:</label>
                                                <input type="text" class="form-control " 
                                                    id="paralelo"  
                                                    placeholder="Paralelo"
                                                    readonly="true"
                                                    value="<?php echo $paralelo; ?>"
                                                    >
                                            </div>     
                                            
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label class="form-check-label"  for="repite_anio">Repite año</label> 
                                                <br>   
                                                   <?php
                                                if ($repite_anio=="SI")
                                                {
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="repite_anio" 
                                                            name="repite_anio" 
                                                            disabled="true"
                                                            checked
                                                            >
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="repite_anio" 
                                                            name="repite_anio"
                                                            disabled="true"
                                                            >
                                                    <?php

                                                }
                                                ?>
                                                    
                                                    
                                            </div>
                                
                                            <div class="form-group col-lg-4 col-md-4">
                                                <label for="tutor">Tutor de curso:</label>
                                                <input type="text" class="form-control " 
                                                       id="tutor" placeholder="Tutor" 
                                                       readonly="true"
                                                       value="<?php echo $bdtutor; ?>"
                                                       >
                                            </div>
                                       </div>
                                   </div>
                                     


                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <h3>Información del Representante</h3>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                                <label for="cedula_rep">Cédula:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                       id="cedula_rep"  placeholder="Cédula"
                                                       value="<?php echo $bdcedula_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="nombres_a_rep">Nombres y  Apellidos:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                       id="nombres_a_rep" 
                                                       placeholder="Nombres y Apellido"
                                                       value="<?php echo $bdnombres_a_rep; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                                <label for="celular_rep">Celular:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                      id="celular_rep"  placeholder="Celular"
                                                      value="<?php echo $bdcelular_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="convencional_rep">Telf. Convencional:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                       id="convencional_rep" placeholder="telf."
                                                       value="<?php echo  $bdconvencional_rep; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                                <label for="parentezco_rep">Parentezco:</label>
                                                <input type="text" class="form-control " 
                                                      readonly="true"
                                                      id="parentezco_rep"  placeholder="Parentezco"
                                                      value="<?php echo $bdparentezco_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="email_rep">Email:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                       id="email_rep" placeholder="Email"
                                                       value="<?php echo $bdemail_rep ; ?>">
                                            </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-x" role="tabpanel" aria-labelledby="pills-x-tab">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <h3>Información de la Madre</h3>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="cedula_m">Cédula:</label>
                                            <input type="text" class="form-control " 
                                                   readonly="true"
                                                  id="cedula_m"  placeholder="Cédula"
                                                  value="<?php echo $bdcedula_m; ?>">
                                        </div>   
                                        <div class="form-group col-lg-6 col-md-12">
                                         <label for="vive_c_mama"> Vive con la madre</label><br>
                                                <?php
                                                if ($bdvive_c_mama=="SI")
                                                {
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_mama"disabled="true" name="vive_c_mama" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_mama" disabled="true" name="vive_c_mama">
                                                    <?php

                                                }
                                                ?>
                                        </div>   
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="nombres_a_m">Nombres y Apellidos:</label>
                                            <input type="text" class="form-control " 
                                                   id="nombres_a_m"  
                                                   readonly="true"
                                                   placeholder="Nombres y Apellidos"
                                                   value="<?php echo $bdnombres_a_m; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="celular_m">Celular:</label>
                                            <input type="text" class="form-control " 
                                                  readonly="true"
                                                   id="celular_m"  placeholder="Celular"
                                                   value="<?php echo $bdcelular_m; ?>">
                                        </div>  
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                            <label for="convencional_mama">Telf. Convencional:</label>
                                            <input type="text" class="form-control " 
                                                 id="convencional_mama"  placeholder="Telf"
                                                 readonly="true"
                                                 value="<?php echo $bdconvencional_mama; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="email_m">Correo Electronico:</label>
                                            <input type="text" class="form-control " 
                                                  readonly="true"
                                                  id="email_m"  placeholder="Email"
                                                  value="<?php echo $bdemail_m; ?>">
                                        </div>  
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-d" role="tabpanel" aria-labelledby="pills-d-tab">
                                <div class="row d-flex justify-content-center align-items-center">
                                        <h3>Información del Padre</h3>
                                    </div>
                                <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="cedula_p">Cédula:</label>
                                            <input type="text" class="form-control " 
                                                  readonly="true"
                                                  id="cedula_p"  placeholder="Cédula"
                                                  value="<?php echo $bdcedula_p; ?>">
                                        </div>   
                                        <div class="form-group col-lg-6 col-md-12">
                                         <label for="vive_c_papa"> Vive con el Padre</label><br>
                                         <?php
                                                if ($bdvive_c_papa=="SI")
                                                {
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_papa" disabled="true" name="vive_c_papa" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_papa" disabled="true" name="vive_c_papa">
                                                    <?php

                                                }
                                            ?>
                                                
                                        </div>   
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="nombres_a_p">Nombres y Apellidos:</label>
                                            <input type="text" class="form-control " 
                                                   id="nombres_a_p"  
                                                   placeholder="Nombres y Apellidos"
                                                   readonly="true"
                                                   value="<?php echo $bdnombres_a_p; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="celular_p">Celular:</label>
                                            <input type="text" class="form-control " 
                                                   id="celular_p"  placeholder="Celular"
                                                   readonly="true"
                                                   value="<?php echo $bdcelular_p; ?>">
                                        </div>  
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                            <label for="convencional_papa">Telf. Convencional:</label>
                                            <input type="text" class="form-control " 
                                                  readonly="true"
                                                 id="convencional_papa"  placeholder="Telf"
                                                 value="<?php echo $bdconvencional_papa; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="email_p">Correo Electronico:</label>
                                            <input type="text" class="form-control " 
                                                   readonly="true"
                                                  id="email_p"  placeholder="Email"
                                                  value="<?php echo $bdemail_p; ?>">
                                        </div>  
                                    </div> 
                                </div>
                                <div class="tab-pane fade" id="pills-a" role="tabpanel" aria-labelledby="pills-a-tab">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <h3>Información de la salud del estudiante</h3>
                                    </div>
                                
                                    <div class="row">
                                          
                                            <div class="form-group col-lg-6 col-md-6">
                                                <label for="alergias">Alergias:</label>
                                                <input type="text" class="form-control " 
                                                       id="alergias" placeholder="Alergias"
                                                       readonly="true"
                                                       value="<?php echo $bdalergias; ?>">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6">
                                                <label for="talergias">Tipo de Alergia:</label>
                                                <input type="text" class="form-control " 
                                                       readonly="true"
                                                       id="talergias" placeholder="Tipo Alergias"
                                                       value="<?php echo $bdtalergias; ?>">
                                            </div>
                                    </div>

                                    <div class="row">              
                                        <div class="form-group col-lg-4 col-md-4">
                                                       <?php
                                                if ($bdtdiscapacidad=="SI")
                                                {
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="tdiscapacidad" disabled="true" name="tdiscapacidad" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="tdiscapacidad" disabled="true" name="tdiscapacidad">
                                                    <?php

                                                }
                                                ?>
                                            <label for="tdiscapacidad">Tiene Carnet de discapacidad:</label>
                                                
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4">
                                                <label for="por_discapacidad">% Discapacidad Y Tipo:</label>
                                                <input type="text" class="form-control" readonly="true"
                                                       id="por_discapacidad"  
                                                       placeholder="% Discapacidad y Tipo"
                                                       value="<?php echo $bdpor_discapacidad; ?>">
                                        </div>  
                                        <div class="form-group col-lg-4 col-md-4">
                                                <?php
                                                if ($bdvac_covidv=="SI")
                                                {
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vac_covid" disabled="true" name="vac_covid" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vac_covid" disabled="true" name="vac_covid">
                                                    <?php

                                                }
                                                ?>
                                         <label for="vac_covid"> Vacuna Covid 19:</label><br>
                                          
                                        </div>                   
                                    </div>
                
                                    <div class="row"> 
                                        <legend>Datos Emergencia:</legend>                                             
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="ncel1">Numero de Celular1:</label>
                                                <input type="text" class="form-control " 
                                                       id="ncel1" readonly="true" placeholder="Numero 1"
                                                       value="<?php echo $bdncel1; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="nomcel1">Nombres del Contacto:</label>
                                                <input type="text" class="form-control " 
                                                       id="nomcel1" readonly="true" placeholder="Nombres del contacto"
                                                       value="<?php echo $bdnomcel1; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="ncel2">Numero de Celular2:</label>
                                                <input type="text" 
                                                       class="form-control"
                                                       readonly="true"
                                                       id="ncel2" placeholder="Numero 2"
                                                       value="<?php echo $bdncel2; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="nomcel2">Nombres del Contacto:</label>
                                                <input type="text" class="form-control " 
                                                       id="nomcel2" 
                                                       readonly="true"
                                                       placeholder="Nombres del contacto"
                                                       value="<?php echo $bdnomcel2 ; ?>">
                                            </div>
                                    </div>
                                    
            
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <!--
                                        <button type="button" class="btn btn-primary" onclick="guardar_udp_est()">Guardar
                                        <i class="fas fa-info-circle"></i></a>
                                        </button>-->
                                       <!--
                                        <a class="btn bg-gradient-info text-light"
                                            href="ficha_de_matricula_final.php"  target="_blank" title="Ficha de matricula" >Imprimir
                                        <i class="fas fa-file-pdf"></i></a>
                                        
                                          <div class="modal-footer">
                                          
                                              <button type="button" class="btn btn-outline-danger" data-dismiss="modal" onclick="cerrar_pantalla()">Close</button>
                                             
                                            </div>
                                        -->
                                    </div>
                                </div>
                              </div>
                              </form>
                    </div>
                </div>

            </div>


        </div>
                
                
                <!--
               
                -->
            </form>
          </div>
        </div>
      </div>
 
        </div>
        <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "Cerrar Sesión" a continuación si está listo para finalizar su sesión actual.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" href="logout.php">Cerrar Sesión</a>
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
</body>
</html>
