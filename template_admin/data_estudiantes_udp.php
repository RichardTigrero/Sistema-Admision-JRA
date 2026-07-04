<?php
session_start();
include("../conexion/conexion.php");

if (!function_exists('obtener_detalle_periodo_lectivo_udp')) {
    function obtener_detalle_periodo_lectivo_udp($conn, $id_periodo_lectivo)
    {
        $detalle = array(
            'estado' => '',
            'texto' => ''
        );

        $id_periodo_lectivo = (int) $id_periodo_lectivo;
        if ($id_periodo_lectivo <= 0) {
            return $detalle;
        }

        $sql_periodo = "SELECT estado, descripcion, observacion
                        FROM periodo_lectivo
                        WHERE id_periodo_lectivo = $id_periodo_lectivo
                        LIMIT 1";
        $result_periodo = mysqli_query($conn, $sql_periodo);

        if ($result_periodo && mysqli_num_rows($result_periodo) > 0) {
            $row_periodo = mysqli_fetch_assoc($result_periodo);
            $texto_periodo = trim((string) $row_periodo['observacion']);

            if ($texto_periodo === '') {
                $texto_periodo = trim((string) $row_periodo['descripcion']);
            }

            $detalle['estado'] = strtoupper(trim((string) $row_periodo['estado']));
            $detalle['texto'] = strtoupper($texto_periodo);
        }

        return $detalle;
    }
}

$periodo_seleccionado = isset($_GET['periodo']) ? (int) $_GET['periodo'] : 0;
$descr_periodo = '';

if ($periodo_seleccionado > 0) {
    $detalle_periodo = obtener_detalle_periodo_lectivo_udp($conn, $periodo_seleccionado);
    $descr_periodo = $detalle_periodo['texto'];
}

