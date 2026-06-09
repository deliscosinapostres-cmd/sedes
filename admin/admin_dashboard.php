<?php
session_start();

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$admin_nombre = $_SESSION['admin_nombre'];
$admin_rol = $_SESSION['admin_rol'];

// Directorio para guardar historiales clínicos
$historiales_dir = __DIR__ . '/historiales_subidos/';
if (!file_exists($historiales_dir)) {
    mkdir($historiales_dir, 0777, true);
}

// Base de datos simulada (en producción usar MySQL)
// Pacientes
$pacientes = isset($_SESSION['admin_pacientes']) ? $_SESSION['admin_pacientes'] : [
    ["id" => "PAC-001", "nombre" => "Carlos Mendoza", "cedula" => "10203040", "telefono" => "71234567", "email" => "carlos@email.com", "fecha_nac" => "1979-03-15", "direccion" => "Av. 6 de Agosto N° 123", "sangre" => "O+", "alergias" => "Penicilina", "estado" => "Activo"],
    ["id" => "PAC-002", "nombre" => "María Elena Gómez", "cedula" => "45678901", "telefono" => "79876543", "email" => "maria@email.com", "fecha_nac" => "1995-07-22", "direccion" => "Calle Loayza N° 456", "sangre" => "A-", "alergias" => "Ninguna", "estado" => "Activo"],
    ["id" => "PAC-003", "nombre" => "Jorge Luis Prado", "cedula" => "83321122", "telefono" => "60011223", "email" => "jorge@email.com", "fecha_nac" => "1962-11-05", "direccion" => "Zona Sur Calle 10", "sangre" => "O+", "alergias" => "Sulfa", "estado" => "Activo"],
    ["id" => "PAC-004", "nombre" => "Ana Sofía Castro", "cedula" => "99887766", "telefono" => "75544332", "email" => "ana@email.com", "fecha_nac" => "2015-04-12", "direccion" => "Bajo Següencoma", "sangre" => "B+", "alergias" => "AINEs", "estado" => "Activo"],
];

// Médicos
$medicos = isset($_SESSION['admin_medicos']) ? $_SESSION['admin_medicos'] : [
    ["id" => "MED-001", "nombre" => "Dr. Alejandro Vance", "especialidad" => "Cardiología", "matricula" => "12345", "telefono" => "71122334", "email" => "alejandro.vance@medigest.com", "horario" => "08:00-12:00", "estado" => "Activo"],
    ["id" => "MED-002", "nombre" => "Dra. Ana María Soto", "especialidad" => "Pediatría", "matricula" => "12346", "telefono" => "71122335", "email" => "ana.soto@medigest.com", "horario" => "14:00-18:00", "estado" => "Activo"],
    ["id" => "MED-003", "nombre" => "Dr. Roberto Méndez", "especialidad" => "Traumatología", "matricula" => "12347", "telefono" => "71122336", "email" => "roberto.mendez@medigest.com", "horario" => "08:00-12:00", "estado" => "Activo"],
];

// Citas
$citas = isset($_SESSION['admin_citas']) ? $_SESSION['admin_citas'] : [
    ["id" => "CIT-001", "fecha" => "2026-06-20", "hora" => "09:00", "paciente_id" => "PAC-001", "paciente_nombre" => "Carlos Mendoza", "medico_id" => "MED-001", "medico_nombre" => "Dr. Alejandro Vance", "motivo" => "Control de Hipertensión", "estado" => "Confirmada"],
    ["id" => "CIT-002", "fecha" => "2026-06-20", "hora" => "11:00", "paciente_id" => "PAC-002", "paciente_nombre" => "María Elena Gómez", "medico_id" => "MED-002", "medico_nombre" => "Dra. Ana María Soto", "motivo" => "Control Pediátrico", "estado" => "Confirmada"],
    ["id" => "CIT-003", "fecha" => "2026-06-21", "hora" => "10:00", "paciente_id" => "PAC-003", "paciente_nombre" => "Jorge Luis Prado", "medico_id" => "MED-003", "medico_nombre" => "Dr. Roberto Méndez", "motivo" => "Control Post-Operatorio", "estado" => "Pendiente"],
];

// Recetas
$recetas = isset($_SESSION['admin_recetas']) ? $_SESSION['admin_recetas'] : [
    ["id" => "RX-001", "fecha" => "2026-05-15", "paciente_id" => "PAC-001", "paciente_nombre" => "Carlos Mendoza", "medico_id" => "MED-001", "medico_nombre" => "Dr. Alejandro Vance", "medicamentos" => "Losartán 50mg - 1 tableta cada 24 horas", "indicaciones" => "Tomar después del desayuno", "vigencia" => "2026-08-15"],
    ["id" => "RX-002", "fecha" => "2026-05-10", "paciente_id" => "PAC-002", "paciente_nombre" => "María Elena Gómez", "medico_id" => "MED-002", "medico_nombre" => "Dra. Ana María Soto", "medicamentos" => "Paracetamol 500mg - 1 tableta cada 8 horas", "indicaciones" => "En caso de fiebre", "vigencia" => "2026-07-10"],
];

