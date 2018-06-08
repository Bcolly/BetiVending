<?php
	if (isset($_GET["idanterior"])) $idenAnterior = $_GET["idanterior"];
	if (isset($_GET["iden"])) $iden = $_GET["iden"];
	
	include('../conexion.php');
	
	try{
		$sql=$basededatos->query("SELECT * FROM v_dispositivo WHERE nombre='$iden'");
		$num=$sql->rowCount();
	} catch (exception $e) {
		echo "No se puede la consulta 1";
		exit;
	}
	
	if ($num<1) {
		try{
			$sql=$basededatos->prepare("UPDATE v_dispositivo SET nombre='$iden' WHERE nombre='$idenAnterior'");
			$sql->execute();
		} catch (exception $e) {
			echo "No se puede la consulta 2";
			exit;
		}
		echo "cambiado";
	} else {
		echo "no se puede cambiar el nombre del dispositivo por <b>'$iden'</b>";
	}
?>