

-- =====================================================
-- TABLAS CATÁLOGO (Tablas maestras)
-- =====================================================
-- Tabla de ARMAS (especialidades militares)
CREATE TABLE armas (
    arm_codigo SMALLINT NOT NULL AUTO_INCREMENT,
    arm_desc_lg VARCHAR(30) NOT NULL COMMENT 'Descripción larga',
    arm_desc_md VARCHAR(15) NOT NULL COMMENT 'Descripción media',
    arm_desc_ct VARCHAR(8) NOT NULL COMMENT 'Descripción corta',
    estado CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (arm_codigo)
) ENGINE = InnoDB COMMENT = 'Catálogo de armas y especialidades militares';

-- Tabla de GRADOS militares
CREATE TABLE grados (
    gra_codigo SMALLINT NOT NULL AUTO_INCREMENT,
    gra_desc_lg VARCHAR(30) NOT NULL COMMENT 'Descripción larga',
    gra_desc_md VARCHAR(15) NOT NULL COMMENT 'Descripción media',
    gra_desc_ct VARCHAR(8) NOT NULL COMMENT 'Descripción corta',
    gra_asc SMALLINT COMMENT 'Código del grado ascendente',
    gra_tiempo SMALLINT COMMENT 'Tiempo mínimo en el grado (meses)',
    gra_clase CHAR(1) NOT NULL COMMENT '1=Tropa, 2=Clases, 3=Oficiales, 4=Jefes, 5=Oficiales Superiores, 6=Generales',
    gra_demeritos SMALLINT COMMENT 'Deméritos permitidos',
    estado CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (gra_codigo),
    FOREIGN KEY (gra_asc) REFERENCES grados(gra_codigo),
    CHECK (gra_clase IN ('1', '2', '3', '4', '5', '6'))
) ENGINE = InnoDB COMMENT = 'Catálogo de grados militares';

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
-- TABLA DE ALUMNOS (extiende información de mper)
-- =====================================================
CREATE TABLE alumnos (
    per_catalogo INTEGER NOT NULL,
    codigo_alumno VARCHAR(20) UNIQUE NOT NULL COMMENT 'Código único como alumno (ej: ESC001)',
    estado_alumno CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo, G=Graduado, R=Retirado',
    fecha_ingreso DATE NOT NULL,
    fecha_egreso DATE,
    notas_especiales TEXT,
    PRIMARY KEY (per_catalogo),
    FOREIGN KEY (per_catalogo) REFERENCES mper(per_catalogo) ON DELETE CASCADE,
    INDEX idx_codigo_alumno (codigo_alumno),
    INDEX idx_estado (estado_alumno)
) ENGINE = InnoDB COMMENT = 'Información específica de alumnos';

-- =====================================================
-- TABLA DE INSTRUCTORES (extiende información de mper)
-- =====================================================
CREATE TABLE instructores (
    per_catalogo INTEGER NOT NULL,
    codigo_instructor VARCHAR(20) UNIQUE NOT NULL COMMENT 'Código único como instructor',
    especialidades TEXT COMMENT 'Áreas de especialización',
    años_experiencia SMALLINT,
    certificaciones TEXT,
    estado_instructor CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo, L=Licencia',
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    PRIMARY KEY (per_catalogo),
    FOREIGN KEY (per_catalogo) REFERENCES mper(per_catalogo) ON DELETE CASCADE,
    INDEX idx_codigo_instructor (codigo_instructor),
    INDEX idx_estado (estado_instructor)
) ENGINE = InnoDB COMMENT = 'Información específica de instructores';

-- =====================================================
-- TABLA DE CURSOS
-- =====================================================
CREATE TABLE cursos (
    id_curso INT NOT NULL AUTO_INCREMENT,
    codigo_curso VARCHAR(20) UNIQUE NOT NULL,
    nombre_curso VARCHAR(150) NOT NULL,
    descripcion TEXT,
    duracion_horas SMALLINT NOT NULL,
    requisitos TEXT,
    tipo_curso VARCHAR(50) COMMENT 'Básico, Intermedio, Avanzado, Especialización',
    area_especialidad VARCHAR(100) COMMENT 'Rescate Acuático, Montaña, Urbano, etc.',
    estado_curso CHAR(1) DEFAULT 'A' COMMENT 'A=Activo, I=Inactivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_curso),
    INDEX idx_codigo_curso (codigo_curso),
    INDEX idx_estado (estado_curso),
    INDEX idx_area (area_especialidad)
) ENGINE = InnoDB COMMENT = 'Catálogo de cursos disponibles';

-- =====================================================
-- TABLA DE PROMOCIONES/COHORTES
-- =====================================================
CREATE TABLE promociones (
    id_promocion INT NOT NULL AUTO_INCREMENT,
    id_curso INT NOT NULL,
    numero_promocion SMALLINT NOT NULL COMMENT '1era, 2da, 3era promoción del año',
    año SMALLINT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    fecha_graduacion DATE,
    per_instructor INTEGER COMMENT 'Catálogo del instructor principal',
    lugar VARCHAR(100),
    cupo_maximo SMALLINT DEFAULT 30,
    estado_promocion CHAR(1) DEFAULT 'P' COMMENT 'P=Planificada, E=En curso, F=Finalizada, C=Cancelada',
    observaciones TEXT,
    PRIMARY KEY (id_promocion),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    FOREIGN KEY (per_instructor) REFERENCES instructores(per_catalogo),
    UNIQUE KEY uk_promocion (id_curso, numero_promocion, año),
    INDEX idx_curso_año (id_curso, año),
    INDEX idx_fechas (fecha_inicio, fecha_fin),
    INDEX idx_estado (estado_promocion)
) ENGINE = InnoDB COMMENT = 'Promociones o cohortes de cursos';