// Historiales clínicos (con archivos)
$historiales = isset($_SESSION['admin_historiales']) ? $_SESSION['admin_historiales'] : [
    ["id" => "HIS-001", "fecha" => "2026-05-15", "paciente_id" => "PAC-001", "paciente_nombre" => "Carlos Mendoza", "diagnostico" => "Hipertensión controlada", "tratamiento" => "Continuar con Losartán", "notas" => "Paciente estable, presión 120/80", "archivos" => []],
    ["id" => "HIS-002", "fecha" => "2026-02-10", "paciente_id" => "PAC-001", "paciente_nombre" => "Carlos Mendoza", "diagnostico" => "Pico hipertensivo", "tratamiento" => "Ajuste de medicación", "notas" => "Paciente con estrés laboral", "archivos" => []],
];

// Procesar formularios
$mensaje = null;
$error = null;

// CRUD Pacientes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardar_paciente'])) {
        $nuevo_id = "PAC-" . str_pad(count($pacientes) + 1, 3, "0", STR_PAD_LEFT);
        $nuevo_paciente = [
            "id" => $nuevo_id,
            "nombre" => $_POST['nombre'],
            "cedula" => $_POST['cedula'],
            "telefono" => $_POST['telefono'],
            "email" => $_POST['email'],
            "fecha_nac" => $_POST['fecha_nac'],
            "direccion" => $_POST['direccion'],
            "sangre" => $_POST['sangre'],
            "alergias" => $_POST['alergias'],
            "estado" => "Activo"
        ];
        $pacientes[] = $nuevo_paciente;
        $_SESSION['admin_pacientes'] = $pacientes;
        $mensaje = "✅ Paciente registrado correctamente con ID: " . $nuevo_id;
    }
    
    if (isset($_POST['editar_paciente'])) {
        foreach ($pacientes as $key => $p) {
            if ($p['id'] == $_POST['paciente_id']) {
                $pacientes[$key]['nombre'] = $_POST['nombre'];
                $pacientes[$key]['cedula'] = $_POST['cedula'];
                $pacientes[$key]['telefono'] = $_POST['telefono'];
                $pacientes[$key]['email'] = $_POST['email'];
                $pacientes[$key]['fecha_nac'] = $_POST['fecha_nac'];
                $pacientes[$key]['direccion'] = $_POST['direccion'];
                $pacientes[$key]['sangre'] = $_POST['sangre'];
                $pacientes[$key]['alergias'] = $_POST['alergias'];
                break;
            }
        }
        $_SESSION['admin_pacientes'] = $pacientes;
        $mensaje = "✅ Paciente actualizado correctamente";
    }
    
    if (isset($_POST['eliminar_paciente'])) {
        foreach ($pacientes as $key => $p) {
            if ($p['id'] == $_POST['paciente_id']) {
                unset($pacientes[$key]);
                break;
            }
        }
        $_SESSION['admin_pacientes'] = array_values($pacientes);
        $mensaje = "✅ Paciente eliminado correctamente";
    }
    
    // CRUD Médicos
    if (isset($_POST['guardar_medico'])) {
        $nuevo_id = "MED-" . str_pad(count($medicos) + 1, 3, "0", STR_PAD_LEFT);
        $nuevo_medico = [
            "id" => $nuevo_id,
            "nombre" => $_POST['nombre'],
            "especialidad" => $_POST['especialidad'],
            "matricula" => $_POST['matricula'],
            "telefono" => $_POST['telefono'],
            "email" => $_POST['email'],
            "horario" => $_POST['horario'],
            "estado" => "Activo"
        ];
        $medicos[] = $nuevo_medico;
        $_SESSION['admin_medicos'] = $medicos;
        $mensaje = "✅ Médico registrado correctamente con ID: " . $nuevo_id;
    }
    
    if (isset($_POST['editar_medico'])) {
        foreach ($medicos as $key => $m) {
            if ($m['id'] == $_POST['medico_id']) {
                $medicos[$key]['nombre'] = $_POST['nombre'];
                $medicos[$key]['especialidad'] = $_POST['especialidad'];
                $medicos[$key]['matricula'] = $_POST['matricula'];
                $medicos[$key]['telefono'] = $_POST['telefono'];
                $medicos[$key]['email'] = $_POST['email'];
                $medicos[$key]['horario'] = $_POST['horario'];
                break;
            }
        }
        $_SESSION['admin_medicos'] = $medicos;
        $mensaje = "✅ Médico actualizado correctamente";
    }
    
    if (isset($_POST['eliminar_medico'])) {
        foreach ($medicos as $key => $m) {
            if ($m['id'] == $_POST['medico_id']) {
                unset($medicos[$key]);
                break;
            }
        }
        $_SESSION['admin_medicos'] = array_values($medicos);
        $mensaje = "✅ Médico eliminado correctamente";
    }
    
    // CRUD Citas
    if (isset($_POST['guardar_cita'])) {
        $nuevo_id = "CIT-" . str_pad(count($citas) + 1, 3, "0", STR_PAD_LEFT);
        
        // Buscar nombres
        $paciente_nombre = "";
        foreach ($pacientes as $p) {
            if ($p['id'] == $_POST['paciente_id']) $paciente_nombre = $p['nombre'];
        }
        $medico_nombre = "";
        foreach ($medicos as $m) {
            if ($m['id'] == $_POST['medico_id']) $medico_nombre = $m['nombre'];
        }
        
        $nueva_cita = [
            "id" => $nuevo_id,
            "fecha" => $_POST['fecha'],
            "hora" => $_POST['hora'],
            "paciente_id" => $_POST['paciente_id'],
            "paciente_nombre" => $paciente_nombre,
            "medico_id" => $_POST['medico_id'],
            "medico_nombre" => $medico_nombre,
            "motivo" => $_POST['motivo'],
            "estado" => "Pendiente"
        ];
        $citas[] = $nueva_cita;
        $_SESSION['admin_citas'] = $citas;
        $mensaje = "✅ Cita programada correctamente con ID: " . $nuevo_id;
    }
    
    if (isset($_POST['actualizar_estado_cita'])) {
        foreach ($citas as $key => $c) {
            if ($c['id'] == $_POST['cita_id']) {
                $citas[$key]['estado'] = $_POST['estado'];
                break;
            }
        }
        $_SESSION['admin_citas'] = $citas;
        $mensaje = "✅ Estado de cita actualizado";
    }
    
    if (isset($_POST['eliminar_cita'])) {
        foreach ($citas as $key => $c) {
            if ($c['id'] == $_POST['cita_id']) {
                unset($citas[$key]);
                break;
            }
        }
        $_SESSION['admin_citas'] = array_values($citas);
        $mensaje = "✅ Cita eliminada correctamente";
    }
    
    // CRUD Recetas
    if (isset($_POST['guardar_receta'])) {
        $nuevo_id = "RX-" . str_pad(count($recetas) + 1, 3, "0", STR_PAD_LEFT);
        
        $paciente_nombre = "";
        foreach ($pacientes as $p) {
            if ($p['id'] == $_POST['paciente_id']) $paciente_nombre = $p['nombre'];
        }
        $medico_nombre = "";
        foreach ($medicos as $m) {
            if ($m['id'] == $_POST['medico_id']) $medico_nombre = $m['nombre'];
        }
        
        $nueva_receta = [
            "id" => $nuevo_id,
            "fecha" => date('Y-m-d'),
            "paciente_id" => $_POST['paciente_id'],
            "paciente_nombre" => $paciente_nombre,
            "medico_id" => $_POST['medico_id'],
            "medico_nombre" => $medico_nombre,
            "medicamentos" => $_POST['medicamentos'],
            "indicaciones" => $_POST['indicaciones'],
            "vigencia" => $_POST['vigencia']
        ];
        $recetas[] = $nueva_receta;
        $_SESSION['admin_recetas'] = $recetas;
        $mensaje = "✅ Receta emitida correctamente con ID: " . $nuevo_id;
    }
    
    // CRUD Historiales Clínicos con subida de archivos
    if (isset($_POST['guardar_historial'])) {
        $nuevo_id = "HIS-" . str_pad(count($historiales) + 1, 3, "0", STR_PAD_LEFT);
        
        $paciente_nombre = "";
        foreach ($pacientes as $p) {
            if ($p['id'] == $_POST['paciente_id']) $paciente_nombre = $p['nombre'];
        }
        
        // Procesar archivos subidos
        $archivos_subidos = [];
        if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
            for ($i = 0; $i < count($_FILES['archivos']['name']); $i++) {
                if ($_FILES['archivos']['error'][$i] == 0) {
                    $nombre_archivo = time() . "_" . $paciente_nombre . "_" . $_FILES['archivos']['name'][$i];
                    $ruta_destino = $historiales_dir . $nombre_archivo;
                    if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $ruta_destino)) {
                        $archivos_subidos[] = [
                            "nombre" => $_FILES['archivos']['name'][$i],
                            "ruta" => $ruta_destino,
                            "tipo" => $_FILES['archivos']['type'][$i],
                            "fecha" => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }
        
        $nuevo_historial = [
            "id" => $nuevo_id,
            "fecha" => date('Y-m-d'),
            "paciente_id" => $_POST['paciente_id'],
            "paciente_nombre" => $paciente_nombre,
            "diagnostico" => $_POST['diagnostico'],
            "tratamiento" => $_POST['tratamiento'],
            "notas" => $_POST['notas'],
            "archivos" => $archivos_subidos
        ];
        $historiales[] = $nuevo_historial;
        $_SESSION['admin_historiales'] = $historiales;
        $mensaje = "✅ Historial clínico registrado con " . count($archivos_subidos) . " archivo(s) adjunto(s)";
    }
}

