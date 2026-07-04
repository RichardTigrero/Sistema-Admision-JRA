<?php
// Start the session
session_start();
include("data_estudiantes.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Matricula</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <head>
    <meta charset="UTF-8">
    <style >
    table{
      width: 100%;
      }

      th{

          text-align: center;
          left: auto;
          width: 400px;
          font-size:7pt;
      }

      td{
      text-align: center;

      position:absolute;top:0.49in;left:1.76in;


      }
    </style>


  </head>
<table>
    <td ><img style="text-align:center;" width="115" height="80" src="../imagenes/reporte/ForosEcuador.png"> </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><img style="position: center" src="../imagenes/reporte/ri_1.png"></td>
</table>

<body>


<table style="text-align:center;">




    <tr>
        <td STYLE="font-weight:bold;">UNIDAD EDUCATIVA FISCAL JAIME ROLDÓS AGUILERA</td>
    </tr>

    <tr>
        <td STYLE="font-weight:bold;">PERÍODO LECTIVO 2023 - 2024</td>

    </tr>
    <tr>
        <td STYLE="font-weight:bold;">FICHA DE MATRÍCULA</td>
    </tr>
</table>

 <table width="100%" >
 	<thead>
 	<tr></tr>

 </thead>
 <tbody>

 	<td>

<table border="1" >
    <div style="text-align: center;font-weight:bold;font-size:8pt;color:#000000">DATOS DEL ESTUDIANTE</div>
    <tr>
        <th colspan="2"style="line-height:normal;">No. CÉDULA / PASAPORTE</th>
        <th colspan="1"style="line-height:normal;">COD ALFNUM</th>
        <th colspan="3" style="line-height:normal;">CURSO 2021 - 2022</th>
        <th colspan="1" ROWSPAN="8" >Imagen DEL ESTUDIANTE</th>
    </tr>
    <tr>
        <td  colspan="2" style="line-height:normal;">{{Estudiante.identificacion}}</td>
        <td  colspan="1" style="line-height:normal;">{{Estudiante.cod_alfnum}}  </td>
        <td colspan="3" style="line-height:normal;"> {{curso_estudiante}}</td>
    </tr>
    <tr>
        <th colspan="6" style="line-height:normal;">APELLIDOS / NOMBRES COMPLETOS</th>
    </tr>
    <tr>
        <td colspan="6" style="line-height:normal;">{{Estudiante.apellidos}} &nbsp; {{Estudiante.nombres}}  </td>
    </tr>
    <tr style="line-height:normal;">
        <th colspan="2">FECHA / NACIMIENTO</th>
        <th colspan="1">EDAD</th>
        <th colspan="1">GÉNERO</th>
        <th colspan="1">PAIS NACIM.</th>
        <th colspan="1">CIUDAD</th>
    </tr>
    <tr style="line-height:normal;">
        <td colspan="2">{{Estudiante.fecha_de_nacimiento}}</td>
        <td colspan="1">{{Estudiante.edadEst}} </td>
        <td colspan="1">{{Estudiante.id_genr_genero}}</td>
        <td colspan="1">{{Estudiante.id_genr_pais}}</td>
        <td colspan="1">{{Estudiante.id_genr_ciudad}}</td>
    </tr>
    <tr>
        <th colspan="6" style="line-height:normal;">DIRECCIÓN</th>
    </tr>
    <tr>
        <td colspan="6" style="line-height:normal;">{{Estudiante.direccion}}</td>
    </tr>
    <tr>
        <th colspan="2" style="line-height:normal;">SECTOR</th>
        <th colspan="5" style="line-height:normal;">REFERENCIA DE UBICACIÓN</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.sector}}</td>
        <td colspan="5" style="line-height:normal;">{{Estudiante.referenciadeubicacion}}</td>
    </tr>
    <tr>
        <th colspan="3" style="line-height:normal;">CORREO ELECTRÓNICO:</th>
        <th colspan="3" style="line-height:normal;">NACIONALIDAD</th>
        <th colspan="1" style="line-height:normal;">MAMÁ/PAPÁ</th>
    </tr>
    <tr>
        <td colspan="3" style="line-height:normal;">{{Estudiante.correo_elest}} </td>
        <td colspan="3" style="line-height:normal;">{{Estudiante.nacionalidadEst}} </td>
        <td colspan="1"></td>
    </tr>
    <tr>
        <th colspan="2" style="line-height:normal;">No. TEL. CONVENCIONAL</th>
        <th colspan="2" style="line-height:normal;">No. TEL. CELULAR</th>
        <th colspan="3" style="line-height:normal;">Discapacidad</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.rtelefono_trabajo}}</td>
        <td colspan="2" style="line-height:normal;">{{Estudiante.celular}}</td>
        <td colspan="3" style="line-height:normal;">{{Estudiante.discapacidad}}</td>
    </tr>
    <tr>
        <th colspan="4" style="line-height:normal;">PLANTEL DE PROCEDENCIA</th>
        <th colspan="3" style="line-height:normal;">CURSO 2020 - 2021</th>
    </tr>
    <tr>
        <td colspan="4" style="line-height:normal;">{{Estudiante.plantel_procedenciaEst}} </td>
        <td colspan="3" style="line-height:normal;"> </td>
    </tr>

