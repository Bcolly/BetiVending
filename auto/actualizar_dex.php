<?php
		require_once("lineas_dex.php");

		$mensaje=preg_replace("#0(A|D)#","",$mensaje);
		$mensaje=str_replace("\s", "*", $mensaje);
		$mensaje=str_replace(" ", "*", $mensaje);
		$mensaje=str_replace("==","*",$mensaje);
		$mensaje=str_replace("=","",$mensaje);
		$mensaje=preg_replace("#\*{2,}#","*",$mensaje);

		$mensaje=str_replace("\n", "", $mensaje);
		//echo $mensaje;

		//$lineas = explode("\n", $mensaje);
		preg_match_all('/[A-Z]\s*[A-Z]\s*[0-9]{1,3}[\*]/', $mensaje, $matches);
		$cabeceras = $matches[0];
		$lineas = preg_split("/[A-Z]\s*[A-Z]\s*[0-9]{1,3}[\*]/", $mensaje);
		//print_r($lineas);

		if (count($lineas) > 1) {
			//echo "<br/>";
			for ($x=0;$x<count($lineas)-1;$x++){
				$sLinea = $cabeceras[$x].$lineas[$x];
				//echo ($x+1)." - ".$sLinea."<br/>";
				if(strstr($sLinea, "VA1")) { //buscamos las lineas que comiencen por VA1
					read_va1($sLinea);
				} else if(strstr($sLinea, "PA1")) { //buscamos las lineas que comiencen por PA1
					read_pa1($sLinea);
				} else if(strstr($sLinea, "EA1")) { //buscamos las lineas que comiencen por EA1
					read_ea1($sLinea);
				} else if(strstr($sLinea, "EA2")) { //buscamos las lineas que comiencen por EA2
					read_ea2($sLinea);
				} else if(strstr($sLinea, "CA15") or strstr($sLinea, "CA 15")) { //buscamos las lineas que comiencen por CA15
					read_ca15($sLinea);
				} else if(strstr($sLinea, "CA17")) { //buscamos las lineas que comiencen por CA17
					read_ca17($sLinea);
				} else if(strstr($sLinea, "|FIN|")) {
					break;
				}
			}
			$con = 17;
			execute("UPDATE v_dispositivo SET ultimo_envio='$hora_envio' WHERE nombre='$dispositivo'", $basededatos, $con);
			$con++; //c18
		}
?>
