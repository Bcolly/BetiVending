<?php
	require_once ("../../conexion.php");

	if (isset($_GET['id']) && isset($_GET['sel'])){
		if ($_GET['cant'] >= 0){
			llenarsel($_GET['id'], $_GET['sel'], $_GET['cant']);
		}
	} elseif (isset($_GET['id']) && isset($_GET['all'])){
			llenarmaquina($_GET['id']);
	}

	function llenarsel($id, $sel, $cant){
		$basededatos = conectardb();
		$con = 1;

		$sql = query("SELECT max, cantidad FROM v_seleccion NATURAL JOIN v_productos_seleccion WHERE idmaquina=$id AND sel='$sel'",
			$basededatos, $con);
		$con++;
		$row = $sql->fetch();

		$total = $cant+$row['cantidad'];
		if ($total > $row['max']) $total = $row['max'];

		execute("UPDATE v_productos_seleccion SET cantidad = $total WHERE idmaquina=$id AND sel='$sel'", $basededatos, $con);

		$basededatos = null;
	}

	function llenarmaquina($id){
		$basededatos = conectardb();
		$con=1;
		//execute("UPDATE v_productos_seleccion SET cantidad = $total WHERE idmaquina=$data[id] AND sel='$data[sel]'", $basededatos, 1);
		$sql = query("SELECT sel FROM v_productos_seleccion WHERE idmaquina=$id",
			$basededatos, $con);
		$con++;
		foreach ($sql as $sel) {
			execute("UPDATE v_productos_seleccion
				SET cantidad = (SELECT max FROM v_seleccion WHERE idmaquina=$id AND sel='$sel[sel]')
				WHERE idmaquina=$id AND sel='$sel[sel]'", $basededatos, $con);
		}
		$basededatos = null;
	}
?>
