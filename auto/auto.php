<html><body>
<?php
$mac=$_GET["mac"];   
#$csq=$_GET["csq"];   
#$DEX=$_GET["DEX"];
#$ACC=$_GET["ACC"];
$iden=$_GET["iden"];
$iplocal=$_GET["iplocal"];
$ippublica=$_GET["ippublica"];  
require_once("../conexion.php");

$dia = date("Y-m-d");
$hora = date("H:i");
echo "G=".$dia."<br />";  # G -> Dia 
echo "O=".$hora."<br />"; # O -> Hora (Orain)

try{
    #$dispositivo= $basededatos->query("SELECT * FROM v_dispositivo WHERE nombre='$iden' AND MAC='$mac'");
	$dispositivo= $basededatos->query("SELECT * FROM v_dispositivo WHERE nombre='$iden'");
    $num = $dispositivo->rowCount();
} catch (exception $e) {
	echo "No se puede la consulta 1";
	exit;
}
if ($num < 1) {
	try{
		$dispositivo = $basededatos->query("SELECT * FROM v_provisional_dispo WHERE nombre='$iden'");
		$num = $dispositivo->rowCount();
	} catch (exception $e) {
			echo "No se puede la consulta 2";
			exit;
	}
	if ($num > 0) {
		try{
			$d = $dispositivo->fetch();
			$sql = $basededatos->prepare("INSERT INTO v_dispositivo(userid, nombre, iplocal, ippublica, MAC, fecha, hora) 
			VALUES ($d[userid], '$iden', '$iplocal', '$ippublica', '$mac', '$dia', '$hora');");
			$sql->execute();
		} catch (exception $e) {
				echo "No se puede la consulta 3";
				exit;
		}
	}
} else {
	try{
		$d = $dispositivo->fetch();
		if ($mac != '') 
			$query = "UPDATE v_dispositivo SET fecha='$dia', hora='$hora'
			WHERE nombre='$iden' AND MAC='$mac'";
		else 
			$query = "UPDATE v_dispositivo SET fecha='$dia', hora='$hora'
			WHERE nombre='$iden'";
		$sql = $basededatos->prepare($query);
		$sql->execute();
	} catch (exception $e) {
			echo "No se puede la consulta 4";
			exit;
	}
}

#Borrar la siguiente
if ($iden=="Snacks_04") echo "P=S";

if($d["solicitud_envio"]==1) {
	echo "P=S";
} else {
	if ($d["ultimo_envio"]) { #si el dispositivo existe y ha mandado datos antes
		$ahora=date("Y-m-d H:i:s");
		$siguiente=sumarFecha($d["frecuencia"], $d["ultimo_envio"]);
		//if ($d["ultimo_envio"]) $siguiente=sumarFecha($d["frecuencia"], $d["ultimo_envio"]);
		//else $siguiente = $ahora;
		if ($iden=="JOF_01" or $iden=="JOF_02" or $iden=="JOF_03") echo "P=N";
		elseif ($iden=="Snacks_04") echo "P=S";
		elseif ($ahora >= $siguiente) echo "P=S";
		else echo "P=N";
	} else echo "P=N";
} # P -> Solicitud de envio N=no/S=sí
echo "<br />";

if($d["solicitud_iden"]!="") {
	$newIden=$d["solicitud_iden"];
	echo "I=$newIden";
	include("cambiaIden.php");
	$iden=$newIden;
} else echo "IXX"; # I -> Identificador
echo "<br />";
 
if($d["solicitud_frecuencia"]>0) {
	echo "T=$d[solicitud_frecuencia]";
} else echo "TXX"; # T -> Frecuencia (tiempo entre envio de datos)
echo "<br />";

#datos que faltan por insertar
echo "SXX<br />"; # S -> SSID
echo "WXX<br />"; # W -> Password
echo "MXX<br />"; # M -> Modo de lectura
echo "CXX<br />"; # C -> Puerto de comunicación

try{
	$sql = $basededatos->prepare("UPDATE v_dispositivo SET solicitud_envio=0, solicitud_iden='', solicitud_frecuencia=0 WHERE nombre='$iden' ");
	$sql->execute();
} catch (exception $e) {
	echo "No se puede la consulta 4";
	exit;
}
?>
</body></html>
<?php 
function sumarFecha($num, $ultimo){
  $ahora = date($ultimo);
  $res=($num/60);
  $div=explode('.',$res);
  $hor=$div[0];//aqui obtienes las horas
  $min=$num - (60*$hor);//aqui obtienes los minutos
  $nuevafecha = strtotime ( '+'. $hor .' hour' , strtotime ( $ahora ) ) ;
  $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
  $nuevafecha = strtotime ( '+'. $min .' minute' , strtotime ( $nuevafecha ) ) ;
  $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
  return $nuevafecha;
}
?>
