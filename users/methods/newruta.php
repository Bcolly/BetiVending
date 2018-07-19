<?php
	include_once ("../../conexion.php");

  $basededatos=conectardb();
  execute("UPDATE v_locales SET ruta = '$_POST[nombre]' WHERE id = $_POST[idlocal]", $basededatos, 1);

  header('Location: ../s_locales.php');
?>
