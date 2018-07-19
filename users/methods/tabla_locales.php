<?php
	if (isset($_GET["js"])){
		require_once ("../seguridad.php");
		require_once ("../../conexion.php");
		require_once ("../../lang/language.php");
		$pre = "../";
	} else $pre = "";

		$userid=$_SESSION["userid"];

		$query="SELECT l.*, COUNT(m.id) as maquinas FROM v_locales as l, v_maquinas as m WHERE l.userid=$userid AND m.idlocal = l.id";
		$aux = "";
		if (isset($_GET["dir"]))
			$aux = " AND (l.pais LIKE '%$_GET[dir]%' OR l.provincia LIKE '%$_GET[dir]%' OR l.municipio LIKE '%$_GET[dir]%'
				OR l.calle LIKE '%$_GET[dir]%' OR l.numero LIKE '%$_GET[dir]%') ";

		if (isset($_GET["ruta"]))
			$aux .= " AND l.ruta LIKE '%$_GET[ruta]%'";

		$query .= $aux."
		UNION

		SELECT DISTINCT *, 0 as maquinas
		FROM v_locales as l
		WHERE id NOT IN (
		    SELECT l.id
		    FROM v_locales as l, v_maquinas as m
		    WHERE l.userid=$userid AND m.idlocal = l.id
		    GROUP BY l.id)
		    AND userid=$userid";

		$query .=  $aux;

		$con=1;
		$basededatos=conectardb();
		$locales=query($query, $basededatos, $con);
		$con++;
?>
	<table class="table table-striped">
		<tr>
			<th><?php echo __('ADDRESS', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;"/></th>
			<th><?php echo __('COUNTRY', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" /></th>
			<th><?php echo __('ROUTE', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" /></th>
			<th></th>
			<th><?php echo __('Nº OF MACHINES', $lang, $pre.'../') ?><span class="caret" style="visibility: collapse;" /></th>
			<th></th>
		</tr>
<?php
		foreach($locales as $l)
			mostrarlocal($l);

		$basededatos = null; #cerramos la conexión
?>
	</table>
<?php
	function mostrarlocal($local){
		global $lang, $basededatos, $con, $pre;
		echo "<tr>
		<td>$local[calle] $local[numero], $local[municipio]</td>
		<td>$local[pais]</td>
		<td>";
			if (!empty($local["ruta"]))
				echo $local["ruta"].
					"<a href='methods/removeruta.php?idlocal=$local[id]'>
						<img src='../img/delete.png' height='20' width='20' alt=".__('Remove', $lang, $pre.'../').
							"title='".__('Remove route', $lang, $pre.'../')."'
					</a>";
			else
				echo "<a href='#newruta' onclick='setLocal($local[id])'>".__('New', $lang, $pre.'../')."</a>";
		echo "</td>
		<td><a href='#changeruta'><button type='button' class='btn btn-info' onclick='setLocal2($local[id])''>".__('Change', $lang, $pre.'../')."</button></td>
		<td>$local[maquinas]</td>
		</tr>";
	}
?>
