<?php
// Verificar si hay sesión iniciada
$isAdmin = isset($_SESSION['ADMINISTRADOR']);
$isInstructor = isset($_SESSION['INSTRUCTOR']);
$nombreUsuario = $_SESSION['user']['usu_nombre'] ?? 'Usuario';
?>
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
            <a class="navbar-brand" href="/Escuela_BHR/menu">
                <img src="<?= asset('images/LIMPIO.png') ?>" alt="BHR Logo" class="brand-logo">
                <span>Escuela BHR</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if ($isAdmin): ?>
                        <!-- MENÚ COMPLETO PARA ADMINISTRADOR -->
                        <li class="nav-item dropdown modern-dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill"></i> Administración
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <li> <!-- ✅ Sin "nav-item" -->
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/usuarios">
                                        <i class="bi bi-people-fill"></i> Usuarios Sistema
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- MENÚ COMPLETO PARA ADMINISTRADOR -->
                        <li class="nav-item dropdown modern-dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill"></i> Gestión de Personal
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/instructores">
                                        <i class="bi bi-person-raised-hand"></i> Instructores
                                    </a>
                                </li>
                                <li> <!-- ✅ Sin "nav-item" -->
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/alumnosbhr">
                                        <i class="bi bi-people-fill"></i> Alumnos BHR
                                    </a>
                                </li>
                            </ul>
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
                                <i class="bi bi-calendar-event"></i>Promociones
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/promociones">
                                        <i class="bi bi-person-fill-add"></i>Crear Nueva Promoción
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/historial">
                                        <i class="bi bi-book-half"></i>Historial de Promociones
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
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/participantes">
                                        <i class="bi bi-person-video2"></i>Asignación de Promociones
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/record">
                                        <i class="bi bi-person-lines-fill"></i>Récord de Cursos (Por Alumno)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown modern-dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-flag-fill"></i>Reportes
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <!-- 
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/reporte/alumnos">
                                        <i class="bi bi-list-columns"></i>Listado por Promoción
                                    </a>
                                </li>-->
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/estadisticas">
                                        <i class="bi bi-bar-chart-fill"></i>Estadísticas
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php elseif ($isInstructor): ?>
                        <!-- MENÚ LIMITADO PARA INSTRUCTOR -->
                        <li class="nav-item dropdown modern-dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill"></i> Administración
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/personal">
                                        <i class="bi bi-person-raised-hand"></i> Gestión de Personal
                                    </a>
                                </li>
                            </ul>
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
                                <i class="bi bi-calendar-event"></i>Promociones
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/promociones">
                                        <i class="bi bi-person-fill-add"></i>Crear Nueva Promoción
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/historial">
                                        <i class="bi bi-book-half"></i>Historial de Promociones
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
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/participantes">
                                        <i class="bi bi-person-video2"></i>Asignación de Promociones
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/record">
                                        <i class="bi bi-person-lines-fill"></i>Récord de Cursos (Por Alumno)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown modern-dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-flag-fill"></i>Reportes
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu">
                                <!-- 
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/reporte/alumnos">
                                        <i class="bi bi-list-columns"></i>Listado por Promoción
                                    </a>
                                </li>-->
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="/Escuela_BHR/estadisticas">
                                        <i class="bi bi-bar-chart-fill"></i>Estadísticas
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Usuario y Logout -->
                <div class="d-flex align-items-center gap-3">
                    <div class="user-info d-none d-lg-block">
                        <small style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-person-circle"></i>
                            <?= htmlspecialchars($nombreUsuario) ?>
                            <?php if ($isAdmin): ?>
                                <span class="badge bg-success">Admin</span>
                            <?php elseif ($isInstructor): ?>
                                <span class="badge bg-info">Instructor</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <a href="/Escuela_BHR/logout" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
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