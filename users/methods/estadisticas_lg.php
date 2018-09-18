<?php require('../../conexion.php');
session_start();
$user = $_SESSION['user'];

$idm = $_GET['maq'];
$vista = $_GET['vista'];
$mes = $_GET['mes'];
$año = date ("Y");

$con = 1;
$basededatos=conectardb();

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
$data = array();
if ($historico->rowCount() > 0){
  while($row = $historico->fetch()) {
	if ($minus > 0){
		$row[$vista] = $row[$vista]-$minus;
	}
    if (empty($data))
      $data = [$row['fecha'] => $row[$vista]];
    else
      $data += [$row['fecha'] => $row[$vista]];
  }
}
echo json_encode($data, JSON_FORCE_OBJECT);
?>
