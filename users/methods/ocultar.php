<?php
	//include_once ("../conexion.php");
	$id=$_GET["id"];
	if (isset($_GET["vis"]) && $_GET["vis"]=="true") $visibility=false;
	else $visibility=true;
	
	if (isset($_GET["sel"])) {
		$sel=$_GET["sel"];
		try{
			$sql= $basededatos->query("SELECT visible_log FROM v_seleccion WHERE idmaquina=$id AND sel='$sel'");
			$row= $sql->fetch();
		} catch (exception $e) {
			echo "No se puede la consulta 1";
			exit;
		}
		if ($row["visible_log"]==1 && !$visibility) $query="UPDATE v_seleccion SET visible_log=0 WHERE idmaquina=$id AND sel='$sel'";
		elseif ($row["visible_log"]==0 && $visibility) $query="UPDATE v_seleccion SET visible_log=1 WHERE idmaquina=$id AND sel='$sel'";
		try{
			$sql = $basededatos->prepare($query);
			$sql->execute();
		} catch (exception $e) {
				echo "No se puede la consulta 2";
				exit;
		}
	}
?>