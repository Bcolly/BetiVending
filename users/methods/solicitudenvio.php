<?php
//include("../conexion.php");
try{
	$stmt = $basededatos->prepare("UPDATE v_dispositivo SET solicitud_envio = 1 WHERE id = $_GET[id]");  // Prepare statement
	$stmt->execute();  // execute the query
} catch (exception $e) {
	echo "no se pudo completar la accion";
	exit;
}
?>