<?php
session_start();

if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
    header("Location: index.php");
    exit();
}

$host = $_SESSION['host'] ?? 'Localhost';
$doctor_user = $_SESSION['user'] ?? 'Doctor';

$mensaje = null;
$tipo_mensaje = 'info';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion_rapida'])) {
    $accion = $_POST['accion_rapida'];
    
    if ($accion == "RESPALDO_HC") {
        $mensaje = "✅ ¡Éxito! El historial clínico se ha respaldado correctamente.";
        $tipo_mensaje = "success";
    } elseif ($accion == "ALTA_MEDICA") {
        $mensaje = "📋 Alta médica procesada y notificada al paciente.";
        $tipo_mensaje = "info";
    } else {
        $mensaje = "❌ La acción solicitada no se pudo ejecutar.";
        $tipo_mensaje = "danger";
    }
    
    $_SESSION['flash_msg'] = $mensaje;
    $_SESSION['flash_type'] = $tipo_mensaje;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$mensaje = $_SESSION['flash_msg'] ?? null;
$tipo_mensaje = $_SESSION['flash_type'] ?? 'info';
unset($_SESSION['flash_msg'], $_SESSION['flash_type']);

$doctor_name = "Dr. Alejandro Vance";
$especialidad = "Medicina General / Cardiología";
$pacientes_hoy = 8;
$consultas_pendientes = 3;
$camas_disponibles = "85%";

