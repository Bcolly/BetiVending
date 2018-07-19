<?php
	include_once ("../../conexion.php");
  session_start();

	$userid=$_SESSION["userid"];

	if (!empty($_POST["ruta"]))
		$query = "INSERT INTO v_locales (pais, provincia, municipio, cp, calle, numero, ruta, userid)
	    VALUES ('$_POST[pais]', '$_POST[provincia]', '$_POST[pais]', $_POST[cp], '$_POST[direccion]', $_POST[numero], '$_POST[ruta]', $userid)";
	else
		$query = "INSERT INTO v_locales (pais, provincia, municipio, cp, calle, numero, userid)
	    VALUES ('$_POST[pais]', '$_POST[provincia]', '$_POST[pais]', $_POST[cp], '$_POST[direccion]', $_POST[numero], $userid)";

  $basededatos=conectardb();
  execute($query, $basededatos, 1);

  header('Location: ../s_locales.php');
?>
