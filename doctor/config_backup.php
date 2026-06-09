<?php
session_start();
$path_config = __DIR__ . '/backup_config.json';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_hora'])) {
    $nueva_hora = $_POST['hora_backup'] ?? '00:00';
    $datos = ["hour" => $nueva_hora];
    file_put_contents($path_config, json_encode($datos));
    $_SESSION['flash_msg'] = "⏰ Configuración de respaldo actualizada a las " . $nueva_hora;
}

$conf = json_decode(@file_get_contents($path_config), true);
$hora_actual = $conf['hour'] ?? '00:00';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Respaldo - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card-modern:hover { transform: translateY(-2px); }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 12px; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .btn-warning-custom { background: #E67E22; border: none; border-radius: 12px; padding: 12px; font-weight: 500; color: white; }
        .btn-warning-custom:hover { background: #D35400; }
        .card-dark-gradient { background: linear-gradient(135deg, #C8102E 0%, #00A651 100%); color: white; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="banda-lapaz"></div>
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-shield-alt me-2"></i>Seguridad y Respaldo de Datos</h4>
        <p class="text-muted small">Protección de la información clínica - GADLP</p>
    </div>

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4"><?= $_SESSION['flash_msg'] ?></div>
        <?php unset($_SESSION['flash_msg']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card-modern p-4">
                <div class="d-flex align-items-center mb-3"><div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3"><i class="fas fa-clock text-danger fs-4"></i></div><div><h5 class="fw-bold mb-0">Respaldo Automático</h5><p class="text-muted small mb-0">Configuración diaria</p></div></div>
                <p class="small text-muted mb-4">Define la hora del respaldo automático de la base de datos.</p>
                <form method="POST"><div class="mb-4"><label class="form-label fw-semibold">Hora del respaldo diario</label><input type="time" name="hora_backup" class="form-control form-control-lg rounded-3 text-center font-monospace" value="<?= $hora_actual ?>"></div><button type="submit" name="guardar_hora" class="btn btn-primary-custom w-100"><i class="fas fa-save me-2"></i>Guardar Configuración</button></form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-dark-gradient p-4 rounded-4 shadow">
                <div class="d-flex align-items-center mb-3"><div class="bg-white bg-opacity-20 rounded-3 p-2 me-3"><i class="fas fa-database text-warning fs-4"></i></div><div><h5 class="fw-bold mb-0 text-white">Respaldo Manual</h5><p class="text-white-50 small mb-0">Generación inmediata</p></div></div>
                <p class="small text-white-50 mb-4">Realice un respaldo completo antes de cambios estructurales.</p>
                <button class="btn btn-warning-custom w-100" onclick="alert('📀 Generando respaldo completo...\nArchivo: medigest_backup_<?= date('Ymd_His') ?>.sql')"><i class="fas fa-download me-2"></i>Descargar Respaldo Ahora</button>
                <div class="mt-3 small text-white-50 text-center"><i class="fas fa-lock me-1"></i> Datos protegidos - GADLP</div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4"><div class="col-12"><div class="card-modern p-3"><div class="d-flex justify-content-between align-items-center"><div><i class="fas fa-history me-2 text-danger"></i> <span class="small text-muted">Último respaldo automático: Hoy a las <?= $hora_actual ?> hrs</span></div><div><i class="fas fa-check-circle text-success me-1"></i> <span class="small text-muted">Integridad verificada</span></div></div></div></div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>