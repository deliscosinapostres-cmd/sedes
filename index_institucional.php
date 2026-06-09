<?php
// Página principal institucional del Gobierno Autónomo Departamental de La Paz
// Acceso a los tres sistemas: Médico, Paciente y Administrativo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADLP | Portal de Servicios de Salud - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #F5F7FB;
            overflow-x: hidden;
        }
        
        /* Bandas decorativas con colores de La Paz */
        .banda-top {
            height: 6px;
            background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            position: sticky;
            top: 6px;
            z-index: 999;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .nav-link {
            font-weight: 500;
            color: #1a1a2e;
            transition: all 0.3s;
            margin: 0 5px;
        }
        
        .nav-link:hover {
            color: #C8102E;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(200,16,46,0.95) 0%, rgba(0,166,81,0.9) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(255,255,255,0.05)"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>');
            background-repeat: repeat;
            opacity: 0.3;
        }
        
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        .hero .badge-gadlp {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        /* Tarjetas de sistemas */
        .system-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            height: 100%;
            cursor: pointer;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .system-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(200,16,46,0.15);
            border-color: rgba(200,16,46,0.2);
        }
        
        .system-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .system-icon i {
            font-size: 40px;
            color: white;
        }
        
        .system-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1a1a2e;
        }
        
        .system-desc {
            color: #6B7280;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .btn-system {
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border: none;
            border-radius: 40px;
            padding: 10px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-system:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(200,16,46,0.4);
        }
        
        /* Sección de información */
        .info-section {
            padding: 60px 0;
            background: white;
        }
        
        .info-card {
            background: #F8FAFE;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            height: 100%;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .info-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #C8102E, #00A651);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .info-icon i {
            font-size: 28px;
            color: white;
        }
        
        /* Sección de sedes */
        .sede-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .sede-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .sede-img {
            height: 200px;
            background: linear-gradient(135deg, #C8102E, #00A651);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sede-img i {
            font-size: 60px;
            color: rgba(255,255,255,0.8);
        }
        
        .sede-body {
            padding: 20px;
        }
        
        .sede-title {
            font-weight: 700;
            color: #C8102E;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer a:hover {
            color: #C8102E;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background: #C8102E;
            transform: translateY(-3px);
        }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Pestañas */
        .nav-pills .nav-link {
            background: white;
            color: #1a1a2e;
            padding: 12px 30px;
            margin: 0 5px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #C8102E, #00A651);
            color: white;
        }
        
        .tab-content {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="banda-top"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-hospital-user me-2" style="color: #C8102E;"></i>
           <span style="font-size: 0.8rem; color: #00A651;">GADLP</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#sistemas">Sistemas</a></li>
                <li class="nav-item"><a class="nav-link" href="#sedes">Nuestras Sedes</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="inicio" class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="badge-gadlp">
                    <i class="fas fa-building me-2"></i> Gobierno Autónomo Departamental de La Paz
                </div>
                <h1>Sistema Integral de<br>Gestión de Salud</h1>
                <p class="lead mb-4 opacity-90">Tecnología al servicio de la salud pública. Acceda a los diferentes sistemas según su perfil: Médico, Paciente o Administrativo.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#sistemas" class="btn btn-light btn-lg rounded-pill px-4">
                        <i class="fas fa-arrow-down me-2"></i>Ver Sistemas
                    </a>
                    <a href="#sedes" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="fas fa-map-marker-alt me-2"></i>Nuestras Sedes
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center" data-aos="fade-left">
                <img src="img/escudo_la_paz.png" alt="Escudo La Paz" style="max-width: 250px;" onerror="this.style.display='none'">
                <div class="mt-3">
                    <i class="fas fa-mountain fs-1 opacity-50"></i>
                    <p class="small mt-2 opacity-75">"Por la integración pacífica y el bienestar social"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sistemas - Pestañas -->
<section id="sistemas" class="py-5" style="background: #F5F7FB;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-2">
                <i class="fas fa-microchip me-1"></i> Plataforma Digital
            </span>
            <h2 class="fw-bold" style="color: #1a1a2e;">Acceso a los Sistemas</h2>
            <p class="text-muted">Seleccione el sistema al que desea acceder según su perfil</p>
        </div>
        
        <!-- Pestañas de navegación -->
        <ul class="nav nav-pills justify-content-center mb-4" id="systemTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="medico-tab" data-bs-toggle="pill" data-bs-target="#medico" type="button" role="tab">
                    <i class="fas fa-user-md me-2"></i>Sistema Médico
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="paciente-tab" data-bs-toggle="pill" data-bs-target="#paciente" type="button" role="tab">
                    <i class="fas fa-user-injured me-2"></i>Portal del Paciente
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin" type="button" role="tab">
                    <i class="fas fa-user-tie me-2"></i>Sistema Administrativo
                </button>
            </li>
        </ul>
        
        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="systemTabContent">
            <!-- Sistema Médico -->
            <div class="tab-pane fade show active" id="medico" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="system-card" onclick="location.href='index.php'">
                            <div class="system-icon mx-auto">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <h3 class="system-title">Sistema Médico</h3>
                            <p class="system-desc">Plataforma exclusiva para profesionales de la salud. Gestione citas, recetas, expedientes clínicos y más.</p>
                            <div class="row text-start mt-3">
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Agenda de citas</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Recetario digital</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Historias clínicas</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Expedientes de pacientes</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Control de farmacia</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Copias de seguridad</li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn-system mt-3">
                                <i class="fas fa-arrow-right me-2"></i>Ingresar al Sistema Médico
                            </button>
                            <div class="mt-3">
                                <small class="text-muted">Acceso exclusivo para personal médico autorizado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Portal del Paciente -->
            <div class="tab-pane fade" id="paciente" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="system-card" onclick="location.href='paciente_login.php'">
                            <div class="system-icon mx-auto">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3 class="system-title">Portal del Paciente</h3>
                            <p class="system-desc">Acceda a su información de salud desde cualquier lugar. Consulte citas, recetas y su historial clínico.</p>
                            <div class="row text-start mt-3">
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Ver mis citas</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Consultar recetas</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Historial clínico</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Solicitar citas</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Actualizar perfil</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Descargar documentos</li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn-system mt-3">
                                <i class="fas fa-arrow-right me-2"></i>Ingresar al Portal del Paciente
                            </button>
                            <div class="mt-3">
                                <small class="text-muted">¿Nuevo? <a href="paciente_registro.php" style="color:#C8102E;">Regístrese aquí</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sistema Administrativo -->
            <div class="tab-pane fade" id="admin" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="system-card" onclick="location.href='admin_login.php'">
                            <div class="system-icon mx-auto">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="system-title">Sistema Administrativo</h3>
                            <p class="system-desc">Gestión completa del centro de salud. Controle pacientes, médicos, citas, recetas e historiales clínicos.</p>
                            <div class="row text-start mt-3">
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Gestión de pacientes</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Gestión de médicos</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Control de citas</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled small">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Emisión de recetas</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Historiales con archivos</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Reportes y estadísticas</li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn-system mt-3">
                                <i class="fas fa-arrow-right me-2"></i>Ingresar al Sistema Administrativo
                            </button>
                            <div class="mt-3">
                                <small class="text-muted">Acceso exclusivo para personal administrativo</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Información -->
<section class="info-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color: #1a1a2e;">/h2>
            <p class="text-muted">La plataforma integral de salud del Departamento de La Paz</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="info-card">
                    <div class="info-icon mx-auto"><i class="fas fa-clock"></i></div>
                    <h5 class="fw-bold">Atención 24/7</h5>
                    <p class="small text-muted">Sistema disponible las 24 horas del día, los 7 días de la semana para consultas y gestión.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card">
                    <div class="info-icon mx-auto"><i class="fas fa-lock"></i></div>
                    <h5 class="fw-bold">Datos Seguros</h5>
                    <p class="small text-muted">Información clínica protegida bajo los más altos estándares de seguridad.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="info-card">
                    <div class="info-icon mx-auto"><i class="fas fa-chart-simple"></i></div>
                    <h5 class="fw-bold">Reportes en Tiempo Real</h5>
                    <p class="small text-muted">Estadísticas actualizadas para la toma de decisiones administrativas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sedes -->
<section id="sedes" class="py-5" style="background: #F5F7FB;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2">
                <i class="fas fa-map-marker-alt me-1"></i> Nuestras Instalaciones
            </span>
            <h2 class="fw-bold" style="color: #1a1a2e;">Sedes del Departamento de La Paz</h2>
            <p class="text-muted">Centros de salud públicos conectados al sistema MediGest</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Sede Central - La Paz</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Av. 6 de Agosto, Zona Central</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2770000</p>
                        <span class="badge bg-danger">Matriz</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-church"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Hospital de Clínicas</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Zona Miraflores</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2222222</p>
                        <span class="badge bg-success">General</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Centro de Salud - El Alto</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Av. Panamericana, Zona 16 de Julio</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2830000</p>
                        <span class="badge bg-info text-white">Regional</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Centro Salud - Viacha</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Av. Bolívia, Viacha</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2450000</p>
                        <span class="badge bg-secondary">Provincial</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-water"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Centro Salud - Laja</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Plaza Principal, Laja</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2560000</p>
                        <span class="badge bg-secondary">Provincial</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="sede-card">
                    <div class="sede-img">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div class="sede-body">
                        <h5 class="sede-title">Centro de Especialidades</h5>
                        <p class="small text-muted"><i class="fas fa-location-dot me-1 text-danger"></i> Calle Potosí, La Paz</p>
                        <p class="small text-muted"><i class="fas fa-phone me-1 text-success"></i> +591 2 2440000</p>
                        <span class="badge bg-warning text-dark">Especialidades</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-bold" style="color: #1a1a2e;">¿Necesita ayuda?</h2>
                <p class="text-muted mb-4">Contáctenos para soporte técnico, dudas sobre el sistema o gestión administrativa.</p>
                <div class="mb-3">
                    <i class="fas fa-phone-alt text-danger me-3"></i>
                    <strong>Línea de soporte:</strong> 800-10-5050
                </div>
                <div class="mb-3">
                    <i class="fas fa-envelope text-danger me-3"></i>
                    <strong>Email:</strong> soporte@spk.gadlp.bo
                </div>
                <div class="mb-3">
                    <i class="fas fa-clock text-danger me-3"></i>
                    <strong>Horario de atención:</strong> Lun-Vie 08:00 a 18:00 hrs
                </div>
                <div class="mt-4">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="bg-light rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Envíenos un mensaje</h5>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6"><input type="text" class="form-control rounded-3" placeholder="Nombre"></div>
                            <div class="col-md-6"><input type="email" class="form-control rounded-3" placeholder="Email"></div>
                            <div class="col-12"><textarea class="form-control rounded-3" rows="4" placeholder="Mensaje"></textarea></div>
                            <div class="col-12"><button type="button" class="btn btn-danger rounded-3 px-4" onclick="alert('Mensaje enviado. Nos comunicaremos pronto.')"><i class="fas fa-paper-plane me-2"></i>Enviar Mensaje</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3"> GADLP</h5>
                <p class="small opacity-75">Sistema Integral de Gestión de Salud del Gobierno Autónomo Departamental de La Paz.</p>
                <div class="banda-lapaz" style="height: 3px; width: 50px; background: linear-gradient(90deg, #C8102E, #00A651);"></div>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Enlaces Rápidos</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#inicio">Inicio</a></li>
                    <li class="mb-2"><a href="#sistemas">Sistemas</a></li>
                    <li class="mb-2"><a href="#sedes">Nuestras Sedes</a></li>
                    <li class="mb-2"><a href="#contacto">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Acceso Directo</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="index.php">🩺 Sistema Médico</a></li>
                    <li class="mb-2"><a href="paciente_login.php">👤 Portal del Paciente</a></li>
                    <li class="mb-2"><a href="admin_login.php">👔 Sistema Administrativo</a></li>
                </ul>
            </div>
        </div>
        <hr class="opacity-25">
        <div class="text-center small opacity-50">
            &copy; <?= date('Y') ?> Gobierno Autónomo Departamental de La Paz - Todos los derechos reservados<br>
            <i class="fas fa-mountain me-1"></i> "Por la integración pacífica y el bienestar social"
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
    
    // Smooth scroll para enlaces
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // Redirección al hacer clic en las tarjetas (si no fue clic en botón)
    document.querySelectorAll('.system-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Si el clic no fue en el botón
            if (!e.target.closest('.btn-system')) {
                const btn = this.querySelector('.btn-system');
                if (btn) {
                    const parentLink = btn.closest('.system-card');
                    if (parentLink && parentLink.getAttribute('onclick')) {
                        // Ejecutar el onclick
                        eval(parentLink.getAttribute('onclick'));
                    }
                }
            }
        });
    });
</script>
</body>
</html>