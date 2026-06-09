<?php
$medicinas = [
    ["codigo" => "MED-403", "nombre" => "Losartán 50mg", "categoria" => "Cardiología", "stock" => 120, "estado" => "Disponible"],
    ["codigo" => "MED-902", "nombre" => "Amoxicilina 500mg", "categoria" => "Antibiótico", "stock" => 15, "estado" => "Stock Bajo"],
    ["codigo" => "MED-112", "nombre" => "Paracetamol 1g", "categoria" => "Analgésico", "stock" => 250, "estado" => "Disponible"],
    ["codigo" => "MED-054", "nombre" => "Salbutamol Inhalador", "categoria" => "Neumología", "stock" => 3, "estado" => "Crítico"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> | Farmacia - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #F0F2F5; font-family: 'Inter', sans-serif; padding: 24px; }
        .card-modern { background: white; border-radius: 20px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; }
        .table-header { background: #F8FAFE; font-weight: 600; color: #1F2A44; border-bottom: 1px solid #E5E9F0; }
        .badge-stock { padding: 6px 12px; border-radius: 30px; font-weight: 500; font-size: 0.75rem; }
        .alert-stock-critico { background: #FEF2F2; border-left: 4px solid #C8102E; border-radius: 16px; }
        .banda-lapaz { height: 4px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); margin-bottom: 20px; border-radius: 2px; }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="banda-lapaz"></div>
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: #C8102E;"><i class="fas fa-pills me-2"></i>Inventario de Farmacia</h4>
        <p class="text-muted small">Control de stock - Red de Salud La Paz</p>
    </div>

    <div class="alert alert-stock-critico rounded-4 mb-4 d-flex align-items-center">
        <i class="fas fa-exclamation-triangle text-danger fs-4 me-3"></i>
        <div><strong>Alertas de inventario:</strong> 2 productos requieren reabastecimiento inmediato.</div>
    </div>

    <div class="card-modern">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-header">
                    <tr><th>Código</th><th>Medicamento</th><th>Categoría</th><th class="text-center">Stock</th><th>Estado</th><th class="text-center">Acción</th></tr>
                </thead>
                <tbody>
                    <?php foreach($medicinas as $m): 
                        $color_estado = $m['estado'] == "Disponible" ? "success" : ($m['estado'] == "Stock Bajo" ? "warning text-dark" : "danger");
                    ?>
                    <tr>
                        <td class="font-monospace fw-bold text-danger"><?= $m['codigo'] ?></td>
                        <td class="fw-bold"><?= $m['nombre'] ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $m['categoria'] ?></span></td>
                        <td class="text-center fw-bold"><?= $m['stock'] ?> unidades</td>
                        <td><span class="badge-stock bg-<?= $color_estado ?>"><?= $m['estado'] ?></span></td>
                        <td class="text-center"><button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="alert('📦 Solicitud de reabastecimiento enviada al proveedor.')"><i class="fas fa-truck me-1"></i> Solicitar</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>