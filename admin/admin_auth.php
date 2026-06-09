<?php
session_start();

// Usuarios administrativos predefinidos (en producción usar BD)
$admin_users = [
    "admin" => ["password" => "admin123", "nombre" => "Administrador General", "rol" => "Super Administrador"],
    "recepcion" => ["password" => "recep123", "nombre" => "María Laura Gómez", "rol" => "Recepción"],
    "farmacia" => ["password" => "farm123", "nombre" => "Carlos Rodríguez", "rol" => "Farmacia"],
    "caja" => ["password" => "caja123", "nombre" => "Ana María López", "rol" => "Caja"],
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (isset($admin_users[$username]) && $admin_users[$username]['password'] === $password) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $username;
        $_SESSION['admin_nombre'] = $admin_users[$username]['nombre'];
        $_SESSION['admin_rol'] = $admin_users[$username]['rol'];
        
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Credenciales incorrectas. Verifique su usuario y contraseña.";
        header("Location: admin_login.php");
        exit();
    }
} else {
    header("Location: admin_login.php");
    exit();
}
?>