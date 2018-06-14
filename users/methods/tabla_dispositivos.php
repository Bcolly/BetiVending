<?php
	if (isset($_GET["js"])){
		require_once ("../seguridad.php");
		require_once ("../../conexion.php");
		require_once("../../lang/language.php");
		$pre = "../";
	} else $pre = "";

		$usuario=$_SESSION["user"];
		
		if (!isset($_SESSION["dispord"])){
			$_SESSION["dispord"] = "-";
			$_SESSION["discont"] = "-";
		}
		
		if (isset($_GET["ordb"])) {
			if (strcmp($_SESSION["dispord"], $_GET["ordb"]) != 0){
				$_SESSION["dispord"] = $_GET["ordb"];
				$_SESSION["discont"] = "0";
			}
			else {
				if (strcmp($_SESSION["discont"], 0) == 0)
					$_SESSION["discont"] = "1";
				else $_SESSION["discont"] = "0";
			}
		}

		if (isset($_GET["disp"]) && !isset($_GET["zona"]))
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and d.nombre LIKE '%$_GET[disp]%'";
			
		elseif (!isset($_GET["disp"]) && isset($_GET["zona"])) 
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and l.calle LIKE '%$_GET[zona]%'";
			
		elseif (isset($_GET["disp"]) && isset($_GET["zona"]))
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal and l.calle LIKE '%$_GET[zona]%' and d.nombre LIKE '%$_GET[disp]%'";
			
		else 
			$query="SELECT d.*, l.calle FROM v_locales as l, v_user as u, v_dispositivo as d
			WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal";
		
		if (strcmp($_SESSION["dispord"], "-") != 0) {
			$query .= " ORDER BY $_SESSION[dispord]";
			if ((strcmp($_SESSION["discont"], "0") == 0))
				$query  .=" ASC";
			else $query .=" DESC";
		}
		
		$query .= ';';
		
		$con=1;
		$basededatos=conectardb();
		$dispositivos=query($query, $basededatos, $con);
		$con++;
?>
	<table class="table table-striped">
		<tr>
			<th onclick="filtro('d.fecha')"><?php echo __('DATE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;"/></th>
			<th><?php echo __('HOUR', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
			<th><?php echo __('DEVICE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
			<th>IP</th>
			<th onclick="filtro('l.calle')"><?php echo __('ZONE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
			<th><?php echo __('MACHINE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
		</tr>
<?php	
		foreach($dispositivos as $dispositivo)
			mostrardisp($dispositivo);
			
		$basededatos = null; #cerramos la conexión
?>	
	</table>
<?php 
	function mostrardisp($dispositivo){
		global $lang, $basededatos, $con, $pre;
		$a=$dispositivo["nombre"];
		if ($a=="") $a="--";
		echo "<tr><td class='text-danger'>$dispositivo[fecha]</td>
		<td class='text-warning' > $dispositivo[hora]</td>
		<td><a href='dispositivo.php?OBJ=".serialize($dispositivo)."'>$a</a></td>
		<td><a href='http://$dispositivo[IPpublica]' target='new'>$dispositivo[IPpublica]</a></td>
		<td>". __($dispositivo['calle'], $lang, $pre.'../') ."</td>";
		
		$maquinas=query("SELECT * FROM v_maquinas WHERE dispositivoid=$dispositivo[id]", $basededatos, $con);
		$con++;		
			
		echo "<td>";
		foreach ($maquinas as $maquina) 
			mostrarmaq($maquina);
		echo "<td/></tr>";
	}
	
	function mostrarmaq($maquina){
		global $lang, $basededatos, $con, $pre;
		echo "<a href='s_maquina.php?OBJ=$maquina[id]'>$maquina[nombre]</a>";
		$sql=query("SELECT * FROM v_evento WHERE idmaquina=$maquina[id]", $basededatos, $con);
		$con++;
		if ($sql->rowCount() > 0){ ?>
			<img src='../img/emergency.png' height='20' width='20' alt="<?php echo __('EVENTS', $lang, $pre.'../').': '.$num; ?>" title="<?php echo __('EVENTS', $lang, $pre.'../').': '.$num; ?>"
			onclick="abrir('s_evento.php?id=<?php echo $maquina['id']; ?>')" />
		<?php }
		echo "<br/>";
	}
?>