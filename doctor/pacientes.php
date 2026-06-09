<?php
session_start();

if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
    echo "<div class='alert alert-danger m-3'>Acceso denegado.</div>";
    exit();
}

$mensaje = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_paciente'])) {
    $nombre = $_POST['nombre'] ?? '';
    $mensaje = "✅ Expediente de <b>" . htmlspecialchars($nombre) . "</b> registrado correctamente.";
}

$pacientes = [
    ["id" => "PAC-001", "nombre" => "Carlos Mendoza", "edad" => 45, "cedula" => "10203040", "telefono" => "+591 71234567", "sangre" => "O+", "triaje" => "Verde", "estado" => "En Espera", "antecedentes" => "Hipertensión", "alergias" => "Penicilina"],
    ["id" => "PAC-002", "nombre" => "María Elena Gómez", "edad" => 29, "cedula" => "4567890", "telefono" => "+591 79876543", "sangre" => "A-", "triaje" => "Amarillo", "estado" => "En Consulta", "antecedentes" => "Asma", "alergias" => "Ninguna"],
    ["id" => "PAC-003", "nombre" => "Jorge Luis Prado", "edad" => 62, "cedula" => "8332112", "telefono" => "+591 60011223", "sangre" => "O+", "triaje" => "Verde", "estado" => "Atendido", "antecedentes" => "Diabetes", "alergias" => "Sulfa"],
    ["id" => "PAC-004", "nombre" => "Ana Sofía Castro", "edad" => 11, "cedula" => "9988776", "telefono" => "+591 75544332", "sangre" => "B+", "triaje" => "Rojo", "estado" => "Urgencia", "antecedentes" => "Ninguno", "alergias" => "AINEs"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Expedientes - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-elegant { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .btn-primary-custom { background: #C8102E; border: none; border-radius: 12px; padding: 10px 24px; font-weight: 500; }
        .btn-primary-custom:hover { background: #A00D26; }
        .btn-success-custom { background: #00A651; border: none; border-radius: 12px; }
        .btn-success-custom:hover { background: #008A42; }
        .badge-triaje { border-radius: 30px; padding: 5px 12px; font-weight: 500; }
        .table-header { background: #F8FAFE; font-weight: 600; color: #1F2A44; border-bottom: 1px solid #E5E9F0; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
    </style>
</head>
<body>
<div class="container-fluid px-0">
    <div class="banda-lapaz"></div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-folder-open me-2"></i>Expedientes Clínicos</h4>
            <p class="text-muted small mb-0">Registro y gestión de pacientes - GADLP</p>
        </div>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalPaciente"><i class="fas fa-user-plus me-2"></i>Nuevo Paciente</button>
    </div>
    
    <?php if ($mensaje): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><?= $mensaje ?></div>
    <?php endif; ?>
    
    <div class="card-elegant p-3 mb-4">
        <div class="row g-3">
            <div class="col-md-6"><input type="text" id="buscar" class="form-control form-control-lg rounded-4" placeholder="🔍 Buscar por nombre, cédula o ID..."></div>
            <div class="col-md-3"><select id="filtroTriaje" class="form-select rounded-4"><option value="">Todos los triajes</option><option value="Rojo">Rojo - Crítico</option><option value="Amarillo">Amarillo - Moderado</option><option value="Verde">Verde - Estable</option></select></div>
            <div class="col-md-3"><button class="btn btn-outline-secondary w-100 rounded-4" onclick="limpiarFiltros()"><i class="fas fa-eraser me-1"></i> Limpiar</button></div>
        </div>
    </div>
    
    <div class="card-elegant overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-header">
                    <tr><th>N° Exp.</th><th>Paciente</th><th>Identificación</th><th>Contacto</th><th>Sangre</th><th>Triaje</th><th>Estado</th><th class="text-center">Acciones</th></tr>
                </thead>
                <tbody id="tablaPacientes">
                    <?php foreach($pacientes as $p): ?>
                    <tr data-triaje="<?= $p['triaje'] ?>">
                        <td class="fw-bold text-danger"><?= $p['id'] ?></td>
                        <td><div class="fw-bold"><?= htmlspecialchars($p['nombre']) ?></div><small class="text-muted"><?= $p['edad'] ?> años</small></td>
                        <td><?= $p['cedula'] ?></td>
                        <td><i class="fas fa-phone-alt text-muted me-1"></i> <?= $p['telefono'] ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $p['sangre'] ?></span></td>
                        <td><span class="badge-triaje <?= $p['triaje']=='Rojo'?'bg-danger text-white':($p['triaje']=='Amarillo'?'bg-warning text-dark':'bg-success text-white') ?>"><?= $p['triaje'] ?></span></td>
                        <td><?= $p['estado'] == 'Urgencia' ? '<span class="badge bg-danger"><i class="fas fa-ambulance me-1"></i> Urgencia</span>' : '<span class="text-secondary">'.$p['estado'].'</span>' ?></td>
                        <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill" onclick="alert('📋 Ver historial de <?= $p['nombre'] ?>')"><i class="fas fa-eye me-1"></i> Ver</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaciente" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header" style="background: #C8102E; color: white; border-radius: 16px 16px 0 0;"><h5 class="modal-title"><i class="fas fa-notes-medical me-2"></i>Registro de Nuevo Paciente - GADLP</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Nombre completo *</label><input type="text" name="nombre" class="form-control rounded-3" required></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Cédula *</label><input type="text" name="cedula" class="form-control rounded-3" required></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Edad *</label><input type="number" name="edad" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Teléfono *</label><input type="text" name="telefono" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Correo electrónico</label><input type="email" name="email" class="form-control rounded-3"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Grupo Sanguíneo</label><select name="sangre" class="form-select rounded-3"><option>O+</option><option>A+</option><option>B+</option></select></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Triaje Inicial</label><select name="triaje" class="form-select rounded-3"><option>Verde</option><option>Amarillo</option><option>Rojo</option></select></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Estado</label><select name="estado" class="form-select rounded-3"><option>En Espera</option><option>En Consulta</option><option>Urgencia</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Alergias</label><textarea name="alergias" class="form-control rounded-3" rows="2"></textarea></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Antecedentes</label><textarea name="antecedentes" class="form-control rounded-3" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4"><button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button><button type="submit" name="guardar_paciente" class="btn btn-success-custom rounded-3 px-4">Guardar Expediente</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function filtrarTabla() {
        const busqueda = document.getElementById('buscar').value.toLowerCase();
        const triaje = document.getElementById('filtroTriaje').value;
        const filas = document.querySelectorAll('#tablaPacientes tr');
        filas.forEach(fila => {
            const texto = fila.innerText.toLowerCase();
            const triajeFila = fila.getAttribute('data-triaje');
            const ok = texto.includes(busqueda) && (triaje === "" || triajeFila === triaje);
            fila.style.display = ok ? '' : 'none';
        });
    }
    function limpiarFiltros() { document.getElementById('buscar').value = ''; document.getElementById('filtroTriaje').value = ''; filtrarTabla(); }
    document.getElementById('buscar').addEventListener('keyup', filtrarTabla);
    document.getElementById('filtroTriaje').addEventListener('change', filtrarTabla);
</script>
</body>
</html>