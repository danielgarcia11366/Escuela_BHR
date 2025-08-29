<h1 class="text-center">Registro de Alumnos</h1>
<div class="row justify-content-center mb-5">
    <form id="formularioAlumnos" class="border shadow p-4 col-lg-10">
        <input type="hidden" name="per_catalogo" id="per_catalogo">
        <div class="row mb-3">
            <div class="col">
                <label for="per_nom1">Primer Nombre</label>
                <input type="text" name="per_nom1" id="per_nom1" class="form-control">
            </div>
            <div class="col">
                <label for="per_nom2">Segundo Nombre</label>
                <input type="text" name="per_nom2" id="per_nom2" class="form-control">
            </div>
            <div class="col">
                <label for="per_ape1">Primer Apellido</label>
                <input type="text" name="per_ape1" id="per_ape1" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="per_ape2">Segundo Apellido</label>
                <input type="text" name="per_ape2" id="per_ape2" class="form-control">
            </div>
            <div class="col">
                <label for="per_grado">Grado</label>
                <input type="text" name="per_grado" id="per_grado" class="form-control">
            </div>
            <div class="col">
                <label for="per_arma">Arma</label>
                <input type="text" name="per_arma" id="per_arma" class="form-control">
            </div>
        </div>
        <div class="row mb-3">

            <div class="col">
                <label for="per_telefono">Telefono</label>
                <input type="text" name="per_telefono" id="per_telefono" class="form-control">
            </div>
            <div class="col">
                <label for="per_sexo">Sexo</label>
                <input type="text" name="per_sexo" id="per_sexo" class="form-control">
            </div>
            <div class="col">
                <label for="per_fec_nac">Fecha de Nacimiento</label>
                <input type="text" name="per_fec_nac" id="per_fec_nac" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="per_nac_lugar">Lugar de Nacimiento</label>
                <input type="text" name="per_nac_lugar" id="per_nac_lugar" class="form-control">
            </div>
            <div class="col">
                <label for="per_dpi">Numero de Identificación Personal</label>
                <input type="text" name="per_dpi" id="per_dpi" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <button type="submit" form="formularioCursos" id="btnGuardar" class="btn btn-success w-100"> <i class="bi bi-save"></i> Guardar</button>
            </div>
            <div class="col">
                <button type="button" id="btnModificar" class="btn btn-warning w-100">Modificar</button>
            </div>
            <div class="col">
                <button type="button" id="btnCancelar" class="btn btn-danger w-100">Cancelar</button>
            </div>
        </div>
    </form>
</div>

<h2 class="text-center">Alumnos Registrados</h2>
<div class="row justify-content-center">
    <div class="col table-responsive">
        <table id="tablaAlumnos" class="table table-bordered table-hover">
        </table>
    </div>
</div>
<script src="<?= asset('./build/js/nuevoalumno/index.js') ?>"></script>