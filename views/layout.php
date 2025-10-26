<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tus archivos CSS existentes -->
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">

    <!-- NUEVO: CSS del navbar moderno -->
    <link rel="stylesheet" href="<?= asset('build/css/navbar-modern.css') ?>">

    <link rel="shortcut icon" href="<?= asset('images/LIMPIO.png') ?>" type="image/x-icon">

    <!-- Tus archivos JS existentes -->
    <script src="build/js/app.js"></script>

    <title>Escuela BHR</title>
</head>

<body>
    <!-- Navbar Moderno -->
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/Escuela_BHR/">
                <img src="<?= asset('images/LIMPIO.png') ?>" alt="BHR Logo" class="brand-logo">
                <span>Escuela BHR</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link modern-nav-link" href="/Escuela_BHR/">
                            <i class="bi bi-house-fill"></i>Inicio
                        </a>
                    </li>

                    <li class="nav-item dropdown modern-dropdown">
                        <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-journals"></i> Cursos
                        </a>
                        <ul class="dropdown-menu modern-dropdown-menu">
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/cursos">
                                    <i class="bi bi-eye-fill"></i> Gestión de Cursos
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown modern-dropdown">
                        <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-mortarboard-fill"></i>Alumnos
                        </a>
                        <ul class="dropdown-menu modern-dropdown-menu">
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/nuevoalumno">
                                    <i class="bi bi-person-plus"></i>Agregar Nuevo Alumno
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/alumnos">
                                    <i class="bi bi-person-video2"></i>Inscribir a Promoción
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/alumnos">
                                    <i class="bi bi-person-lines-fill"></i>Historial de Cursos
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown modern-dropdown">
                        <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-raised-hand"></i>Promociones
                        </a>
                        <ul class="dropdown-menu modern-dropdown-menu">
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/promociones">
                                    <i class="bi bi-person-fill-add"></i>Crear Nueva Promoción
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/nueva">
                                    <i class="bi bi-book-half"></i>Historial de Promociones
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown modern-dropdown">
                        <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-flag-fill"></i>Reportes
                        </a>
                        <ul class="dropdown-menu modern-dropdown-menu">
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/alumnos">
                                    <i class="bi bi-list-columns"></i>Listado por Promoción
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/alumnos">
                                    <i class="bi bi-bar-chart-fill"></i>Estadísticas
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="d-grid d-lg-block">
                    <a href="/menu/" class="btn btn-menu">
                        <i class="bi bi-arrow-bar-left"></i> MENÚ
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Progress Bar -->
    <div class="progress-bar-custom">
        <div class="progress-bar-fill" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Contenido dinámico de las vistas -->
    <div class="content-wrapper">
        <div class="container-fluid pt-3 mb-4" style="min-height: 85vh">
            <?php echo $contenido; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold; color: rgba(255, 255, 255, 0.95); text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                    Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>

</html>