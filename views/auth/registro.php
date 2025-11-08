<div class="auth-card">
    <div class="auth-logo">
        <img src="<?= asset('images/LIMPIO.png') ?>" alt="Escuela BHR">
        <h2>Registro de Usuario</h2>
        <p style="color: #718096;">Crea tu cuenta en el sistema</p>
    </div>

    <form id="formRegistro">
        <div class="mb-3">
            <label for="usu_nombre" class="form-label">
                <i class="bi bi-person-fill"></i> Nombre Completo
            </label>
            <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" autocomplete="name" required>
        </div>

        <div class="mb-3">
            <label for="usu_catalogo" class="form-label">
                <i class="bi bi-person-badge"></i> Catálogo
            </label>
            <input type="number" class="form-control" id="usu_catalogo" name="usu_catalogo" required>
        </div>

        <div class="mb-3">
            <label for="usu_password" class="form-label">
                <i class="bi bi-lock-fill"></i> Contraseña
            </label>
            <input type="password" class="form-control" id="usu_password" name="usu_password" autocomplete="new-password" required>
        </div>

        <div class="mb-3">
            <label for="usu_password2" class="form-label">
                <i class="bi bi-lock-fill"></i> Confirmar Contraseña
            </label>
            <input type="password" class="form-control" id="usu_password2" name="usu_password2" autocomplete="new-password" required>
        </div>

        <div class="mb-3 text-center">
            <small style="color: #718096;">
                ¿Ya tienes cuenta?
                <a href="/Escuela_BHR/" class="auth-link">Inicia sesión</a>
            </small>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-person-plus-fill"></i> Registrar
        </button>
    </form>
</div>

<script src="<?= asset('build/js/auth/registro.js') ?>"></script>