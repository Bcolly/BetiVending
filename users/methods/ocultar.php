<?php
	require_once ("../../conexion.php");
	$id=$_GET["id"];
	if (isset($_GET["vis"]) && $_GET["vis"]=="true") $visibility=false;
	else $visibility=true;

	if (isset($_GET["sel"])) {
		$sel=$_GET["sel"];

		$basededatos = conectardb();
		$con=1;
		$sql = query("SELECT visible_log FROM v_seleccion WHERE idmaquina=$id AND sel='$sel'", $basededatos, $con);
		$con++;
		$row= $sql->fetch();

		if ($row["visible_log"]==1 && !$visibility) $query="UPDATE v_seleccion SET visible_log=0 WHERE idmaquina=$id AND sel='$sel'";
		elseif ($row["visible_log"]==0 && $visibility) $query="UPDATE v_seleccion SET visible_log=1 WHERE idmaquina=$id AND sel='$sel'";

		execute($query, $basededatos, $con);
	}
?>
