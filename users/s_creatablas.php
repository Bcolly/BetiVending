<?php
include_once ("seguridad.php"); 
echo "Creando tablas...";
try{
#$dispositivos= $basededatos->query("SELECT * FROM bv_dispositivos WHERE usuario='usuario'");
	$sql= $basededatos->query("create table if not exists bv_provisional_dispo (ID_provisional_dispo INT auto_increment primary key, user INT, iden varchar(20), caduca date)");
} catch (exception $e) {
	echo "No se puede la consulta 1";
	echo $e->getMessage();
	exit;
}
?>