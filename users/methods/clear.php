<?php
require_once ("seguridad.php");
require_once ("../conexion.php");

if (isset($_GET['id'])){
	try {
		$sql=$basededatos->prepare("DELETE FROM v_evento WHERE idmaquina=$_GET[id]");
		$sql->execute();
	} catch (exception $e) {
		echo "No se puede la consulta 1";
		exit;
	}
	try {
		$sql=$basededatos->query("SELECT d.id FROM v_maquinas as m, v_dispositivo as d WHERE dispositivoid = d.id AND m.id = $_GET[id]");
		$row = $sql->fetch();
		$sql=$basededatos->prepare("UPDATE v_dispositivo SET comprobacion_evento = current_timestamp WHERE id=$row[id]");
		$sql->execute();
	} catch (exception $e) {
		echo "No se puede la consulta 2";
		exit;
	}
}
?>