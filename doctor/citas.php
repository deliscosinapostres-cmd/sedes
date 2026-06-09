<?php
session_start();
$mensaje = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agendar_cita'])) {
    $mensaje = "✅ Cita agendada con éxito. Se ha enviado recordatorio al paciente.";
}

$citas = [
    ["hora" => "08:30", "paciente" => "Carlos Mendoza", "motivo" => "Control de Hipertensión", "estado" => "Confirmado", "color" => "success"],
    ["hora" => "09:15", "paciente" => "María Elena Gómez", "motivo" => "Evaluación Asmática", "estado" => "En Progreso", "color" => "primary"],
    ["hora" => "10:00", "paciente" => "Juan Pablo Roca", "motivo" => "Chequeo Post-Operatorio", "estado" => "Pendiente", "color" => "warning"],
    ["hora" => "11:30", "paciente" => "Ana Sofía Castro", "motivo" => "Consulta Pediátrica Urgente", "estado" => "Cancelado", "color" => "danger"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Agenda - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 10px 24px; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .stat-card { background: linear-gradient(135deg, #C8102E 0%, #00A651 100%); border-radius: 20px; padding: 20px; color: white; }
        .table-header { background: #F8FAFE; font-weight: 600; color: #1F2A44; border-bottom: 1px solid #E5E9F0; }
        .badge-estado { padding: 6px 12px; border-radius: 30px; font-weight: 500; font-size: 0.75rem; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="banda-lapaz"></div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-calendar-alt me-2"></i>Agenda de Citas Médicas</h4>
            <p class="text-muted small mb-0">Planificación diaria - Red de Salud La Paz</p>
        </div>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCita"><i class="fas fa-plus me-2"></i>Nueva Cita</button>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card text-center">
                <i class="fas fa-calendar-day fs-1 mb-2 opacity-75"></i>
                <h6 class="text-white-50 mb-1">Citas Programadas</h6>
                <div class="display-5 fw-bold text-warning"><?= count($citas) ?></div>
                <small class="opacity-75">Para hoy</small>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card-modern p-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="fas fa-info-circle text-danger"></i>
                    <span class="small text-muted">Horario de atención: 08:00 - 18:00 hrs | Tiempo por consulta: 30 min</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-header">
                    <tr><th>Hora</th><th>Paciente</th><th>Motivo de Consulta</th><th>Estado</th><th class="text-center">Acción</th></tr>
                </thead>
                <tbody>
                    <?php foreach($citas as $c): ?>
                    <tr>
                        <td class="fw-bold text-danger"><i class="far fa-clock me-1"></i> <?= $c['hora'] ?></td>
                        <td class="fw-bold"><?= $c['paciente'] ?></td>
                        <td><span class="text-muted small"><?= $c['motivo'] ?></span></td>
                        <td><span class="badge-estado bg-<?= $c['color'] ?> text-white"><?= $c['estado'] ?></span></td>
                        <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="alert('📞 Llamando al paciente...')"><i class="fas fa-phone me-1"></i> Llamar</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header" style="background: #C8102E; color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Programar Nueva Cita</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label fw-semibold">Paciente</label><input type="text" name="paciente" class="form-control rounded-3" required placeholder="Nombre completo"></div>
                    <div class="row mb-3"><div class="col"><label class="form-label fw-semibold">Fecha</label><input type="date" class="form-control rounded-3" required value="<?= date('Y-m-d') ?>"></div><div class="col"><label class="form-label fw-semibold">Hora</label><input type="time" class="form-control rounded-3" required></div></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Motivo / Síntomas</label><textarea class="form-control rounded-3" rows="3" placeholder="Descripción del motivo de consulta..."></textarea></div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="agendar_cita" class="btn btn-primary-custom rounded-3 px-4">Confirmar Cita</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>