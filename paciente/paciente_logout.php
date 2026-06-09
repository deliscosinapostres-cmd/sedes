<?php
session_start();
session_destroy();
header("Location: paciente_login.php");
exit();
?>