$etiqueta_periodo_form = $descr_periodo !== '' ? $descr_periodo : 'PERIODO LECTIVO';

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
async function guardar_udp_est() {
    try {
        // Recopilar todos los campos requeridos
        const camposRequeridos = {
            'cedula': document.getElementById('cedula').value,
            'nombres': document.getElementById('nombres').value,
            'apellidos': document.getElementById('apellidos').value,
            'id_est_estudiante': document.getElementById('id_est_estudiante').value,
            'id_det_estudiante': document.getElementById('id_det_estudiante').value
        };

        // Verificar campos requeridos
        for (const [campo, valor] of Object.entries(camposRequeridos)) {
            if (!valor || valor.trim() === '') {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: `El campo ${campo} es obligatorio`,
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
        }

        // Mostrar loading
        await Swal.fire({
            title: 'Guardando cambios...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Recopilar todos los datos del formulario
        const formData = new URLSearchParams({
            // Datos básicos del estudiante
            cedula: camposRequeridos.cedula,
            nombres: camposRequeridos.nombres,
            apellidos: camposRequeridos.apellidos,
            id_est_estudiante: camposRequeridos.id_est_estudiante,
            id_det_estudiante: camposRequeridos.id_det_estudiante,
            
            // Datos adicionales
            id_jornada: document.getElementById('id_jornada')?.value || '',
            nacionalidad: document.getElementById('nacionalidad')?.value || '',
            genero: document.getElementById('genero')?.value || '',
            edad: document.getElementById('edad')?.value || '',
            celular: document.getElementById('celular')?.value || '',
            direccion: document.getElementById('direccion')?.value || '',
            fecha_nacimiento: document.getElementById('fecha_nacimiento')?.value || '',
            email: document.getElementById('email')?.value || '',
            institucion_prev: document.getElementById('institucion_prev')?.value || '',
            
            // Información académica
            nivel: document.getElementById('nivel')?.value || '',
            cur: document.getElementById('cur')?.value || '',
            jorna: document.getElementById('jorna')?.value || '',
            paralelo: document.getElementById('paralelo')?.value || '',
            repite: document.getElementById('repite_anio')?.checked ? 'SI' : 'NO',
            tutor: document.getElementById('tutor')?.value || '',
            
            // Información del representante
            cedula_rep: document.getElementById('cedula_rep')?.value || '',
            nombres_a_rep: document.getElementById('nombres_a_rep')?.value || '',
            celular_rep: document.getElementById('celular_rep')?.value || '',
            convencional_rep: document.getElementById('convencional_rep')?.value || '',
            parentezco_rep: document.getElementById('parentezco_rep')?.value || '',
            email_rep: document.getElementById('email_rep')?.value || '',
            
            // Información de la madre
            cedula_m: document.getElementById('cedula_m')?.value || '',
            vive_c_mama: document.getElementById('vive_c_mama')?.checked ? 'SI' : 'NO',
            nombres_a_m: document.getElementById('nombres_a_m')?.value || '',
            celular_m: document.getElementById('celular_m')?.value || '',
            convencional_mama: document.getElementById('convencional_mama')?.value || '',
            email_m: document.getElementById('email_m')?.value || '',
            
            // Información del padre
            cedula_p: document.getElementById('cedula_p')?.value || '',
            vive_c_papa: document.getElementById('vive_c_papa')?.checked ? 'SI' : 'NO',
            nombres_a_p: document.getElementById('nombres_a_p')?.value || '',
            celular_p: document.getElementById('celular_p')?.value || '',
            convencional_papa: document.getElementById('convencional_papa')?.value || '',
            email_p: document.getElementById('email_p')?.value || '',
            
            // Información de salud
            alergias: document.getElementById('alergias')?.value || '',
            talergias: document.getElementById('talergias')?.value || '',
            tdiscapacidad: document.getElementById('tdiscapacidad')?.checked ? 'SI' : 'NO',
            por_discapacidad: document.getElementById('por_discapacidad')?.value || '',
            vac_covid: document.getElementById('vac_covid')?.checked ? 'SI' : 'NO',
            
            // Contactos de emergencia
            ncel1: document.getElementById('ncel1')?.value || '',
            nomcel1: document.getElementById('nomcel1')?.value || '',
            ncel2: document.getElementById('ncel2')?.value || '',
            nomcel2: document.getElementById('nomcel2')?.value || ''
        });

        // Realizar petición
        const response = await fetch('modificar_estudiante.php?' + formData.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });

        const data = await response.json();

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            });

            // Notificar a la ventana padre y cerrar
            if (window.opener && !window.opener.closed) {
                window.opener.postMessage('estudiante_actualizado', '*');
                setTimeout(() => window.close(), 1600);
            }
        } else {
            throw new Error(data.message || 'Error al actualizar los datos');
        }

    } catch (error) {
        console.error('Error completo:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al guardar los cambios'
        });
    }
}

// Configuración global de SweetAlert2 para mejorar accesibilidad
const swalConfig = {
    focusConfirm: true,
    returnFocus: true,
    showCloseButton: true,
    closeButtonHtml: '&times;',
    closeButtonAriaLabel: 'Cerrar'
};

// Inicialización del formulario
document.addEventListener('DOMContentLoaded', function() {
    // Asignar IDs únicos a elementos focusables
    const focusableElements = document.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    focusableElements.forEach((el, index) => {
        if (!el.id) {
            el.id = `focusable-element-${index}`;
        }
    });

    // Manejar el envío del formulario
    const form = document.getElementById('formEstudiante');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardar_udp_est();
        });
    }
});

// Función para manejar mensajes entre ventanas
window.addEventListener('message', function(event) {
    if (event.data === 'estudiante_actualizado') {
        if (window.opener && !window.opener.closed) {
            window.opener.location.reload();
        }
    }
});

