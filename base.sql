

-- =====================================================
-- TABLA MAESTRA DE PERSONAS (mper)
-- Centraliza toda la información personal
-- =====================================================
CREATE TABLE mper (
    per_catalogo INTEGER NOT NULL,
    per_serie VARCHAR(8) COMMENT 'Serie o código adicional',
    per_grado SMALLINT NOT NULL,
    per_arma SMALLINT NOT NULL,
    per_nom1 VARCHAR(15) NOT NULL COMMENT 'Primer nombre',
    per_nom2 VARCHAR(15) COMMENT 'Segundo nombre',
    per_ape1 VARCHAR(15) NOT NULL COMMENT 'Primer apellido',
    per_ape2 VARCHAR(15) COMMENT 'Segundo apellido',
    per_telefono VARCHAR(15),
    per_sexo CHAR(1) NOT NULL,
    per_fec_nac DATE NOT NULL,
    per_nac_lugar VARCHAR(100) NOT NULL COMMENT 'Lugar de nacimiento',
    per_dpi VARCHAR(15) UNIQUE COMMENT 'DPI u otro documento',
    per_tipo_doc CHAR(3) DEFAULT 'DPI' COMMENT 'DPI, CED, PAS',
    per_email VARCHAR(100),
    per_direccion VARCHAR(255),
    per_estado CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo, R=Retirado',
    per_tipo CHAR(1) NOT NULL COMMENT 'A=Alumno, I=Instructor, O=Otro',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    observaciones TEXT,
    PRIMARY KEY (per_catalogo),
    FOREIGN KEY (per_grado) REFERENCES grados(gra_codigo),
    FOREIGN KEY (per_arma) REFERENCES armas(arm_codigo),
    CHECK (per_sexo IN ('M', 'F')),
    CHECK (per_tipo IN ('A', 'I', 'O')),
    INDEX idx_nombre_completo (per_ape1, per_ape2, per_nom1, per_nom2),
    INDEX idx_dpi (per_dpi),
    INDEX idx_tipo (per_tipo),
    INDEX idx_estado (per_estado)
) ENGINE = InnoDB COMMENT = 'Tabla maestra de personas';
-- =====================================================
-- BASE DE DATOS: SISTEMA DE CONTROL DE CURSOS
-- Escuela de Bomberos Rescatistas Militares
-- =====================================================

-- Tabla de ARMAS (auxiliar para mper)
CREATE TABLE `armas` (
  `arm_codigo` SMALLINT NOT NULL AUTO_INCREMENT,
  `arm_desc_lg` VARCHAR(30) NOT NULL COMMENT 'Descripción larga',
  `arm_desc_md` VARCHAR(15) NOT NULL COMMENT 'Descripción media',
  `arm_desc_ct` VARCHAR(8) NOT NULL COMMENT 'Descripción corta',
  `estado` CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`arm_codigo`)
) ENGINE=InnoDB COMMENT='Catálogo de armas y especialidades militares';

