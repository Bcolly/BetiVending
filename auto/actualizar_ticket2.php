<?php
//JOF_03
include_once("../conexion.php");
$hoy=date("Y-m-d");
$ahora=date("G:i");
#configuramos los valores para leer el correo
$popusuario = "beti-dex-prueba@zurgaia.net";
#$popusuario = "beti-ticket-prueba@zurgaia.net";
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
		$fecha=$asuntos[5];
		$hora_envio=date("Y-m-d H:i:s", strtotime($fecha." ".$hora));
		echo $hora_envio."<br/>";
		
		#comprobamos si la maquina esta en la base de datos. Si no esta la guardamos
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
				values ('$dispositivo',(SELECT id FROM `v_dispositivo` WHERE nombre='$dispositivo'))");
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
		//echo $maquina."<br/>";
		
		$mensaje=imap_body($stream, $nm); #cogemos el cuerpo del mensaje $nm
		$mensaje=chop($mensaje); #retira los caracteres en blanco del final de $mensaje
		
		$bloques = explode("\n\r\n", $mensaje);
		
		if (count($bloques)>1) {
			$b = $bloques[0];
			$lineas = explode("\n", $b);
			try{
				$sql = $basededatos->prepare("UPDATE v_maquinas as m SET m5c=$lineas[10],m10c=$lineas[11],m20c=$lineas[12],m50c=$lineas[13],m100c=$lineas[14]
				WHERE id=$maquina");
				$sql->execute();
			} catch (exception $e) {
				echo "No se puede la consulta 5";
				exit;
			}
			
			try{ # buscamos si hay una linea con la fecha del mensaje
				$sql = $basededatos->query("SELECT fecha FROM v_historico WHERE fecha='$fecha' AND idmaquina=$maquina");
				$num = $sql->rowCount();
			} catch (exception $e) {
				echo "No se puede la consulta 6";
				exit;
			}
			if ($num < 1) { # si no la hay, la creamos 
				$ganancias = $lineas[27];
				try{
					$sql = $basededatos->prepare("INSERT into v_historico (idmaquina, fecha, ganancias) 
					values ($maquina,'$fecha',$ganancias)");
					$sql->execute();
				} catch (exception $e) {
					echo "No se puede la consulta 7";
					exit;
				}
				echo "grabada nueva fecha.<br />";
			}
			
			unset($bloques[0]);  #eliminamos el primer bloque porque despues de este estan todas las selecciones
			$sel = 1;
			foreach ($bloques as $b){
				$lineas = explode("\n", $b);
				$linea = explode(" ", $lineas[2]);
				$precio=$linea[3];
				try{
					$sql = $basededatos->query("SELECT * FROM v_seleccion
					WHERE sel='$sel' AND idmaquina=$maquina");
					$num = $sql->rowCount();
				} catch (exception $e) {
					echo "No se puede la consulta 8";
					exit;
				}
				if ($num < 1) {
					try{
						$sql = $basededatos->prepare("INSERT into v_seleccion (idmaquina, sel, uDEXprecio)
						values ($maquina,'$sel',$precio)");
						$sql->execute();
					} catch (exception $e) {
						echo "No se puede la consulta 9";
						exit;
					}
					echo "grabada nueva seleccion.<br />";
					$linea=explode(" ", $lineas[4]);
					$ventas=$linea[0];
					$beneficio=$ventas*$precio;
					try{
						$sql = $basededatos->prepare("INSERT into v_ultimasventas (idmaquina, sel, fecha, unidades, beneficio)
						values ($maquina,'$sel','$fecha',$ventas,$beneficio)");
						$sql->execute();
					} catch (exception $e) {
						echo "No se puede la consulta 10";
						exit;
					}
				} else {
					try{ #buscamos la ultima linea de la seleccion de una maquina
						$sql = $basededatos->query("SELECT sel, MAX(fecha) as fecha, unidades, beneficio 
						FROM v_ultimasventas WHERE sel='$sel' AND idmaquina=$maquina");
						$row = $sql->fetch();
					} catch (exception $e) {
						echo "No se puede la consulta 11";
						exit;
					}
					$linea=explode(" ", $lineas[4]);
					$ventas=$linea[0]-$row["unidades"];
					$beneficio=$ventas*$precio;
					$fecha_hora = $fecha." 00:00:00";
					if ($row["fecha"]==$fecha_hora) {
						$query="UPDATE v_ultimasventas SET unidades=unidades+$ventas, beneficio=beneficio+$beneficio WHERE sel='$sel' 
						AND idmaquina=$maquina AND fecha='$fecha_hora'";
					} else {
						$query="INSERT into v_ultimasventas (idmaquina, sel, fecha, unidades, beneficio)
						values ($maquina,'$sel','$fecha',$ventas,$beneficio)";
					}
					try{
						$sql = $basededatos->prepare($query);
						$sql->execute();
					} catch (exception $e) {
						imap_delete($stream,$nm); 	# si algun email produce un error, este se borrara
						imap_expunge($stream); 		# para que a la siguente busqueda no haya errores
						echo "No se puede la consulta 12";
						exit;
					}
				}
				/*foreach ($lineas as $l){
					echo $sel." -> ".$l."<br />";
				}
				echo "<br/><br/>";*/
				$sel++;
			}
			try{
				$sql = $basededatos->prepare("UPDATE v_dispositivo SET ultimo_envio='$hora_envio' WHERE nombre='$dispositivo'");
				$sql->execute();
			} catch (exception $e) {
				echo "No se puede la consulta 13";
				exit;
			}
		}
		imap_delete($stream,$nm);//Marca el mail para ser borrado
	}
	imap_expunge($stream);//Borra los mail marcados para borrar
	imap_close($stream);
	echo"<br/>DESCONECTADO";
}
?>