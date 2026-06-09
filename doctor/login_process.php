<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = $_POST['hospital_host'];
    $user = $_POST['doctor_user'];
    $password = $_POST['doctor_password'];

    // Aquí colocarías tu validación real (consulta a Base de Datos, API o seguridad.php)
    // Ejemplo de validación estática de prueba:
    if ($user === "admin" && $password === "12345") {
        
        // Seteamos las variables de sesión idénticas a las que tu Dashboard espera
        $_SESSION['connected'] = true;
        $_SESSION['host'] = $host;
        $_SESSION['user'] = $user;
        $_SESSION['password'] = $password;
        
        header("Location: dashboard.php");
        exit();
    } else {
        // En caso de fallo, guardamos el mensaje flash y regresamos
        $_SESSION['login_error'] = "Credenciales incorrectas o médico no registrado en el sistema.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}