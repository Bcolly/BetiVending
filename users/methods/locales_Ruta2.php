<?php
require_once ("../seguridad.php");
require_once ("../../conexion.php");

if (!empty($_GET['loc']) && !empty($_GET['mid'])){
  $userid=$_SESSION["userid"];
  $basededatos=conectardb();
  $con=1;
  execute("UPDATE v_maquinas SET idlocal=$_GET[loc] WHERE id=$_GET[mid]", $basededatos, $con);
  $con++;
  $m=query("SELECT dispositivoid FROM v_maquinas WHERE id=$_GET[mid]", $basededatos, $con);
  $con++;
  if ($m->rowCount() > 0) {
    $row = $m->fetch();
    execute("UPDATE v_dispositivo SET idlocal=$_GET[loc] WHERE id=$row[dispositivoid]", $basededatos, $con);
  }
}
?>