</table>
<br>
<br>



<table border="1">

    <div style="text-align: center;font-weight:bold;font-size:8pt;color:#000000">DATOS DEL REPRESENTANTE</div>
    <tr>
        <th colspan="2"style="line-height:normal;">No. CÉDULA / PASAPORTE</th>
        <th colspan="4" style="line-height:normal;">NOMBRE COMPLETO (APELLIDOS / NOMBRES)</th>
        <th colspan="1" ROWSPAN="8" >Imagen DEL REPRESENTANTE</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.ridentificacion}}</td>
        <td colspan="4" style="line-height:normal;">{{Estudiante.rapellidos}} &nbsp; {{Estudiante.rnombres}} </td>

    </tr>
    <tr >
        <th colspan="2" style="line-height:normal;">FECHA / NACIMIENTO:</th>
        <th colspan="1" style="line-height: normal">EDAD:</th>
        <th colspan="1" style="line-height: normal">GÉNERO</th>
        <th colspan="1" style="line-height: normal">PAIS</th>
        <th colspan="1" style="line-height: normal">CIUDAD</th>
    </tr>
    <tr>
        <td colspan="2"style="line-height:normal;">{{Estudiante.fecha_nacimientoRe}}</td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.edadRe}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.generoRe}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.paisRe}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.ciudadRe}} </td>
    </tr>
    <tr>
        <th colspan="2"style="line-height:normal;">CORREO ELECTRÓNICO:</th>
        <th colspan="4"style="line-height:normal;">DIRECCIÓN</th>

    </tr>
    <tr>
        <td colspan="2"style="line-height:normal;">{{Estudiante.rcorreo}} </td>
        <td colspan="4"style="line-height:normal;">{{Estudiante.direccionRe}} </td>

    </tr>
    <tr>

        <th  colspan="2"style="line-height:normal;">PARENTEZCO CON ESTUD.</th>
        <th colspan="2"style="line-height:normal;">No. TEL. CELULAR</th>
        <th colspan="2"style="line-height:normal;">No. TEL. CONVENCIONAL</th>
    </tr>
    <tr>

        <td colspan="2"style="line-height:normal;">{{Estudiante.tipo_parentesco}}</td>
        <td colspan="2"style="line-height:normal;">{{Estudiante.rtelefono}}</td>
        <td colspan="2"style="line-height:normal;">{{Estudiante.rtelefono_trabajo}}</td>
    </tr>
    <tr>

        <th colspan="1"style="line-height:normal;">PROFESIÓN</th>

        <th colspan="4"style="line-height:normal;">LUGAR DE TRABAJO</th>
        <th colspan="1"style="line-height:normal;">TELEF. LUGAR TRABAJO</th>

        <th colspan="1" style="line-height:normal;">VIVE / ESTUD</th>
    </tr>
    <tr>

        <td colspan="1"style="line-height:normal;">{{Estudiante.profesionRe}} </td>

        <td colspan="4"style="line-height:normal;">{{Estudiante.lugardetrabajoRe}}</td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.rtelefono_trabajo}}</td>

        <td colspan="1" style="line-height:normal;">{{Estudiante.rvive_con_usted}}</td>
    </tr>

    </table>