-- Tabla de GRADOS militares (auxiliar para mper)
CREATE TABLE `grados` (
  `gra_codigo` SMALLINT NOT NULL AUTO_INCREMENT,
  `gra_desc_lg` VARCHAR(30) NOT NULL COMMENT 'Descripción larga',
  `gra_desc_md` VARCHAR(15) NOT NULL COMMENT 'Descripción media',
  `gra_desc_ct` VARCHAR(8) NOT NULL COMMENT 'Descripción corta',
  `gra_asc` SMALLINT COMMENT 'Código del grado ascendente',
  `gra_tiempo` SMALLINT COMMENT 'Tiempo mínimo en el grado (meses)',
  `gra_clase` CHAR(1) NOT NULL COMMENT '1=Tropa, 2=Clases, 3=Oficiales, 4=Jefes, 5=Oficiales Superiores, 6=Generales',
  `gra_demeritos` SMALLINT COMMENT 'Deméritos permitidos',
  `estado` CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gra_codigo`),
  FOREIGN KEY (`gra_asc`) REFERENCES `grados`(`gra_codigo`),
  CHECK (`gra_clase` IN ('1', '2', '3', '4', '5', '6'))
) ENGINE=InnoDB COMMENT='Catálogo de grados militares';




-- ============================================
-- INSERTS para la tabla ARMAS
-- ============================================
INSERT INTO `armas` (`arm_desc_lg`, `arm_desc_md`, `arm_desc_ct`, `estado`) VALUES
('Infantería', 'Infantería', 'INF', 'A'),
('Artillería', 'Artillería', 'ART', 'A'),
('Caballería', 'Caballería', 'CAB', 'A'),
('Aviación', 'Aviación', 'AVI', 'A'),
('Ingenieros', 'Ingenieros', 'ING', 'A'),
('Transmisiones Militares', 'Transmisiones', 'TRANS', 'A'),
('Material de Guerra', 'Mat. Guerra', 'MAT', 'A'),
('Sanidad Militar', 'Sanidad', 'SAN', 'A'),
('Policía Militar', 'Policía Mil.', 'PM', 'A'),
('Intendencia', 'Intendencia', 'INT', 'A');

-- ============================================
-- INSERTS para la tabla GRADOS
-- ============================================
-- Clase 1: Tropa
INSERT INTO `grados` (`gra_desc_lg`, `gra_desc_md`, `gra_desc_ct`, `gra_asc`, `gra_tiempo`, `gra_clase`, `gra_demeritos`, `estado`) VALUES
('Soldado de Segunda', 'Sold. 2da.', 'S2', 2, 6, '1', 5, 'A'),
('Soldado de Primera', 'Sold. 1ra.', 'S1', 3, 12, '1', 5, 'A'),

-- Clase 2: Clases (Cabos y Sargentos)
('Cabo', 'Cabo', 'CBO', 4, 12, '2', 4, 'A'),
('Sargento Segundo', 'Sgto. 2do.', 'S2do', 5, 18, '2', 4, 'A'),
('Sargento Primero', 'Sgto. 1ro.', 'S1ro', 6, 24, '2', 3, 'A'),
('Sargento Técnico', 'Sgto. Téc.', 'STec', 7, 24, '2', 3, 'A'),
('Sargento Mayor', 'Sgto. Mayor', 'SMay', 8, 36, '2', 2, 'A'),

-- Clase 3: Oficiales Subalternos
('Subteniente', 'Subteniente', 'SBTE', 9, 12, '3', 2, 'A'),
('Teniente', 'Teniente', 'TTE', 10, 24, '3', 2, 'A'),

-- Clase 4: Oficiales Superiores (Capitanes)
('Capitán Segunda', 'Cap. 2do.', 'CAP2', 11, 24, '4', 1, 'A'),
('Capitán Primera', 'Cap. 1ro.', 'CAP1', 12, 36, '4', 1, 'A'),

-- Clase 5: Jefes
('Mayor', 'Mayor', 'MAY', 13, 48, '5', 1, 'A'),
('Teniente Coronel', 'TC', 'TC', 14, 48, '5', 0, 'A'),
('Coronel', 'Coronel', 'CRL', 15, 60, '5', 0, 'A'),

-- Clase 6: Generales
('General de Brigada', 'Gral. Brig.', 'GB', 16, NULL, '6', 0, 'A'),
('General de División', 'Gral. Div.', 'GD', NULL, NULL, '6', 0, 'A');

-- =====================================================
-- TABLAS PRINCIPALES DEL SISTEMA DE CURSOS
-- =====================================================

-- Catálogo de Países (para cursos en el extranjero)
CREATE TABLE `paises` (
  `pais_codigo` smallint NOT NULL AUTO_INCREMENT,
  `pais_nombre` varchar(100) NOT NULL,
  `pais_codigo_iso` char(3) DEFAULT NULL COMMENT 'Código ISO 3166',
  PRIMARY KEY (`pais_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Catálogo de Instituciones (para personal externo: Cruz Roja, CONRED, etc.)
CREATE TABLE `instituciones` (
  `inst_codigo` smallint NOT NULL AUTO_INCREMENT,
  `inst_nombre` varchar(100) NOT NULL COMMENT 'Cruz Roja, CONRED, etc.',
  `inst_siglas` varchar(20) DEFAULT NULL,
  `inst_tipo` char(1) NOT NULL COMMENT 'M=Militar, C=Civil',
  `inst_activa` char(1) DEFAULT 'S' COMMENT 'S=Sí, N=No',
  PRIMARY KEY (`inst_codigo`),
  CONSTRAINT `inst_chk_1` CHECK ((`inst_tipo` in (_utf8mb4'M',_utf8mb4'C'))),
  CONSTRAINT `inst_chk_2` CHECK ((`inst_activa` in (_utf8mb4'S',_utf8mb4'N')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Catálogo de Cursos
CREATE TABLE `cursos` (
  `cur_codigo` int NOT NULL AUTO_INCREMENT,
  `cur_nombre` varchar(150) NOT NULL COMMENT 'Ej: Bombero Rescatista Básico',
  `cur_nombre_corto` varchar(50) DEFAULT NULL COMMENT 'Ej: BRB',
  `cur_duracion_dias` smallint NOT NULL COMMENT 'Duración en días',
  `cur_nivel` char(1) NOT NULL COMMENT 'B=Básico, I=Intermedio, A=Avanzado, E=Especializado',
  `cur_tipo` char(1) NOT NULL COMMENT 'N=Nacional, X=Extranjero',
  `cur_certificado` char(1) DEFAULT 'N' COMMENT 'S=Emite certificado, N=No emite',
  `cur_institucion_certifica` smallint DEFAULT NULL COMMENT 'Institución que certifica',
  `cur_descripcion` text,
  `cur_activo` char(1) DEFAULT 'S' COMMENT 'S=Sí, N=No',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cur_codigo`),
  KEY `idx_nombre` (`cur_nombre`),
  KEY `cur_institucion_certifica` (`cur_institucion_certifica`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`cur_institucion_certifica`) REFERENCES `instituciones` (`inst_codigo`),
  CONSTRAINT `cursos_chk_1` CHECK ((`cur_nivel` in (_utf8mb4'B',_utf8mb4'I',_utf8mb4'A',_utf8mb4'E'))),
  CONSTRAINT `cursos_chk_2` CHECK ((`cur_tipo` in (_utf8mb4'N',_utf8mb4'X'))),
  CONSTRAINT `cursos_chk_3` CHECK ((`cur_activo` in (_utf8mb4'S',_utf8mb4'N'))),
  CONSTRAINT `cursos_chk_4` CHECK ((`cur_certificado` in (_utf8mb4'S',_utf8mb4'N')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Promociones de Cursos (cada vez que se imparte un curso)
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
  `pro_cantidad_graduados` smallint DEFAULT 0,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Participantes en Promociones (quién sacó qué curso)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;







-- Tabla de Niveles
CREATE TABLE niveles (
    niv_codigo SMALLINT PRIMARY KEY AUTO_INCREMENT,
    niv_nombre VARCHAR(20) NOT NULL
);

-- Tabla de Tipos
CREATE TABLE tipos (
    tip_codigo SMALLINT PRIMARY KEY AUTO_INCREMENT,
    tip_nombre VARCHAR(20) NOT NULL
);

-- Datos para Niveles
INSERT INTO niveles (niv_nombre) VALUES
('Básico'),
('Intermedio'),
('Avanzado');

-- Datos para Tipos
INSERT INTO tipos (tip_nombre) VALUES
('Teórico'),
('Práctico'),
('Taller');

-- Modificar tabla cursos para usar números en lugar de texto
ALTER TABLE cursos 
    MODIFY COLUMN cur_nivel SMALLINT,
    MODIFY COLUMN cur_tipo SMALLINT;

-- Crear las relaciones (llaves foráneas)
ALTER TABLE cursos
    ADD FOREIGN KEY (cur_nivel) REFERENCES niveles(niv_codigo),
    ADD FOREIGN KEY (cur_tipo) REFERENCES tipos(tip_codigo);










-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista: Historial de cursos por persona
CREATE VIEW `v_historial_cursos_persona` AS
SELECT 
    m.per_catalogo,
    CONCAT(g.gra_desc_ct, ' ', m.per_ape1, ' ', IFNULL(m.per_ape2, ''), ' ', m.per_nom1, ' ', IFNULL(m.per_nom2, '')) AS nombre_completo,
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
FROM participantes part
INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
INNER JOIN promociones p ON part.par_promocion = p.pro_codigo
INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
INNER JOIN grados g ON m.per_grado = g.gra_codigo
LEFT JOIN paises pa ON p.pro_pais = pa.pais_codigo
LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo
WHERE part.par_estado = 'G'
ORDER BY m.per_catalogo, p.pro_anio DESC, p.pro_fecha_graduacion DESC;

-- Vista: Personal sin cursos
CREATE VIEW `v_personal_sin_cursos` AS
SELECT 
    m.per_catalogo,
    CONCAT(g.gra_desc_ct, ' ', m.per_ape1, ' ', IFNULL(m.per_ape2, ''), ' ', m.per_nom1, ' ', IFNULL(m.per_nom2, '')) AS nombre_completo,
    g.gra_desc_lg AS grado,
    g.gra_clase AS clase_grado,
    a.arm_desc_lg AS arma,
    m.per_tipo,
    m.per_estado
FROM mper m
INNER JOIN grados g ON m.per_grado = g.gra_codigo
INNER JOIN armas a ON m.per_arma = a.arm_codigo
WHERE m.per_estado = 'A'
AND NOT EXISTS (
    SELECT 1 
    FROM participantes part 
    WHERE part.par_catalogo = m.per_catalogo 
    AND part.par_estado = 'G'
)
ORDER BY g.gra_clase DESC, m.per_ape1, m.per_ape2;

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
    CONCAT(g.gra_desc_ct, ' ', m.per_ape1, ' ', IFNULL(m.per_ape2, ''), ' ', m.per_nom1, ' ', IFNULL(m.per_nom2, '')) AS nombre_completo,
    m.per_catalogo,
    part.par_calificacion,
    part.par_posicion,
    part.par_certificado_numero,
    part.par_certificado_fecha,
    IFNULL(pa.pais_nombre, 'Guatemala') AS pais_curso
FROM promociones p
INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
INNER JOIN participantes part ON part.par_promocion = p.pro_codigo
INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
INNER JOIN grados g ON m.per_grado = g.gra_codigo
LEFT JOIN paises pa ON p.pro_pais = pa.pais_codigo
WHERE part.par_estado = 'G'
ORDER BY p.pro_codigo, part.par_posicion, m.per_ape1;

-- Vista: Cursos en el extranjero por persona
CREATE VIEW `v_cursos_extranjero` AS
SELECT 
    m.per_catalogo,
    CONCAT(g.gra_desc_ct, ' ', m.per_ape1, ' ', IFNULL(m.per_ape2, ''), ' ', m.per_nom1, ' ', IFNULL(m.per_nom2, '')) AS nombre_completo,
    c.cur_nombre,
    p.pro_numero AS promocion,
    p.pro_anio,
    pa.pais_nombre,
    p.pro_fecha_graduacion
FROM participantes part
INNER JOIN mper m ON part.par_catalogo = m.per_catalogo
INNER JOIN promociones p ON part.par_promocion = p.pro_codigo
INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
INNER JOIN grados g ON m.per_grado = g.gra_codigo
INNER JOIN paises pa ON p.pro_pais = pa.pais_codigo
WHERE part.par_estado = 'G'
AND p.pro_pais IS NOT NULL
ORDER BY m.per_catalogo, p.pro_anio DESC;

-- =====================================================
-- DATOS INICIALES DE EJEMPLO
-- =====================================================

-- Insertar país Guatemala
INSERT INTO `paises` (`pais_codigo`, `pais_nombre`, `pais_codigo_iso`) VALUES
(1, 'Guatemala', 'GTM'),
(2, 'Estados Unidos', 'USA'),
(3, 'México', 'MEX'),
(4, 'Colombia', 'COL'),
(5, 'España', 'ESP'),
(6, 'Chile', 'CHL');

-- Insertar instituciones
INSERT INTO `instituciones` (`inst_nombre`, `inst_siglas`, `inst_tipo`, `inst_activa`) VALUES
('Brigada de Bomberos Rescatistas', 'BBR', 'M', 'S'),
('Cruz Roja Guatemalteca', 'CRG', 'C', 'S'),
('Coordinadora Nacional para la Reducción de Desastres', 'CONRED', 'C', 'S'),
('Cuerpo Voluntario de Bomberos', 'CVB', 'C', 'S');

-- Insertar algunos cursos de ejemplo
INSERT INTO `cursos` (`cur_nombre`, `cur_nombre_corto`, `cur_duracion_dias`, `cur_nivel`, `cur_tipo`, `cur_descripcion`) VALUES
('Bombero Rescatista Básico', 'BRB', 30, 'B', 'N', 'Curso básico de bombero rescatista'),
('Bombero Rescatista Intermedio', 'BRI', 30, 'I', 'N', 'Curso intermedio de bombero rescatista'),
('Bombero Rescatista Avanzado', 'BRA', 30, 'A', 'N', 'Curso avanzado de bombero rescatista'),
('Rescate en Estructuras Colapsadas', 'REC', 15, 'E', 'N', 'Especialización en rescate urbano'),
('Manejo de Materiales Peligrosos', 'HAZMAT', 15, 'E', 'N', 'Curso de materiales peligrosos'),
('Rescate Acuático', 'RESACUA', 15, 'E', 'N', 'Técnicas de rescate en agua'),
('Instructor de Bomberos', 'INST-BOMB', 20, 'A', 'N', 'Formación de instructores'),
('Primeros Auxilios Avanzados', 'PAA', 5, 'I', 'N', 'Primeros auxilios nivel avanzado');