<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paciente_login'])) {
    $cedula = $_POST['cedula'];
    $password = $_POST['password'];
    
    // En producción, aquí validarías contra base de datos
    // Simulación: cualquier cédula con contraseña "12345678" funciona
    // En realidad, la contraseña debería ser su fecha de nacimiento
    
    if (!empty($cedula) && !empty($password)) {
        // Simular datos del paciente desde "base de datos"
        $_SESSION['paciente_logged'] = true;
        $_SESSION['paciente_cedula'] = $cedula;
        $_SESSION['paciente_nombre'] = "Carlos Mendoza"; // Se buscaría en BD
        
        header("Location: paciente_dashboard.php");
        exit();
    } else {
        $_SESSION['paciente_error'] = "Credenciales incorrectas. Verifique su cédula y contraseña.";
        header("Location: paciente_login.php");
        exit();
    }
} else {
    header("Location: paciente_login.php");
    exit();
}
?>