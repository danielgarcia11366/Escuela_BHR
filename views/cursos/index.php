<style>
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
    }

    /* Estado cuando el formulario está abierto */
    .floating-btn.activo {
        background: linear-gradient(45deg, #dc3545, #c82333);
        box-shadow: 0 4px 20px rgba(220, 53, 69, 0.4);
    }

    .floating-btn.activo:hover {
        background: linear-gradient(45deg, #c82333, #bd2130);
        box-shadow: 0 6px 25px rgba(220, 53, 69, 0.6);
        transform: translateY(-3px);
    }

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

    .table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
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
        <div class="col-lg-10">
            <form id="formularioCursos" class="form-container p-4">
                <div class="form-header">
                    <h3 class="mb-0">
                        <i class="bi bi-journal-plus"></i>
                        <span id="tituloFormulario">Nuevo Curso</span>
                    </h3>
                </div>

                <input type="hidden" name="cur_codigo" id="cur_codigo">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="cur_nombre" class="form-label">
                            <i class="bi bi-bookmark"></i> Nombre del Curso *
                        </label>
                        <input type="text" name="cur_nombre" id="cur_nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cur_nombre_corto" class="form-label">
                            <i class="bi bi-tag"></i> Nombre Corto *
                        </label>
                        <input type="text" name="cur_nombre_corto" id="cur_nombre_corto" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="cur_descripcion" class="form-label">
                            <i class="bi bi-file-text"></i> Descripción del Curso
                        </label>
                        <textarea name="cur_descripcion" id="cur_descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="cur_duracion_dias" class="form-label">
                            <i class="bi bi-calendar"></i> Duración (días) *
                        </label>
                        <input type="number" name="cur_duracion_dias" id="cur_duracion_dias" class="form-control" min="1" required>
                    </div>
                    <div class="col">
                        <label for="cur_nivel" class="form-label">
                            <i class="bi bi-journals"></i> Nivel de Curso</label>
                        <select name="cur_nivel" id="cur_nivel" class="form-select">
                            <option value="#">Seleccione...</option>
                            <?php foreach ($niveles as $nivel) : ?>
                                <option value="<?= $nivel['niv_codigo'] ?>">
                                    <?= $nivel['niv_nombre'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="cur_tipo" class="form-label">
                            <i class="bi bi-check2-square"></i> Tipo de Curso</label>
                        <select name="cur_tipo" id="cur_tipo" class="form-select">
                            <option value="#">Seleccione...</option>
                            <?php foreach ($tipos as $tipo) : ?>
                                <option value="<?= $tipo['tip_codigo'] ?>">
                                    <?= $tipo['tip_nombre'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <!-- Columna para Otorga Certificado -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-award"></i> Otorga Certificado *
                        </label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cur_certificado"
                                    id="certificado_si" value="SI" required>
                                <label class="form-check-label" for="certificado_si">
                                    Sí
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cur_certificado"
                                    id="certificado_no" value="NO" checked required>
                                <label class="form-check-label" for="certificado_no">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Columna para Institución (inicialmente oculta) -->
                    <div class="col-md-8" id="contenedorInstitucion" style="display: none;">
                        <label for="cur_institucion_certifica" class="form-label">
                            <i class="bi bi-buildings"></i> Institución *
                        </label>
                        <select name="cur_institucion_certifica" id="cur_institucion_certifica" class="form-select">
                            <option value="#">Seleccione...</option>
                            <?php foreach ($instituciones as $institucion) : ?>
                                <option value="<?= $institucion['inst_codigo'] ?>">
                                    <?= $institucion['inst_nombre'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>


                <div class="row mb-4">
                    <div class="col" id="contenedorBtnGuardar">
                        <button type="submit" form="formularioCursos" id="btnGuardar" class="btn btn-success w-100">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                    <div class="col" id="contenedorBtnModificar" style="display:none;">
                        <button type="button" id="btnModificar" class="btn btn-warning w-100">
                            <i class="bi bi-pencil-square"></i> Modificar
                        </button>
                    </div>
                    <div class="col" id="contenedorBtnCancelar">
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

<script src="build/js/cursos/index.js" type="module"></script>