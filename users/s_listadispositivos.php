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
		<?php include("methods/tabla_dispositivos.php"); ?>
    </div>
	<h3><a href="nuevodispositivo.php"><i class="glyphicon glyphicon-plus"></i></a> <?php echo __('Add new device', $lang, '../') ?> </h3>
<?php require ("s_footer.php"); ?>
