<?php
require_once ("../seguridad.php");
require_once ("../../conexion.php");

if (isset($_GET['mid'])&&isset($_GET['rid'])){
	$basededatos = conectardb();
	execute("UPDATE v_maquinas SET idlocal = 0 WHERE id=$_GET[id]", $basededatos, 1);
	execute("UPDATE v_dispositivo SET idlocal = 0 WHERE id=(SELECT id FROM v_maquinas WHERE id=$_GET[id])", $basededatos, 2);
}
?>
