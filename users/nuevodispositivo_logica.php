<?php
function step1($basededatos){
  $m1="disabled";
  $con = 1;
	if (isset($_GET["iden"]))
    $iden=$_GET["iden"];

  $usuario=$_SESSION["user"];
	$error = NULL;
  if (isset($_GET["ACC"]) AND $_GET["ACC"]=="COMPROBAR") {
    $error="NO";
    $dispositivos = query("SELECT * FROM v_dispositivo WHERE nombre='$iden'", $basededatos, $con);
    $con++; //2

    if ($dispositivos->rowCount()>0)
      $error="enuso";
    if(strlen($iden)<6 or strlen($iden)>20)
      $error="tamano";

    $dispositivos = query("SELECT d.* FROM v_dispositivo as d, v_user as u WHERE nombre='$iden' and u.usuario='$usuario' and d.userid = u.id;",
       $basededatos, $con);
      $con++; //3

		  if ($dispositivos->rowCount()>0)
        $error="registrado";

      $dispositivos = query("SELECT * FROM v_provisional_dispo WHERE nombre='$iden'", $basededatos, $con);
      $con++; //4

      if ($dispositivos->rowCount()>0)
        $error="enuso";

      $dispositivos = query("SELECT d.* FROM v_provisional_dispo as d, v_user as u WHERE d.nombre='$iden' and u.usuario='$usuario' and d.userid = u.id;",
       $basededatos, $con);
      $con++; //5

      if ($dispositivos->rowCount()>0)
        $error="preparando";
    }
	  switch ($error) {
      case "enuso":
        echo "<br /><div class='alert alert-danger'>";
			  echo "¡Atención! El identificador de dispositivo introducido \"$iden\" ya esta siendo utilizado. Debe elegir otro identificador";
			  echo "</div>";
        break;
      case "tamano":
        echo "<br /><div class='alert alert-danger'>";
			  echo "¡Atención! El identificador debe tener entre 6 y 20 caracteres. El valor introducido (".$iden.") tiene ".strlen($iden)." caracteres ";
        echo "</div>";
        break;
      case "preparando":
        $m1="active";
        echo "<br /><div class='alert alert-success'>";
        $_SESSION['iden']=$iden;
			  echo "¡Atención! El identificador  introducido (".$iden.") ya está en proceso de alta, pulse SIGUIENTE para el siguiente paso.";
			  echo "</div>";
			  break;
		  case "registrado":
        $m1="active";
        echo "<br /><div class='alert alert-success'>";
        $_SESSION['iden']=$iden;
			  echo "¡Atención! El identificador  introducido (".$iden.") ya está en uso, pulse SIGUIENTE siguiente si quiere configurarlo.";
			  echo "</div>";
	      break;
      case "NO":
        $m1="active";
			  echo "<br /><div class='alert alert-success'>";
    		echo "¡Bien! el identificador \"$iden\" ha sido registrarlo a su nombre. Ahora tiene 10 dìas para conectarlo a una red
    		  WiFi y completar el segundo paso. Pulse \"SIGUIENTE\" para continuar...";
    		echo "</div>";
    		$_SESSION['iden']=$iden;
    		$hoy=date("Y-m-d");
    		$caduca = strtotime ( '+10 day' , strtotime ( $hoy ) ) ;
    		$caduca = date ( 'Y-m-d' , $caduca );

        execute("INSERT INTO v_provisional_dispo (nombre,caduca,userid) VALUES('$iden','$caduca',
          (SELECT id FROM v_user WHERE usuario='$usuario'));", $basededatos, $con);
        $con++; //6
	  }
	  if ($m1!="active") {
  		echo "<br /><h3>Introduzca el identificador unico de su dispositivo:</h3>";
  		echo "<p>Con su dispositivo se le ha hecho entrega de un identificador único. Este identificador es como la matricula de su máquina, y sirve para diferenciarlo del resto de
  		dispositivos en la red. </p><p>Si no dispone de un identificador por que esta utilizando un ordenador u otro dispositivo propio, puede inventarselo, comprobaremos que no exista otro
  		dispositivo con el mismo identificador.</p>";
?>
		<form class="form-inline" role="form" action="nuevodispositivo.php" method="GET">
			<div class="form-group">
				<label class="sr-only" for="identificador">Email</label>
				<input type='hidden' name='step' value='step1'/>
				<input type="text" class="form-control" id="identificador"
				placeholder="Introduce el identificador" name="iden"/>
				</select>
				<button type="submit" class="btn btn-primary" name="ACC" value="COMPROBAR">COMPROBAR</button>
			</div>
		</form>
		<br />
		<form class="form-inline" role="form" action="nuevodispositivo.php" method="GET">
			<div class="form-group">
				<label class="sr-only" for="identificador">Email</label>
				<input type='hidden' name='step' value='step1'/>
				<select name='iden' class="form-control" onchange='mostrarText()'>
				  <option value=""></option>
<?php
          $dispositivos = query("SELECT d.* FROM v_provisional_dispo as d, v_user as u WHERE u.usuario='$usuario' and d.userid = u.id;",
           $basededatos, $con);
          $con++; //7

					foreach ($dispositivos as $dis) {
						echo "<option value='$dis[nombre]'>$dis[nombre]</option>";
					}
?>
				</select>
				<button type="submit" class="btn btn-primary" name="ACC" value="COMPROBAR">BUSCAR</button>
			</div>
		</form><br /><br />
<?php
	}
	return $m1;
} #end function step1

