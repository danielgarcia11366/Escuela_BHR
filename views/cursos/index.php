<h1 class="text-center">Creación de Cursos</h1>
<div class="row justify-content-center mb-5">
<form id="formularioCursos" class="border shadow p-4 col-lg-10">
        <input type="hidden" name="cur_codigo" id="cur_codigo">
        <div class="row mb-3">
            <div class="col">
                <label for="cur_nombre">Nombre del Curso</label>
                <input type="text" name="cur_nombre" id="cur_nombre" class="form-control">
            </div>
            <div class="col">
                <label for="cur_desc_lg">Descripción del Curso</label>
                <input type="text" name="cur_desc_lg" id="cur_desc_lg" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="cur_duracion">Duración en días del Curso</label>
                <input type="number" name="cur_duracion" id="cur_duracion" class="form-control">
            </div>
            <div class="col">
                <label for="cur_fec_creacion">Fecha de Creación</label>
                <input type="date" name="cur_fec_creacion" id="cur_fec_creacion" class="form-control">
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

<h2 class="text-center">Cursos Creados</h2>
<div class="row justify-content-center">
    <div class="col table-responsive">
        <table id="tablaCursos" class="table table-bordered table-hover">
        </table>
    </div>
</div>
<script src="<?= asset('./build/js/cursos/index.js') ?>"></script>