// Estadísticas
$total_pacientes = count($pacientes);
$total_medicos = count($medicos);
$citas_hoy = count(array_filter($citas, function($c) { return $c['fecha'] == date('Y-m-d'); }));
$citas_semana = count(array_filter($citas, function($c) { return $c['fecha'] >= date('Y-m-d') && $c['fecha'] <= date('Y-m-d', strtotime('+7 days')); }));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Panel Administrativo - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F0F2F5; overflow-x: hidden; }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1a 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }
        
        .sidebar-header { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); }
        .nav-menu { flex: 1; padding: 0 16px; }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 6px;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background: #C8102E; }
        .nav-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto; }
        
        .main-content { margin-left: 280px; padding: 24px 32px; min-height: 100vh; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; overflow: hidden; }
        .stat-card { background: white; border-radius: 20px; padding: 20px; border-left: 4px solid #C8102E; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-number { font-size: 2rem; font-weight: 700; color: #C8102E; }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 10px 20px; color: white; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .btn-success-custom { background: #00A651; border: none; border-radius: 12px; padding: 8px 16px; color: white; }
        .view { display: none; }
        .view.active-view { display: block; }
        .table-header { background: #F8FAFE; font-weight: 600; border-bottom: 2px solid #E5E9F0; }
        .avatar-circle { width: 50px; height: 50px; background: linear-gradient(135deg, #C8102E, #00A651); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; }
        .avatar-circle i { font-size: 24px; color: white; }
        .status-badge { padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 600; }
        .file-attachment { background: #f8f9fa; border-radius: 10px; padding: 8px; margin-bottom: 5px; }
        .file-attachment i { margin-right: 8px; color: #C8102E; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="banda-lapaz"></div>
    <div class="sidebar-header">
        <div class="avatar-circle mx-auto"><i class="fas fa-building"></i></div>
        <h5 class="fw-bold mb-1"> Admin</h5>
        <span class="badge bg-danger mt-1"><?= htmlspecialchars($admin_rol) ?></span>
        <small class="d-block mt-2"><i class="fas fa-user me-1"></i> <?= htmlspecialchars($admin_nombre) ?></small>
    </div>
    <div class="nav-menu">
        <div class="nav-item active" onclick="showView('dashboard')"><i class="fas fa-chart-pie"></i> Dashboard</div>
        <div class="nav-item" onclick="showView('pacientes')"><i class="fas fa-users"></i> Pacientes</div>
        <div class="nav-item" onclick="showView('medicos')"><i class="fas fa-user-md"></i> Médicos</div>
        <div class="nav-item" onclick="showView('citas')"><i class="fas fa-calendar-check"></i> Citas</div>
        <div class="nav-item" onclick="showView('recetas')"><i class="fas fa-prescription-bottle-alt"></i> Recetas</div>
        <div class="nav-item" onclick="showView('historiales')"><i class="fas fa-folder-medical"></i> Historiales Clínicos</div>
    </div>
    <div class="nav-footer">
        <div class="nav-item" onclick="location.href='admin_logout.php'"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</div>
        <div class="small text-center mt-3 opacity-50">GADLP - Gestión Administrativa</div>
    </div>
</div>

<div class="main-content">
    <?php if ($mensaje): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4"><?= $mensaje ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Dashboard -->
    <div id="view-dashboard" class="view active-view">
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><small class="text-muted">Pacientes</small><div class="stat-number"><?= $total_pacientes ?></div></div><i class="fas fa-users fa-2x text-muted opacity-50"></i></div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><small class="text-muted">Médicos</small><div class="stat-number"><?= $total_medicos ?></div></div><i class="fas fa-user-md fa-2x text-muted opacity-50"></i></div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><small class="text-muted">Citas Hoy</small><div class="stat-number"><?= $citas_hoy ?></div></div><i class="fas fa-calendar-day fa-2x text-muted opacity-50"></i></div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><small class="text-muted">Citas Semana</small><div class="stat-number"><?= $citas_semana ?></div></div><i class="fas fa-calendar-week fa-2x text-muted opacity-50"></i></div></div></div>
        </div>
        <div class="card-modern p-4"><h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-danger me-2"></i>Bienvenido al Sistema de Gestión Administrativa</h6><p class="text-muted mb-0">Desde aquí puede gestionar completamente el centro de salud: pacientes, médicos, citas, recetas e historiales clínicos con subida de archivos.</p></div>
    </div>

    <!-- Gestión de Pacientes -->
    <div id="view-pacientes" class="view">
        <div class="d-flex justify-content-between align-items-center mb-4"><h4 class="fw-bold" style="color:#C8102E;"><i class="fas fa-users me-2"></i>Gestión de Pacientes</h4><button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalPaciente"><i class="fas fa-plus me-2"></i>Nuevo Paciente</button></div>
        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header"><tr><th>ID</th><th>Nombre</th><th>Cédula</th><th>Teléfono</th><th>Email</th><th>Grupo Sanguíneo</th><th>Estado</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($pacientes as $p): ?>
                        <tr>
                            <td class="fw-bold text-danger"><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= $p['cedula'] ?></td>
                            <td><?= $p['telefono'] ?></td>
                            <td><?= $p['email'] ?></td>
                            <td><span class="badge bg-light text-dark"><?= $p['sangre'] ?></span></td>
                            <td><span class="status-badge bg-success"><?= $p['estado'] ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="editarPaciente('<?= $p['id'] ?>','<?= htmlspecialchars($p['nombre']) ?>','<?= $p['cedula'] ?>','<?= $p['telefono'] ?>','<?= $p['email'] ?>','<?= $p['fecha_nac'] ?>','<?= htmlspecialchars($p['direccion']) ?>','<?= $p['sangre'] ?>','<?= htmlspecialchars($p['alergias']) ?>')"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminarPaciente('<?= $p['id'] ?>')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestión de Médicos -->
    <div id="view-medicos" class="view">
        <div class="d-flex justify-content-between align-items-center mb-4"><h4 class="fw-bold" style="color:#C8102E;"><i class="fas fa-user-md me-2"></i>Gestión de Médicos</h4><button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalMedico"><i class="fas fa-plus me-2"></i>Nuevo Médico</button></div>
        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header"><tr><th>ID</th><th>Nombre</th><th>Especialidad</th><th>Matrícula</th><th>Teléfono</th><th>Horario</th><th>Estado</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($medicos as $m): ?>
                        <tr>
                            <td class="fw-bold text-danger"><?= $m['id'] ?></td>
                            <td><?= htmlspecialchars($m['nombre']) ?></td>
                            <td><?= $m['especialidad'] ?></td>
                            <td><?= $m['matricula'] ?></td>
                            <td><?= $m['telefono'] ?></td>
                            <td><?= $m['horario'] ?></td>
                            <td><span class="status-badge bg-success"><?= $m['estado'] ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="editarMedico('<?= $m['id'] ?>','<?= htmlspecialchars($m['nombre']) ?>','<?= $m['especialidad'] ?>','<?= $m['matricula'] ?>','<?= $m['telefono'] ?>','<?= $m['email'] ?>','<?= $m['horario'] ?>')"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminarMedico('<?= $m['id'] ?>')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestión de Citas -->
    <div id="view-citas" class="view">
        <div class="d-flex justify-content-between align-items-center mb-4"><h4 class="fw-bold" style="color:#C8102E;"><i class="fas fa-calendar-alt me-2"></i>Gestión de Citas</h4><button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCita"><i class="fas fa-plus me-2"></i>Programar Cita</button></div>
        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header"><tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Paciente</th><th>Médico</th><th>Motivo</th><th>Estado</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($citas as $c): ?>
                        <tr>
                            <td class="fw-bold text-danger"><?= $c['id'] ?></td>
                            <td><?= $c['fecha'] ?></td>
                            <td><?= $c['hora'] ?></td>
                            <td><?= $c['paciente_nombre'] ?></td>
                            <td><?= $c['medico_nombre'] ?></td>
                            <td><?= $c['motivo'] ?></td>
                            <td>
                                <select class="form-select form-select-sm" style="width: auto;" onchange="actualizarEstadoCita('<?= $c['id'] ?>', this.value)">
                                    <option value="Pendiente" <?= $c['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="Confirmada" <?= $c['estado'] == 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                    <option value="Cancelada" <?= $c['estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                    <option value="Completada" <?= $c['estado'] == 'Completada' ? 'selected' : '' ?>>Completada</option>
                                </select>
                            </td>
                            <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminarCita('<?= $c['id'] ?>')"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestión de Recetas -->
    <div id="view-recetas" class="view">
        <div class="d-flex justify-content-between align-items-center mb-4"><h4 class="fw-bold" style="color:#C8102E;"><i class="fas fa-prescription-bottle-alt me-2"></i>Recetas Médicas</h4><button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalReceta"><i class="fas fa-plus me-2"></i>Nueva Receta</button></div>
        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header"><tr><th>ID</th><th>Fecha</th><th>Paciente</th><th>Médico</th><th>Medicamentos</th><th>Vigencia</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($recetas as $r): ?>
                        <tr>
                            <td class="fw-bold text-danger"><?= $r['id'] ?></td>
                            <td><?= $r['fecha'] ?></td>
                            <td><?= $r['paciente_nombre'] ?></td>
                            <td><?= $r['medico_nombre'] ?></td>
                            <td><?= substr($r['medicamentos'], 0, 50) ?>...</td>
                            <td><span class="status-badge <?= $r['vigencia'] >= date('Y-m-d') ? 'bg-success' : 'bg-secondary' ?>"><?= $r['vigencia'] ?></span></td>
                            <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill" onclick="verReceta('<?= $r['id'] ?>')"><i class="fas fa-print"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestión de Historiales Clínicos -->
    <div id="view-historiales" class="view">
        <div class="d-flex justify-content-between align-items-center mb-4"><h4 class="fw-bold" style="color:#C8102E;"><i class="fas fa-folder-medical me-2"></i>Historiales Clínicos</h4><button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalHistorial"><i class="fas fa-plus me-2"></i>Nuevo Historial</button></div>
        <div class="card-modern">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header"><tr><th>ID</th><th>Fecha</th><th>Paciente</th><th>Diagnóstico</th><th>Tratamiento</th><th>Archivos</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($historiales as $h): ?>
                        <tr>
                            <td class="fw-bold text-danger"><?= $h['id'] ?></td>
                            <td><?= $h['fecha'] ?></td>
                            <td><?= $h['paciente_nombre'] ?></td>
                            <td><?= substr($h['diagnostico'], 0, 40) ?>...</td>
                            <td><?= substr($h['tratamiento'], 0, 40) ?>...</td>
                            <td>
                                <?php if(count($h['archivos']) > 0): ?>
                                    <span class="badge bg-info"><?= count($h['archivos']) ?> archivo(s)</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="verHistorial('<?= $h['id'] ?>')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminarHistorial('<?= $h['id'] ?>')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="modalPaciente" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-4"><div class="modal-header" style="background:#C8102E;color:white"><h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Registrar Paciente</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form method="POST"><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="form-label">Nombre Completo</label><input type="text" name="nombre" class="form-control rounded-3" required></div><div class="col-md-6"><label class="form-label">Cédula de Identidad</label><input type="text" name="cedula" class="form-control rounded-3" required></div><div class="col-md-4"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control rounded-3" required></div><div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control rounded-3" required></div><div class="col-md-4"><label class="form-label">Fecha Nacimiento</label><input type="date" name="fecha_nac" class="form-control rounded-3" required></div><div class="col-md-12"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control rounded-3"></div><div class="col-md-6"><label class="form-label">Grupo Sanguíneo</label><select name="sangre" class="form-select rounded-3"><option>O+</option><option>A+</option><option>B+</option><option>AB+</option><option>O-</option><option>A-</option></select></div><div class="col-md-6"><label class="form-label">Alergias</label><input type="text" name="alergias" class="form-control rounded-3" placeholder="Ej: Penicilina, Sulfa..."></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_paciente" class="btn btn-primary-custom">Guardar Paciente</button></div></form></div></div></div>

<div class="modal fade" id="modalMedico" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content rounded-4"><div class="modal-header" style="background:#C8102E;color:white"><h5 class="modal-title"><i class="fas fa-user-md me-2"></i>Registrar Médico</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form method="POST"><div class="modal-body"><div class="mb-3"><label>Nombre Completo</label><input type="text" name="nombre" class="form-control rounded-3" required></div><div class="mb-3"><label>Especialidad</label><input type="text" name="especialidad" class="form-control rounded-3" required></div><div class="mb-3"><label>Matrícula</label><input type="text" name="matricula" class="form-control rounded-3" required></div><div class="mb-3"><label>Teléfono</label><input type="text" name="telefono" class="form-control rounded-3"></div><div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control rounded-3"></div><div class="mb-3"><label>Horario</label><select name="horario" class="form-select rounded-3"><option>08:00-12:00</option><option>14:00-18:00</option><option>08:00-18:00</option></select></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_medico" class="btn btn-primary-custom">Guardar Médico</button></div></form></div></div></div>

<div class="modal fade" id="modalCita" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content rounded-4"><div class="modal-header" style="background:#C8102E;color:white"><h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Programar Cita</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form method="POST"><div class="modal-body"><div class="mb-3"><label>Paciente</label><select name="paciente_id" class="form-select rounded-3" required><?php foreach($pacientes as $p): ?><option value="<?= $p['id'] ?>"><?= $p['nombre'] ?> (<?= $p['id'] ?>)</option><?php endforeach; ?></select></div><div class="mb-3"><label>Médico</label><select name="medico_id" class="form-select rounded-3" required><?php foreach($medicos as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nombre'] ?> (<?= $m['especialidad'] ?>)</option><?php endforeach; ?></select></div><div class="row mb-3"><div class="col"><label>Fecha</label><input type="date" name="fecha" class="form-control rounded-3" required value="<?= date('Y-m-d') ?>"></div><div class="col"><label>Hora</label><input type="time" name="hora" class="form-control rounded-3" required></div></div><div class="mb-3"><label>Motivo</label><textarea name="motivo" class="form-control rounded-3" rows="2" required></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_cita" class="btn btn-primary-custom">Agendar Cita</button></div></form></div></div></div>

<div class="modal fade" id="modalReceta" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-4"><div class="modal-header" style="background:#C8102E;color:white"><h5 class="modal-title"><i class="fas fa-prescription me-2"></i>Emitir Receta</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form method="POST"><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label>Paciente</label><select name="paciente_id" class="form-select rounded-3" required><?php foreach($pacientes as $p): ?><option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label>Médico</label><select name="medico_id" class="form-select rounded-3" required><?php foreach($medicos as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nombre'] ?></option><?php endforeach; ?></select></div><div class="col-12"><label>Medicamentos / Tratamiento</label><textarea name="medicamentos" class="form-control rounded-3" rows="4" required placeholder="Ej: Losartán 50mg - 1 tableta cada 24 horas durante 30 días"></textarea></div><div class="col-12"><label>Indicaciones Adicionales</label><textarea name="indicaciones" class="form-control rounded-3" rows="2" placeholder="Tomar después del desayuno, evitar alimentos grasos..."></textarea></div><div class="col-md-6"><label>Fecha de Vigencia</label><input type="date" name="vigencia" class="form-control rounded-3" value="<?= date('Y-m-d', strtotime('+90 days')) ?>" required></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_receta" class="btn btn-primary-custom">Emitir Receta</button></div></form></div></div></div>

<div class="modal fade" id="modalHistorial" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content rounded-4"><div class="modal-header" style="background:#C8102E;color:white"><h5 class="modal-title"><i class="fas fa-file-medical me-2"></i>Agregar Historial Clínico</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form method="POST" enctype="multipart/form-data"><div class="modal-body"><div class="mb-3"><label>Paciente</label><select name="paciente_id" class="form-select rounded-3" required><?php foreach($pacientes as $p): ?><option value="<?= $p['id'] ?>"><?= $p['nombre'] ?> (<?= $p['cedula'] ?>)</option><?php endforeach; ?></select></div><div class="mb-3"><label>Diagnóstico</label><input type="text" name="diagnostico" class="form-control rounded-3" required placeholder="Ej: Hipertensión Grado 1"></div><div class="mb-3"><label>Tratamiento Indicado</label><textarea name="tratamiento" class="form-control rounded-3" rows="3" required placeholder="Medicamentos, terapias, etc."></textarea></div><div class="mb-3"><label>Notas Adicionales</label><textarea name="notas" class="form-control rounded-3" rows="3" placeholder="Evolución, recomendaciones, etc."></textarea></div><div class="mb-3"><label><i class="fas fa-paperclip me-2"></i>Adjuntar Archivos (PDF, Imágenes, Documentos)</label><input type="file" name="archivos[]" class="form-control rounded-3" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></div><small class="text-muted">Puede seleccionar múltiples archivos. Formatos permitidos: PDF, JPG, PNG, DOC, DOCX</small></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_historial" class="btn btn-primary-custom">Guardar Historial</button></div></form></div></div></div>

<form id="formEliminar" method="POST" style="display:none;"><input type="hidden" name="eliminar_paciente" id="eliminar_paciente_input"><input type="hidden" name="paciente_id" id="eliminar_paciente_id"></form>
<form id="formEliminarMedico" method="POST" style="display:none;"><input type="hidden" name="eliminar_medico" id="eliminar_medico_input"><input type="hidden" name="medico_id" id="eliminar_medico_id"></form>
<form id="formEliminarCita" method="POST" style="display:none;"><input type="hidden" name="eliminar_cita" id="eliminar_cita_input"><input type="hidden" name="cita_id" id="eliminar_cita_id"></form>
<form id="formEstadoCita" method="POST" style="display:none;"><input type="hidden" name="actualizar_estado_cita" id="estado_cita_input"><input type="hidden" name="cita_id" id="estado_cita_id"><input type="hidden" name="estado" id="estado_cita_valor"></form>
<form id="formEditarPaciente" method="POST" style="display:none;"><input type="hidden" name="editar_paciente" id="editar_paciente_input"><input type="hidden" name="paciente_id" id="editar_paciente_id"><input type="hidden" name="nombre" id="editar_nombre"><input type="hidden" name="cedula" id="editar_cedula"><input type="hidden" name="telefono" id="editar_telefono"><input type="hidden" name="email" id="editar_email"><input type="hidden" name="fecha_nac" id="editar_fecha_nac"><input type="hidden" name="direccion" id="editar_direccion"><input type="hidden" name="sangre" id="editar_sangre"><input type="hidden" name="alergias" id="editar_alergias"></form>
<form id="formEditarMedico" method="POST" style="display:none;"><input type="hidden" name="editar_medico" id="editar_medico_input"><input type="hidden" name="medico_id" id="editar_medico_id"><input type="hidden" name="nombre" id="editar_medico_nombre"><input type="hidden" name="especialidad" id="editar_especialidad"><input type="hidden" name="matricula" id="editar_matricula"><input type="hidden" name="telefono" id="editar_medico_telefono"><input type="hidden" name="email" id="editar_medico_email"><input type="hidden" name="horario" id="editar_horario"></form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showView(viewName) {
        document.querySelectorAll('.view').forEach(v => v.classList.remove('active-view'));
        document.getElementById(`view-${viewName}`).classList.add('active-view');
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        if(event && event.currentTarget) event.currentTarget.classList.add('active');
    }
    
    function eliminarPaciente(id) { if(confirm('¿Eliminar este paciente? Los historiales quedarán archivados.')) { document.getElementById('eliminar_paciente_input').value='1'; document.getElementById('eliminar_paciente_id').value=id; document.getElementById('formEliminar').submit(); } }
    function eliminarMedico(id) { if(confirm('¿Eliminar este médico?')) { document.getElementById('eliminar_medico_input').value='1'; document.getElementById('eliminar_medico_id').value=id; document.getElementById('formEliminarMedico').submit(); } }
    function eliminarCita(id) { if(confirm('¿Cancelar y eliminar esta cita?')) { document.getElementById('eliminar_cita_input').value='1'; document.getElementById('eliminar_cita_id').value=id; document.getElementById('formEliminarCita').submit(); } }
    function actualizarEstadoCita(id, estado) { document.getElementById('estado_cita_input').value='1'; document.getElementById('estado_cita_id').value=id; document.getElementById('estado_cita_valor').value=estado; document.getElementById('formEstadoCita').submit(); }
    
    function editarPaciente(id, nombre, cedula, telefono, email, fecha_nac, direccion, sangre, alergias) {
        document.getElementById('editar_paciente_input').value='1';
        document.getElementById('editar_paciente_id').value=id;
        document.getElementById('editar_nombre').value=nombre;
        document.getElementById('editar_cedula').value=cedula;
        document.getElementById('editar_telefono').value=telefono;
        document.getElementById('editar_email').value=email;
        document.getElementById('editar_fecha_nac').value=fecha_nac;
        document.getElementById('editar_direccion').value=direccion;
        document.getElementById('editar_sangre').value=sangre;
        document.getElementById('editar_alergias').value=alergias;
        document.getElementById('formEditarPaciente').submit();
    }
    
    function editarMedico(id, nombre, especialidad, matricula, telefono, email, horario) {
        document.getElementById('editar_medico_input').value='1';
        document.getElementById('editar_medico_id').value=id;
        document.getElementById('editar_medico_nombre').value=nombre;
        document.getElementById('editar_especialidad').value=especialidad;
        document.getElementById('editar_matricula').value=matricula;
        document.getElementById('editar_medico_telefono').value=telefono;
        document.getElementById('editar_medico_email').value=email;
        document.getElementById('editar_horario').value=horario;
        document.getElementById('formEditarMedico').submit();
    }
    
    function verReceta(id) { alert('Receta ID: ' + id + '\n\nFuncionalidad de impresión en desarrollo.\nEn producción generaría PDF.'); }
    function verHistorial(id) { alert('Ver historial ID: ' + id + '\n\nAquí se mostrarían los archivos adjuntos.'); }
    function eliminarHistorial(id) { if(confirm('¿Eliminar este historial?')) alert('Funcionalidad en desarrollo'); }
</script>
</body>
</html>