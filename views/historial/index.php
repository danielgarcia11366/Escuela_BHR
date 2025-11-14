<style>
    /* === Fondo general === */
    body {
        background: linear-gradient(135deg, #ff7b00 0%, #ffb347 20%, #ffde7d 40%, #88d498 70%, #4ac29a 100%);
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        min-height: 100vh;
    }

    /* === Encabezado negro === */
    .page-header {
        background: #000 !important;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .page-header h1 {
        font-weight: 700;
        font-size: 2.3rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header i {
        color: #ffb347;
    }

    .page-header p {
        color: #ccc;
        margin: 0.5rem 0 0;
        font-size: 1.1rem;
    }

    /* === Tarjetas negras === */
    .info-card {
        background: #000 !important;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .info-card:hover {
        transform: translateY(-4px);
    }

    .info-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff7b00, #ffb347);
        color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .info-card-content h3 {
        color: #fff;
        font-size: 1.8rem;
        margin: 0;
    }

    .info-card-content p {
        color: #bbb;
        margin: 0;
    }

    /* === TABLA CON FONDO BLANCO === */
    .table-container {
        background: white !important;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }

    .table-header h2 {
        color: #1a1a1a !important;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #e2e8f0;
    }

    /* Encabezado de tabla con gradiente oscuro */
    #tablaHistorial thead th {
        background: linear-gradient(135deg, #2d2d2d 0%, #404040 100%) !important;
        color: white !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        font-weight: 600;
        border: none;
        padding: 1rem;
    }

    #tablaHistorial thead th:first-child {
        border-radius: 10px 0 0 0;
    }

    #tablaHistorial thead th:last-child {
        border-radius: 0 10px 0 0;
    }

    /* Filas de la tabla con fondo blanco */
    #tablaHistorial tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e2e8f0;
    }

    #tablaHistorial tbody td {
        background: white !important;
        color: #4a5568 !important;
        border-top: 1px solid #e2e8f0;
        padding: 1rem;
        vertical-align: middle;
    }

    #tablaHistorial tbody tr:hover {
        background: #f7fafc !important;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #tablaHistorial tbody tr:hover td {
        background: #f7fafc !important;
    }

    /* === Botón PDF === */
    .btn-ver-pdf {
        background: linear-gradient(135deg, #ff7b00ff, #ff7b00ff);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-ver-pdf:hover {
        background: linear-gradient(135deg, #000000ff, #000000ff);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        color: #fff;
    }

    .btn-ver-pdf i {
        font-size: 1.1rem;
    }

    /* === DataTables === */
    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_length label {
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        background: white;
        color: #4a5568;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #2d2d2d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
    }

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

    .dataTables_info {
        color: #4a5568 !important;
        font-weight: 500;
    }

    /* === Scrollbar === */
    ::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #ff7b00, #ffb347);
        border-radius: 10px;
    }

    /* === Responsive === */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .table-container {
            padding: 1rem;
        }

        .info-card {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="bi bi-clock-history"></i> Historial de Promociones</h1>
        <p>Consulta el historial completo de promociones y descarga reportes en PDF</p>
    </div>

    <!-- Tarjetas resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="info-card-content">
                    <h3 id="totalPromociones">0</h3>
                    <p>Promociones Registradas</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="info-card-content">
                    <h3 id="totalParticipantes">0</h3>
                    <p>Total de Participantes</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div class="info-card-content">
                    <h3 id="promocionesConCert">0</h3>
                    <p>Promociones Certificadas</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="info-card-content">
                    <h3 id="totalGraduados">0</h3>
                    <p>Total de Graduados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla principal -->
    <div class="table-container">
        <div class="table-header">
            <h2>
                <i class="bi bi-list-ul"></i> Listado de Promociones
                <small class="text-muted" style="font-size: 0.7rem; margin-left: 1rem;">
                    <i class="bi bi-info-circle"></i> Haz clic en "VER" para generar el Listado de Participantes
                </small>
            </h2>
        </div>
        <div class="table-responsive">
            <table id="tablaHistorial" class="table table-hover w-100"></table>
        </div>
    </div>
</div>

<script src="build/js/promociones/historial.js" type="module"></script>