--==================ARMAS===================================
CREATE TABLE `armas` (
  `arm_codigo` smallint NOT NULL AUTO_INCREMENT,
  `arm_desc_lg` varchar(30) NOT NULL COMMENT 'Descripción larga',
  `arm_desc_md` varchar(15) NOT NULL COMMENT 'Descripción media',
  `arm_desc_ct` varchar(8) NOT NULL COMMENT 'Descripción corta',
  `estado` char(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`arm_codigo`)
) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'Catálogo de armas y especialidades militares' 



--==================CURSOS===================================
CREATE TABLE `cursos` (
  `cur_codigo` int NOT NULL AUTO_INCREMENT,
  `cur_nombre` varchar(150) NOT NULL COMMENT 'Ej: Bombero Rescatista Básico',
  `cur_nombre_corto` varchar(50) DEFAULT NULL COMMENT 'Ej: BRB',
  `cur_duracion_dias` smallint NOT NULL COMMENT 'Duración en días',
  `cur_nivel` smallint DEFAULT NULL,
  `cur_tipo` smallint DEFAULT NULL,
  `cur_certificado` varchar(2) DEFAULT 'No' COMMENT 'Si=Emite certificado, No=No emite',
  `cur_institucion_certifica` smallint DEFAULT NULL COMMENT 'Institución que certifica',
  `cur_descripcion` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cur_codigo`),
  KEY `idx_nombre` (`cur_nombre`),
  KEY `cur_institucion_certifica` (`cur_institucion_certifica`),
  KEY `cur_nivel` (`cur_nivel`),
  KEY `cur_tipo` (`cur_tipo`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`cur_institucion_certifica`) REFERENCES `instituciones` (`inst_codigo`),
  CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`cur_nivel`) REFERENCES `niveles` (`niv_codigo`),
  CONSTRAINT `cursos_ibfk_3` FOREIGN KEY (`cur_tipo`) REFERENCES `tipos` (`tip_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci




--==================GRADOS===================================
CREATE TABLE `grados` (
  `gra_codigo` smallint NOT NULL AUTO_INCREMENT,
  `gra_desc_lg` varchar(30) NOT NULL COMMENT 'Descripción larga',
  `gra_desc_md` varchar(15) NOT NULL COMMENT 'Descripción media',
  `gra_desc_ct` varchar(8) NOT NULL COMMENT 'Descripción corta',
  `gra_asc` smallint DEFAULT NULL COMMENT 'Código del grado ascendente',
  `gra_tiempo` smallint DEFAULT NULL COMMENT 'Tiempo mínimo en el grado (meses)',
  `gra_clase` char(1) NOT NULL COMMENT '1=Tropa, 2=Clases, 3=Oficiales, 4=Jefes, 5=Oficiales Superiores, 6=Generales',
  `gra_demeritos` smallint DEFAULT NULL COMMENT 'Deméritos permitidos',
  `estado` char(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gra_codigo`),
  KEY `gra_asc` (`gra_asc`),
  CONSTRAINT `grados_ibfk_1` FOREIGN KEY (`gra_asc`) REFERENCES `grados` (`gra_codigo`),
  CONSTRAINT `grados_chk_1` CHECK ((`gra_clase` in (_utf8mb4'1',_utf8mb4'2',_utf8mb4'3',_utf8mb4'4',_utf8mb4'5',_utf8mb4'6')))
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Catálogo de grados militares'



--==================INSTITUCIONES===================================

CREATE TABLE `instituciones` (
  `inst_codigo` smallint NOT NULL AUTO_INCREMENT,
  `inst_nombre` varchar(100) NOT NULL COMMENT 'Cruz Roja, CONRED, etc.',
  `inst_siglas` varchar(20) DEFAULT NULL,
  `inst_tipo` char(1) NOT NULL COMMENT 'M=Militar, C=Civil',
  `inst_activa` char(1) DEFAULT 'S' COMMENT 'S=Sí, N=No',
  PRIMARY KEY (`inst_codigo`),
  CONSTRAINT `inst_chk_1` CHECK ((`inst_tipo` in (_utf8mb4'M',_utf8mb4'C'))),
  CONSTRAINT `inst_chk_2` CHECK ((`inst_activa` in (_utf8mb4'S',_utf8mb4'N')))
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

--==================MPER===================================

CREATE TABLE `mper` (
  `per_catalogo` int NOT NULL,
  `per_serie` varchar(8) DEFAULT NULL COMMENT 'Serie o código adicional',
  `per_grado` smallint NOT NULL,
  `per_arma` smallint NOT NULL,
  `per_nom1` varchar(15) NOT NULL COMMENT 'Primer nombre',
  `per_nom2` varchar(15) DEFAULT NULL COMMENT 'Segundo nombre',
  `per_ape1` varchar(15) NOT NULL COMMENT 'Primer apellido',
  `per_ape2` varchar(15) DEFAULT NULL COMMENT 'Segundo apellido',
  `per_telefono` varchar(15) DEFAULT NULL,
  `per_sexo` char(1) NOT NULL,
  `per_fec_nac` date NOT NULL,
  `per_nac_lugar` varchar(100) NOT NULL COMMENT 'Lugar de nacimiento',
  `per_dpi` varchar(15) DEFAULT NULL COMMENT 'DPI u otro documento',
  `per_tipo_doc` char(3) DEFAULT 'DPI' COMMENT 'DPI, CED, PAS',
  `per_email` varchar(100) DEFAULT NULL,
  `per_direccion` varchar(255) DEFAULT NULL,
  `per_estado` char(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo, R=Retirado',
  `per_tipo` char(1) NOT NULL COMMENT 'A=Alumno, I=Instructor, O=Otro',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `observaciones` text,
  PRIMARY KEY (`per_catalogo`),
  UNIQUE KEY `per_dpi` (`per_dpi`),
  KEY `per_grado` (`per_grado`),
  KEY `per_arma` (`per_arma`),
  KEY `idx_nombre_completo` (`per_ape1`,`per_ape2`,`per_nom1`,`per_nom2`),
  KEY `idx_dpi` (`per_dpi`),
  KEY `idx_tipo` (`per_tipo`),
  KEY `idx_estado` (`per_estado`),
  CONSTRAINT `mper_ibfk_1` FOREIGN KEY (`per_grado`) REFERENCES `grados` (`gra_codigo`),
  CONSTRAINT `mper_ibfk_2` FOREIGN KEY (`per_arma`) REFERENCES `armas` (`arm_codigo`),
  CONSTRAINT `mper_chk_1` CHECK ((`per_sexo` in (_utf8mb4'M',_utf8mb4'F'))),
  CONSTRAINT `mper_chk_2` CHECK ((`per_tipo` in (_utf8mb4'A',_utf8mb4'I',_utf8mb4'J',_utf8mb4'O')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabla maestra de personas'

--==================PAISES===================================

CREATE TABLE `paises` (
  `pais_codigo` smallint NOT NULL AUTO_INCREMENT,
  `pais_nombre` varchar(100) NOT NULL,
  `pais_codigo_iso` char(3) DEFAULT NULL COMMENT 'Código ISO 3166',
  PRIMARY KEY (`pais_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci



INSERT INTO paises (pais_nombre, pais_codigo_iso) VALUES
('Guatemala', 'GTM'),
('El Salvador', 'SLV'),
('Honduras', 'HND'),
('Nicaragua', 'NIC'),
('República Dominicana', 'DOM');


--==================PARTICIPANTES===================================
CREATE TABLE `participantes` (
  `par_codigo` int NOT NULL AUTO_INCREMENT,
  `par_promocion` int NOT NULL,
  `par_catalogo` int NOT NULL COMMENT 'Catálogo del personal (de mper)',
  `par_calificacion` decimal(5,2) DEFAULT NULL COMMENT 'Calificación final',
  `par_posicion` smallint DEFAULT NULL COMMENT 'Posición en la promoción',
  `par_certificado_numero` varchar(50) DEFAULT NULL COMMENT 'Número de certificado emitido',
  `par_certificado_fecha` date DEFAULT NULL COMMENT 'Fecha de emisión del certificado',
  `par_estado` char(1) DEFAULT 'G' COMMENT 'G=Graduado, C=Cursando, R=Retirado, D=Desertor',
  `par_observaciones` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`par_codigo`),
  UNIQUE KEY `par_promocion_catalogo` (`par_promocion`,`par_catalogo`),
  UNIQUE KEY `par_certificado_numero` (`par_certificado_numero`),
  KEY `idx_catalogo` (`par_catalogo`),
  KEY `idx_promocion` (`par_promocion`),
  KEY `idx_certificado` (`par_certificado_numero`),
  CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`par_promocion`) REFERENCES `promociones` (`pro_codigo`),
  CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`par_catalogo`) REFERENCES `mper` (`per_catalogo`),
  CONSTRAINT `participantes_chk_1` CHECK ((`par_estado` in (_utf8mb4'G',_utf8mb4'C',_utf8mb4'R',_utf8mb4'D')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


--==================PROMOCIONES===================================

CREATE TABLE `promociones` (
  `pro_codigo` int NOT NULL AUTO_INCREMENT,
  `pro_curso` int NOT NULL,
  `pro_numero` varchar(20) NOT NULL COMMENT 'Número de promoción Ej: 2024-01, I-2024',
  `pro_anio` smallint NOT NULL,
  `pro_fecha_inicio` date NOT NULL,
  `pro_fecha_fin` date NOT NULL,
  `pro_fecha_graduacion` date DEFAULT NULL,
  `pro_lugar` varchar(150) DEFAULT NULL COMMENT 'Lugar donde se impartió',
  `pro_pais` smallint DEFAULT NULL COMMENT 'NULL si es en Guatemala',
  `pro_institucion_imparte` smallint DEFAULT NULL COMMENT 'Quién impartió el curso',
  `pro_cantidad_graduados` smallint DEFAULT '0',
  `pro_observaciones` text,
  `pro_activa` char(1) DEFAULT 'S' COMMENT 'S=Sí, N=No',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pro_codigo`),
  UNIQUE KEY `pro_curso_numero` (`pro_curso`,`pro_numero`),
  KEY `idx_anio` (`pro_anio`),
  KEY `idx_curso` (`pro_curso`),
  KEY `idx_pais` (`pro_pais`),
  KEY `pro_institucion_imparte` (`pro_institucion_imparte`),
  CONSTRAINT `promociones_ibfk_1` FOREIGN KEY (`pro_curso`) REFERENCES `cursos` (`cur_codigo`),
  CONSTRAINT `promociones_ibfk_2` FOREIGN KEY (`pro_pais`) REFERENCES `paises` (`pais_codigo`),
  CONSTRAINT `promociones_ibfk_3` FOREIGN KEY (`pro_institucion_imparte`) REFERENCES `instituciones` (`inst_codigo`),
  CONSTRAINT `promociones_chk_1` CHECK ((`pro_activa` in (_utf8mb4'S',_utf8mb4'N')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

--==================NIVELES===================================
CREATE TABLE niveles (
  niv_codigo SMALLINT PRIMARY KEY AUTO_INCREMENT,
  niv_nombre VARCHAR(20) NOT NULL
);

-- insertar datos en Niveles
INSERT INTO
  niveles (niv_nombre)
VALUES
  ('Básico'),
  ('Intermedio'),
  ('Avanzado');

--==================TIPOS===================================
CREATE TABLE tipos (
  tip_codigo SMALLINT PRIMARY KEY AUTO_INCREMENT,
  tip_nombre VARCHAR(20) NOT NULL
);

-- Insertar datos en Tipos
INSERT INTO
  tipos (tip_nombre)
VALUES
  ('Teórico'),
  ('Práctico'),
  ('Taller');

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================
-- Vista: Historial de cursos por persona
CREATE VIEW `v_historial_cursos_persona` AS
SELECT
  m.per_catalogo,
  CONCAT(
    g.gra_desc_ct,
    ' ',
    m.per_ape1,
    ' ',
    IFNULL(m.per_ape2, ''),
    ' ',
    m.per_nom1,
    ' ',
    IFNULL(m.per_nom2, '')
  ) AS nombre_completo,
  c.cur_nombre,
  c.cur_nombre_corto,
  c.cur_nivel,
  p.pro_numero AS promocion,
  p.pro_anio,
  p.pro_fecha_graduacion,
  IFNULL(pa.pais_nombre, 'Guatemala') AS pais_curso,
  part.par_calificacion,
  part.par_posicion,
  part.par_certificado_numero,
  part.par_certificado_fecha,
  i.inst_nombre AS institucion_certifica,
  part.par_estado
FROM
  participantes part
  INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
  INNER JOIN promociones p ON part.par_promocion = p.pro_codigo
  INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
  INNER JOIN grados g ON m.per_grado = g.gra_codigo
  LEFT JOIN paises pa ON p.pro_pais = pa.pais_codigo
  LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo
WHERE
  part.par_estado = 'G'
ORDER BY
  m.per_catalogo,
  p.pro_anio DESC,
  p.pro_fecha_graduacion DESC;

-- Vista: Personal sin cursos
CREATE VIEW `v_personal_sin_cursos` AS
SELECT
  m.per_catalogo,
  CONCAT(
    g.gra_desc_ct,
    ' ',
    m.per_ape1,
    ' ',
    IFNULL(m.per_ape2, ''),
    ' ',
    m.per_nom1,
    ' ',
    IFNULL(m.per_nom2, '')
  ) AS nombre_completo,
  g.gra_desc_lg AS grado,
  g.gra_clase AS clase_grado,
  a.arm_desc_lg AS arma,
  m.per_tipo,
  m.per_estado
FROM
  mper m
  INNER JOIN grados g ON m.per_grado = g.gra_codigo
  INNER JOIN armas a ON m.per_arma = a.arm_codigo
WHERE
  m.per_estado = 'A'
  AND NOT EXISTS (
    SELECT
      1
    FROM
      participantes part
    WHERE
      part.par_catalogo = m.per_catalogo
      AND part.par_estado = 'G'
  )
ORDER BY
  g.gra_clase DESC,
  m.per_ape1,
  m.per_ape2;

-- Vista: Listado de promoción
CREATE VIEW `v_listado_promocion` AS
SELECT
  p.pro_codigo,
  p.pro_numero,
  c.cur_nombre,
  p.pro_anio,
  p.pro_fecha_inicio,
  p.pro_fecha_fin,
  p.pro_fecha_graduacion,
  CONCAT(
    g.gra_desc_ct,
    ' ',
    m.per_ape1,
    ' ',
    IFNULL(m.per_ape2, ''),
    ' ',
    m.per_nom1,
    ' ',
    IFNULL(m.per_nom2, '')
  ) AS nombre_completo,
  m.per_catalogo,
  part.par_calificacion,
  part.par_posicion,
  part.par_certificado_numero,
  part.par_certificado_fecha,
  IFNULL(pa.pais_nombre, 'Guatemala') AS pais_curso
FROM
  promociones p
  INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
  INNER JOIN participantes part ON part.par_promocion = p.pro_codigo
  INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
  INNER JOIN grados g ON m.per_grado = g.gra_codigo
  LEFT JOIN paises pa ON p.pro_pais = pa.pais_codigo
WHERE
  part.par_estado = 'G'
ORDER BY
  p.pro_codigo,
  part.par_posicion,
  m.per_ape1;

-- Vista: Cursos en el extranjero por persona
CREATE VIEW `v_cursos_extranjero` AS
SELECT
  m.per_catalogo,
  CONCAT(
    g.gra_desc_ct,
    ' ',
    m.per_ape1,
    ' ',
    IFNULL(m.per_ape2, ''),
    ' ',
    m.per_nom1,
    ' ',
    IFNULL(m.per_nom2, '')
  ) AS nombre_completo,
  c.cur_nombre,
  p.pro_numero AS promocion,
  p.pro_anio,
  pa.pais_nombre,
  p.pro_fecha_graduacion
FROM
  participantes part
  INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
  INNER JOIN promociones p ON part.par_promocion = p.pro_codigo
  INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
  INNER JOIN grados g ON m.per_grado = g.gra_codigo
  INNER JOIN paises pa ON p.pro_pais = pa.pais_codigo
WHERE
  part.par_estado = 'G'
  AND p.pro_pais IS NOT NULL
ORDER BY
  m.per_catalogo,
  p.pro_anio DESC;

-- =====================================================
-- DATOS INICIALES DE EJEMPLO
-- =====================================================
-- Insertar país Guatemala
INSERT INTO
  `paises` (`pais_codigo`, `pais_nombre`, `pais_codigo_iso`)
VALUES
  (1, 'Guatemala', 'GTM'),
  (2, 'Estados Unidos', 'USA'),
  (3, 'México', 'MEX'),
  (4, 'Colombia', 'COL'),
  (5, 'España', 'ESP'),
  (6, 'Chile', 'CHL');

-- Insertar instituciones
INSERT INTO
  `instituciones` (
    `inst_nombre`,
    `inst_siglas`,
    `inst_tipo`,
    `inst_activa`
  )
VALUES
  (
    'Brigada de Bomberos Rescatistas',
    'BBR',
    'M',
    'S'
  ),
  ('Cruz Roja Guatemalteca', 'CRG', 'C', 'S'),
  (
    'Coordinadora Nacional para la Reducción de Desastres',
    'CONRED',
    'C',
    'S'
  ),
  ('Cuerpo Voluntario de Bomberos', 'CVB', 'C', 'S');

-- Insertar algunos cursos de ejemplo
INSERT INTO
  `cursos` (
    `cur_nombre`,
    `cur_nombre_corto`,
    `cur_duracion_dias`,
    `cur_nivel`,
    `cur_tipo`,
    `cur_descripcion`
  )
VALUES
  (
    'Bombero Rescatista Básico',
    'BRB',
    30,
    'B',
    'N',
    'Curso básico de bombero rescatista'
  ),
  (
    'Bombero Rescatista Intermedio',
    'BRI',
    30,
    'I',
    'N',
    'Curso intermedio de bombero rescatista'
  ),
  (
    'Bombero Rescatista Avanzado',
    'BRA',
    30,
    'A',
    'N',
    'Curso avanzado de bombero rescatista'
  ),
  (
    'Rescate en Estructuras Colapsadas',
    'REC',
    15,
    'E',
    'N',
    'Especialización en rescate urbano'
  ),
  (
    'Manejo de Materiales Peligrosos',
    'HAZMAT',
    15,
    'E',
    'N',
    'Curso de materiales peligrosos'
  ),
  (
    'Rescate Acuático',
    'RESACUA',
    15,
    'E',
    'N',
    'Técnicas de rescate en agua'
  ),
  (
    'Instructor de Bomberos',
    'INST-BOMB',
    20,
    'A',
    'N',
    'Formación de instructores'
  ),
  (
    'Primeros Auxilios Avanzados',
    'PAA',
    5,
    'I',
    'N',
    'Primeros auxilios nivel avanzado'
  );