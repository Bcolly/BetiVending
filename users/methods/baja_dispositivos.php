<?php
	if (isset($_GET["js"])){
		require_once ("../seguridad.php");
		require_once ("../../conexion.php");
		require_once("../../lang/language.php");
		$pre = "../";
	} else $pre = "";

		$userid=$_SESSION["userid"];

		$query="SELECT d.*, l.calle FROM v_locales as l INNER JOIN v_dispositivo as d ON d.idlocal=l.id WHERE d.userid = $userid";
		if (isset($_GET["disp"]))
			$query.=" and d.nombre LIKE '%$_GET[disp]%'";

		$query .= ';';
		$con=1;
		$basededatos=conectardb();
		$dispositivos=query($query, $basededatos, $con);
		$con++;
?>
	<table class="table table-striped">
		<tr>
			<th><?php echo __('DEVICE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
			<th></th>
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
		echo "<tr>
		<td><a href='dispositivo.php?OBJ=".serialize($dispositivo)."'>$a</a></td>
		<td><a href='#baja' onclick='bajadis($dispositivo[id])'><img src='../img/delete.png' height='20' width='20' alt='". __('Delete', $lang, $pre.'../') ."'
			title='".__('Delete', $lang, $pre.'../') ."'/></a></td>
		<td>". __($dispositivo['calle'], $lang, $pre.'../') ."</td>";

		$maquinas=query("SELECT * FROM v_maquinas WHERE dispositivoid=$dispositivo[id]", $basededatos, $con);
		$con++;

		echo "<td>";
		foreach ($maquinas as $maquina)
			mostrarmaq($maquina);
		echo "<td/>
				</tr>";
	}

	function mostrarmaq($maquina){
		global $lang, $basededatos, $con, $pre;
		echo "<a href='s_maquina.php?OBJ=$maquina[id]'>$maquina[nombre]</a>";
	}
?>