function step2($iden, $basededatos){
	$m2 = "disabled";
  $con= 1;
	if (isset($_GET["ACC"]) AND $_GET["ACC"]=="BUSCAR") {
    $dispositivos = query("SELECT * FROM v_dispositivo WHERE nombre='$iden'", $basededatos, $con);
    $con++; //2

		if ($dispositivos->rowCount() < 1) {
			echo "<br /><div class='alert alert-danger'>";
			echo "¡Atención! El dispositivo <b>$iden</b> que busca no ha enviado aun ninguna señal para iniciarse en el sistema.";
			echo "</div>";
		} else {
			echo "<br /><div class='alert alert-success'>";
			echo "¡Bien! el dispositivo \"$iden\" ha sido conectado a una red y esta enviando listo para funcionar.";
			echo "</div>";
      execute("UPDATE v_dispositivo SET tipo='$_GET[dispositivo]' WHERE nombre='$iden';", $basededatos, $con);
      $con++; //3

			$m2="active";
		}
	}
  echo"<p><h3>Instalación del dispositivo <b>$iden</b></h3></p>";
 ?>
 		<p><h3>Conecte el dispositivo a una red wifi y pulse "BUSCAR".</h3></p>
		<form class="form-inline" role="form" action="nuevodispositivo.php" method="GET">
			<div class="form-group">
				<input type='hidden' name='step' value='step2'/>
<?php
        $dispositivos = query("SELECT * FROM v_dispositivo WHERE nombre='$iden'", $basededatos, $con);
        $con++; //4

        $row=$dispositivos->fetch();
				if ($row["tipo"]) {
					echo "<h4>El dispositivo $row[nombre] es del tipo $row[tipo]. Pulse CAMBIAR si no es correcto.</h4>";
					$m2="active";
					echo "<button type='submit' class='btn btn-primary' name='ACC' value='BUSCAR'>CAMBIAR</button>";
				} else {	?>
					<font size='5'><label class="label label-default" for="dispositivo">Seleccione el dispositivo:</label></font>
					<select name='dispositivo' class="form-control" id="dipositivo" onchange='mostrarText()'>
					  <option value="Raspberry">Raspberry Pi</option>
					  <option value="Orange">Orange Pi</option>
					  <option value="Arduino">Arduino</option>
					  <option value="Wemos">Wemos</option>
					  <option value="PC">PC e-mail</option>
					  <option value="Nayax">Nayax</option>
					</select>
					<button type="submit" class="btn btn-primary" name="ACC" value="BUSCAR">BUSCAR</button>
<?php
        }
?>
			</div>
		</form><br /><br />
<?php
	return $m2;
} #end function step2

function step3($iden, $basededatos){
	$m3 = "GRABAR";
  $con = 1;
?>
	<p><h3>Añada una maquina al dispositivo.</h3></p>
<?php
  $dispositivos = query("SELECT * FROM v_maquinas as m, v_dispositivo as d
    WHERE d.nombre='$iden' AND m.dispositivoid=d.id", $basededatos, $con);
  $con++; //2

	if ($dispositivos->rowCount() > 0) {
		echo "<br /><div class='alert alert-success'>";
		echo "¡Bien! el dispositivo \"$iden\" ya esta enviando datos sobre su maquina.";
		echo "</div>";
		$m3 = "SIGUIENTE";
	} else {
?>
	<p>Parece que su dispositivo aun no ha enviado información sobre ninguna maquina. Si lo desea puede insertar usted mismo la maquina
	o puede forzar el envio de datos de su dispositivo pinchando
	<a href="
<?php
  $dispositivos = query("SELECT enlace FROM v_dispositivo WHERE nombre='$iden'", $basededatos, $con);
  $con++; //3

	foreach ($dispositivos as $d) {
		echo $d["enlace"];
	}
?>
  ">aquí</a>.</p>
		<form class="form-inline" role="form" action="nuevodispositivo.php" method="GET">
			<div class="form-group">
				<input type='hidden' name='step' value='step3'/>
				<input type="text" class="form-control" id="identificador"
				placeholder="Introduce el identificador" name="iden"/>
				<button type="submit" class="btn btn-primary" name="ACC" value="AÑADIR">AÑADIR</button>
			</div>
		</form><br /><br />
<?php
	}
	if (isset($_GET["ACC"]) AND $_GET["ACC"]=="AÑADIR") {
    execute("INSERT INTO v_maquinas (nombre, tipo, dispositivoid)
      VALUES ($_GET[identificador], NULL, ((SELECT id FROM v_dispositivo WHERE nombre='$iden'));",
      $basededatos, $con);
    $con++; //4
	}
	return $m3;
} #end function step3

 function step4(){
?>
	<p><h3>Felicidades su dispositivo esta listo para mantenerle informado del estado de su maquina.</h3></p>
	<p>Haga click en finalizar para volver al menu principal. Para poder gestionar sus dispositivos o maquinas nuevamente
	vaya a su lista de dispositivos y haga click en los enlaces a los dispositivos o las maquinas.</p>
<?php
  } #end function step4
?>
