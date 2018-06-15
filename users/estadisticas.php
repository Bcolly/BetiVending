<?php require ("s_index.php"); ?>

	<div class="row">
		<form class="navbar-form navbar-left">
			<div class="form-group">
				<label for="vista"><?php echo __('See', $lang, '../') ?> : </label>
				<select class="form-control" id="vista" name="vista">
				  <option value="ventas" selected="selected"><?php echo __('Sales', $lang, '../') ?></option>
					<option value="ganancias"><?php echo __('Earnings', $lang, '../') ?></option>
				</select>
			</div>
		</form>
	</div>
	<div class="row">
		<form class="navbar-form navbar-left">
			<div class="form-group">
				<label for="vista"><?php echo __('Machines', $lang, '../') ?> : </label>
				<select class="form-control" id="maquinas" name="maquinas">
<?php
				$con = 1;
				$basededatos=conectardb();
				$maquinas=query("SELECT m.id, m.nombre
												 FROM v_maquinas as m INNER JOIN v_dispositivo as d ON m.dispositivoid=d.id INNER JOIN v_user as u ON userid=u.id
												 WHERE u.usuario = '$usuario';", $basededatos, $con);
				$con++;
				$fmaq = -1; //variable de la primera maquina
				foreach ($maquinas as $m) {
					echo "<option value='$m[id]'>$m[nombre]</option>";
					if ($fmaq < 0)
						$fmaq = $m[id]; //cogemos por defecto el ID de la primera para que crear el gráfico
				}
?>
				</select>
			</div>
		</form>
		<canvas id="grafico">
		Su navegador no sopoeta Canvas.</canvas>
		<script type="text/javascript" src="../js/graficos/prueba.js"></script>
<?php
		$historico=query("SELECT h.fecha, h.ventas from v_historico as h
		WHERE h.idmaquina = $fmaq;", $basededatos, $con);

		while($row = $historico->fetch()) {
			if (empty($data))
				$data = [$row['fecha'] => $row['ventas']];
			else
				$data += [$row['fecha'] => $row['ventas']];
		}
		$stringdata = json_encode($data);

?>
	</div>

	<script type="text/javascript">
		drawGrafo();
	</script>

<?php require ("s_footer.php"); ?>
