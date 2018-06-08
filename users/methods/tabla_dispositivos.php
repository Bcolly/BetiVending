<?php
require_once ("../../conexion.php");
		session_start();
		$usuario=$_SESSION["user"];
		if (isset($_GET["disp"]) && !isset($_GET["zona"])) {
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and d.nombre LIKE '%$_GET[disp]%';";
			
		} elseif (!isset($_GET["disp"]) && isset($_GET["zona"])) {
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and l.calle LIKE '%$_GET[zona]%';";
			
		} elseif (isset($_GET["disp"]) && isset($_GET["zona"])) {
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and l.calle LIKE '%$_GET[zona]%' and d.nombre LIKE '%$_GET[disp]%';";
			
		} else {
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal;";
		}
		
		$con=1;
		$basededatos=conectardb();
		$dispositivos=query($query, $basededatos, $con);
		$con++;
?>
<table class="table table-striped">
		<tr><th>FECHA</th><th>HORA</th><th>DISPOSITIVO</th><th>IP</th><th>ZONA</th><th>MAQUINAS</th></tr>
<?php	
		foreach($dispositivos as $dispositivo){
			$a=$dispositivo["nombre"];
			if ($a=="") $a="--";
			$b="";
			if ($a==$a) $b=" class='text-danger'";
			$c="";
			if ($a==$a) $c=" class='text-warning'";
			echo "<tr><td $b>$dispositivo[fecha]</td>
			<td $c > $dispositivo[hora]</td>
			<td><a href='dispositivo.php?OBJ=".serialize($dispositivo)."'>$a</a></td>
			<td><a href='http://$dispositivo[IPpublica]/dex.php' target='new'>$dispositivo[IPpublica]</a></td>
			<td>$dispositivo[calle]</td>";
			
			$maquinas=query("SELECT * FROM v_maquinas WHERE dispositivoid=$dispositivo[id]", $basededatos, $con);
			$con++;
			
			echo "<td>";
			foreach ($maquinas as $maquina) {
				echo "<a href='s_maquina.php?OBJ=$maquina[id]'>$maquina[nombre]</a>";
				
				$sql=query("SELECT * FROM v_evento WHERE idmaquina=$maquina[id]", $basededatos, $con);
				$con++;
				$num = $sql->rowCount();
				
				if ($num>0){
					echo " <img src='../img/emergency.png'height='20' width='20' alt='Tienes $num eventos' title='Tienes $num eventos'/>";
				}
				echo "<br/>";
			}
			echo "<td></tr>";
		}
		$basededatos = null; #cerramos la conexión
	?>	
</table>