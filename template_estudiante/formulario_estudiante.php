<?php
// Start the session
session_start();
include("data_estudiantes.php")

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
    <title>inicio</title>

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
    function cerrar_sesion()
    {
        //alert("Cerrar Session");
        window.open("logout.php","_self");
    }
    
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
    
    function subir_foto(param_id, param_nombres) {
        const featuresString = 'height=700,width=800,left=200,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,titlebar=no,toolbar=no,top=100';
        const newWindow = window.open(`subir_foto.php?est_id=${param_id}&nombre=${encodeURIComponent(param_nombres)}`, 'SubirFoto', featuresString);
        
        if (newWindow) {
            newWindow.focus();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Bloqueador de ventanas detectado',
                text: 'Por favor, permita las ventanas emergentes para este sitio para subir la foto.',
                confirmButtonText: 'Entendido'
            });
        }
    }
    function guardar()
    {
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Guardando...',
            text: 'Procesando información',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        var cedula = document.getElementById('cedula').value;
        var id_det_estudiante = document.getElementById('id_det_estudiante').value;
        var nacionalidad = document.getElementById('nacionalidad').value;
        var genero = document.getElementById('genero').value;
        var edad = document.getElementById('edad').value;
        var celular = document.getElementById('celular').value;
        var direccion = document.getElementById('direccion').value;
        var fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
        var email = document.getElementById('email').value;
        var institucion_prev = document.getElementById('institucion_prev').value;
        var tutor = document.getElementById('tutor').value;

        var cedula_rep = document.getElementById('cedula_rep').value;
        var nombres_a_rep = document.getElementById('nombres_a_rep').value;
        var celular_rep = document.getElementById('celular_rep').value;
        var convencional_rep = document.getElementById('convencional_rep').value;
        var parentezco_rep = document.getElementById('parentezco_rep').value;
        var email_rep = document.getElementById('email_rep').value;

        var cedula_m = document.getElementById('cedula_m').value;
        var vive_c_mama2 = document.getElementById('vive_c_mama').value;
        var vive_c_mama = "";
        if (document.getElementById('vive_c_mama').checked==true)
        {
            vive_c_mama = "SI"; 
        }else
        {
            vive_c_mama = "NO";      
        }
        var nombres_a_m = document.getElementById('nombres_a_m').value;
        var celular_m = document.getElementById('celular_m').value;
        var convencional_mama = document.getElementById('convencional_mama').value;
        var email_m = document.getElementById('email_m').value;

        var cedula_p = document.getElementById('cedula_p').value;
        var vive_c_papa2 = document.getElementById('vive_c_papa').value;
        var vive_c_papa = "";
        if (document.getElementById('vive_c_papa').checked==true)
        {
            vive_c_papa = "SI"; 
        }else
        {
            vive_c_papa = "NO";      
        }
        var nombres_a_p = document.getElementById('nombres_a_p').value;
        var celular_p = document.getElementById('celular_p').value;
        var convencional_papa = document.getElementById('convencional_papa').value;
        var email_p = document.getElementById('email_p').value;

        var alergias = document.getElementById('alergias').value;
        var talergias = document.getElementById('talergias').value;
        var tdiscapacidad2 = document.getElementById('tdiscapacidad').value;
        var tdiscapacidad = "";
        if (document.getElementById('tdiscapacidad').checked==true)
        {
            tdiscapacidad = "SI"; 
        }else
        {
            tdiscapacidad = "NO";
        }
        var por_discapacidad = document.getElementById('por_discapacidad').value;
        var vac_covid2 = document.getElementById('vac_covid').value;
        var vac_covid = "";
        if (document.getElementById('vac_covid').checked==true)
        {
            vac_covid = "SI"; 
        }else
        {
            vac_covid = "NO";      
        }
        var ncel1 = document.getElementById('ncel1').value;
        var nomcel1 = document.getElementById('nomcel1').value;
        var ncel2 = document.getElementById('ncel2').value;
        var nomcel2 = document.getElementById('nomcel2').value;
        //var foto_est = document.getElementById('').value;

        //alert(nacionalidad+genero+edad+celular+direccion+fecha_nacimiento+email+institucion_prev+tutor);
        param = "?nacionalidad="+nacionalidad+"&genero="+genero+"&edad="+edad+"&celular="+celular+"&direccion="+direccion+"&fecha_nacimiento="+fecha_nacimiento+"&email="+email+"&institucion_prev="+institucion_prev+"&tutor="+tutor;
        param2 = "&cedula_rep="+cedula_rep+"&nombres_a_rep="+nombres_a_rep+"&celular_rep="+celular_rep+"&convencional_rep="+convencional_rep+"&parentezco_rep="+parentezco_rep+"&email_rep="+email_rep;
        param3 = "&cedula_m="+cedula_m+"&vive_c_mama="+vive_c_mama+"&nombres_a_m="+nombres_a_m+"&celular_m="+celular_m+"&convencional_mama="+convencional_mama+"&email_m="+email_m; 
        param4 = "&cedula_p="+cedula_p+"&vive_c_papa="+vive_c_papa+"&nombres_a_p="+nombres_a_p+"&celular_p="+celular_p+"&convencional_papa="+convencional_papa+"&email_p="+email_p;
        param5 = "&alergias="+alergias+"&talergias="+talergias+"&tdiscapacidad="+tdiscapacidad+"&por_discapacidad="+por_discapacidad+"&vac_covid="+vac_covid+"&ncel1="+ncel1+"&nomcel1="+nomcel1+"&ncel2="+ncel2+"&nomcel2="+nomcel2;
        param6 = "&cedula="+cedula+"&id_det_estudiante="+id_det_estudiante;
        
        // Crear una solicitud fetch para llamar a la API
        fetch("grabar_datos_estudiantes.php" + param + param2 + param3 + param4 + param5 + param6)
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
                        // Recargar la página después de mostrar el mensaje
                        window.location.reload();
                    });
                } else {
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Ocurrió un error al guardar los datos'
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
</script>
<body id="page-top">
<form id="formulario_est">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="bi bi-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Menu</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="bi bi-bank"></i></i>
                    <span>UE Jaime Roldos Aguilera</span></a>
            </li>

           
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                opciones
            </div>
            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="formulario_estudiante.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Datos personales</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombres_estudiante"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="cerrar_sesion()" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container container-fluid">
                    <div class="d-flex justify-content-center row"><h1>Formulario de estudiante</h1></div>
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
                                        <input id="id_jornada" hidden="hidden" value="<?php echo $_SESSION["codigo_jornada_curso"] ?>"/>
                                        <input type="text" class="form-control " 
                                               id="nombre_jornada" readonly="true" value="<?php echo $_SESSION["nombre_jornada_curso"];?>">
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
                                        <div class="form-group col-lg-3 col-md-6">
                                        <label for="nombres">Nombres:</label>
                                        <input type="text" class="form-control " 
                                               id="nombres" readonly="true" placeholder="Nombres" value="<?php echo $_SESSION["nombres_estudiante"];?>">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6">
                                        <label for="apellidos">Apellidos:</label>
                                        <input type="text" class="form-control " 
                                               id="apellidos" readonly="true" placeholder="Apellidos" value="<?php echo $_SESSION["apellidos_estudiante"];?>">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6">
                                            <label for="cedula">Cedula:</label>
                                            <input type="text" class="form-control " 
                                            id="cedula" readonly="true" placeholder="cedula" value="<?php echo $_SESSION["cedula_estudiante"];?>">
                                            <input id="id_det_estudiante" hidden="hidden" value="<?php echo $_SESSION["id_det_estudiante"] ?>"/>
                                        </div>
                                        <!-- RTIGRERO SE desabilita temporalemte el boton de la foto
                                        <div class="form-group col-lg-3 col-md-6 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary" onclick="subir_foto('<?php echo $_SESSION["id_det_estudiante"]; ?>', '<?php echo htmlspecialchars($_SESSION["nombres_estudiante"] . ' ' . $_SESSION["apellidos_estudiante"], ENT_QUOTES, 'UTF-8'); ?>')">
                                                <i class="bi bi-camera-fill"></i> Subir foto
                                            </button>
                                        </div>
                                        -->
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-6">
                                                <label for="exampleInputEmail1">Nacionalidad:</label>
                                                <input type="text" class="form-control " 
                                                       id="nacionalidad" 
                                                       placeholder="Nacionalidad"
                                                       value="<?php echo $bdnacionalidad; ?>"
                                                       >
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="genero">Género</label>
                                                <select id="genero" class="form-control">
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
                                                  placeholder="Edad"
                                                  value="<?php echo $bdedad; ?>">
                                            </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-6">
                                                <label for="celular">Celular:</label>
                                                <input type="text" class="form-control " 
                                                     id="celular"  
                                                     placeholder="celular"
                                                     value="<?php echo $bdcelular; ?>">
                                            </div>     
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="direccion">Dirección:</label>
                                                <input type="text" class="form-control " 
                                                    id="direccion" 
                                                    placeholder="Direccion"
                                                    value="<?php echo $bddireccion; ?>">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                                <input type="date" class="form-control " 
                                                    id="fecha_nacimiento"
                                                    value="<?php echo $bdfecha_nacimiento; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 col-md-4">
                                                <label for="email">Correo Electrónico:</label>
                                                <input type="text" class="form-control " 
                                                       id="email"  
                                                       placeholder="Correo Electronico"
                                                       value="<?php echo $bdemail; ?>">
                                            </div>     
                                            <div class="form-group col-lg-4 col-md-4">
                                                <label for="institucion_prev">Institución Educativa que proviene:</label>
                                                <input type="text" class="form-control " 
                                                       id="institucion_prev" 
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
                                                <label class="form-check-label" for="repite_anio">Repite año</label> 
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
                                                       id="cedula_rep"  placeholder="Cédula"
                                                       value="<?php echo $bdcedula_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="nombres_a_rep">Nombres y  Apellidos:</label>
                                                <input type="text" class="form-control " 
                                                       id="nombres_a_rep" 
                                                       placeholder="Nombres y Apellido"
                                                       value="<?php echo $bdnombres_a_rep; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                                <label for="celular_rep">Celular:</label>
                                                <input type="text" class="form-control " 
                                                      id="celular_rep"  placeholder="Celular"
                                                      value="<?php echo $bdcelular_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="convencional_rep">Telf. Convencional:</label>
                                                <input type="text" class="form-control " 
                                                       id="convencional_rep" placeholder="telf."
                                                       value="<?php echo  $bdconvencional_rep; ?>">
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-12">
                                                <label for="parentezco_rep">Parentezco:</label>
                                                <input type="text" class="form-control " 
                                                      id="parentezco_rep"  placeholder="Parentezco"
                                                      value="<?php echo $bdparentezco_rep; ?>">
                                            </div>     
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label for="email_rep">Email:</label>
                                                <input type="text" class="form-control " 
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
                                                            id="vive_c_mama" name="vive_c_mama" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_mama" name="vive_c_mama">
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
                                                   placeholder="Nombres y Apellidos"
                                                   value="<?php echo $bdnombres_a_m; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="celular_m">Celular:</label>
                                            <input type="text" class="form-control " 
                                                   id="celular_m"  placeholder="Celular"
                                                   value="<?php echo $bdcelular_m; ?>">
                                        </div>  
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                            <label for="convencional_mama">Telf. Convencional:</label>
                                            <input type="text" class="form-control " 
                                                 id="convencional_mama"  placeholder="Telf"
                                                 value="<?php echo $bdconvencional_mama; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="email_m">Correo Electronico:</label>
                                            <input type="text" class="form-control " 
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
                                                            id="vive_c_papa" name="vive_c_papa" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vive_c_papa" name="vive_c_papa">
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
                                                   value="<?php echo $bdnombres_a_p; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="celular_p">Celular:</label>
                                            <input type="text" class="form-control " 
                                                   id="celular_p"  placeholder="Celular"
                                                   value="<?php echo $bdcelular_p; ?>">
                                        </div>  
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                            <label for="convencional_papa">Telf. Convencional:</label>
                                            <input type="text" class="form-control " 
                                                 id="convencional_papa"  placeholder="Telf"
                                                 value="<?php echo $bdconvencional_papa; ?>">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="email_p">Correo Electronico:</label>
                                            <input type="text" class="form-control " 
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
                                                       value="<?php echo $bdalergias; ?>">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6">
                                                <label for="talergias">Tipo de Alergia:</label>
                                                <input type="text" class="form-control " 
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
                                                            id="tdiscapacidad" name="tdiscapacidad" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="tdiscapacidad" name="tdiscapacidad">
                                                    <?php

                                                }
                                                ?>
                                            <label for="tdiscapacidad">Tiene Carnet de discapacidad:</label>
                                                
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4">
                                                <label for="por_discapacidad">% Discapacidad Y Tipo:</label>
                                                <input type="text" class="form-control " 
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
                                                            id="vac_covid" name="vac_covid" checked>
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="vac_covid" name="vac_covid">
                                                    <?php

                                                }
                                                ?>
                                         <label for="vac_covid"> Vacuna Covid 19:</label><br>
                                          
                                        </div>                   
                                    </div>
                
                                    <div class="row"> 
                                        <legend>Datos Emergencia:</legend>                                             
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="ncel1">Número de Celular1:</label>
                                                <input type="text" class="form-control " 
                                                       id="ncel1" placeholder="Numero 1"
                                                       value="<?php echo $bdncel1; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="nomcel1">Nombres del Contacto:</label>
                                                <input type="text" class="form-control " 
                                                       id="nomcel1" placeholder="Nombres del contacto"
                                                       value="<?php echo $bdnomcel1; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="ncel2">Número de Celular2:</label>
                                                <input type="text" class="form-control " 
                                                       id="ncel2" placeholder="Numero 2"
                                                       value="<?php echo $bdncel2; ?>">
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3">
                                                <label for="nomcel2">Nombres del Contacto:</label>
                                                <input type="text" class="form-control " 
                                                       id="nomcel2" placeholder="Nombres del contacto"
                                                       value="<?php echo $bdnomcel2 ; ?>">
                                            </div>
                                    </div>
                                    
            
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-secondary" onclick="guardar()">Guardar
                                        <i class="fas fa-info-circle"></i></button>
                                        <!--<button class="btn btn-primary">Imprimir</button>-->
                                        <a class="btn bg-gradient-info text-light"
                                            href="ficha_de_matricula_final.php"  target="_blank" title="Ficha de matricula" >Imprimir
                                        <i class="fas fa-file-pdf"></i></a>

                                        <button type="button" class="btn btn-warning" onclick="visualizar_documento('<?php echo $_SESSION['id_det_estudiante']; ?>', '<?php echo htmlspecialchars($_SESSION['nombres_estudiante'] . ' ' . $_SESSION['apellidos_estudiante'], ENT_QUOTES, 'UTF-8'); ?>')">
                                            Documentos Adjuntos <i class="fas fa-file-alt"></i>
                                        </button>
                                        
                                    </div>
                                </div>
                              </div>
                              </form>
                    </div>
                </div>

            </div>


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

   
</form>
</body>
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
</html>