function validarCampos() {
    // Campos requeridos por sección
    const camposRequeridos = {
        datosBasicos: ['nombres', 'apellidos', 'cedula', 'nacionalidad', 'genero', 'edad'],
        infoAcademica: ['nivel', 'cur', 'jorna', 'paralelo', 'tutor'],
        representante: ['cedula_rep', 'nombres_a_rep', 'celular_rep', 'parentezco_rep'],
        madre: ['cedula_m', 'nombres_a_m'],
        padre: ['cedula_p', 'nombres_a_p'],
        contactosEmergencia: ['ncel1', 'nomcel1']
    };

    // Validar campos por sección
    for (const seccion in camposRequeridos) {
        for (const campo of camposRequeridos[seccion]) {
            const elemento = document.getElementById(campo);
            if (!elemento || !elemento.value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: `Por favor complete el campo ${campo} en la sección ${seccion}`
                });
                if (elemento) elemento.focus();
                return false;
            }
        }
    }

    // Validar formato de correos electrónicos
    const camposEmail = ['email', 'email_rep', 'email_m', 'email_p'];
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    for (const campo of camposEmail) {
        const elemento = document.getElementById(campo);
        if (elemento && elemento.value.trim() && !emailRegex.test(elemento.value.trim())) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato inválido',
                text: `El correo electrónico en ${campo} no tiene un formato válido`
            });
            elemento.focus();
            return false;
        }
    }

    // Validar formato de números de teléfono
    const camposTelefono = ['celular', 'celular_rep', 'convencional_rep', 'celular_m', 
                           'convencional_mama', 'celular_p', 'convencional_papa', 'ncel1', 'ncel2'];
    const telefonoRegex = /^\d{10}$/;  // Para números de 10 dígitos

    for (const campo of camposTelefono) {
        const elemento = document.getElementById(campo);
        if (elemento && elemento.value.trim() && !telefonoRegex.test(elemento.value.trim())) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato inválido',
                text: `El número telefónico en ${campo} debe tener 10 dígitos`
            });
            elemento.focus();
            return false;
        }
    }

    return true;
}

function carga_data()
{
    
    var ModalEdit = new bootstrap.Modal(data_actualizar_estudiantes, {}).show();
    //window.resizeTo(640,480);
}

// Agregar función JavaScript para actualizar datos del curso
function actualizarInfoCurso() {
    // Obtener el ID del curso seleccionado
    var cursoId = document.getElementById('id_jornada').value;
    
    if (!cursoId) {
        // Si no hay curso seleccionado, limpiar los campos
        document.getElementById('nivel').value = '';
        document.getElementById('cur').value = '';
        document.getElementById('jorna').value = '';
        document.getElementById('paralelo').value = '';
        document.getElementById('tutor').value = '';
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del curso',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Realizar petición AJAX para obtener los datos del curso
    fetch('obtener_datos_curso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_jornada_curso=' + encodeURIComponent(cursoId)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la conexión');
        }
        return response.json();
    })
    .then(data => {
        // Cerrar el indicador de carga
        Swal.close();
        
        if (data.success) {
            // Actualizar los campos con los datos recibidos
            document.getElementById('nivel').value = data.data.nivel;
            document.getElementById('cur').value = data.data.curso;
            document.getElementById('jorna').value = data.data.jornada;
            document.getElementById('paralelo').value = data.data.paralelo;
            document.getElementById('tutor').value = data.data.tutor;
        } else {
            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo obtener la información del curso'
            });
        }
    })
    .catch(error => {
        // Cerrar el indicador de carga y mostrar error
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al obtener datos del curso'
        });
    });
}

// Ejecutar al cargar la página si hay un curso seleccionado
document.addEventListener('DOMContentLoaded', function() {
    var cursoSelect = document.getElementById('id_jornada');
    if (cursoSelect && cursoSelect.value) {
        actualizarInfoCurso();
    }
});
</script>


<body onload="carga_data();"></body> 

<?php

