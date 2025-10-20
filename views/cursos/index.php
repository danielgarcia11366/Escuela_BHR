<style>
        /* Botón flotante circular */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
            color: white;
            font-size: 24px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .floating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0, 123, 255, 0.6);
            background: linear-gradient(45deg, #0056b3, #004085);
        }
        
        .floating-btn:active {
            transform: translateY(0);
        }
        
        /* Animación para el formulario */
        .slide-down {
            animation: slideDown 0.5s ease-out;
        }
        
        .slide-up {
            animation: slideUp 0.3s ease-in;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-30px);
            }
        }
        
        /* Estilo para el formulario */
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border: none;
            max-width: 100%;
            margin: 0 auto;
        }
        
        .form-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }
        
        /* Estilo para la tabla */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        /* Ocultar botón normal cuando aparece el flotante */
        .btn-nuevo-hidden {
            display: none !important;
        }
    </style>

<div class="container mt-4">
        <h1 class="text-center mb-5 text-danger">Gestión de Cursos</h1>

        <!-- Botón flotante circular -->
        <button id="btnFlotante" class="floating-btn" title="Nuevo Curso">
            <i class="bi bi-plus"></i>
        </button>

        <!-- FORMULARIO (inicia oculto) -->
        <div class="row justify-content-center mb-5" id="contenedorFormulario" style="display:none;">
            <div class="col-lg-12">
                <form id="formularioCursos" class="form-container p-4">
                    <div class="form-header">
                        <h3 class="mb-0">
                            <i class="bi bi-journal-plus"></i>
                            <span id="tituloFormulario">Nuevo Curso</span>
                        </h3>
                    </div>
                    
                    <input type="hidden" name="id_curso" id="id_curso">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="codigo_curso" class="form-label">
                                <i class="bi bi-upc-scan"></i> Código del Curso
                            </label>
                            <input type="text" name="codigo_curso" id="codigo_curso" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre_curso" class="form-label">
                                <i class="bi bi-bookmark"></i> Nombre del Curso
                            </label>
                            <input type="text" name="nombre_curso" id="nombre_curso" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="descripcion" class="form-label">
                                <i class="bi bi-file-text"></i> Descripción del Curso
                            </label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="duracion_horas" class="form-label">
                                <i class="bi bi-clock"></i> Duración (horas)
                            </label>
                            <input type="number" name="duracion_horas" id="duracion_horas" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo_curso" class="form-label">
                                <i class="bi bi-layers"></i> Tipo de Curso
                            </label>
                            <select name="tipo_curso" id="tipo_curso" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="Básico">Básico</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Avanzado">Avanzado</option>
                                <option value="Especialización">Especialización</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="estado_curso" class="form-label">
                                <i class="bi bi-toggle-on"></i> Estado
                            </label>
                            <select name="estado_curso" id="estado_curso" class="form-select" required>
                                <option value="A">Activo</option>
                                <option value="I">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="area_especialidad" class="form-label">
                                <i class="bi bi-pin-map"></i> Área de Especialidad
                            </label>
                            <select name="area_especialidad" id="area_especialidad" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="Rescate Acuático">Rescate Acuático</option>
                                <option value="Montaña">Montaña</option>
                                <option value="Urbano">Urbano</option>
                                <option value="Incendios Forestales">Incendios Forestales</option>
                                <option value="Materiales Peligrosos">Materiales Peligrosos</option>
                                <option value="Primeros Auxilios">Primeros Auxilios</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="requisitos" class="form-label">
                                <i class="bi bi-card-checklist"></i> Requisitos
                            </label>
                            <textarea name="requisitos" id="requisitos" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6" id="contenedorBtnGuardar">
                            <button type="submit" form="formularioCursos" id="btnGuardar" class="btn btn-success w-100">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                        <div class="col-6" id="contenedorBtnModificar" style="display:none;">
                            <button type="button" id="btnModificar" class="btn btn-warning w-100">
                                <i class="bi bi-pencil-square"></i> Modificar
                            </button>
                        </div>
                        <div class="col-6" id="contenedorBtnCancelar">
                            <button type="button" id="btnCancelar" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLA DE CURSOS -->
        <div class="row justify-content-center" id="contenedorTabla">
            <div class="col-12">
                <div class="table-container">
                    <h2 class="text-center mb-4 text-secondary">
                        <i class="bi bi-list-ul"></i> Cursos Registrados
                    </h2>
                    <div class="table-responsive">
                        <table id="tablaCursos" class="table table-striped table-hover"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?= asset('./build/js/cursos/index.js') ?>"></script>