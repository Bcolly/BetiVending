<?php
	include_once ("../../conexion.php");

  $basededatos=conectardb();
  execute("UPDATE v_locales SET ruta = '$_POST[nuevaruta]' WHERE id = $_POST[idlocal2]", $basededatos, 1);

  header('Location: ../s_locales.php');
?>
