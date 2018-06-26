<?php
	require_once ("../../conexion.php");
	if (isset($_GET['id']) && isset($_GET['sel'])){
		$basededatos = conectardb();
		execute("DELETE FROM v_productos_seleccion WHERE idmaquina=$_GET[id] AND sel='$_GET[sel]'", $basededatos, 1);
		$basededatos = null;
	}
?>