$det_estudiante = isset($_GET['id_detalle']) ? (int) $_GET['id_detalle'] : 0;
$sql = "SELECT a.est_id,a.est_nombres,a.est_apellidos,a.est_cedula
       ,b.*,c.periodo,c.id_periodo_lectivo as periodo_fuente_id,
       concat(c.nivel,'-',c.jornada,'-',c.curso,'-',c.paralelo) as nombre_jornada_curso 
FROM estudiantes a, est_datos b , jornada_curso c
WHERE b.infaca_jornada_curso = c.id_jornada_curso
AND   a.est_cedula = b.dtest_cedula
AND   b.dtest_id = $det_estudiante";

if ($periodo_seleccionado > 0) {
    $sql .= " AND (b.dtest_ciclo_datos = '$periodo_seleccionado'
               OR c.id_periodo_lectivo = $periodo_seleccionado)";
}

$result = mysqli_query($conn, $sql);
$bdgenero = "0";
$bg_genero_dat = "";
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($descr_periodo === '') {
        $periodo_fuente_id = 0;

        if ($periodo_seleccionado > 0) {
            $periodo_fuente_id = $periodo_seleccionado;
        } elseif (!empty($row["periodo_fuente_id"])) {
            $periodo_fuente_id = (int) $row["periodo_fuente_id"];
        } elseif (!empty($row["dtest_ciclo_datos"])) {
            $periodo_fuente_id = (int) $row["dtest_ciclo_datos"];
        }

        if ($periodo_fuente_id > 0) {
            $detalle_periodo = obtener_detalle_periodo_lectivo_udp($conn, $periodo_fuente_id);
            $descr_periodo = $detalle_periodo['texto'];
        }

        if ($descr_periodo === '' && !empty($row["periodo"])) {
            $descr_periodo = strtoupper(trim((string) $row["periodo"]));
        }
    }

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



