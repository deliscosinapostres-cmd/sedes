<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Historia Clínica - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .timeline { border-left: 3px solid #C8102E; padding-left: 30px; position: relative; }
        .timeline-item { position: relative; margin-bottom: 32px; }
        .timeline-item::before { content: ""; position: absolute; width: 14px; height: 14px; background: #00A651; border-radius: 50%; left: -38px; top: 6px; border: 2px solid white; box-shadow: 0 0 0 3px #E5E9F0; }
        .timeline-date { font-size: 0.75rem; color: #C8102E; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .timeline-title { font-weight: 700; margin-bottom: 8px; color: #1F2A44; }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 10px 24px; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="banda-lapaz"></div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-notes-medical me-2"></i>Historia Clínica</h4>
            <p class="text-muted small">Línea de evolución - GADLP</p>
        </div>
        <button class="btn btn-primary-custom" onclick="alert('📝 Abriendo editor de notas clínicas...')"><i class="fas fa-plus me-2"></i>Nueva Nota</button>
    </div>

    <div class="card-modern p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <h5 class="fw-bold mb-1">Carlos Mendoza</h5>
                <span class="badge bg-secondary rounded-pill">Expediente: PAC-001</span>
                <span class="badge bg-light text-dark border rounded-pill ms-2"><i class="fas fa-calendar me-1"></i> 45 años</span>
            </div>
            <div>
                <span class="badge bg-success rounded-pill"><i class="fas fa-tint me-1"></i> O+</span>
                <span class="badge bg-warning text-dark rounded-pill ms-1"><i class="fas fa-allergies me-1"></i> Alergia: Penicilina</span>
            </div>
        </div>

        <div class="timeline">
            <div class="timeline-item"><div class="timeline-date">15 de Mayo, 2026 - Consulta General</div><div class="timeline-title">Hipertensión Grado 1 - Estabilizada</div><p class="text-secondary small mb-0">Paciente presenta presión arterial controlada (120/80). Se autoriza disminución de dosis. Próximo control en 3 meses.</p></div>
            <div class="timeline-item"><div class="timeline-date">10 de Febrero, 2026 - Control Interno</div><div class="timeline-title">Pico Hipertensivo por Estrés Laboral</div><p class="text-secondary small mb-0">Presión registrada: 150/95. Se ajusta dosificación. Se solicitan estudios de laboratorio.</p></div>
            <div class="timeline-item"><div class="timeline-date">04 de Enero, 2026 - Apertura de Expediente</div><div class="timeline-title">Diagnóstico Inicial: Hipertensión Esencial</div><p class="text-secondary small mb-0">Paciente acude por cefaleas recurrentes. Se inicia tratamiento con Losartán.</p></div>
        </div>
        
        <div class="mt-4 pt-3 text-center border-top"><small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Registro conforme a normativa departamental</small></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>