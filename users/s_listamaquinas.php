<?php require ("s_index.php");

	$con = 1;
	$basededatos=conectardb();
	$dispositivos=query("SELECT d.*, l.calle
		FROM v_locales as l, v_user as u, v_dispositivo as d
		WHERE d.userid = u.id and u.usuario = '$usuario' and l.id = d.idlocal;", $basededatos, $con);
	$con++;
?>
	<div class="row">
		<form class="navbar-form navbar-left" role="form" method="POST">
			<div class="form-group">
				<label for="ruta"><?php echo __('ROUTE', $lang, '../') ?>: </label>
				<input type="text" class="form-control" id="ruta" name="ruta">
			</div>
			<button type="button" class="btn btn-success" onclick="filtroRut()"><?php echo __('FILTER', $lang, '../') ?></button>
		</form>
	</div>
	<div class="row">
  	<h3><?php echo __('Machine list by route', $lang, '../') ?>: </h3>
	</div>
	<div class="row">
		<div id="tabla">
			<?php include("methods/tabla_maquinas.php"); ?>
	  </div>
	</div>

<?php require ("s_footer.php"); ?>
