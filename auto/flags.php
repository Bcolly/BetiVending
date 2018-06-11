<?php
require_once("../conexion.php");
$envio=0;
$newIden="";
$frecuencia=0;
if (isset($_GET["envio"])) $envio=1;
if (isset($_GET["newIden"])) $newIden=$_GET["newIden"];
if (isset($_GET["frecuencia"])) $frecuencia=$_GET["frecuencia"];

$sql=conectardb();
exectute("UPDATE v_dispositivo SET solicitud_envio=$envio, solicitud_iden='$newIden', solicitud_frecuencia=$frecuencia
	WHERE nombre='$_GET[id]'", $sql, 0);

//if (isset($_GET["url"])) header("location : $_GET[url]");
?>

