<?php require ("s_index.php"); ?>

	<div class="row">
		<div id="grselet" class="col col-md-3">
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<div>
<?php
					$con = 1;
					$basededatos=conectardb();
					$vistames = true; // true si se vera el es, false para el año
					if (isset($_GET['view']) && $_GET['view']== 'y'){
						$vistames = false;
?>
						<button type="button" class="btn btn-success" onclick="location.href='?view=m';"><?php echo __('See by months', $lang, '../') ?></button><br/>
						<label for="año"><?php echo __('Year', $lang, '../') ?> : </label>
						<select class="form-control" id="año" name="año" onchange="cargarGrafoMaq(<?php echo $vistames; ?>)">
<?php
							$sql = query("SELECT DISTINCT YEAR(fecha) as y FROM v_historico NATURAL JOIN v_user_maquinas WHERE userid = $userid", $basededatos, $con);
							$con++;
							foreach ($sql as $año) {
								echo "<option value=$año[y]";
								if (getdate()["year"] == $año['y'])
									echo " selected='selected'";

								echo ">$año[y]</option>";
							}
?>
						</select>
<?php
					}
					else {
?>
						<button type="button" class="btn btn-success" onclick="location.href='?view=y';"><?php echo __('See by years', $lang, '../') ?></button><br/>
						<label for="mes"><?php echo __('Month', $lang, '../') ?> : </label>
						<select class="form-control" id="mes" name="mes" onchange="cargarGrafoMaq(<?php echo $vistames; ?>)">
<?php
							$año = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
							$num = 1;
							foreach ($año as $mes) {
								echo "<option value=$num";
								if (getdate()["mon"] == $num)
									echo " selected='selected'";

								echo ">".__($mes, $lang, '../')."</option>";
								$num++;
							}
?>
						</select>
<?php
						}
?>
					</div>
					<div>
						<label for="vista"><?php echo __('See', $lang, '../') ?> : </label>
						<select class="form-control" id="vista" name="vista" onchange="cargarGrafoMaq(<?php echo $vistames; ?>)">
						  <option value="ventas" selected="selected"><?php echo __('Sales', $lang, '../') ?></option>
							<option value="ganancias"><?php echo __('Earnings', $lang, '../') ?></option>
						</select>
					</div>
					<div style='margin: 5px;'>
						<label for="maquinas"><?php echo __('Machines', $lang, '../') ?> : </label>
						<select class="form-control" id="maquinas" name="maquinas" onchange="cargarGrafoMaq(<?php echo $vistames; ?>)">
<?php
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
		<!--<div class="col col-md-4">
			<form class="navbar-form navbar-left">
				<label for="otros"><?php echo __('Do you want to see anything else?', $lang, '../') ?></label>
					<div class="btn-group">
					 <button type="button" class="btn btn-success" onclick="showgpr()"><?php echo __('Machines', $lang, '../') ?></button>
					 <button type="button" class="btn btn-success" onclick="showgmq()"><?php echo __('Products', $lang, '../') ?></button>
					</div>
			</form>
		</div>-->
	</div>
	<div class="row">
		<canvas id="grafico">Su navegador no soporta Canvas.</canvas>
		<script type="text/javascript" src="../js/graficos/canvas.js"></script>
	</div>

	<script type="text/javascript">
		drawGrafo("", 'mes', ""); //iniciamos el canvas
		cargarGrafoMaq(<?php echo $vistames; ?>);
	</script>
	<script>
		function showgmq(){
			document.getElementById("grprod").style.display="inline";
			document.getElementById("grselet").style.display="none";
			cargarGrafoMaq(<?php echo $vistames; ?>);
		}

		function showgpr(){
			document.getElementById("grprod").style.display="none";
			document.getElementById("grselet").style.display="inline";
			//drawGrafo(<?php echo $stringdata; ?>, 'mes', '<?php echo __('Total sales of the machine this month', $lang, '../'); ?>');
		}
	</script>

<?php require ("s_footer.php"); ?>
