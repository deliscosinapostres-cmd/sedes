<?php
session_start();

// Si el doctor ya está autenticado, lo redirigimos al dashboard directamente
if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
    header("Location: dashboard.php");
    exit();
}

// Recuperar mensajes flash de error o cierre de sesión si existen
$mensaje = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> | Acceso - Gobierno de La Paz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #C8102E 0%, #8B0000 50%, #00A651 100%);
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
            background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.03) 0px, rgba(255,255,255,0.03) 2px, transparent 2px, transparent 8px);
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 460px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255,255,255,0.4);
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

        .institution-badge {
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 0.7rem;
            letter-spacing: 1px;
            margin-bottom: 20px;
            font-weight: 600;
            text-transform: uppercase;
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
            pointer-events: none;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #C8102E;
            background: white;
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
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(200,16,46,0.4);
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 0.75rem;
            color: #6b7280;
            border-top: 1px solid #eef2f6;
            padding-top: 25px;
        }

        .alert-custom {
            border-radius: 14px;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }
        
        .escudo-mini {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #C8102E, #00A651);
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="banda-superior"></div>
    <div class="login-header">
        <div class="institution-badge">
            <i class="fas fa-shield-alt me-1"></i> GOBIERNO AUTÓNOMO DEPARTAMENTAL
        </div>
        <img src="img/mbx.png" alt="Logo MediGest" style="height: 70px; margin-bottom: 12px;" onerror="this.style.display='none'">
        <h4 class="fw-bold mb-1"></h4>
        <p class="opacity-75 small mb-0">Sistema de Gestión Clínica</p>
        <p class="small mt-2 mb-0" style="font-size:0.7rem;"><i class="fas fa-mountain me-1"></i> Departamento de La Paz</p>
    </div>

    <div class="login-body">
        <?php if ($mensaje): ?>
            <div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <i class="fas fa-server"></i>
                <input type="text" name="hospital_host" class="form-control-custom" placeholder="Servidor / IP Hospital" value="localhost" required>
            </div>

            <div class="form-group">
                <i class="fas fa-user-md"></i>
                <input type="text" name="doctor_user" class="form-control-custom" placeholder="Usuario o Matrícula Médica" required>
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="doctor_password" class="form-control-custom" placeholder="Contraseña de acceso" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" style="border-radius: 4px; border-color: #C8102E;">
                    <label class="form-check-label text-secondary" for="rememberMe">Mantener sesión</label>
                </div>
                <a href="#" class="text-decoration-none" style="color: #C8102E;">¿Olvidó su contraseña?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket me-2"></i> Ingresar al Sistema
            </button>
        </form>

        <div class="footer-text">
            <div class="escudo-mini"></div>
            <span>Red de Salud Pública Departamental - La Paz</span><br>
            &copy; <?= date('Y') ?> GADLP - Todos los derechos reservados
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>