<?
include_once ("seguridad.php");
$TABLA=$_POST["TABLA"];
?>
<form action="s_vertablas.php" method="POST">
<input type='text' name='TABLA' value=''>
<input type='SUBMIT' name='ACC' value='ENVIAR'>
</form>
<?php
try{
$basededatos = new PDO ("mysql:host=localhost;dbname=betiVENDING","betiuser","betiV-2013");
$basededatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$basededatos->exec("SET NAMES 'utf8'");
} catch (Exception $e){
  echo "Conexion no disponible. Contacte con el administrador";
  exit;
}

try{
  $dispositivos= $basededatos->query("SELECT * FROM ".$TABLA."");
  } catch (exception $e) {
    echo "No se puede la consulta 1";
    exit;
    }
echo "<pre>";
var_dump($dispositivos->fetchALL(PDO::FETCH_ASSOC));
?>