-- =====================================================
-- TABLA DE INSCRIPCIONES/PARTICIPACIONES
-- =====================================================
CREATE TABLE inscripciones (
    id_inscripcion INT NOT NULL AUTO_INCREMENT,
    per_catalogo INTEGER NOT NULL,
    id_promocion INT NOT NULL,
    fecha_inscripcion DATE DEFAULT (CURRENT_DATE),
    nota_final DECIMAL(5, 2) COMMENT 'Nota de 0 a 100',
    asistencia_porcentaje DECIMAL(5, 2) COMMENT 'Porcentaje de asistencia',
    estado_inscripcion VARCHAR(15) DEFAULT 'INSCRITO' COMMENT 'INSCRITO, CURSANDO, APROBADO, REPROBADO, RETIRADO',
    fecha_graduacion DATE,
    numero_diploma VARCHAR(50) UNIQUE COMMENT 'Número único del diploma',
    observaciones TEXT,
    PRIMARY KEY (id_inscripcion),
    FOREIGN KEY (per_catalogo) REFERENCES alumnos(per_catalogo),
    FOREIGN KEY (id_promocion) REFERENCES promociones(id_promocion),
    UNIQUE KEY uk_inscripcion (per_catalogo, id_promocion),
    INDEX idx_catalogo (per_catalogo),
    INDEX idx_promocion (id_promocion),
    INDEX idx_estado (estado_inscripcion),
    INDEX idx_diploma (numero_diploma)
) ENGINE = InnoDB COMMENT = 'Inscripciones de alumnos a promociones';







-- INSERT para tabla ARMAS
INSERT INTO armas (arm_desc_lg, arm_desc_md, arm_desc_ct, estado) VALUES
('Infantería', 'Infantería', 'INF', 'A'),
('Caballería', 'Caballería', 'CAB', 'A'),
('Artillería', 'Artillería', 'ART', 'A'),
('Aviación', 'Aviación', 'AVC', 'A'),
('Ingenieros', 'Ingenieros', 'ING', 'A'),
('Transmisiones Militares', 'Transmisiones', 'TTMM', 'A'),
('Intendencia', 'Intendencia', 'INT', 'A'),
('Material de Guerra', 'Mat. Guerra', 'MG', 'A'),
('Sanidad Militar', 'Sanidad', 'SSM', 'A'),
('Policía Militar', 'Policía Mil.', 'PM', 'A');

-- INSERT para tabla GRADOS
-- Primer paso: Insertar todos los grados sin gra_asc
INSERT INTO grados (gra_desc_lg, gra_desc_md, gra_desc_ct, gra_asc, gra_tiempo, gra_clase, gra_demeritos, estado) VALUES
-- TROPA (Clase 1)
('Soldado de Segunda', 'Sold. 2da.', 'Sold. 2', NULL, 12, '1', 5, 'A'),
('Soldado de Primera', 'Sold. 1ra.', 'Sold 1.', NULL, 12, '1', 5, 'A'),
('Cabo', 'Cabo', 'Cabo', NULL, 18, '1', 4, 'A'),
-- CLASES (Clase 2)
('Sargento Segundo', 'Sgto. 2do.', 'S2', NULL, 24, '2', 3, 'A'),
('Sargento Primero', 'Sgto. 1ro.', 'S1', NULL, 24, '2', 2, 'A'),
('Sargento Mayor', 'Sgto. Mayor', 'SM', NULL, NULL, '2', 1, 'A'),
-- OFICIALES (Clase 3)
('Subteniente', 'Subteniente', 'Subtte', NULL, 12, '3', 3, 'A'),
('Teniente', 'Teniente', 'Tte', NULL, 24, '3', 2, 'A'),
('Capitán Segundo', 'Cap. 2do.', 'Cap 2', NULL, 24, '3', 1, 'A'),
-- JEFES (Clase 4)
('Capitán Primero', 'Cap. 1ro.', 'Cap 1', NULL, 36, '4', 0, 'A'),
('Mayor', 'Mayor', 'May', NULL, 36, '4', 0, 'A'),
-- OFICIALES SUPERIORES (Clase 5)
('Teniente Coronel', 'TC', 'TC', NULL, 48, '5', 0, 'A'),
('Coronel', 'Coronel', 'CNL', NULL, NULL, '5', 0, 'A'),
-- GENERALES (Clase 6)
('General de Brigada', 'Gral. Brig.', 'GB', NULL, NULL, '6', 0, 'A'),
('General de División', 'Gral. Div.', 'GD', NULL, NULL, '6', 0, 'A');

-- Segundo paso: Actualizar las referencias de gra_asc
UPDATE grados SET gra_asc = 2 WHERE gra_codigo = 1;
UPDATE grados SET gra_asc = 3 WHERE gra_codigo = 2;
UPDATE grados SET gra_asc = 4 WHERE gra_codigo = 3;
UPDATE grados SET gra_asc = 5 WHERE gra_codigo = 4;
UPDATE grados SET gra_asc = 6 WHERE gra_codigo = 5;
UPDATE grados SET gra_asc = 7 WHERE gra_codigo = 6;
UPDATE grados SET gra_asc = 8 WHERE gra_codigo = 7;
UPDATE grados SET gra_asc = 9 WHERE gra_codigo = 8;
UPDATE grados SET gra_asc = 10 WHERE gra_codigo = 9;
UPDATE grados SET gra_asc = 11 WHERE gra_codigo = 10;
UPDATE grados SET gra_asc = 12 WHERE gra_codigo = 11;
UPDATE grados SET gra_asc = 13 WHERE gra_codigo = 12;
UPDATE grados SET gra_asc = 14 WHERE gra_codigo = 13;
UPDATE grados SET gra_asc = 15 WHERE gra_codigo = 14;