<br>
<br>
<br>
<table border="1">
        <div style="text-align: center;font-weight:bold;font-size:8pt;color:#000000">DATOS DE LA MADRE</div>
    <tr>
        <th  colspan="2" style="line-height:normal;">No. CÉDULA / PASAPORTE</th>
        <th colspan="4" style="line-height:normal;">APELLIDOS / NOMBRES COMPLETOS</th>
        <th  colspan="1" style="line-height:normal;">VIVE / ESTUD</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.midentificacion}}</td>
        <td   colspan="4" style="line-height:normal;">{{Estudiante.mapellidos}} &nbsp; {{Estudiante.mnombres}}</td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.mvive_con_usted}}</td>
    </tr>
    <tr>
        <th colspan="2" style="line-height:normal;">FECHA / NACIMIENTO:</th>
        <th colspan="1" style="line-height:normal;">EDAD</th>
        <th colspan="1" style="line-height:normal;">GENERO</th>
        <th colspan="1" style="line-height:normal;">PAIS</th>
        <th colspan="1" style="line-height:normal;">CIUDAD</th>
        <th colspan="1" style="line-height:normal;">No. TEL. CELULAR</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;" >{{Estudiante.fecha_nacimientoMa}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.edadMam}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.generoMam}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.paisMam}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.ciudadMam}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.mtelefono}}</td>
    </tr>
    <tr>
           <th colspan="3" style="line-height:normal;">CORREO ELECTRÓNICO:</th>
        <th colspan="4" style="line-height:normal;">OCUPACION</th>
    </tr>
    <tr>
        <td  colspan="3" style="line-height:normal;">{{Estudiante.correo_elMam}}</td>
        <td colspan="4" style="line-height:normal;">{{Estudiante.id_genr_estado_laboralm}}</td>
    </tr>
</table>

<br>
<br>
<br>
<table border="1">
        <div style="text-align: center;font-weight:bold;font-size:8pt;color:#000000">DATOS DEL PADRE</div>
    <tr>
        <th colspan="2"  style="line-height:normal;">No. CÉDULA / PASAPORTE</th>
        <th colspan="4" style="line-height:normal;">APELLIDOS / NOMBRES COMPLETOS</th>
        <th colspan="1" style="line-height:normal;">VIVE / ESTUD</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.pidentificacion}}</td>
        <td colspan="4" style="line-height:normal;">{{Estudiante.pnombres}} &nbsp; {{Estudiante.papellidos}}</td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.pvive_con_usted}}</td>
    </tr>
    <tr>
        <th colspan="2" style="line-height:normal;">FECHA / NACIMIENTO:</th>
        <th colspan="1" style="line-height:normal;">EDAD</th>
        <th colspan="1" style="line-height:normal;">GENERO</th>
        <th colspan="1" style="line-height:normal;">PAIS</th>
        <th colspan="1" style="line-height:normal;">CIUDAD</th>
        <th colspan="1" style="line-height:normal;">No. TEL. CELULAR</th>
    </tr>
    <tr>
        <td colspan="2" style="line-height:normal;">{{Estudiante.fecha_nacimientoPap}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.edadPap}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.generoPap}} </td>
        <td colspan="1"style="line-height:normal;">{{Estudiante.paisPap}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.ciudadPap}} </td>
        <td colspan="1" style="line-height:normal;">{{Estudiante.ptelefono}}</td>
    </tr>
    <tr>
        <th colspan="3" style="line-height:normal;">CORREO ELECTRÓNICO:</th>
        <th colspan="4" style="line-height:normal;">OCUPACION</th>
    </tr>
    <tr>
        <td colspan="3" style="line-height:normal;">{{Estudiante.correo_elPap}} </td>
        <td colspan="4" style="line-height:normal;">{{Estudiante.id_genr_estado_laboralp}}</td>
    </tr>
</table>
<table>
  <tr>
    <br>

    <td >REPRES. LEGAL </td>
    <td>SECRETARIA</td>
    <td>RECTORA</td>

  </tr>
</table>

<br>
</table>
<!--
<div id="footer_content">
    <br>
    <br>
      <div id="contenido_del_footer" style="font-size: 10px;">

        <td >Fecha: {{fecha_actual}}</td>
    </div>
</div>
    -->
</body>
</html>