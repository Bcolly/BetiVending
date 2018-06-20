<?php
		$lineas = explode("\n", $mensaje);

		if (count($lineas)>1) {
			$x=8;
			$fin=false;
			$m5=$m10=$m20=$m50=$m100=$m200=0;
			while (!$fin) {
				if (preg_match("/^\d+?/", $lineas[$x])){
					if ($m5 == 0) $m5=$lineas[$x];
					elseif ($m10 == 0) $m10=$lineas[$x];
					elseif ($m20 == 0) $m20=$lineas[$x];
					elseif ($m50 == 0) $m50=$lineas[$x];
					elseif ($m100 == 0) $m100=$lineas[$x];
					elseif ($m200 == 0) $m200=$lineas[$x];
					$x++;
				} else {
					$fin=true;
				}
			}
			
			try{
				$sql = $basededatos->prepare("UPDATE v_maquinas as m SET m5c=$m5,m10c=$m10,m20c=$m20,m50c=$m50,m100c=$m100,m200c=$m200 WHERE id=$maquina");
				$sql->execute();
			} catch (exception $e) {
				echo "No se puede la consulta 5";
				exit;
			}
			$x=$x+23;
			try{ # buscamos si hay una linea con la fecha del mensaje
				$sql = $basededatos->query("SELECT fecha FROM v_historico WHERE fecha='$fecha' AND idmaquina=$maquina");
				$num = $sql->rowCount();
			} catch (exception $e) {
				echo "No se puede la consulta 6";
				exit;
			}
			if ($num < 1) { # si no la hay, la creamos
				#echo $lineas[$x]."<br/>";
				$ganancias=$lineas[$x];
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
			$fin=false;
			$x=$x+14;
			$sel=1;
			for ($x=$x;$x<count($lineas)-1;$x++){
				#echo $lineas[$x]."<br/>";
				if(preg_match("/[A-Z]+(\s)*\d{2}\.\d{2}\:\d{4}/", $lineas[$x])) {
					$linea=explode(":", $lineas[$x]);
					$precio=substr($linea[0], -5);
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
						$ventas=explode(" ", $lineas[++$x])[0];
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
						$ventas=(explode(" ", $lineas[++$x])[0])-$row["unidades"];
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
					$sel++;
				}
			}
			try{
				$sql = $basededatos->prepare("UPDATE v_dispositivo SET ultimo_envio='$hora_envio' WHERE nombre='$dispositivo'");
				$sql->execute();
			} catch (exception $e) {
				echo "No se puede la consulta 13";
				exit;
			}
		}
?>
