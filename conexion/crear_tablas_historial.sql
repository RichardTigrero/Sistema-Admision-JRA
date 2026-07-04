-- ============================================
-- SCRIPT DE CREACION DE TABLAS HISTORICAS
-- ============================================

-- 1. Crear tabla estudiantes_historial
CREATE TABLE IF NOT EXISTS `estudiantes_historial` (
  `est_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `est_nombres` varchar(50) NOT NULL,
  `est_apellidos` varchar(50) NOT NULL,
  `est_cedula` varchar(30) NOT NULL,
  `est_usuario` varchar(30) NOT NULL,
  `est_password` varchar(60) NOT NULL,
  `est_estado` varchar(10) NOT NULL,
  `id_periodo_lectivo` int(11) NOT NULL,
  `fecha_archivo` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`est_id`, `id_periodo_lectivo`) USING BTREE,
  KEY `est_apellidos` (`est_apellidos`) USING BTREE,
  KEY `est_cedula` (`est_cedula`) USING BTREE,
  KEY `est_nombres` (`est_nombres`) USING BTREE,
  KEY `id_periodo_lectivo` (`id_periodo_lectivo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 2. Crear tabla est_datos_historial
CREATE TABLE IF NOT EXISTS `est_datos_historial` (
  `dtest_id` int(11) NOT NULL AUTO_INCREMENT,
  `dtest_nombres` varchar(50) NOT NULL,
  `dtest_apellidos` varchar(50) NOT NULL,
  `dtest_cedula` varchar(30) NOT NULL,
  `infaca_jornada_curso` int(11) NOT NULL,
  `infaca_nivel_edu` varchar(50) DEFAULT NULL,
  `infaca_curso_act` varchar(50) DEFAULT NULL,
  `infaca_jornada_archivo` varchar(50) DEFAULT NULL,
  `infaca_paralelo` varchar(50) DEFAULT NULL,
  `infaca_repite` varchar(50) DEFAULT NULL,
  `infaca_tutorcurso` varchar(50) DEFAULT NULL,
  `dtest_nacionalidad` varchar(30) DEFAULT NULL,
  `dtest_genero` varchar(30) DEFAULT NULL,
  `dtest_fnnacimiento` varchar(40) DEFAULT NULL,
  `dtest_edad` varchar(4) DEFAULT NULL,
  `dtest_celular` varchar(15) DEFAULT NULL,
  `dtest_direccion` varchar(50) DEFAULT NULL,
  `dtest_gmail` varchar(50) DEFAULT NULL,
  `dest_institucion_prev` varchar(50) DEFAULT NULL,
  `infrepre_cedula` varchar(20) DEFAULT NULL,
  `infrepre_nomape` varchar(100) DEFAULT NULL,
  `infrepre_clular` varchar(15) DEFAULT NULL,
  `infrepre_convencional` varchar(9) DEFAULT NULL,
  `infrepre_gmail` varchar(50) DEFAULT NULL,
  `infrepre_parentezco` varchar(40) DEFAULT NULL,
  `infmadre_vivemadre` varchar(30) DEFAULT NULL,
  `infmadre_cedula` varchar(20) DEFAULT NULL,
  `infmadre_nomape` varchar(100) DEFAULT NULL,
  `infmadre_celular` varchar(15) DEFAULT NULL,
  `infmadre_convencional` varchar(9) DEFAULT NULL,
  `infmadre_gmail` varchar(50) DEFAULT NULL,
  `infpadre_vivepadre` varchar(30) DEFAULT NULL,
  `infpadre_cedula` varchar(15) DEFAULT NULL,
  `infpadre_nomap` varchar(100) DEFAULT NULL,
  `infpadre_celular` varchar(15) DEFAULT NULL,
  `infpadre_convencional` varchar(9) DEFAULT NULL,
  `infpadre_gmail` varchar(50) DEFAULT NULL,
  `estsalud_alergias` varchar(20) DEFAULT NULL,
  `estsalud_tipoalerg` varchar(100) DEFAULT NULL,
  `estsalud_vacuna19` varchar(10) DEFAULT NULL,
  `estsalud_carnet` varchar(15) DEFAULT NULL,
  `estsalud_discapatipo` varchar(30) DEFAULT NULL,
  `estemergencia_numerocell1` varchar(15) DEFAULT NULL,
  `estemergencia_nombre1` varchar(50) DEFAULT NULL,
  `estemergencia_numcell2` varchar(15) DEFAULT NULL,
  `estemergencia_nombre2` varchar(50) DEFAULT NULL,
  `dtest_estado_reg` varchar(50) DEFAULT NULL,
  `dtest_usuario_reg` varchar(100) DEFAULT NULL,
  `dtest_imagen_usuario` varchar(200) DEFAULT NULL,
  `dtest_documento_adjunto` varchar(200) DEFAULT NULL,
  `dtest_ciclo_datos` varchar(10) DEFAULT NULL,
  `id_periodo_lectivo` int(11) NOT NULL,
  `fecha_archivo` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dtest_id`, `id_periodo_lectivo`) USING BTREE,
  KEY `dtest_cedula` (`dtest_cedula`) USING BTREE,
  KEY `id_periodo_lectivo` (`id_periodo_lectivo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 3. Crear tabla jornada_curso_historial
CREATE TABLE IF NOT EXISTS `jornada_curso_historial` (
  `id_jornada_curso` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` varchar(50) DEFAULT NULL,
  `jornada` varchar(45) DEFAULT NULL,
  `curso` varchar(45) DEFAULT NULL,
  `paralelo` varchar(45) DEFAULT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `id_periodo_lectivo` int(11) DEFAULT NULL,
  `fecha_archivo` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jornada_curso`, `id_periodo_lectivo`) USING BTREE,
  KEY `idx_jornada_hist_periodo` (`id_periodo_lectivo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 4. Asegurar columna id_periodo_lectivo en jornada_curso
ALTER TABLE jornada_curso
ADD COLUMN IF NOT EXISTS id_periodo_lectivo INT DEFAULT NULL AFTER estado;

-- ============================================
-- PROCEDIMIENTO ALMACENADO
-- ============================================

DROP PROCEDURE IF EXISTS `sp_procesar_periodo_lectivo`;

DELIMITER //

CREATE PROCEDURE `sp_procesar_periodo_lectivo`(
    IN p_id_periodo INT,
    OUT p_mensaje VARCHAR(500),
    OUT p_exito BOOLEAN
)
sp_proc: BEGIN
    DECLARE v_estudiantes_archivados INT DEFAULT 0;
    DECLARE v_datos_archivados INT DEFAULT 0;
    DECLARE v_jornadas_archivadas INT DEFAULT 0;
    DECLARE v_id_periodo_destino INT DEFAULT NULL;
    DECLARE v_periodo_actual VARCHAR(50);
    DECLARE v_periodo_destino VARCHAR(50);
    DECLARE v_sqlstate CHAR(5) DEFAULT '';
    DECLARE v_errno INT DEFAULT 0;
    DECLARE v_error_text TEXT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            v_sqlstate = RETURNED_SQLSTATE,
            v_errno = MYSQL_ERRNO,
            v_error_text = MESSAGE_TEXT;
        SET p_exito = FALSE;
        SET p_mensaje = CONCAT(
            'Error en el procedimiento. SQLSTATE: ',
            v_sqlstate,
            ' - Codigo: ',
            v_errno,
            ' - Detalle: ',
            v_error_text
        );
        ROLLBACK;
    END;

    START TRANSACTION;

    IF NOT EXISTS (
        SELECT 1
        FROM periodo_lectivo
        WHERE id_periodo_lectivo = p_id_periodo
    ) THEN
        SET p_exito = FALSE;
        SET p_mensaje = 'El periodo lectivo no existe.';
        ROLLBACK;
        LEAVE sp_proc;
    END IF;

    SELECT descripcion
      INTO v_periodo_actual
      FROM periodo_lectivo
     WHERE id_periodo_lectivo = p_id_periodo
     LIMIT 1;

    SELECT id_periodo_lectivo, descripcion
      INTO v_id_periodo_destino, v_periodo_destino
      FROM periodo_lectivo
     WHERE estado = 'ACTIVO'
       AND id_periodo_lectivo <> p_id_periodo
     ORDER BY id_periodo_lectivo DESC
     LIMIT 1;

    IF v_id_periodo_destino IS NULL THEN
        SET p_exito = FALSE;
        SET p_mensaje = 'No existe otro periodo lectivo en estado ACTIVO para reasignar los datos.';
        ROLLBACK;
        LEAVE sp_proc;
    END IF;

    DROP TEMPORARY TABLE IF EXISTS tmp_jornada_curso_periodo;

    CREATE TEMPORARY TABLE tmp_jornada_curso_periodo AS
    SELECT DISTINCT jc.*
      FROM jornada_curso jc
     WHERE jc.id_periodo_lectivo = p_id_periodo
        OR (
            jc.id_periodo_lectivo IS NULL
            AND CONVERT(jc.periodo USING utf8mb4) COLLATE utf8mb4_unicode_ci =
                CONVERT(v_periodo_actual USING utf8mb4) COLLATE utf8mb4_unicode_ci
        );

    DROP TEMPORARY TABLE IF EXISTS tmp_estudiantes_periodo;

    CREATE TEMPORARY TABLE tmp_estudiantes_periodo AS
    SELECT DISTINCT e.*
      FROM estudiantes e
      INNER JOIN est_datos ed
              ON e.est_cedula = ed.dtest_cedula
      INNER JOIN tmp_jornada_curso_periodo tjc
              ON ed.infaca_jornada_curso = tjc.id_jornada_curso;

    DROP TEMPORARY TABLE IF EXISTS tmp_est_datos_periodo;

    CREATE TEMPORARY TABLE tmp_est_datos_periodo AS
    SELECT DISTINCT ed.*
      FROM est_datos ed
      INNER JOIN tmp_jornada_curso_periodo tjc
              ON ed.infaca_jornada_curso = tjc.id_jornada_curso;

    INSERT INTO jornada_curso_historial
    (id_jornada_curso, nivel, jornada, curso, paralelo, periodo, id_docente, estado, id_periodo_lectivo)
    SELECT id_jornada_curso, nivel, jornada, curso, paralelo, periodo, id_docente, estado, p_id_periodo
      FROM tmp_jornada_curso_periodo tjc
     WHERE NOT EXISTS (
         SELECT 1
           FROM jornada_curso_historial jch
          WHERE jch.id_jornada_curso = tjc.id_jornada_curso
            AND jch.id_periodo_lectivo = p_id_periodo
     );

    SET v_jornadas_archivadas = ROW_COUNT();

    INSERT INTO estudiantes_historial
    (est_id, est_nombres, est_apellidos, est_cedula, est_usuario, est_password, est_estado, id_periodo_lectivo)
    SELECT est_id, est_nombres, est_apellidos, est_cedula, est_usuario, est_password, est_estado, p_id_periodo
      FROM tmp_estudiantes_periodo tep
     WHERE NOT EXISTS (
         SELECT 1
           FROM estudiantes_historial eh
          WHERE eh.est_id = tep.est_id
            AND eh.id_periodo_lectivo = p_id_periodo
     );

    SET v_estudiantes_archivados = ROW_COUNT();

    INSERT INTO est_datos_historial
    (dtest_id, dtest_nombres, dtest_apellidos, dtest_cedula, infaca_jornada_curso, infaca_nivel_edu,
     infaca_curso_act, infaca_jornada_archivo, infaca_paralelo, infaca_repite, infaca_tutorcurso,
     dtest_nacionalidad, dtest_genero, dtest_fnnacimiento, dtest_edad, dtest_celular, dtest_direccion,
     dtest_gmail, dest_institucion_prev, infrepre_cedula, infrepre_nomape, infrepre_clular,
     infrepre_convencional, infrepre_gmail, infrepre_parentezco, infmadre_vivemadre, infmadre_cedula,
     infmadre_nomape, infmadre_celular, infmadre_convencional, infmadre_gmail, infpadre_vivepadre,
     infpadre_cedula, infpadre_nomap, infpadre_celular, infpadre_convencional, infpadre_gmail,
     estsalud_alergias, estsalud_tipoalerg, estsalud_vacuna19, estsalud_carnet, estsalud_discapatipo,
     estemergencia_numerocell1, estemergencia_nombre1, estemergencia_numcell2, estemergencia_nombre2,
     dtest_estado_reg, dtest_usuario_reg, dtest_imagen_usuario, dtest_documento_adjunto, dtest_ciclo_datos, id_periodo_lectivo)
    SELECT dtest_id, dtest_nombres, dtest_apellidos, dtest_cedula, infaca_jornada_curso, infaca_nivel_edu,
           infaca_curso_act, infaca_jornada_archivo, infaca_paralelo, infaca_repite, infaca_tutorcurso,
           dtest_nacionalidad, dtest_genero, dtest_fnnacimiento, dtest_edad, dtest_celular, dtest_direccion,
           dtest_gmail, dest_institucion_prev, infrepre_cedula, infrepre_nomape, infrepre_clular,
           infrepre_convencional, infrepre_gmail, infrepre_parentezco, infmadre_vivemadre, infmadre_cedula,
           infmadre_nomape, infmadre_celular, infmadre_convencional, infmadre_gmail, infpadre_vivepadre,
           infpadre_cedula, infpadre_nomap, infpadre_celular, infpadre_convencional, infpadre_gmail,
           estsalud_alergias, estsalud_tipoalerg, estsalud_vacuna19, estsalud_carnet, estsalud_discapatipo,
           estemergencia_numerocell1, estemergencia_nombre1, estemergencia_numcell2, estemergencia_nombre2,
           dtest_estado_reg, dtest_usuario_reg, dtest_imagen_usuario, dtest_documento_adjunto, p_id_periodo, p_id_periodo
      FROM tmp_est_datos_periodo ted
     WHERE NOT EXISTS (
         SELECT 1
           FROM est_datos_historial edh
          WHERE edh.dtest_id = ted.dtest_id
            AND edh.id_periodo_lectivo = p_id_periodo
     );

    SET v_datos_archivados = ROW_COUNT();

    UPDATE est_datos ed
      INNER JOIN tmp_est_datos_periodo ted
              ON ed.dtest_id = ted.dtest_id
       SET ed.dtest_ciclo_datos = v_id_periodo_destino;

    UPDATE jornada_curso jc
      INNER JOIN tmp_jornada_curso_periodo tjc
              ON jc.id_jornada_curso = tjc.id_jornada_curso
       SET jc.id_periodo_lectivo = v_id_periodo_destino,
           jc.periodo = v_periodo_destino;

    UPDATE periodo_lectivo
       SET estado = 'CERRADO'
     WHERE id_periodo_lectivo = p_id_periodo;

    DROP TEMPORARY TABLE IF EXISTS tmp_jornada_curso_periodo;
    DROP TEMPORARY TABLE IF EXISTS tmp_estudiantes_periodo;
    DROP TEMPORARY TABLE IF EXISTS tmp_est_datos_periodo;

    SET p_exito = TRUE;
    SET p_mensaje = CONCAT(
        'Periodo procesado exitosamente. Jornadas archivadas: ',
        v_jornadas_archivadas,
        '. Estudiantes archivados: ',
        v_estudiantes_archivados,
        '. Datos archivados: ',
        v_datos_archivados,
        '. Datos reasignados al periodo activo ID ',
        v_id_periodo_destino,
        '.'
    );

    COMMIT;
END sp_proc //

DELIMITER ;

-- ============================================
-- EJECUTAR ESTE SCRIPT EN TU BASE DE DATOS
-- ============================================
-- Copia todo el contenido de este archivo
-- Accede a tu base de datos mediante phpMyAdmin
-- Ve a la pestana SQL
-- Pega todo el contenido y ejecuta
