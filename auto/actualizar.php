<?php
require_once("../conexion.php");
$hoy=date("Y-m-d");
$ahora=date("Y-m-d H:i");
/*if (isset($_POST["borra"])) {
	$borra=$_POST["borra"];
	if ($borra=="LIMPIAR") {
		#$sql=mysql_query("delete from v_ultimasventas") or die ('No se puede borrar la tabla 1: '.mysql_error());
		#$sql=mysql_query("INSERT INTO v_ultimasventas (maquina, fecha, hora) values ('LIMPIADO', '$hoy','$ahora')") or die("No se puede ejecutar la consulta 7: ".mysql_error());
		//TODO...
	}
}*/

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

##esto es diferente en cada dispositivo
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
		$fechas=explode("/",$asuntos[5]); 
		$fecha=$fechas[2]."-".$fechas[1]."-".$fechas[0]; #cambiamos el formato de la fecha de XX/XX/XXXX a XXXX-XX-XX
		$hora_envio=date("Y-m-d H:i:s", strtotime($fecha." ".$hora));
		
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
		#echo $maquina."<br/>";
		
		#esta consulta no se usa para nada
		/*try{
			$fecha= $basededatos->query("SELECT fecha FROM v_historico WHERE fecha='$fecha'");
			$num = $fecha->rowCount();
		} catch (exception $e) {
			echo "No se puede la consulta 1";
			exit;
		}*/
		
		$mensaje=imap_body($stream, $nm); #cogemos el cuerpo del mensaje $nm
		$mensaje=chop($mensaje); #retira los caracteres en blanco del final de $mensaje
		#echo $mensaje."<br/>";
		
		$mensaje=preg_replace("#0(A|D)#","",$mensaje);
		#echo $mensaje."<br/>";
		$mensaje = str_replace(" ", "*", $mensaje);
		#echo $mensaje."<br/>";
		$mensaje=str_replace("==","*",$mensaje);
		#echo $mensaje."<br/>";
		$mensaje=str_replace("=","",$mensaje);
		#echo $mensaje."<br/>";
		$mensaje=preg_replace("#\*{2,}#","*",$mensaje);
		#echo $mensaje."<br/>";
		
		$lineas = explode("\n", $mensaje);

		if (count($lineas) > 1) {
			for ($x=0;$x<count($lineas)-1;$x++){
				$sLinea = $lineas[$x];
				#echo $sLinea."<br/>";
				if(strstr($sLinea, "VA1")) { #buscamos las lineas que comiencen por VA1
					#echo $sLinea."<br/>";
					$linea=explode("*", $sLinea);
					$ganancias=$linea[1]/100;
					$ventas=$linea[2];
					try{ # buscamos si hay una linea con la fecha del mensaje
						$fHist = $basededatos->query("SELECT fecha FROM v_historico WHERE fecha='$fecha' AND idmaquina=$maquina");
						$num = $fHist->rowCount();
					} catch (exception $e) {
						echo "No se puede la consulta 5";
						exit;
					}
					if ($num < 1) { # si no la hay, la creamos
						try{
							$sql = $basededatos->prepare("INSERT into v_historico (idmaquina, fecha, ventas, ganancias) 
							values ($maquina,'$fecha',$ventas,$ganancias)");
							$sql->execute();
						} catch (exception $e) {
							echo "No se puede la consulta 6";
							exit;
						}
						echo "grabada nueva fecha.<br />";
					}
				} else if(strstr($sLinea, "PA1")) { #buscamos las lineas que comiencen por PA1
					$linea=explode("*", $sLinea);
					if (count($linea)==4) {
						$sel=substr($linea[1], 0, 3);
						//$sel=$linea[1];
						$precio=$linea[2]/100;
						#comprobamos que la seleccion existe y sino la creamos
						try{
							$sql = $basededatos->query("SELECT * FROM v_seleccion
							WHERE sel='$sel' AND idmaquina=$maquina");
							$num = $sql->rowCount();
						} catch (exception $e) {
							echo "No se puede la consulta 7";
							exit;
						}
						if ($num < 1) {
							try{
								$sql = $basededatos->prepare("INSERT into v_seleccion (idmaquina, sel, uDEXprecio)
								values ($maquina,'$sel',$precio)");
								$sql->execute();
							} catch (exception $e) {
								echo "No se puede la consulta 8";
								exit;
							}
							echo "grabada nueva seleccion.<br />";
						}
						$PA2 = $lineas[++$x]; #nos pasamos a la siguiente linea que deberia contener el codigo PA2
						if(strstr($PA2, "PA2")) { #nos aseguramos que la linea es PA2
							#echo $PA2."<br/>";
							try{ #buscamos la ultima linea de la seleccion de una maquina
								$sql = $basededatos->query("SELECT sel, MAX(fecha) as fecha, unidades, beneficio 
								FROM v_ultimasventas WHERE sel='$sel' AND idmaquina=$maquina");
								$num = $sql->rowCount();
							} catch (exception $e) {
								echo "No se puede la consulta 9";
								exit;
							}
							if ($num < 1) {
								$ventas=explode("*", $PA2)[1];
							} else {
								try{
									$row = $sql->fetch();
								} catch (exception $e) {
									echo "No se puede la consulta 10";
									exit;
								}
								$ventas=(explode("*", $PA2)[1])-$row["unidades"];
							}
							$beneficio = $ventas*$precio;
							#$fecha_hora = $fecha." ".$hora;
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
								echo "No se puede la consulta 11";
								exit;
							}
						}
					}
				} else if(strstr($sLinea, "EA1")) { #buscamos las lineas que comiencen por EA1
					$linea=explode("*", $sLinea);
					#$sel=$linea[1];
					$sel=substr($linea[1], -3); #cojemos la parte referente a la seleccion de la linea
					$dia="20".substr($linea[2], 0, 2)."-".substr($linea[2], 2, 2)."-".substr($linea[2], 4);
					#$hor=substr($linea[3], 0, 2).":".substr($linea[3], 2, 2).":".substr($linea[3], 4);
					$dia=$dia." ".substr($linea[3], 0, 2).":".substr($linea[3], 2, 2).":".substr($linea[3], 4);
					try{
						$sql = $basededatos->query("SELECT comprobacion_evento FROM v_dispositivo
						WHERE nombre='$dispositivo'");
						$row = $sql->fetch();
					} catch (exception $e) {
						echo "No se puede la consulta de tiempo";
						exit;
					}
					if ($dia >= $row['comprobacion_evento']) {
						#hacemos algun ajuste para evitar errores (temporal)
							$sel = str_replace("A", "V", $sel);
							$sel = str_replace("B", "W", $sel);
						# fin de los ajustes
						
						$sel=substr($linea[1], 0, 4)."".$sel;
						#echo $sel."<br/>";
						
						#buscamos si el evento ya existe
						try{
							$sql = $basededatos->query("SELECT * FROM v_evento
							WHERE sel='$sel' AND idmaquina=$maquina");
							$num = $sql->rowCount();
						} catch (exception $e) {
							echo "No se puede la consulta 12";
							exit;
						}
						if ($num < 1) { #si el evento de este dia no existe lo creamos
							try{
								$sql = $basededatos->prepare("INSERT into v_evento (idmaquina, sel, fecha_hora)
								values ($maquina,'$sel','$dia')");
								$sql->execute();
							} catch (exception $e) {
								echo "No se puede la consulta 13";
								exit;
							}
							echo "grabado nuevo evento.<br />";
						}
					}
				} else if(strstr($sLinea, "EA2")) { #buscamos las lineas que comiencen por EA2
					#TODO.....
				} else if(strstr($sLinea, "CA15")) { #buscamos las lineas que comiencen por CA15
					echo $sLinea."<br/>";
					$linea=explode("*", $sLinea);
					$error=$linea[0]."*".$linea[1];
					if ($error!="ERROR*CRC" && count($linea)>3) {
						$m5=$linea[3];
						$m10=$linea[4];
						$m20=$linea[5];
						$m50=$linea[6];
						try{
							echo "UPDATE v_maquinas SET m5c=$m5,m10c=$m10,m20c=$m20,m50c=$m50 WHERE id=$maquina";
							$sql = $basededatos->prepare("UPDATE v_maquinas SET m5c=$m5,m10c=$m10,m20c=$m20,m50c=$m50 WHERE id=$maquina");
							$sql->execute();
						} catch (exception $e) {
							echo "No se puede la consulta 14";
							imap_delete($stream,$nm);//Marca el mail para ser borrado
							imap_expunge($stream);//Borra los mail marcados para borrar
							exit;
						}
						$x++;
					}
				}
				else if(strstr($sLinea, "CA17")) {
					$query="UPDATE v_maquinas SET ";
					$fin=false;
					while (!$fin) {
						#echo "linea: ".$sLinea."<br/>";
						$linea=explode("*", $sLinea);
						$query=$query."m".$linea[2]."c=".$linea[3].",";
						if(strstr($lineas[$x+1], "CA17")) $sLinea = $lineas[++$x];
						else $fin=true;
					}
					if ($fin) {
						$query=substr($query, 0, -1)." WHERE id=$maquina";
						#echo "query: ".$query."<br/>";
						try{
							$sql = $basededatos->prepare($query);
							$sql->execute();
						} catch (exception $e) {
							echo "No se puede la consulta 15";
							exit;
						}
					}
				}
				else if(strstr($sLinea, "|FIN|")) {
					break;
				}
			}
			try{
				$sql = $basededatos->prepare("UPDATE v_dispositivo SET ultimo_envio='$hora_envio' WHERE nombre='$dispositivo'");
				$sql->execute();
			} catch (exception $e) {
				echo "No se puede la consulta 16";
				exit;
			}
		}
		imap_delete($stream,$nm);//Marca el mail para ser borrado
		#echo $sLinea."<br/>";
	}
	imap_expunge($stream);//Borra los mail marcados para borrar
	imap_close($stream);
	echo"<br/>DESCONECTADO";
}
?>