<?php
require_once("../conexion.php");
try{
	$sql = $basededatos->prepare("UPDATE v_dispositivo SET nombre='$newIden' WHERE nombre='$iden'");
	$sql->execute();
} catch (exception $e) {
	echo "No se puede cambiar el identificador";
	exit;
}
?>