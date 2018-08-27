<?php
require_once ("../seguridad.php");
require_once ("../../conexion.php");

if (!empty($_GET['rut'])){
  $userid=$_SESSION["userid"];
  $basededatos=conectardb();
  $locales=query("SELECT * FROM v_locales WHERE ruta='$_GET[rut]' AND userid=$userid", $basededatos, 1);
  foreach ($locales as $l) {
    //echo "<option value='$l[id]'>$l[calle] $l[numero], $l[municipio]</option>";
    echo "$l[id]@*$l[calle] $l[numero], $l[municipio]*/*";
  }
}
?>
