<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se usa cookie de sesión, destruirla también
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login después de 3 segundos
$redirect_time = 3;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?= $redirect_time ?>; url=index.php">
    <title> | Cerrando Sesión - La Paz</title>
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
            position: relative;
        }
        
        /* Patrón de fondo sutil con efecto wiphala/diagonal */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.02) 0px,
                rgba(255,255,255,0.02) 2px,
                transparent 2px,
                transparent 8px
            );
            pointer-events: none;
        }
        
        .logout-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
            padding: 40px 32px;
        }
        
        /* Banda superior con los colores de La Paz */
        .banda-departamental {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #C8102E 0%, #C8102E 33%, #00A651 33%, #00A651 66%, #808080 66%, #808080 100%);
        }
        
        .icon-circle {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 8px 20px rgba(200,16,46,0.4);
            position: relative;
        }
        
        .icon-circle::after {
            content: "🏔️";
            position: absolute;
            bottom: -8px;
            right: -8px;
            font-size: 24px;
            background: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        
        .icon-circle i {
            font-size: 42px;
            color: white;
        }
        
        .logout-title {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 12px;
        }
        
        .logout-subtitle {
            font-size: 0.8rem;
            color: #C8102E;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }
        
        .logout-message {
            color: #4B5563;
            font-size: 0.95rem;
            margin-bottom: 28px;
            line-height: 1.6;
        }
        
        .spinner-custom {
            width: 45px;
            height: 45px;
            border: 3px solid #E5E9F0;
            border-top: 3px solid #C8102E;
            border-right: 3px solid #00A651;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .redirect-info {
            font-size: 0.85rem;
            background: #F9FAFB;
            padding: 12px;
            border-radius: 40px;
            margin-top: 16px;
        }
        
        .redirect-info strong {
            color: #C8102E;
            font-size: 1.2rem;
        }
        
        .btn-manual {
            display: inline-block;
            margin-top: 16px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #C8102E 0%, #00A651 100%);
            color: white;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .btn-manual:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(200,16,46,0.3);
            color: white;
        }
        
        .footer-secure {
            margin-top: 24px;
            font-size: 0.7rem;
            color: #9CA3AF;
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
        
        .departamento-badge {
            background: #F3F4F6;
            padding: 6px 16px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .departamento-badge i {
            color: #C8102E;
        }
    </style>
</head>
<body>

<div class="logout-card">
    <div class="banda-departamental"></div>
    
    <div class="icon-circle">
        <i class="fas fa-door-open"></i>
    </div>
    
    <div class="departamento-badge">
        <i class="fas fa-map-marker-alt"></i>
        GOBIERNO AUTÓNOMO DEPARTAMENTAL DE LA PAZ
        <i class="fas fa-mountain"></i>
    </div>
    
    <h1 class="logout-title">Sesión Cerrada</h1>
    <div class="logout-subtitle">SISTEMA DE GESTIÓN CLÍNICA</div>
    
    <p class="logout-message">
        <i class="fas fa-check-circle" style="color: #00A651;"></i>
        Ha cerrado sesión correctamente en el sistema .<br>
        Sus datos han sido resguardados conforme a la normativa departamental.
    </p>
    
    <div class="spinner-custom"></div>
    
    <div class="redirect-info">
        <i class="fas fa-hourglass-half me-1" style="color: #C8102E;"></i>
        Redirigiendo al portal de acceso en <strong id="contador"><?= $redirect_time ?></strong> segundos...
    </div>
    
    <a href="index.php" class="btn-manual">
        <i class="fas fa-arrow-right-to-bracket me-2"></i>Acceder al Sistema
    </a>
    
    <div class="footer-secure">
        <div class="escudo-mini"></div>
        <span>MediGest - Red de Salud Pública Departamental</span>
        <div class="mt-1">
            <i class="fas fa-shield-alt me-1"></i> Sesión finalizada de forma segura
        </div>
    </div>
</div>

<script>
    let segundos = <?= $redirect_time ?>;
    const contadorElement = document.getElementById('contador');
    
    const intervalo = setInterval(function() {
        segundos--;
        if (contadorElement) {
            contadorElement.textContent = segundos;
        }
        if (segundos <= 0) {
            clearInterval(intervalo);
        }
    }, 1000);
</script>

</body>
</html>