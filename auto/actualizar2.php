<?php
include_once("../conexion.php");
$hoy=date("Y-m-d");
$ahora=date("G:i");
#configuramos los valores para leer el correo
#$popusuario = "beti-dex@zurgaia.net";
#$poppassword = "Asdf1234";
#$popservidor = '{webmail.zurgaia.net:143/imap/notls}INBOX';

$popusuario = "beti-dex-prueba@zurgaia.net";
$poppassword = "Asdf1234";
$popservidor = '{webmail.zurgaia.net:143/imap/notls}INBOX';

echo "Conectando con el servidor de correo <b>$popusuario</b>... <br/>";
#hacemos la conexion con el correo
$stream = imap_open($popservidor,$popusuario,$poppassword);

if($stream){
	$cant=imap_num_msg($stream); #contamos los mensajes que hay
	echo "<br/><b>CANTIDAD CORREOS: </b>".$cant."<br/><br/>";
	for($nm=1;$nm<=$cant;$nm++){
		$header = imap_header($stream, $nm);
		$asunto=$header->subject;
		$fecha=$header->date;
		echo $asunto."<br/>"; echo $fecha."<br/>";
		$asuntos=explode(" ",$asunto); #dividimos el asunto en un array por los espacios
		$hora=$asuntos[6];
		$dispositivo=$asuntos[3];
		$tipo=$asuntos[2];
		//cuidado con las fechas, cada uno las envia de una manera.
		if ($tipo == "Dex") {
			$fechas=explode("/",$asuntos[5]); 
			$fecha=$fechas[2]."-".$fechas[1]."-".$fechas[0]; #cambiamos el formato de la fecha de XX/XX/XXXX a XXXX-XX-XX
		} else $fecha=$asuntos[5];
		$hora_envio=date("Y-m-d H:i:s", strtotime($fecha." ".$hora));
		
		try{
			$maquinas = $basededatos->query("SELECT m.id FROM v_maquinas as m, v_dispositivo as d WHERE m.dispositivoid=d.id AND d.nombre='$dispositivo'");
			$num = $maquinas->rowCount();
		} catch (exception $e) {
			echo "No se puede la consulta 1";
			exit;
		}
		if ($num < 1) {
			try{
				$sql = $basededatos->prepare("INSERT into v_maquinas (nombre, dispositivoid) 
				values ('$dispositivo',(SELECT id FROM v_dispositivo WHERE nombre='$dispositivo'))");
				$sql->execute();
			} catch (exception $e) {
				imap_delete($stream,$nm); 	# si algun email produce un error, este se borrara
				imap_expunge($stream); 		# para que a la siguente busqueda no haya errores
				echo "No se puede la consulta 2";
				exit;
			}
			echo "grabada nueva maquina.<br />";
			
			try{ #ahora buscamos la id de la maquina que acabamos de crear
				$maquinas = $basededatos->query("SELECT m.id FROM v_maquinas as m, v_dispositivo as d WHERE m.dispositivoid=d.id AND d.nombre='$dispositivo'");
			} catch (exception $e) {
				echo "No se puede la consulta 3";
				exit;
			}
		}
		#ahora cogemos la id de la maquina que se va a utilizar para guardar datos
		try{
			$row = $maquinas->fetch();
		} catch (exception $e) {
			echo "No se puede la consulta 4";
			exit;
		}
		$maquina=$row["id"];
		
		$mensaje=imap_body($stream, $nm); #cogemos el cuerpo del mensaje $nm
		$mensaje=chop($mensaje); #retira los caracteres en blanco del final de $mensaje
		
		switch ($tipo){
			case "Dex":
				include("actualizar_dex.php");
				break;
			case "Ticket":
				$tipo=$asuntos[1];
				switch ($tipo){
					case "Artic272":
						include("actualizar_artic272.php");
						break;
				}
				break;
			default:
				echo "Correo desconocido<br />";
		}
		
		imap_delete($stream,$nm);//Marca el mail para ser borrado
		#echo $sLinea."<br/>";
	}
	imap_expunge($stream);//Borra los mail marcados para borrar
	imap_close($stream);
	echo"<br/>DESCONECTADO";
}
?>