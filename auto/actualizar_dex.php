<?php
		$mensaje=preg_replace("#0(A|D)#","",$mensaje);
		$mensaje = str_replace(" ", "*", $mensaje);
		$mensaje=str_replace("==","*",$mensaje);
		$mensaje=str_replace("=","",$mensaje);
		$mensaje=preg_replace("#\*{2,}#","*",$mensaje);

		$lineas = explode("\n", $mensaje);

		if (count($lineas) > 1) {
			for ($x=0;$x<count($lineas)-1;$x++){
				$sLinea = $lineas[$x];
				//echo $sLinea."<br/>";
				if(strstr($sLinea, "VA1")) { //buscamos las lineas que comiencen por VA1
					read_va1($sLinea);
				} else if(strstr($sLinea, "PA1")) { //buscamos las lineas que comiencen por PA1
					read_pa1($sLinea);
				} else if(strstr($sLinea, "EA1")) { //buscamos las lineas que comiencen por EA1
					read_ea1($sLinea);
				} else if(strstr($sLinea, "EA2")) { //buscamos las lineas que comiencen por EA2
					read_ea2($sLinea);
				} else if(strstr($sLinea, "CA15")) { //buscamos las lineas que comiencen por CA15
					read_ca15($sLinea);
				} else if(strstr($sLinea, "CA17")) {
					read_ca17($sLinea);
				} else if(strstr($sLinea, "|FIN|")) {
					break;
				}
			}
			$con = 17;
			execute("UPDATE v_dispositivo SET ultimo_envio='$hora_envio' WHERE nombre='$dispositivo'", $basededatos, $con);
			$con++; //c18
		}

		function read_va1($sl) {
			global $basededatos, $fecha, $maquina;
			global $stream, $nm;
			$con = 5;
			//echo $sl."<br/>";
			$linea=explode("*", $sl);
			$ganancias=$linea[1]/100;
			$ventas=$linea[2];
			// buscamos si hay una linea con la fecha del mensaje
			$fHist = query("SELECT fecha FROM v_historico WHERE fecha='$fecha' AND idmaquina=$maquina", $basededatos, $con);
			$con++; //c6
			if ($fHist->rowCount() < 1) { // si no la hay, la creamos
				//ERROR: no se pasa bien el DEX y por lo tanto no se ejecuta
				/*execute("INSERT into v_historico (idmaquina, fecha, ventas, ganancias)
				values ($maquina,'$fecha',$ventas,$ganancias)", $basededatos, $con);
				echo "grabada nueva fecha.<br />";*/
				//SOLUCION TEMP.
				try{
					$sql = $basededatos->prepare("INSERT into v_historico (idmaquina, fecha, ventas, ganancias)
					values ($maquina,'$fecha',$ventas,$ganancias)");
					$sql->execute();
				} catch (exception $e) {
					imap_delete($stream,$nm); 	// si algun email produce un error, este se borrara
					imap_expunge($stream); 		// para que a la siguente busqueda no haya errores
					echo "No se puede la consulta $con";
					exit;
				}
			}
			$con++; //c7
		}

		function read_pa1($sl) {
			global $basededatos, $maquina, $lineas, $x;
			$con = 7;

			$linea=explode("*", $sl);
			if (count($linea)==4) {
				$sel=substr($linea[1], 0, 3);
				//$sel=$linea[1];
				$precio=$linea[2]/100;
				//comprobamos que la seleccion existe y sino la creamos
				$sql = query("SELECT * FROM v_seleccion
				WHERE sel='$sel' AND idmaquina=$maquina", $basededatos, $con);
				$con++; //c8

				if ($sql->rowCount() < 1) {
					execute("INSERT into v_seleccion (idmaquina, sel, uDEXprecio)
					values ($maquina,'$sel',$precio)", $basededatos, $con);
					$con++; //c9
					echo "grabada nueva seleccion.<br />";
				}
				$PA2 = $lineas[++$x]; //nos pasamos a la siguiente linea que deberia contener el codigo PA2
				if(strstr($PA2, "PA2")) { //nos aseguramos que la linea es PA2
					read_pa2($PA2, $con);
				}
			}
		}

		function read_pa2($PA2, $con) {
			global $basededatos, $fecha, $maquina;

			//echo $PA2."<br/>";
			//buscamos la ultima linea de la seleccion de una maquina
			$sql = query("SELECT sel, MAX(fecha) as fecha, unidades, beneficio
			FROM v_ultimasventas WHERE sel='$sel' AND idmaquina=$maquina", $basededatos, $con);
			$con++; //c10

			if ($sql->rowCount() < 1) {
				$ventas=explode("*", $PA2)[1];
			} else {
				try{
					$row = $sql->fetch();
				} catch (exception $e) {
					echo "No se puede la consulta $con";
					exit;
				}
				$con++; //c11
				$ventas=(explode("*", $PA2)[1])-$row["unidades"];
			}
			$beneficio = $ventas*$precio;
			//$fecha_hora = $fecha." ".$hora;
			$fecha_hora = $fecha." 00:00:00";
			if ($row["fecha"]==$fecha_hora)
				$query="UPDATE v_ultimasventas SET unidades=unidades+$ventas, beneficio=beneficio+$beneficio WHERE sel='$sel'
				AND idmaquina=$maquina AND fecha='$fecha_hora'";
			else
				$query="INSERT into v_ultimasventas (idmaquina, sel, fecha, unidades, beneficio)
				values ($maquina,'$sel','$fecha',$ventas,$beneficio)";

			try{
				$sql = $basededatos->prepare($query);
				$sql->execute();
			} catch (exception $e) {
				imap_delete($stream,$nm); 	// si algun email produce un error, este se borrara
				imap_expunge($stream); 		// para que a la siguente busqueda no haya errores
				echo "No se puede la consulta $con";
				exit;
			}
			$con++; //c12
		}

		function read_ea1($sl) {
			global $basededatos, $dispositivo, $maquina;
			$con = 12;

			$linea=explode("*", $sl);
			//$sel=$linea[1];
			$sel=substr($linea[1], -3); //cojemos la parte referente a la seleccion de la linea
			$dia="20".substr($linea[2], 0, 2)."-".substr($linea[2], 2, 2)."-".substr($linea[2], 4);
			//$hor=substr($linea[3], 0, 2).":".substr($linea[3], 2, 2).":".substr($linea[3], 4);
			$dia=$dia." ".substr($linea[3], 0, 2).":".substr($linea[3], 2, 2).":".substr($linea[3], 4);
			$sql = query("SELECT comprobacion_evento FROM v_dispositivo
			WHERE nombre='$dispositivo'", $basededatos, $con);
			$con++; //c13
			try{
				$row = $sql->fetch();
			} catch (exception $e) {
				echo "No se puede la consulta de tiempo";
				exit;
			}
			if ($dia >= $row['comprobacion_evento']) {
				//hacemos algun ajuste para evitar errores (temporal)
				$sel = str_replace("A", "V", $sel);
				$sel = str_replace("B", "W", $sel);
				//fin de los ajustes

				$sel=substr($linea[1], 0, 4)."".$sel;

				//buscamos si el evento ya existe
				$sql = query("SELECT * FROM v_evento
				WHERE sel='$sel' AND idmaquina=$maquina", $basededatos, $con);
				$con++; //c14

				if ($sql->rowCount() < 1) { //si el evento de este dia no existe lo creamos
					execute("INSERT into v_evento (idmaquina, sel, fecha_hora)
					values ($maquina,'$sel','$dia')", $basededatos, $con);
					echo "grabado nuevo evento.<br />";
				}
				$con++; //c15
			}
		}

		function read_ea2($sl) {
			//TODO
		}

		function read_ca15($sl) {
			global $basededatos, $maquina, $x;
			global $stream, $nm;
			$con = 15;

			echo $sl."<br/>";
			$linea=explode("*", $sl);
			$error=$linea[0]."*".$linea[1];
			if ($error!="ERROR*CRC" && count($linea)>3) {
				$m5=$linea[3];
				$m10=$linea[4];
				$m20=$linea[5];
				$m50=$linea[6];

				//echo "UPDATE v_maquinas SET m5c=$m5,m10c=$m10,m20c=$m20,m50c=$m50 WHERE id=$maquina";
				try{
					$sql = $basededatos->prepare("UPDATE v_maquinas SET m5c=$m5,m10c=$m10,m20c=$m20,m50c=$m50 WHERE id=$maquina");
					$sql->execute();
				} catch (exception $e) {
					echo "No se puede la consulta $con";
					imap_delete($stream,$nm);//Marca el mail para ser borrado
					imap_expunge($stream);//Borra los mail marcados para borrar
					exit;
				}
				$con++; //c16
				$x++;
			}
		}

		function read_ca17($sl) {
			global $basededatos, $maquina, $lineas, $x;
			$con = 16;

			$query="UPDATE v_maquinas SET ";
			$fin=false;
			while (!$fin) {
				//echo "linea: ".$sLinea."<br/>";
				$linea=explode("*", $sl);
				$query=$query."m".$linea[2]."c=".$linea[3].",";
				if(strstr($lineas[$x+1], "CA17")) $sl = $lineas[++$x];
				else $fin=true;
			}
			if ($fin) {
				$query=substr($query, 0, -1)." WHERE id=$maquina";
				//echo "query: ".$query."<br/>";
				execute($query, $basededatos, $con);
				$con++; //c17
			}
		}
?>
