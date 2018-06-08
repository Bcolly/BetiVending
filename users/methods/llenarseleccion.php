<?php
//include_once ("../conexion.php");
if (isset($_GET['id']) and isset($_GET['sel'])){
	if ($_GET['cant'] >= 0){
		try{
			$sql = $basededatos->query("SELECT max FROM v_seleccion WHERE idmaquina=$_GET[id] AND sel='$_GET[sel]'");
			$row = $sql->fetch();
		} catch (exception $e) {
			echo "No se puede la consulta 1";
			exit;
		}
		if ($_GET['cant'] > $row['max']) $_GET['cant'] = $row['max'];
		try{
			$sql = $basededatos->prepare("UPDATE v_productos_seleccion SET cantidad = $_GET[cant] WHERE idmaquina=$_GET[id] AND sel='$_GET[sel]'");
			$sql->execute();
		} catch (exception $e) {
			echo "No se puede la consulta 2";
			exit;
		}
	}
}
?>