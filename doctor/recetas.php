<?php
session_start();
$imprimir = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emitir_receta'])) {
    $imprimir = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Recetario - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .prescription-card { background: white; border-radius: 20px; padding: 40px; max-width: 800px; margin: 0 auto; border: 1px solid #E5E9F0; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 12px; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
        @media print { .no-print { display: none; } .prescription-card { box-shadow: none; padding: 20px; } }
    </style>
</head>
<body>

<div class="container">
    <div class="banda-lapaz"></div>
    <?php if (!$imprimir): ?>
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-prescription-bottle-alt me-2"></i>Recetario Digital</h4>
        <p class="text-muted small">Generación de prescripciones - GADLP</p>
    </div>

    <div class="card-modern p-4" style="max-width: 800px; margin: 0 auto;">
        <form method="POST">
            <div class="row g-3 mb-3">
                <div class="col-md-8"><label class="form-label fw-semibold">Paciente</label><input type="text" name="paciente" class="form-control rounded-3" required placeholder="Nombre completo"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Fecha</label><input type="text" class="form-control rounded-3 bg-light" readonly value="<?= date('d/m/Y') ?>"></div>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">Medicamentos y Posología</label><textarea name="tratamiento" class="form-control rounded-3" rows="5" required placeholder="Ej:&#10;1. Amoxicilina 500mg - 1 cápsula cada 8 horas por 7 días&#10;2. Paracetamol 1g - 1 tableta cada 6 horas"></textarea></div>
            <div class="mb-3"><label class="form-label fw-semibold">Indicaciones Adicionales</label><textarea name="indicaciones" class="form-control rounded-3" rows="2" placeholder="Reposo, hidratación, dieta..."></textarea></div>
            <button type="submit" name="emitir_receta" class="btn btn-primary-custom w-100"><i class="fas fa-file-prescription me-2"></i>Generar Prescripción</button>
        </form>
    </div>
    
    <?php else: ?>
    <div class="no-print text-center mb-4">
        <button class="btn btn-danger rounded-3 px-4 me-2" onclick="window.print()"><i class="fas fa-print me-2"></i>Imprimir Receta</button>
        <a href="recetas.php" class="btn btn-secondary rounded-3 px-4"><i class="fas fa-plus me-2"></i>Nueva Receta</a>
    </div>
    
    <div class="prescription-card">
        <div class="text-center border-bottom pb-4 mb-4">
            <img src="img/mbx.png" alt="Logo" style="height: 60px;" onerror="this.style.display='none'">
            <h4 class="fw-bold mb-0" style="color: #C8102E;">MediGest</h4>
            <small class="text-muted">Gobierno Autónomo Departamental de La Paz</small>
            <p class="small mt-2 mb-0"><strong>RECETA N°:</strong> <?= strtoupper(uniqid('LP-')) ?> | <strong>Fecha:</strong> <?= date('d/m/Y H:i') ?></p>
        </div>
        <div class="mb-4"><h6 class="fw-bold">PACIENTE:</h6><p class="fs-5"><?= htmlspecialchars($_POST['paciente']) ?></p></div>
        <div class="mb-4"><h6 class="fw-bold text-danger"><i class="fas fa-pills me-1"></i> TRATAMIENTO PRESCRITO:</h6><div class="p-3 bg-light rounded-3" style="white-space: pre-line; line-height: 1.8;"><?= htmlspecialchars($_POST['tratamiento']) ?></div></div>
        <?php if(!empty($_POST['indicaciones'])): ?><div class="mb-4"><h6 class="fw-bold"><i class="fas fa-notes-medical me-1"></i> INDICACIONES:</h6><p class="text-secondary"><?= htmlspecialchars($_POST['indicaciones']) ?></p></div><?php endif; ?>
        <div class="text-center mt-5 pt-4 border-top"><div style="width: 250px; border-top: 1px solid #000; margin: 0 auto 8px;"></div><small class="text-muted">Firma y sello del médico - GADLP</small><div class="mt-3 small text-muted"><i class="fas fa-qrcode me-1"></i> Válido por 30 días | Sistema Público de Salud</div></div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>