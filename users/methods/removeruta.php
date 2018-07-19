<?php
	include_once ("../../conexion.php");

  $basededatos=conectardb();
  execute("UPDATE v_locales SET ruta = NULL WHERE id = $_GET[idlocal]", $basededatos, 1);

  header('Location: ../s_locales.php');
?>
