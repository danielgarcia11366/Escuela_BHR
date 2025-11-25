<div class="auth-card">
    <div class="auth-logo">
        <img src="<?= asset('images/LIMPIO.png') ?>" alt="Escuela BHR">
        <h2>Iniciar Sesión</h2>
        <p style="color: #718096;">Escuela BHR - Sistema de Gestión</p>
    </div>

    <form id="formLogin">
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
            <input type="password" class="form-control" id="usu_password" name="usu_password" autocomplete="current-password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
        </button>
    </form>
</div>

<script src="<?= asset('build/js/auth/login.js') ?>"></script>