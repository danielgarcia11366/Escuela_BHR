<!-- Hero Section con Gradiente -->
<div class="hero-section mb-5">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="display-4 fw-bold text-white mb-3">
          Escuela de Adiestramiento de Asistencia Humanitaria y Rescate
        </h1>
        <p class="lead text-white-50 mb-4">
          Formando profesionales en rescate y asistencia humanitaria con los más altos estándares de calidad
        </p>
        <div class="d-flex gap-3">
          <a href="/Escuela_BHR/cursos" class="btn btn-light btn-lg">
            <i class="bi bi-journal-plus"></i> Nuevo Curso
          </a>
          <a href="/Escuela_BHR/promociones" class="btn btn-outline-light btn-lg">
            <i class="bi bi-calendar-plus"></i> Nueva Promoción
          </a>
        </div>
      </div>
      <div class="col-lg-4 text-center">
        <img src="./images/NEW.png" class="hero-logo" alt="Escuela BHR">
      </div>
    </div>
  </div>
</div>

<!-- Dashboard de Estadísticas -->
<div class="container-fluid">
  <div class="row g-4 mb-4">
    <!-- Card 1: Total Alumnos -->
    <div class="col-xl-3 col-md-6">
      <div class="stat-card stat-card-blue">
        <div class="stat-icon">
          <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" id="totalAlumnos">0</h3>
          <p class="stat-label">Total Alumnos</p>
          <small class="stat-change positive">
            <i class="bi bi-arrow-up"></i> Activos
          </small>
        </div>
      </div>
    </div>

    <!-- Card 2: Cursos Activos -->
    <div class="col-xl-3 col-md-6">
      <div class="stat-card stat-card-green">
        <div class="stat-icon">
          <i class="bi bi-book-half"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" id="cursosActivos">0</h3>
          <p class="stat-label">Cursos Disponibles</p>
          <small class="stat-change">
            <i class="bi bi-journal-check"></i> En catálogo
          </small>
        </div>
      </div>
    </div>

    <!-- Card 3: Promociones -->
    <div class="col-xl-3 col-md-6">
      <div class="stat-card stat-card-orange">
        <div class="stat-icon">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" id="promocionesActivas">0</h3>
          <p class="stat-label">Promociones Activas</p>
          <small class="stat-change">
            <i class="bi bi-clock-history"></i> En curso
          </small>
        </div>
      </div>
    </div>

    <!-- Card 4: Graduados -->
    <div class="col-xl-3 col-md-6">
      <div class="stat-card stat-card-purple">
        <div class="stat-icon">
          <i class="bi bi-award-fill"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" id="graduados">0</h3>
          <p class="stat-label">Graduados</p>
          <small class="stat-change positive">
            <i class="bi bi-trophy"></i> Certificados
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Accesos Rápidos -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <h3 class="section-title">
        <i class="bi bi-lightning-charge-fill"></i> Accesos Rápidos
      </h3>
    </div>

    <div class="col-lg-3 col-md-6">
      <a href="/Escuela_BHR/participantes" class="quick-access-card">
        <div class="quick-icon bg-primary">
          <i class="bi bi-person-plus-fill"></i>
        </div>
        <h5>Registrar Persona</h5>
        <p>Agregar nuevo Instructor o Alumno</p>
      </a>
    </div>

    <div class="col-lg-3 col-md-6">
      <a href="/Escuela_BHR/cursos" class="quick-access-card">
        <div class="quick-icon bg-success">
          <i class="bi bi-journals"></i>
        </div>
        <h5>Gestionar Cursos</h5>
        <p>Administrar catálogo de cursos</p>
      </a>
    </div>

    <div class="col-lg-3 col-md-6">
      <a href="/Escuela_BHR/promociones" class="quick-access-card">
        <div class="quick-icon bg-warning">
          <i class="bi bi-calendar-plus-fill"></i>
        </div>
        <h5>Nueva Promoción</h5>
        <p>Crear promoción y asignar alumnos</p>
      </a>
    </div>

    <div class="col-lg-3 col-md-6">
      <a href="/Escuela_BHR/alumnos" class="quick-access-card">
        <div class="quick-icon bg-info">
          <i class="bi bi-file-earmark-bar-graph"></i>
        </div>
        <h5>Ver Reportes</h5>
        <p>Estadísticas y listados</p>
      </a>
    </div>
  </div>

  <!-- Actividad Reciente -->
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="activity-card">
        <div class="activity-header">
          <h4><i class="bi bi-clock-history"></i> Actividad Reciente</h4>
        </div>
        <div class="activity-body">
          <div class="activity-item">
            <div class="activity-icon bg-success">
              <i class="bi bi-person-check"></i>
            </div>
            <div class="activity-content">
              <h6>Nuevo Alumno Registrado</h6>
              <p>Se agregó un nuevo estudiante al sistema</p>
              <small><i class="bi bi-clock"></i> Hace 2 horas</small>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon bg-primary">
              <i class="bi bi-calendar-event"></i>
            </div>
            <div class="activity-content">
              <h6>Promoción Iniciada</h6>
              <p>Nueva promoción de curso básico</p>
              <small><i class="bi bi-clock"></i> Hace 5 horas</small>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon bg-warning">
              <i class="bi bi-award"></i>
            </div>
            <div class="activity-content">
              <h6>Graduación Completada</h6>
              <p>15 alumnos recibieron certificación</p>
              <small><i class="bi bi-clock"></i> Hace 1 día</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="info-card">
        <div class="info-header">
          <h4><i class="bi bi-info-circle-fill"></i> Información</h4>
        </div>
        <div class="info-body">
          <div class="info-item">
            <i class="bi bi-telephone-fill"></i>
            <div>
              <strong>Contacto</strong>
              <p>+502 5833-9248</p>
            </div>
          </div>
          <div class="info-item">
            <i class="bi bi-envelope-fill"></i>
            <div>
              <strong>Email</strong>
              <p>info@escuelabhr.mil.gt</p>
            </div>
          </div>
          <div class="info-item">
            <i class="bi bi-geo-alt-fill"></i>
            <div>
              <strong>Ubicación</strong>
              <p>Brigada Militar Mariscal Zavala, Guatemala</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Hero Section */
  .hero-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #404040 100%);
    padding: 4rem 0;
    border-radius: 20px;
    margin-top: 1rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  }

  .hero-logo {
    max-width: 300px;
    filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(-20px);
    }
  }

  /* Stat Cards */
  .stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-left: 4px solid;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  }

  .stat-card-blue {
    border-color: #4299e1;
  }

  .stat-card-green {
    border-color: #48bb78;
  }

  .stat-card-orange {
    border-color: #ed8936;
  }

  .stat-card-purple {
    border-color: #9f7aea;
  }

  .stat-icon {
    font-size: 3rem;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
  }

  .stat-card-blue .stat-icon {
    color: #4299e1;
    background: #ebf8ff;
  }

  .stat-card-green .stat-icon {
    color: #48bb78;
    background: #f0fff4;
  }

  .stat-card-orange .stat-icon {
    color: #ed8936;
    background: #fffaf0;
  }

  .stat-card-purple .stat-icon {
    color: #9f7aea;
    background: #faf5ff;
  }

  .stat-content {
    flex: 1;
  }

  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
  }

  .stat-label {
    color: #718096;
    margin: 0;
    font-size: 0.95rem;
  }

  .stat-change {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #edf2f7;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #4a5568;
  }

  .stat-change.positive {
    background: #c6f6d5;
    color: #22543d;
  }

  /* Section Title */
  .section-title {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  /* Quick Access Cards */
  .quick-access-card {
    display: block;
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    text-align: center;
  }

  .quick-access-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    color: inherit;
  }

  .quick-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
  }

  .quick-access-card h5 {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
  }

  .quick-access-card p {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
  }

  /* Activity Card */
  .activity-card,
  .info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .activity-header,
  .info-header {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 1.5rem;
    color: white;
  }

  .activity-header h4,
  .info-header h4 {
    margin: 0;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .activity-body,
  .info-body {
    padding: 1.5rem;
  }

  .activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: #f7fafc;
    transition: all 0.3s ease;
  }

  .activity-item:hover {
    background: #edf2f7;
    transform: translateX(5px);
  }

  .activity-item:last-child {
    margin-bottom: 0;
  }

  .activity-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
  }

  .activity-content h6 {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.25rem;
  }

  .activity-content p {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
  }

  .activity-content small {
    color: #a0aec0;
    font-size: 0.85rem;
  }

  /* Info Card */
  .info-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    align-items: center;
  }

  .info-item:last-child {
    border-bottom: none;
  }

  .info-item i {
    font-size: 1.5rem;
    color: #2563eb;
  }

  .info-item strong {
    display: block;
    color: #2d3748;
    font-size: 0.9rem;
  }

  .info-item p {
    margin: 0;
    color: #718096;
    font-size: 0.9rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .hero-section {
      padding: 2rem 0;
      text-align: center;
    }

    .hero-logo {
      max-width: 200px;
      margin-top: 2rem;
    }

    .stat-card {
      flex-direction: column;
      text-align: center;
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      font-size: 2rem;
    }
  }
</style>

<script src="build/js/inicio.js"></script>