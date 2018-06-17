<?php require('../../conexion.php');
session_start();
$user = $_SESSION['user'];

$idm = $_GET['maq'];
$vista = $_GET['vista'];

$con = 1;
$basededatos=conectardb();
$historico=query("SELECT fecha, $vista from v_historico
WHERE idmaquina = $idm ORDER BY fecha ASC LIMIT 32;", $basededatos, $con);

while($row = $historico->fetch()) {
  if (empty($data))
    $data = [$row['fecha'] => $row[$vista]];
  else
    $data += [$row['fecha'] => $row[$vista]];
}
echo json_encode($data);
?>
