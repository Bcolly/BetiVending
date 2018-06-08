<?php
require_once("../conexion.php");
$envio=0;
$newIden="";
$frecuencia=0;
if (isset($_GET["envio"])) $envio=1;
if (isset($_GET["newIden"])) $newIden=$_GET["newIden"];
if (isset($_GET["frecuencia"])) $frecuencia=$_GET["frecuencia"];

try{
	$sql = $basededatos->prepare("UPDATE v_dispositivo SET solicitud_envio=$envio, solicitud_iden='$newIden', solicitud_frecuencia=$frecuencia
	WHERE nombre='$_GET[id]'");
	$sql->execute();
} catch (exception $e) {
	echo "No se pueden aplicar los cambios de flag";
	exit;
}

//if (isset($_GET["url"])) header("location : $_GET[url]");
?>

