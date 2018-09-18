<?php require ("s_index.php"); ?>

	<div class="row">
		<div id="grselet" class="col col-md-3">
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<div>
						<label for="mes"><?php echo __('Month', $lang, '../') ?> : </label>
						<select class="form-control" id="mes" name="mes" onchange="cargarGrafoMaq()">
<?php
							$año = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
							$num = 1;
							print_r($año);
							foreach ($año as $mes) {
								echo "<option value=$num";
								if (getdate()["mon"] == $num)
									echo " selected='selected'";

								echo ">".__($mes, $lang, '../')."</option>";
								$num++;
							}
?>
						</select>
					</div>
					<div>
						<label for="vista"><?php echo __('See', $lang, '../') ?> : </label>
						<select class="form-control" id="vista" name="vista" onchange="cargarGrafoMaq()">
						  <option value="ventas" selected="selected"><?php echo __('Sales', $lang, '../') ?></option>
							<option value="ganancias"><?php echo __('Earnings', $lang, '../') ?></option>
						</select>
					</div>
					<div style='margin: 5px;'>
						<label for="maquinas"><?php echo __('Machines', $lang, '../') ?> : </label>
						<select class="form-control" id="maquinas" name="maquinas" onchange="cargarGrafoMaq()">
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
		<div id="grprod" class="col col-md-3" style="display: none">
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<div>
						<label for="productos"><?php echo __('Product', $lang, '../') ?> : </label>
						<input list="product" name="product">
					  	<datalist id="product">
<?php
							$basededatos = conectardb();
							$productos = query("SELECT * FROM v_productos WHERE producto LIKE '%$_GET[prod]%'", $basededatos, 1);

							foreach ($productos as $p){
								echo "<option value='$p[producto]'>";
							}
?>
					  	</datalist>
						</input>
					</div>
				</div>
			</form>
		</div>
		<div class="col col-md-4">
			<form class="navbar-form navbar-left">
				<label for="otros"><?php echo __('Do you want to see anything else?', $lang, '../') ?></label>
					<div class="btn-group">
					 <button type="button" class="btn btn-success" onclick="showgpr()"><?php echo __('Machines', $lang, '../') ?></button>
					 <button type="button" class="btn btn-success" onclick="showgmq()"><?php echo __('Products', $lang, '../') ?></button>
					</div>
			</form>
		</div>
	</div>
	<div class="row">
		<canvas id="grafico">Su navegador no soporta Canvas.</canvas>
		<script type="text/javascript" src="../js/graficos/canvas.js"></script>

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
	<script>
		function showgmq(){
			document.getElementById("grprod").style.display="inline";
			document.getElementById("grselet").style.display="none";
			drawGrafo(<?php echo $stringdata; ?>, 'mes', '<?php echo __('Total sales of the machine this month', $lang, '../'); ?>');
		}

		function showgpr(){
			document.getElementById("grprod").style.display="none";
			document.getElementById("grselet").style.display="inline";
			//drawGrafo(<?php echo $stringdata; ?>, 'mes', '<?php echo __('Total sales of the machine this month', $lang, '../'); ?>');
		}
	</script>

<?php require ("s_footer.php"); ?>
