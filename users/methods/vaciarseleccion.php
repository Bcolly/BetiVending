<?php
//include_once ("../conexion.php");
if (isset($_GET['id']) and isset($_GET['sel'])){
	try{
		$sql = $basededatos->prepare("DELETE FROM v_productos_seleccion WHERE idmaquina=$_GET[id] AND sel='$_GET[sel]'");
		$sql->execute();
	} catch (exception $e) {
		echo "No se puede la consulta 1";
		exit;
	}
}
?>