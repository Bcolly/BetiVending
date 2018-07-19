<?php
	include_once ("../../conexion.php");

  $basededatos=conectardb();
	echo "UPDATE v_locales SET ruta = '$_POST[nuevaruta]' WHERE id = $_POST[idlocal2]";
  execute("UPDATE v_locales SET ruta = '$_POST[nuevaruta]' WHERE id = $_POST[idlocal2]", $basededatos, 1);

  header('Location: ../s_locales.php');
?>
