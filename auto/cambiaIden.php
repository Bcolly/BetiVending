<?php
require_once("../conexion.php");
$sql=conectardb();
exectute("UPDATE v_dispositivo SET nombre='$newIden' WHERE nombre='$iden'", $sql, 0);
?>