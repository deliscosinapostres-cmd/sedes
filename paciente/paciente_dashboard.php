<?php
session_start();

if (!isset($_SESSION['paciente_logged']) || $_SESSION['paciente_logged'] !== true) {
    header("Location: paciente_login.php");
    exit();
}

$paciente_nombre = $_SESSION['paciente_nombre'] ?? "Paciente";
$paciente_cedula = $_SESSION['paciente_cedula'] ?? "";

// Datos simulados del paciente
$paciente_info = [
    "nombre" => "Carlos Mendoza",
    "cedula" => "10203040",
    "fecha_nacimiento" => "15/03/1979",
    "telefono" => "+591 71234567",
    "email" => "carlos.mendoza@email.com",
    "sangre" => "O+",
    "alergias" => "Penicilina",
    "antecedentes" => "Hipertensión arterial controlada"
];

$citas = [
    ["fecha" => "20/06/2026", "hora" => "09:00", "medico" => "Dr. Alejandro Vance", "motivo" => "Control de Hipertensión", "estado" => "Confirmada"],
    ["fecha" => "05/07/2026", "hora" => "11:30", "medico" => "Dra. María Fernández", "motivo" => "Evaluación de rutina", "estado" => "Pendiente"]
];

$recetas = [
    ["fecha" => "15/05/2026", "medico" => "Dr. Alejandro Vance", "medicamento" => "Losartán 50mg", "indicaciones" => "1 tableta cada 24 horas", "vigencia" => "Vigente"],
    ["fecha" => "10/02/2026", "medico" => "Dr. Alejandro Vance", "medicamento" => "Amlodipina 5mg", "indicaciones" => "1 tableta cada 12 horas", "vigencia" => "Vencida"]
];

