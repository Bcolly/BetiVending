<?php require ("s_index.php"); ?>
<?php
$con = 1;
$basededatos=conectardb();
$dispositivos=query("SELECT d.*, l.calle 
	FROM v_locales as l, v_user as u, v_dispositivo as d
	WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal;", $basededatos, $con);
$con++;
?>
    <h3><?php echo __('List of connected devices', $lang, '../') ?>: </h3>
	<form class="navbar-form navbar-left" role="form" method="POST">
		<div class="form-group">
			<label for="dispositivo"><?php echo __('DEVICE', $lang, '../') ?>: </label>
			<input type="text" class="form-control" id="dispositivo" name="dispositivo">
		</div>
		<div class="form-group">
			<label for="zona"><?php echo __('ZONE', $lang, '../') ?>: </label>
			<input type="text" class="form-control" id="zona" name="zona">
		</div>
		<button type="button" class="btn btn-success" onclick="filtro()"><?php echo __('FILTER', $lang, '../') ?></button>
	</form>
	<br/><br/><br/>
	<div class="pre-scrollable" data-spy="scroll" data-target=".table" id="tabla">
		<table class="table table-striped">
		<tr><th><?php echo __('DATE', $lang, '../') ?></th><th><?php echo __('HOUR', $lang, '../') ?></th>
		<th><?php echo __('DEVICE', $lang, '../') ?></th><th>IP</th><th><?php echo __('ZONE', $lang, '../') ?></th>
		<th><?php echo __('MACHINE', $lang, '../') ?></th></tr>
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
			<td><a href='http://$dispositivo[IPpublica]' target='new'>$dispositivo[IPpublica]</a></td>
			<td>". __($dispositivo['calle'], $lang, '../') ."</td>";
			
			$maquinas=query("SELECT * FROM v_maquinas WHERE dispositivoid=$dispositivo[id]", $basededatos, $con);
			$con++;		
			
			echo "<td>";
			foreach ($maquinas as $maquina) {
				echo "<a href='s_maquina.php?OBJ=$maquina[id]'>$maquina[nombre]</a>";
				$sql=query("SELECT * FROM v_evento WHERE idmaquina=$maquina[id]", $basededatos, $con);
				$con++;
				$num = $sql->rowCount();
				if ($num>0){ ?>
					<img src='../img/emergency.png' height='20' width='20' alt="<?php echo __('EVENTS', $lang, '../').': '.$num; ?>" title="<?php echo __('EVENTS', $lang, '../').': '.$num; ?>"
					onclick="abrir('s_evento.php?id=<?php echo $maquina['id']; ?>')" />
				<?php }
				echo "<br/>";
			}
			echo "<td/></tr>";
		}
		$basededatos = null;#cerramos la conexion
	 ?>	
		</table>
    </div>
	<h3><a href="nuevodispositivo.php"><i class="glyphicon glyphicon-plus"></i></a> <?php echo __('Add new device', $lang, '../') ?> </h3>
<?php require ("s_footer.php"); ?>