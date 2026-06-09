<?php
session_start();

$mensaje = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_paciente'])) {
    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    
    // Validación básica
    if (empty($cedula) || empty($nombre) || empty($email) || empty($telefono) || empty($fecha_nacimiento)) {
        $error = "Por favor complete todos los campos obligatorios.";
    } else {
        // En producción, aquí guardarías en BD. Simulación:
        $_SESSION['registro_exitoso'] = "¡Registro completado! Ahora puede iniciar sesión.";
        header("Location: paciente_login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Registro de Paciente - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .register-card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);
        }
        .banda-superior { height: 6px; background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%); }
        .register-header { background: linear-gradient(135deg, #C8102E 0%, #00A651 100%); padding: 25px; text-align: center; color: white; }
        .register-body { padding: 35px; }
        .form-control-custom { border-radius: 12px; padding: 12px 16px; border: 1.5px solid #e5e7eb; transition: all 0.2s; }
        .form-control-custom:focus { border-color: #C8102E; box-shadow: 0 0 0 3px rgba(200,16,46,0.1); }
        .btn-register { background: linear-gradient(135deg, #C8102E 0%, #00A651 100%); border: none; padding: 14px; border-radius: 14px; font-weight: 600; color: white; width: 100%; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(200,16,46,0.4); }
        .btn-back { background: #6c757d; color: white; border-radius: 12px; padding: 10px 20px; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="banda-superior"></div>
    <div class="register-header">
        <i class="fas fa-user-plus fa-3x mb-2 opacity-75"></i>
        <h4 class="fw-bold mb-0">Registro de Paciente</h4>
        <p class="small mb-0">Sistema de Gestión Clínica - GADLP</p>
    </div>

    <div class="register-body">
        <?php if ($error): ?>
            <div class="alert alert-danger rounded-4 mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Número de Cédula / CI *</label>
                    <input type="text" name="cedula" class="form-control-custom w-100" required placeholder="Ej: 12345678">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Nombre Completo *</label>
                    <input type="text" name="nombre" class="form-control-custom w-100" required placeholder="Ej: Juan Pérez Mamani">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control-custom w-100" required placeholder="correo@ejemplo.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Teléfono / Celular *</label>
                    <input type="text" name="telefono" class="form-control-custom w-100" required placeholder="Ej: 71234567">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" class="form-control-custom w-100" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Género</label>
                    <select name="genero" class="form-control-custom w-100">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="direccion" class="form-control-custom w-100" placeholder="Dirección de residencia">
                </div>
                <div class="col-12">
                    <div class="alert alert-light rounded-4 small">
                        <i class="fas fa-info-circle text-danger me-1"></i>
                        Su contraseña de acceso será su <strong>fecha de nacimiento en formato DDMMYYYY</strong>.
                        Podrá cambiarla después de iniciar sesión.
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" name="registrar_paciente" class="btn-register">
                        <i class="fas fa-save me-2"></i> Registrar mi cuenta
                    </button>
                </div>
                <div class="col-12 text-center mt-3">
                    <a href="paciente_login.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i> Volver al Login
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>