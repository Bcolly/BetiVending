<?php
//Inicio la sesión
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if (!$_SESSION["user"]) {
    //si no existe, se envia a la página de autentificacion
    header("Location: ../index.php");
    //ademas se cierra el script
    exit();
}
?> 
