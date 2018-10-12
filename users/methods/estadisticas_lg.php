<?php require('../../conexion.php');
session_start();
$user = $_SESSION['user'];

$idm = $_GET['maq'];
$vista = $_GET['vista'];

$con = 1;
$basededatos=conectardb();
$data = array();

if (isset($_GET['mes'])) {
	$mes = $_GET['mes'];
	$año = date ("Y");

	$anterior=query("SELECT fecha, $vista from v_historico
	WHERE idmaquina = $idm AND (MONTH(fecha)<$mes OR YEAR(fecha)<$año) ORDER BY fecha DESC LIMIT 1;", $basededatos, $con);
	$con++;

	$minus=0;
	if($anterior->rowCount() > 0) {
		$ant = $anterior->fetch();
		$minus = $ant[$vista];
	}
	$historico=query("SELECT fecha, $vista from v_historico
	WHERE idmaquina = $idm AND MONTH(fecha)=$mes AND YEAR(fecha)=$año ORDER BY fecha ASC LIMIT 32;", $basededatos, $con);
	if ($historico->rowCount() > 0){
	  while($row = $historico->fetch()) {
			if ($minus > 0) {
				$aux = $row[$vista];
				$row[$vista] = floatval($row[$vista])-$minus;
				$minus = $aux;
			}
	    if (empty($data))
	      $data = [$row['fecha'] => $row[$vista]];
	    else
	      $data += [$row['fecha'] => $row[$vista]];
	  }
	}
}
else if (isset($_GET['año'])) {
	$año = $_GET["año"];

	$anterior=query("SELECT fecha, $vista from v_historico
	WHERE idmaquina = $idm AND YEAR(fecha)<$año ORDER BY fecha DESC LIMIT 1;", $basededatos, $con);
	$con++;

	$minus=0;
	if($anterior->rowCount() > 0) {
		$ant = $anterior->fetch();
		$minus = $ant[$vista];
	}
	$historico=query("SELECT MONTH(fecha) as fecha, SUM($vista) as $vista from v_historico WHERE idmaquina = $idm AND YEAR(fecha)=$año GROUP BY MONTH(fecha);",
	 $basededatos, $con);
	if ($historico->rowCount() > 0){
		while($row = $historico->fetch()) {
			if ($minus > 0) {
				$aux = $row[$vista];
				$row[$vista] = floatval($row[$vista])-$minus;
				$minus = $aux;
			}

			if (empty($data))
				$data = [$row['fecha'] => $row[$vista]];
			else
				$data += [$row['fecha'] => $row[$vista]];
		}
	}
}
echo json_encode($data, JSON_FORCE_OBJECT);
?>
