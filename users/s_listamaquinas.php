<?php require ("s_index.php");

	$userid=$_SESSION["userid"];

	$con = 1;
	$basededatos=conectardb();
	$dispositivos=query("SELECT d.*, l.calle
		FROM v_locales as l, v_user as u, v_dispositivo as d
		WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal;", $basededatos, $con);
	$con++;

	$rutas=query("SELECT DISTINCT ruta FROM v_locales WHERE userid=$userid", $basededatos, $con);
	$con++;
	$maquinas=query("SELECT id, nombre FROM v_maquinas INNER JOIN v_user_maquinas ON id=maquinaid
		WHERE userid=$userid", $basededatos, $con);
	$con++;
?>
	<div class="row">
		<div class="col col-md-4">
			<form class="navbar-form navbar-left" role="form" method="POST">
				<div class="form-group">
					<label for="ruta"><?php echo __('ROUTE', $lang, '../') ?>: </label>
					<input type="text" class="form-control" id="ruta" name="ruta">
				</div>
				<button type="button" class="btn btn-success" onclick="filtroRut()"><?php echo __('FILTER', $lang, '../') ?></button>
			</form>
		</div>
		<div class="col col-md-5 col-md-offset-3">
			<form class="navbar-form navbar-left" role="form" method="POST">
				<b><?php echo __('Add route', $lang, '../'); ?></b>
				<div class="form-group">
					<select class="form-control" id="aruta" name="aruta">
<?php
					foreach ($rutas as $ruta) {
						echo "<option value='$ruta[ruta]'>$ruta[ruta]</option>";
					}
?>
					</select>
					<b> <?php echo __('to', $lang, '../'); ?> </b>
					<select class="form-control" id="amaquina" name="amaquina">
<?php
					foreach ($maquinas as $m) {
						echo "<option value='$m[id]'>$m[nombre]</option>";
					}
?>
					</select>
				</div>
				<button type="button" class="btn btn-success" onclick="addPZone(aruta.value)"><?php echo __('ADD', $lang, '../') ?></button>
			</form>
		</div>
	</div>
	<div class="row">
  	<h3><?php echo __('Machine list by route', $lang, '../') ?>: </h3>
	</div>
	<div class="row">
		<div id="tabla">
			<?php include("methods/tabla_maquinas.php"); ?>
	  </div>
	</div>
	<div class="popUp" id="formsrutab2">
		<form class="navbar-form navbar-left" role="form" method="POST">
			<h3><?php echo __('Select route', $lang, $pre.'../'); ?></h3>
			<div class="form-group">
				<select class="form-control" id="srutab2" name="srutba"></select>
			</div>
			<button type="button" class="btn btn-success" onclick="addZone3(amaquina.value, srutab2.value)">
				<?php echo __('ADD', $lang, $pre.'../'); ?></button>
		</form>
	</div>

<?php require ("s_footer.php"); ?>
