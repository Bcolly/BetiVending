<?php
require_once ("../../conexion.php");
if (isset($_GET['prod'])){
	try {
		$productos=$basededatos->query("SELECT * FROM v_productos WHERE producto LIKE '%$_GET[prod]%'");
	} catch (exception $e) {
		echo "No se puede la consulta 1";
		exit;
	}
	foreach ($productos as $p){
		echo "<p>$p[producto]</p>";
	}
}
?>