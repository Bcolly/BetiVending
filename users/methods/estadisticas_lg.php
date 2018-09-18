<?php require('../../conexion.php');
session_start();
$user = $_SESSION['user'];

$idm = $_GET['maq'];
$vista = $_GET['vista'];
$mes = $_GET['mes'];
$año = date ("Y");

$con = 1;
$basededatos=conectardb();

$historico=query("SELECT fecha, $vista from v_historico
WHERE idmaquina = $idm AND MONTH(fecha)=$mes AND YEAR(fecha)=$año ORDER BY fecha ASC LIMIT 32;", $basededatos, $con);
$data = array();
if ($historico->rowCount() > 0){
  while($row = $historico->fetch()) {
    if (empty($data))
      $data = [$row['fecha'] => $row[$vista]];
    else
      $data += [$row['fecha'] => $row[$vista]];
  }
}
echo json_encode($data, JSON_FORCE_OBJECT);
?>
