<?php require ("s_index.php"); ?>

	<div class="row">
		<div id="grselet" class="col col-md-3">
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<div>
						<label for="vista"><?php echo __('See', $lang, '../') ?> : </label>
						<select class="form-control" id="vista" name="vista" onchange="cargarGrafo()">
						  <option value="ventas" selected="selected"><?php echo __('Sales', $lang, '../') ?></option>
							<option value="ganancias"><?php echo __('Earnings', $lang, '../') ?></option>
						</select>
					</div>
					<div style='margin: 5px;'>
						<label for="maquinas"><?php echo __('Machines', $lang, '../') ?> : </label>
						<select class="form-control" id="maquinas" name="maquinas" onchange="cargarGrafo()">
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
				</div>
			</form>
		</div>
		<div class="col col-md-4">
			<form class="navbar-form navbar-left">
				<label for="otros"><?php echo __('Do you want to see anything else?', $lang, '../') ?></label>
					<div class="btn-group">
					 <button type="button" class="btn btn-success"><?php echo __('Machines', $lang, '../') ?></button>
					 <button type="button" class="btn btn-success"><?php echo __('Products', $lang, '../') ?></button>
					</div>
			</form>
		</div>
	</div>
	<div class="row">
		<canvas id="grafico">Su navegador no soporta Canvas.</canvas>
		<script type="text/javascript" src="../js/graficos/prueba.js"></script>
		<!--<script type="text/javascript" src="../js/graficos/canvas.js"></script>-->
<?php
		$historico=query("SELECT fecha, ventas from v_historico
		WHERE idmaquina = $fmaq ORDER BY fecha ASC LIMIT 32;", $basededatos, $con);

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
		drawGrafo(<?php echo $stringdata; ?>, 'mes', '<?php echo __('Total sales of the machine this month', $lang, '../'); ?>');
	</script>

<?php require ("s_footer.php"); ?>
