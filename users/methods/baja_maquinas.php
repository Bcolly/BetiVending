<?php
	if (isset($_GET["js"])){
		require_once ("../seguridad.php");
		require_once ("../../conexion.php");
		require_once("../../lang/language.php");
		$pre = "../";
	} else $pre = "";

		$userid=$_SESSION["userid"];

		$query="SELECT * FROM v_maquinas INNER JOIN v_user_maquinas ON maquinaid = id WHERE userid = $userid";
		if (isset($_GET["maq"]))
			$query.=" and nombre LIKE '%$_GET[maq]%'";

		$query .= ';';
		$con=1;
		$basededatos=conectardb();
		$maquinas=query($query, $basededatos, $con);
		$con++;
?>
	<table class="table table-striped">
		<tr>
			<th><?php echo __('MACHINE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" onclick="filtro()"/></th>
			<th></th>
		</tr>
<?php
		foreach($maquinas as $maquina) {
			echo "<tr>
				<td><a href='s_maquina.php?OBJ=$maquina[id]'>$maquina[nombre]</a></td>
				<td><a href='#baja' onclick='bajamaq($maquina[id])'><img src='../img/delete.png' height='20' width='20' alt='". __('Delete', $lang, $pre.'../') ."'
					title='".__('Delete', $lang, $pre.'../') ."'/></a></td>
			</tr>";
		}

		$basededatos = null; #cerramos la conexión
?>
	</table>