$historial = [
    ["fecha" => "15/05/2026", "diagnostico" => "Hipertensión estable", "tratamiento" => "Continuar con Losartán", "notas" => "Paciente estable, presión controlada"],
    ["fecha" => "10/02/2026", "diagnostico" => "Pico hipertensivo", "tratamiento" => "Ajuste de medicación", "notas" => "Recomendar reducción de estrés"],
    ["fecha" => "04/01/2026", "diagnostico" => "Hipertensión esencial", "tratamiento" => "Inicio de Losartán", "notas" => "Primera consulta, se apertura expediente"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Mi Portal - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F0F2F5; }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #C8102E 0%, #8B0000 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            text-align: center;
        }
        
        .nav-menu { flex: 1; padding: 0 16px; }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 6px;
            color: rgba(255,255,255,0.85);
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .nav-item:hover { background: rgba(255,255,255,0.15); color: white; }
        .nav-item.active { background: #00A651; box-shadow: 0 4px 10px rgba(0,166,81,0.3); }
        
        .nav-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.15); margin-top: auto; }
        
        /* Contenido Principal */
        .main-content {
            margin-left: 280px;
            padding: 24px 32px;
            min-height: 100vh;
        }
        
        .card-modern {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border-radius: 20px;
            padding: 20px;
            color: white;
            text-align: center;
        }
        
        .banda-lapaz {
            height: 4px;
            background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%);
        }
        
        .avatar-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #C8102E, #00A651);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .avatar-circle i { font-size: 40px; color: white; }
        
        .section-title {
            color: #C8102E;
            font-weight: 700;
            border-left: 4px solid #C8102E;
            padding-left: 15px;
            margin-bottom: 20px;
        }
        
        .view { display: none; }
        .view.active-view { display: block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="banda-lapaz"></div>
    <div class="sidebar-header">
        <div class="avatar-circle">
            <i class="fas fa-user-circle"></i>
        </div>
        <h5 class="fw-bold mb-1"><?= htmlspecialchars($paciente_nombre) ?></h5>
        <span class="badge bg-light text-dark mt-1">Paciente</span>
        <small class="d-block mt-2 opacity-75"><i class="fas fa-id-card me-1"></i> <?= $paciente_cedula ?></small>
    </div>
    
    <div class="nav-menu">
        <div class="nav-item active" onclick="showView('dashboard')"><i class="fas fa-chart-line"></i> Mi Resumen</div>
        <div class="nav-item" onclick="showView('citas')"><i class="fas fa-calendar-check"></i> Mis Citas</div>
        <div class="nav-item" onclick="showView('recetas')"><i class="fas fa-prescription-bottle-alt"></i> Mis Recetas</div>
        <div class="nav-item" onclick="showView('historial')"><i class="fas fa-history"></i> Historial Clínico</div>
        <div class="nav-item" onclick="showView('perfil')"><i class="fas fa-user-edit"></i> Mi Perfil</div>
    </div>
    
    <div class="nav-footer">
        <div class="nav-item" onclick="location.href='paciente_logout.php'"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</div>
        <div class="small text-center mt-3 opacity-50">
            <i class="fas fa-shield-alt me-1"></i> GADLP - Salud Pública
        </div>
    </div>
</div>

<div class="main-content">
    <!-- Vista Dashboard / Resumen -->
    <div id="view-dashboard" class="view active-view">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-calendar-check fs-1 mb-2 opacity-75"></i>
                    <h3 class="fw-bold"><?= count($citas) ?></h3>
                    <small>Próximas Citas</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-pills fs-1 mb-2 opacity-75"></i>
                    <h3 class="fw-bold"><?= count(array_filter($recetas, function($r) { return $r['vigencia'] == 'Vigente'; })) ?></h3>
                    <small>Recetas Activas</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-file-alt fs-1 mb-2 opacity-75"></i>
                    <h3 class="fw-bold"><?= count($historial) ?></h3>
                    <small>Consultas Registradas</small>
                </div>
            </div>
        </div>
        
        <div class="card-modern p-4">
            <h6 class="section-title">📋 Bienvenido al Sistema de Salud de La Paz</h6>
            <p class="text-muted">Desde este portal puede gestionar sus citas médicas, consultar recetas activas y acceder a su historial clínico. Para cualquier emergencia, comuníquese al <strong>Salud Total: 800-10-5050</strong>.</p>
            <div class="alert alert-success rounded-4 mt-3">
                <i class="fas fa-check-circle me-2"></i> Su próximo control está programado para el <strong><?= $citas[0]['fecha'] ?></strong> a las <strong><?= $citas[0]['hora'] ?></strong>.
            </div>
        </div>
    </div>
    
    <!-- Vista Citas -->
    <div id="view-citas" class="view">
        <h4 class="fw-bold mb-4" style="color: #C8102E;"><i class="fas fa-calendar-alt me-2"></i>Mis Citas Médicas</h4>
        <?php foreach($citas as $c): ?>
        <div class="card-modern p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-1"><?= $c['motivo'] ?></h6>
                    <p class="small text-muted mb-0"><i class="fas fa-user-md me-1"></i> <?= $c['medico'] ?></p>
                    <p class="small text-muted mb-0"><i class="fas fa-calendar me-1"></i> <?= $c['fecha'] ?> | <i class="fas fa-clock me-1"></i> <?= $c['hora'] ?></p>
                </div>
                <div>
                    <span class="badge <?= $c['estado'] == 'Confirmada' ? 'bg-success' : 'bg-warning text-dark' ?> rounded-pill px-3 py-2"><?= $c['estado'] ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <button class="btn btn-primary-custom mt-2" style="background: #C8102E;" onclick="alert('Solicitud enviada. Un asesor lo contactará.')"><i class="fas fa-plus me-2"></i>Solicitar Nueva Cita</button>
    </div>
    
    <!-- Vista Recetas -->
    <div id="view-recetas" class="view">
        <h4 class="fw-bold mb-4" style="color: #C8102E;"><i class="fas fa-prescription-bottle-alt me-2"></i>Mis Recetas Médicas</h4>
        <?php foreach($recetas as $r): ?>
        <div class="card-modern p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold mb-1"><?= $r['medicamento'] ?></h6>
                    <p class="small text-muted mb-0"><i class="fas fa-user-md me-1"></i> <?= $r['medico'] ?></p>
                    <p class="small text-muted mb-0"><i class="fas fa-notes-medical me-1"></i> <?= $r['indicaciones'] ?></p>
                </div>
                <div>
                    <span class="badge <?= $r['vigencia'] == 'Vigente' ? 'bg-success' : 'bg-secondary' ?> rounded-pill px-3 py-2"><?= $r['vigencia'] ?></span>
                    <div class="small text-muted mt-1"><?= $r['fecha'] ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Vista Historial -->
    <div id="view-historial" class="view">
        <h4 class="fw-bold mb-4" style="color: #C8102E;"><i class="fas fa-file-medical me-2"></i>Historial Clínico</h4>
        <div class="timeline" style="border-left: 3px solid #C8102E; padding-left: 25px;">
            <?php foreach($historial as $h): ?>
            <div class="mb-4 position-relative">
                <div style="position: absolute; left: -34px; top: 5px; width: 12px; height: 12px; background: #00A651; border-radius: 50%;"></div>
                <div class="small text-danger fw-bold"><?= $h['fecha'] ?></div>
                <h6 class="fw-bold mb-1"><?= $h['diagnostico'] ?></h6>
                <p class="text-muted small mb-1"><strong>Tratamiento:</strong> <?= $h['tratamiento'] ?></p>
                <p class="text-secondary small"><?= $h['notas'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Vista Perfil -->
    <div id="view-perfil" class="view">
        <h4 class="fw-bold mb-4" style="color: #C8102E;"><i class="fas fa-user-circle me-2"></i>Mi Perfil de Salud</h4>
        <div class="card-modern p-4">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-user text-danger me-2"></i>Nombre:</strong> <?= $paciente_info['nombre'] ?></p>
                    <p><strong><i class="fas fa-id-card text-danger me-2"></i>Cédula:</strong> <?= $paciente_info['cedula'] ?></p>
                    <p><strong><i class="fas fa-calendar text-danger me-2"></i>Fecha Nacimiento:</strong> <?= $paciente_info['fecha_nacimiento'] ?></p>
                    <p><strong><i class="fas fa-tint text-danger me-2"></i>Grupo Sanguíneo:</strong> <?= $paciente_info['sangre'] ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-phone text-danger me-2"></i>Teléfono:</strong> <?= $paciente_info['telefono'] ?></p>
                    <p><strong><i class="fas fa-envelope text-danger me-2"></i>Correo:</strong> <?= $paciente_info['email'] ?></p>
                    <p><strong><i class="fas fa-allergies text-danger me-2"></i>Alergias:</strong> <?= $paciente_info['alergias'] ?></p>
                    <p><strong><i class="fas fa-notes-medical text-danger me-2"></i>Antecedentes:</strong> <?= $paciente_info['antecedentes'] ?></p>
                </div>
            </div>
            <hr>
            <button class="btn btn-outline-danger rounded-3" onclick="alert('Solicitud de actualización enviada.')"><i class="fas fa-edit me-2"></i>Solicitar Actualización de Datos</button>
        </div>
    </div>
</div>

<style>
    .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 10px 24px; color: white; font-weight: 500; }
    .btn-primary-custom:hover { background: #A00D26; }
    .view { display: none; }
    .active-view { display: block; }
</style>

<script>
    function showView(viewName) {
        document.querySelectorAll('.view').forEach(view => view.classList.remove('active-view'));
        document.getElementById(`view-${viewName}`).classList.add('active-view');
        
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        if(event && event.currentTarget) event.currentTarget.classList.add('active');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>