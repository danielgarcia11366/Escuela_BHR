<style>
    /* Header de página */
    .page-header {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .page-header h1 {
        color: white;
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header h1 i {
        font-size: 2.5rem;
    }

    /* Botón flotante mejorado */
    .floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a1a1a 0%, #404040 100%);
        border: none;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
        color: white;
        font-size: 28px;
        z-index: 1000;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .floating-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
        background: linear-gradient(135deg, #2d2d2d 0%, #505050 100%);
    }

    .floating-btn.activo {
        background: linear-gradient(135deg, #dc3545, #c82333);
        box-shadow: 0 6px 25px rgba(220, 53, 69, 0.4);
    }

    .floating-btn.activo:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        box-shadow: 0 10px 35px rgba(220, 53, 69, 0.5);
    }

    /* Animaciones */
    .slide-down {
        animation: slideDown 0.4s ease-out;
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

    /* Formulario moderno */
    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: white;
        padding: 1.5rem 2rem;
        margin: 0;
    }

    .form-header h3 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-body {
        padding: 2rem;
    }

    /* Inputs mejorados */
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2d2d2d;
        box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
    }

    /* Tabla mejorada */
    .table-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        overflow: hidden;
    }

    .table-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #e2e8f0;
    }

    .table-header h2 {
        color: #1a1a1a;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* DataTable personalizado */
    .dataTables_wrapper {
        padding: 0;
    }

    .dataTables_length select,
    .dataTables_filter input {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .dataTables_length select:focus,
    .dataTables_filter input:focus {
        border-color: #2d2d2d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
    }

    .dataTables_length label,
    .dataTables_filter label {
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #tablaPromociones thead th {
        background: linear-gradient(135deg, #2d2d2d 0%, #404040 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    #tablaPromociones tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e2e8f0;
    }

    #tablaPromociones tbody tr:hover {
        background: #f7fafc;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #tablaPromociones tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #4a5568;
    }

    /* Botones de acción mejorados */
    .btn-acciones {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        min-width: 45px;
        height: 40px;
        cursor: pointer;
    }

    .btn-modificar {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
    }

    .btn-modificar:hover {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    }

    .btn-eliminar {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-eliminar:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    /* Botones principales */
    .btn-primary-custom {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary-custom {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
    }

    /* Paginación */
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem !important;
        border: 2px solid #e2e8f0 !important;
        background: white !important;
        color: #4a5568 !important;
        transition: all 0.3s ease;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #2d2d2d !important;
        border-color: #2d2d2d !important;
        color: white !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        border-color: #1a1a1a !important;
        color: white !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }

        .floating-btn {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }

        .table-container {
            padding: 1rem;
        }
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header moderno -->
    <div class="page-header">
        <h1>
            <i class="bi bi-calendar-check"></i>
            Promociones
        </h1>
    </div>

    <!-- Botón flotante circular -->
    <button id="btnFlotante" class="floating-btn" title="Nueva Promoción">
        <i class="bi bi-plus"></i>
    </button>

    <!-- FORMULARIO (inicia oculto) -->
    <div class="row justify-content-center mb-4" id="contenedorFormulario" style="display:none;">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="form-header">
                    <h3>
                        <i class="bi bi-calendar-plus"></i>
                        <span id="tituloFormulario">Nueva Promoción</span>
                    </h3>
                </div>

                <form id="formularioPromocion" class="form-body">
                    <input type="hidden" name="pro_codigo" id="pro_codigo">

                    <!-- Fila 1: Curso y Número -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pro_curso" class="form-label">
                                <i class="bi bi-book-fill"></i> Curso *
                            </label>
                            <select name="pro_curso" id="pro_curso" class="form-select" required>
                                <option value="">Seleccione un curso...</option>
                                <?php foreach ($cursos as $curso) : ?>
                                    <option value="<?= $curso['cur_codigo'] ?>">
                                        <?= $curso['cur_nombre'] . ' - ' . $curso['nivel_nombre'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="pro_numero" class="form-label">
                                <i class="bi bi-hash"></i> Número *
                            </label>
                            <input type="text" name="pro_numero" id="pro_numero" class="form-control"
                                maxlength="20" placeholder="Ej: 2024-01, I-2024" required>
                        </div>
                        <div class="col-md-3">
                            <label for="pro_anio" class="form-label">
                                <i class="bi bi-calendar3"></i> Año *
                            </label>
                            <input type="number" name="pro_anio" id="pro_anio" class="form-control"
                                min="1900" max="2100" value="<?= date('Y') ?>" required>
                        </div>
                    </div>

                    <!-- Fila 2: Fechas -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="pro_fecha_inicio" class="form-label">
                                <i class="bi bi-calendar-event"></i> Fecha Inicio *
                            </label>
                            <input type="date" name="pro_fecha_inicio" id="pro_fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="pro_fecha_fin" class="form-label">
                                <i class="bi bi-calendar-check"></i> Fecha Fin *
                            </label>
                            <input type="date" name="pro_fecha_fin" id="pro_fecha_fin" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="pro_fecha_graduacion" class="form-label">
                                <i class="bi bi-mortarboard-fill"></i> Fecha Graduación
                            </label>
                            <input type="date" name="pro_fecha_graduacion" id="pro_fecha_graduacion" class="form-control">
                        </div>
                    </div>

                    <!-- Fila 3: Lugar y País -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="pro_lugar" class="form-label">
                                <i class="bi bi-geo-alt-fill"></i> Lugar
                            </label>
                            <input type="text" name="pro_lugar" id="pro_lugar" class="form-control"
                                maxlength="150" placeholder="Lugar donde se impartió el curso">
                        </div>
                        <div class="col-md-4">
                            <label for="pro_pais" class="form-label">
                                <i class="bi bi-globe-americas"></i> País
                            </label>
                            <select name="pro_pais" id="pro_pais" class="form-select">
                                <option value="">Guatemala (por defecto)</option>
                                <?php foreach ($paises as $pais) : ?>
                                    <option value="<?= $pais['pais_codigo'] ?>">
                                        <?= $pais['pais_nombre'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 4: Institución y Graduados -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="pro_institucion_imparte" class="form-label">
                                <i class="bi bi-building"></i> Institución que imparte
                            </label>
                            <select name="pro_institucion_imparte" id="pro_institucion_imparte" class="form-select">
                                <option value="">Seleccione institución...</option>
                                <?php foreach ($instituciones as $institucion) : ?>
                                    <option value="<?= $institucion['inst_codigo'] ?>">
                                        <?= $institucion['inst_nombre'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="pro_cantidad_graduados" class="form-label">
                                <i class="bi bi-people-fill"></i> Graduados
                            </label>
                            <input type="number" name="pro_cantidad_graduados" id="pro_cantidad_graduados"
                                class="form-control" min="0" value="0">
                        </div>
                    </div>

                    <!-- Fila 5: Estado -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="pro_activa" class="form-label">
                                <i class="bi bi-check-circle"></i> Estado
                            </label>
                            <select name="pro_activa" id="pro_activa" class="form-select">
                                <option value="S" selected>Activa</option>
                                <option value="N">Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 6: Observaciones -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="pro_observaciones" class="form-label">
                                <i class="bi bi-chat-left-text"></i> Observaciones
                            </label>
                            <textarea name="pro_observaciones" id="pro_observaciones"
                                class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="row">
                        <div class="col" id="contenedorBtnGuardar">
                            <button type="submit" id="btnGuardar" class="btn btn-primary-custom w-100">
                                <i class="bi bi-save-fill"></i> Guardar
                            </button>
                        </div>
                        <div class="col" id="contenedorBtnModificar" style="display:none;">
                            <button type="button" id="btnModificar" class="btn btn-primary-custom w-100">
                                <i class="bi bi-pencil-square"></i> Modificar
                            </button>
                        </div>
                        <div class="col" id="contenedorBtnCancelar" style="display:none;">
                            <button type="button" id="btnCancelar" class="btn btn-secondary-custom w-100">
                                <i class="bi bi-x-circle-fill"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TABLA DE PROMOCIONES -->
    <div class="row justify-content-center" id="contenedorTabla">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h2>
                        <i class="bi bi-list-ul"></i> Promociones Registradas
                    </h2>
                </div>
                <div class="table-responsive">
                    <table id="tablaPromociones" class="table table-hover"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS del módulo -->
<script src="build/js/promociones/index.js" type="module"></script>