<div class="modal fade" id="data_actualizar_estudiantes" tabindex="-1" role="dialog" aria-labelledby="data_actualizar_estudiantes" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-item-center">
              <h5 class="modal-title" id="exampleModalLongTitle"><b>Formulario de estudiante (Modificar)</b></h5>
              <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>-->
            </div>
            
            <form id="formEstudiante" onsubmit="event.preventDefault(); guardar_udp_est();">
                
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
                              <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                   <div class="row d-flex justify-content-center align-items-center">
                                    <h3>Datos básicos del estudiante</h3>
                                   </div>
                                   <div class="row">
                                    <div class="form-group col-lg-4 col-md-6">
                                        <label for="nombre_jornada">Jornada - Curso:</label>
                                       <input id="id_jornada_est" hidden="hidden" value="<?php echo $jornada_curso_act;?>"/>
                                        <input type="text" class="form-control " 
                                               id="nombre_jornada" readonly="true"  hidden="hidden" value="<?php echo  $dat_nombre_jornada_curso;?>">
                                               
                                              <select name="id_jornada" id="id_jornada" class="form-control" onchange="actualizarInfoCurso()">
                                                  <?php
                                                  
                                                    $sqln = "SELECT id_jornada_curso, 
                                                            nivel,
                                                            jornada,
                                                            curso,
                                                            paralelo,
                                                            concat(nivel,'-',jornada,'-',curso,'-',paralelo) as nombre_jornada_curso 
                                                            FROM jornada_curso";
                                                    $result = mysqli_query($conn, $sqln);
                                                    
                                                    if (mysqli_num_rows($result) > 0) {
                                                      // output data of each row
                                                      while($row = mysqli_fetch_assoc($result)) {
                                                        $codigo_cur = $row["id_jornada_curso"];
                                                        $nivel_cur_act = $row["nivel"];
                                                        $jornada_cur_act = $row["jornada"];
                                                        $curso_cur_act = $row["curso"];
                                                        $paralelo_cur_act = $row["paralelo"];
                                                        $nombre_cur_act = $row["nombre_jornada_curso"];
                                                        
                                                        if ($codigo_cur == $jornada_curso_act){
                                                         ?>
                                                            <option selected value="<?php echo  $codigo_cur; ?>"><?php echo $nombre_cur_act; ?></option>
                                                         <?php 
                                                        }else{
                                                        
                                                        ?>
                                                           <option value="<?php echo  $codigo_cur; ?>"><?php echo $nombre_cur_act; ?></option>
                                                           
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
                                               id="nombres" name="nombres" placeholder="Nombres" value="<?php echo $nombres_estudiantes;?>" required>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6">
                                        <label for="apellidos">Apellidos:</label>
                                        <input type="text" class="form-control " 
                                               id="apellidos" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos_estudiantes;?>" required>
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
                                            <div class="form-group col-lg-6 col-md-6">
                                                <label for="institucion_prev">Institución Educativa que proviene:</label>
                                                <input type="text" class="form-control " 
                                                       id="institucion_prev" 
                                                       placeholder="Institucion de donde proviene"
                                                       value="<?php echo $bdinstitucion_prev; ?>">
                                            </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                                    <div class="row d-flex justify-content-center align-content-center">
                                        <h3>Información Académica <?php echo htmlspecialchars($etiqueta_periodo_form); ?></h3>
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
                                                <label for="cur">Curso <?php echo htmlspecialchars($etiqueta_periodo_form); ?>:</label>
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
                                                           
                                                            checked
                                                            >
                                                    <?php

                                                }else{
                                                    ?>
                                                        <input type="checkbox" 
                                                            id="repite_anio" 
                                                            name="repite_anio"
                                                            
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
                                     


                                <div class="tab-pane fade" id="pills-contact" role="tabpanel">
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
                                <div class="tab-pane fade" id="pills-x" role="tabpanel">
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
                                <div class="tab-pane fade" id="pills-d" role="tabpanel">
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
                                <div class="tab-pane fade" id="pills-a" role="tabpanel">
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
                                                <label for="ncel1">Numero de Celular1:</label>
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
                                                <label for="ncel2">Numero de Celular2:</label>
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
                                        <button type="submit" class="btn btn-primary">
                                            Guardar <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </div>
                              </div>
                              </form>
                    </div>
                </div>

            </div>


        </div>
                
                
                <!--
                <div class="modal-footer">
                  <button type="submit"  class="btn btn-outline-primary" >Guardar</button>
                  <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                 
                </div>
                -->
            </form>
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

    <!-- Inicialización de componentes -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Inicializar validaciones de campos especiales
        initializeSpecialFields();
    });

    // Inicializar campos especiales
    function initializeSpecialFields() {
        // Validación de cédula
        const cedulaFields = ['cedula', 'cedula_rep', 'cedula_m', 'cedula_p'];
        cedulaFields.forEach(field => {
            const elemento = document.getElementById(field);
            if (elemento) {
                elemento.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                });
            }
        });

        // Validación de teléfonos
        //const phoneFields = ['celular', 'celular_rep', 'convencional_rep', 'celular_m', 
        //                    'convencional_mama', 'celular_p', 'convencional_papa', 'ncel1', 'ncel2'];
        
        const phoneFields = ['celular', 'celular_rep', 'celular_m', 
                             'celular_p', 'ncel1', 'ncel2'];

        phoneFields.forEach(field => {
            const elemento = document.getElementById(field);
            if (elemento) {
                elemento.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                });
            }
        });

        // Validación de emails
        const emailFields = ['email', 'email_rep', 'email_m', 'email_p'];
        emailFields.forEach(field => {
            const elemento = document.getElementById(field);
            if (elemento) {
                elemento.addEventListener('blur', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (this.value && !emailRegex.test(this.value)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Email inválido',
                            text: 'Por favor ingrese un email válido'
                        });
                    }
                });
            }
        });
    }

    // Función para cambiar entre tabs
    function cambiarTab(tabId) {
        const tab = new bootstrap.Tab(document.querySelector(`#${tabId}`));
        tab.show();
    }
    </script>
</html>
