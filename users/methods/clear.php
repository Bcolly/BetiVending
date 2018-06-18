<?php
require_once ("../seguridad.php");
require_once ("../../conexion.php");

if (isset($_GET['id'])){
	$con = 1;
	$basededatos = conectardb();
	execute("DELETE FROM v_evento WHERE idmaquina=$_GET[id]", $basededatos, $con);
	$con++;
	$sql = query("SELECT d.id FROM v_maquinas as m, v_dispositivo as d WHERE dispositivoid = d.id AND m.id = $_GET[id]", $basededatos, $con);
	$con++;
	$row = $sql->fetch();
	execute("UPDATE v_dispositivo SET comprobacion_evento = current_timestamp WHERE id=$row[id]", $basededatos, $con);
}
?>
