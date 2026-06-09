<?php
session_start();

// Si ya está logueado, redirigir al dashboard de paciente
if (isset($_SESSION['paciente_logged']) && $_SESSION['paciente_logged'] === true) {
    header("Location: paciente_dashboard.php");
    exit();
}

$error = $_SESSION['paciente_error'] ?? null;
unset($_SESSION['paciente_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Portal del Paciente - La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #C8102E 0%, #8B0000 40%, #00A651 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 2px, transparent 2px, transparent 8px);
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .banda-superior {
            height: 6px;
            background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%);
        }

        .login-header {
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            padding: 30px 30px 25px;
            text-align: center;
            color: white;
        }

        .login-body {
            padding: 35px 30px 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #C8102E;
            font-size: 1rem;
            z-index: 1;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #C8102E;
            box-shadow: 0 0 0 4px rgba(200,16,46,0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border: none;
            padding: 14px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            color: white;
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(200,16,46,0.4);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
        }
        
        .register-link a {
            color: #C8102E;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 0.7rem;
            color: #9CA3AF;
            border-top: 1px solid #eef2f6;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="banda-superior"></div>
    <div class="login-header">
        <div class="mb-2">
            <i class="fas fa-hospital-user fa-3x opacity-75"></i>
        </div>
        <h4 class="fw-bold mb-1">Portal del Paciente</h4>
        <p class="small mb-0">Gobierno Autónomo Departamental de La Paz</p>
        <p class="small opacity-75"><i class="fas fa-mountain me-1"></i> Red de Salud Pública</p>
    </div>

    <div class="login-body">
        <?php if ($error): ?>
            <div class="alert alert-danger rounded-4 mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="paciente_auth.php" method="POST">
            <div class="form-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" class="form-control-custom" placeholder="Número de Cédula / CI" required>
            </div>

            <div class="form-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="password" name="password" class="form-control-custom" placeholder="Contraseña (Ej: fecha de nacimiento DDMMYYYY)" required>
            </div>

            <button type="submit" name="paciente_login" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket me-2"></i> Ingresar a mi Portal
            </button>
        </form>

        <div class="register-link">
            ¿Es nuevo? <a href="paciente_registro.php">Regístrese aquí</a>
        </div>

        <div class="footer-text">
            <i class="fas fa-shield-alt me-1"></i> Acceso seguro a su información clínica
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>