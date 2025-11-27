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

    #tablaPersonal {
        border-collapse: separate;
        border-spacing: 0;
    }

    #tablaPersonal thead th {
        background: linear-gradient(135deg, #2d2d2d 0%, #404040 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    #tablaPersonal thead th:first-child {
        border-radius: 10px 0 0 0;
    }

    #tablaPersonal thead th:last-child {
        border-radius: 0 10px 0 0;
    }

    #tablaPersonal tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e2e8f0;
    }

    #tablaPersonal tbody tr:hover {
        background: #f7fafc;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #tablaPersonal tbody td {
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
            <i class="bi bi-person-lines-fill"></i>
            Gestión de Personal
        </h1>
    </div>

    <!-- Botón flotante circular -->
    <button id="btnFlotante" class="floating-btn" title="Nueva Persona">
        <i class="bi bi-plus"></i>
    </button>

    <!-- FORMULARIO (inicia oculto) -->
    <div class="row justify-content-center mb-4" id="contenedorFormulario" style="display:none;">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="form-header">
                    <h3>
                        <i class="bi bi-person-plus-fill"></i>
                        <span id="tituloFormulario">Nueva Persona</span>
                    </h3>
                </div>

                <form id="formularioPersonal" class="form-body" enctype="multipart/form-data">
                    <!-- Fila 1: Catálogo y Serie -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_catalogo" class="form-label">
                                <i class="bi bi-hash"></i> Catálogo *
                            </label>
                            <input type="number" name="per_catalogo" id="per_catalogo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="per_serie" class="form-label">
                                <i class="bi bi-upc"></i> Serie/Código
                            </label>
                            <input type="text" name="per_serie" id="per_serie" class="form-control" maxlength="8">
                        </div>
                    </div>

                    <!-- Fila 2: Grado y Arma -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_grado" class="form-label">
                                <i class="bi bi-star-fill"></i> Grado *
                            </label>
                            <select name="per_grado" id="per_grado" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($grados as $grado) : ?>
                                    <option value="<?= $grado['gra_codigo'] ?>">
                                        <?= $grado['gra_desc_lg'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="per_arma" class="form-label">
                                <i class="bi bi-shield-fill"></i> Arma *
                            </label>
                            <select name="per_arma" id="per_arma" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($armas as $arma) : ?>
                                    <option value="<?= $arma['arm_codigo'] ?>">
                                        <?= $arma['arm_desc_lg'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Nombres -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_nom1" class="form-label">
                                <i class="bi bi-person-fill"></i> Primer Nombre *
                            </label>
                            <input type="text" name="per_nom1" id="per_nom1" class="form-control" maxlength="15" required>
                        </div>
                        <div class="col-md-6">
                            <label for="per_nom2" class="form-label">
                                <i class="bi bi-person"></i> Segundo Nombre
                            </label>
                            <input type="text" name="per_nom2" id="per_nom2" class="form-control" maxlength="15">
                        </div>
                    </div>

                    <!-- Fila 4: Apellidos -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_ape1" class="form-label">
                                <i class="bi bi-person-lines-fill"></i> Primer Apellido *
                            </label>
                            <input type="text" name="per_ape1" id="per_ape1" class="form-control" maxlength="15" required>
                        </div>
                        <div class="col-md-6">
                            <label for="per_ape2" class="form-label">
                                <i class="bi bi-person-lines-fill"></i> Segundo Apellido
                            </label>
                            <input type="text" name="per_ape2" id="per_ape2" class="form-control" maxlength="15">
                        </div>
                    </div>

                    <!-- Fila 5: Sexo y Fecha de Nacimiento -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_sexo" class="form-label">
                                <i class="bi bi-gender-ambiguous"></i> Sexo *
                            </label>
                            <select name="per_sexo" id="per_sexo" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="per_fec_nac" class="form-label">
                                <i class="bi bi-calendar-event"></i> Fecha de Nacimiento *
                            </label>
                            <input type="date" name="per_fec_nac" id="per_fec_nac" class="form-control" required>
                        </div>
                    </div>

                    <!-- Fila 6: Lugar de Nacimiento -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="per_nac_lugar" class="form-label">
                                <i class="bi bi-geo-alt-fill"></i> Lugar de Nacimiento *
                            </label>
                            <input type="text" name="per_nac_lugar" id="per_nac_lugar" class="form-control" maxlength="100" required>
                        </div>
                    </div>

                    <!-- Fila 7: Tipo de Documento y DPI -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="per_tipo_doc" class="form-label">
                                <i class="bi bi-card-heading"></i> Tipo de Documento
                            </label>
                            <select name="per_tipo_doc" id="per_tipo_doc" class="form-select">
                                <option value="DPI">DPI</option>
                                <option value="CED">Cédula</option>
                                <option value="PAS">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="per_dpi" class="form-label">
                                <i class="bi bi-credit-card-2-front"></i> Número de Documento
                            </label>
                            <input type="text" name="per_dpi" id="per_dpi" class="form-control" maxlength="15">
                        </div>
                    </div>

                    <!-- Fila 8: Teléfono y Email -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_telefono" class="form-label">
                                <i class="bi bi-telephone-fill"></i> Teléfono
                            </label>
                            <input type="tel" name="per_telefono" id="per_telefono" class="form-control" maxlength="15">
                        </div>
                        <div class="col-md-6">
                            <label for="per_email" class="form-label">
                                <i class="bi bi-envelope-fill"></i> Correo Electrónico
                            </label>
                            <input type="email" name="per_email" id="per_email" class="form-control" maxlength="100">
                        </div>
                    </div>

                    <!-- Fila 9: Dirección -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="per_direccion" class="form-label">
                                <i class="bi bi-house-fill"></i> Dirección
                            </label>
                            <textarea name="per_direccion" id="per_direccion" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                    </div>

                    <!-- Fila 10: Estado y Tipo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="per_estado" class="form-label">
                                <i class="bi bi-check-circle"></i> Estado
                            </label>
                            <select name="per_estado" id="per_estado" class="form-select">
                                <option value="A">Activo</option>
                                <option value="I">Inactivo</option>
                                <option value="R">Retirado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="per_tipo" class="form-label">
                                <i class="bi bi-person-badge"></i> Tipo *
                            </label>
                            <select name="per_tipo" id="per_tipo" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="A">Alumno</option>
                                <option value="I">Instructor</option>
                                <option value="J">Jefe</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 11: Foto -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="per_foto" class="form-label">
                                <i class="bi bi-camera-fill"></i> Fotografía
                            </label>
                            <input type="file"
                                name="per_foto"
                                id="per_foto"
                                class="form-control"
                                accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Formatos permitidos: JPG, PNG (máx. 10MB)</small>

                            <!-- Vista previa -->
                            <div id="preview-container" class="mt-3" style="display:none;">
                                <img id="preview-image"
                                    src=""
                                    alt="Vista previa"
                                    style="max-width: 200px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                            </div>
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

    <!-- TABLA DE PERSONAL -->
    <div class="row justify-content-center" id="contenedorTabla">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h2>
                        <i class="bi bi-list-ul"></i> Personal Registrado
                    </h2>
                </div>
                <div class="table-responsive">
                    <table id="tablaPersonal" class="table table-hover"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS del módulo -->
<script src="build/js/personal/index.js" type="module"></script>