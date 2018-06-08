<?php
# include ("seguridad.php");
$objeto=$_GET["objeto"];
$ACC2=$_GET["ACC2"];
#echo "ACC2: $ACC2";
if ($objeto!=NULL) $_SESSION["objeto"]=$objeto;
$base="DB494076";
$host="localhost";
$usuario="inakizur";
$contrasena="Zurgaia-2007";
$link = mysql_connect("$host","$usuario","$contrasena") or die ('No se puede conectar: '.mysql_error());
mysql_query("use $base") or die ('No se puede usar: '.mysql_error());

$sql=mysql_query("create table if not exists bv_usr_mak_disp (ID_usrmakdisp INT auto_increment primary key, usr INT UNIQUE, mak INT UNIQUE, disp INT UNIQUE)") or die ('No se puede crear la tabla 9: '.mysql_error());
#echo "prueba";
echo "<div id='menus'>";
echo "<a class='menus' href='s_listado.php?objeto=MAQUINAS'>MAQUINAS</a>";
echo "<a class='menus' href='s_listado.php?objeto=DISPOSITIVOS'>DISPOSITIVOS</a>";
echo "<a class='menus' href='s_listado.php?objeto=PRODUCTOS'>PRODUCTOS</a>";
echo "</div>";
#echo $_SESSION["objeto"];
?> 