$pacientes_lista = [
    ["id" => "1", "nombre" => "Carlos Mendoza", "edad" => "45", "estado" => "En Espera", "triaje" => "Verde"],
    ["id" => "2", "nombre" => "María Elena Gómez", "edad" => "29", "estado" => "En Consulta", "triaje" => "Amarillo"],
    ["id" => "3", "nombre" => "Jorge Luis Prado", "edad" => "62", "estado" => "Atendido", "triaje" => "Verde"],
    ["id" => "4", "nombre" => "Ana Sofía Castro", "edad" => "11", "estado" => "Urgencia", "triaje" => "Rojo"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Panel de Control - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F0F2F5; overflow: hidden; }
        
        /* Sidebar con colores de La Paz */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #e4113457 0%, #8B0000 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            margin-bottom: 24px;
            text-align: center;
        }
        
        .nav-menu {
            flex: 1;
            padding: 0 16px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 6px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .nav-item i { width: 22px; font-size: 1.1rem; }
        
        .nav-item:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .nav-item.active {
            background: #00A651;
            color: white;
            box-shadow: 0 4px 10px rgba(0,166,81,0.3);
        }
        
        .nav-footer {
            padding: 20px 20px 30px;
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: auto;
        }
        
        /* Contenido Principal */
        .main-content {
            margin-left: 280px;
            height: 100vh;
            overflow-y: auto;
            padding: 24px 32px;
        }
        
        .dashboard-view { display: block; }
        .iframe-view { display: none; width: 100%; height: calc(100vh - 48px); border: none; border-radius: 20px; background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        
        .card-modern {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border-radius: 20px;
            padding: 20px;
            color: white;
        }
        
        .clock {
            font-family: 'Inter', monospace;
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .btn-outline-modern {
            border-radius: 12px;
            padding: 8px 18px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .hidden { display: none !important; }
        
        .banda-lapaz {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="banda-lapaz"></div>
    <div class="sidebar-header">
        <img src="img/lp.png" alt="Logo" style="height: 55px; margin-bottom: 12px;" onerror="this.style.display='none'">
        <h5 class="fw-bold mb-1"></h5>
        <span class="badge bg-light text-dark mt-1" style="font-size:0.7rem;">GOBIERNO DE LA PAZ</span>
        <div class="clock mt-3">
            <i class="far fa-clock me-1"></i> <span id="reloj">--:--:--</span>
        </div>
        <small class="d-block mt-2 opacity-75">Servidor: <?= htmlspecialchars($host) ?></small>
    </div>
    
    <div class="nav-menu">
        <div class="nav-item active" onclick="showDashboard()"><i class="fas fa-chart-pie"></i> Panel Principal</div>
        <div class="nav-item" onclick="loadPage('pacientes.php')"><i class="fas fa-user-injured"></i> Expedientes Clínicos</div>
        <div class="nav-item" onclick="loadPage('citas.php')"><i class="fas fa-calendar-check"></i> Agenda de Citas</div>
        <div class="nav-item" onclick="loadPage('recetas.php')"><i class="fas fa-prescription-bottle-alt"></i> Recetario Digital</div>
        <div class="nav-item" onclick="loadPage('historiales.php')"><i class="fas fa-file-medical"></i> Historias Clínicas</div>
        <div class="nav-item" onclick="loadPage('farmacia.php')"><i class="fas fa-pills"></i> Farmacia / Inventario</div>
        <div class="nav-item" onclick="loadPage('config_backup.php')"><i class="fas fa-cogs"></i> Respaldo y Seguridad</div>
    </div>
    
    <div class="nav-footer">
        <div class="nav-item" onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</div>
        <div class="small text-center mt-3 opacity-50">
            <i class="fas fa-shield-alt me-1"></i> GADLP<br>Salud Pública
        </div>
    </div>
</div>

<div class="main-content">
    <div id="dashboardView" class="dashboard-view">
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <i class="fas fa-<?= $tipo_mensaje == 'success' ? 'check-circle' : 'info-circle' ?> me-2"></i>
                <?= $mensaje ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-0 opacity-75 small">Médico a cargo</p>
                            <h5 class="fw-bold mb-1"><?= $doctor_name ?></h5>
                            <span class="badge bg-light text-dark mt-1"><?= $especialidad ?></span>
                        </div>
                        <i class="fas fa-user-md fs-1 opacity-50"></i>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="row text-center">
                        <div class="col-6"><span class="small opacity-75">Hoy</span><h3 class="fw-bold mb-0 text-warning"><?= $pacientes_hoy ?></h3><small>Pacientes</small></div>
                        <div class="col-6"><span class="small opacity-75">Pendientes</span><h3 class="fw-bold mb-0 text-info"><?= $consultas_pendientes ?></h3><small>Consultas</small></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card-modern p-3 h-100">
                    <h6 class="fw-bold mb-3"><i class="fas fa-bolt text-danger me-2"></i>Acciones Rápidas</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" class="d-inline"><input type="hidden" name="accion_rapida" value="RESPALDO_HC"><button class="btn btn-outline-danger btn-outline-modern"><i class="fas fa-database me-1"></i> Respaldar HC</button></form>
                        <form method="POST" class="d-inline"><input type="hidden" name="accion_rapida" value="ALTA_MEDICA"><button class="btn btn-outline-success btn-outline-modern"><i class="fas fa-check-circle me-1"></i> Alta Médica</button></form>
                        <button class="btn btn-outline-primary btn-outline-modern" onclick="loadPage('citas.php')"><i class="fas fa-plus me-1"></i> Nueva Cita</button>
                        <button class="btn btn-outline-secondary btn-outline-modern" onclick="loadPage('recetas.php')"><i class="fas fa-print me-1"></i> Receta</button>
                    </div>
                    <div class="mt-3 small text-muted"><i class="fas fa-shield-alt text-danger me-1"></i> Todas las acciones quedan registradas en el sistema de auditoría.</div>
                </div>
            </div>
        </div>
        
        <div class="card-modern overflow-hidden">
            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="fas fa-stethoscope me-2 text-danger"></i>Monitor de Triaje y Atención</h6>
                <span class="badge bg-success">Capacidad: <?= $camas_disponibles ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-4">ID</th><th>Paciente</th><th>Edad</th><th>Estado</th><th>Triaje</th><th class="text-center">Acción</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($pacientes_lista as $p): ?>
                        <tr>
                            <td class="ps-4"><?= $p['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= $p['edad'] ?></td>
                            <td><?php if($p['estado'] == "Urgencia"): ?><span class="badge bg-danger">URGENCIA</span><?php elseif($p['estado'] == "En Consulta"): ?><span class="badge bg-primary">En Consulta</span><?php elseif($p['estado'] == "Atendido"): ?><span class="badge bg-secondary">Atendido</span><?php else: ?><span class="badge bg-warning text-dark">En Espera</span><?php endif; ?></td>
                            <td><span class="badge <?= $p['triaje'] == 'Rojo' ? 'bg-danger' : ($p['triaje'] == 'Amarillo' ? 'bg-warning text-dark' : 'bg-success') ?>"><?= $p['triaje'] ?></span></td>
                            <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="loadPage('historiales.php?id=<?= $p['id'] ?>')"><i class="fas fa-folder-open me-1"></i> Expediente</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <iframe id="iframeContent" class="iframe-view" src="about:blank"></iframe>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const dashboardView = document.getElementById('dashboardView');
    const iframe = document.getElementById('iframeContent');
    
    function loadPage(url) {
        dashboardView.classList.add('hidden');
        iframe.style.display = 'block';
        iframe.src = url;
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        if(event && event.currentTarget) event.currentTarget.classList.add('active');
    }
    
    function showDashboard() {
        iframe.style.display = 'none';
        dashboardView.classList.remove('hidden');
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        document.querySelector('.nav-item[onclick="showDashboard()"]').classList.add('active');
    }
    
    function actualizarReloj() {
        const ahora = new Date();
        const h = String(ahora.getHours()).padStart(2,'0');
        const m = String(ahora.getMinutes()).padStart(2,'0');
        const s = String(ahora.getSeconds()).padStart(2,'0');
        const reloj = document.getElementById('reloj');
        if(reloj) reloj.innerText = `${h}:${m}:${s}`;
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();
    iframe.style.display = 'none';
</script>
</body>
</html>