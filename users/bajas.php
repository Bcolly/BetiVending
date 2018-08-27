<?php require ("s_index.php");

$con = 1;
$basededatos=conectardb();

if (isset($_POST["md"])) {
	$passcript = sha1($_POST["psw"]);

	$sql = query("SELECT *
		FROM v_user
		WHERE id = $userid and passwd = '$passcript';", $basededatos, $con);
	$con++;

	if ($sql->rowCount() > 0) {
		if (strcmp ($_POST["md"] , "m") == 0){
			execute("DELETE FROM v_maquinas WHERE id = $_POST[id]", $basededatos, $con);
			echo "<div class='alert alert-success'>";
			echo __('Your machine has been droped succesfully.', $lang, '../');
			echo "</div>";
		} elseif (strcmp ($_POST["md"] , "d") == 0){
			execute("DELETE FROM v_dispositivo WHERE id = $_POST[id]", $basededatos, $con);
			echo "<div class='alert alert-success'>";
			echo __('Your device has been droped succesfully.', $lang, '../');
			echo "</div>";
		} else {
			echo "<div class='alert alert-danger'>";
			echo __('Error! Try again later.', $lang, '../');
			echo "</div>";
		}
	} else {
		echo "<div class='alert alert-danger'>";
		echo __('Wrong password try again.', $lang, '../');
		echo "</div>";
	}
	$con++;
}

$dispositivos=query("SELECT d.*, l.calle
	FROM v_locales as l, v_user as u, v_dispositivo as d
	WHERE d.userid = $userid and l.id = d.idlocal;", $basededatos, $con);
$con++;
?>
	<div class="container">
		<div class="row">
			<div class="col col-md-6">
				<h3><?php echo __('DEVICES', $lang, '../') ?></h3>
				<form class="navbar-form navbar-left" role="form" method="POST">
					<div class="form-group">
						<label for="dispositivo"><?php echo __('DEVICE', $lang, '../') ?>: </label>
						<input type="text" class="form-control" id="dispositivo" name="dispositivo">
					</div>
					<button type="button" class="btn btn-success" onclick="filtro()"><?php echo __('FILTER', $lang, '../') ?></button>
				</form>
				<br/><br/><br/>
				<div class="pre-scrollable" data-spy="scroll" data-target=".table" id="tabla">
					<?php include("methods/baja_dispositivos.php"); ?>
			  </div>
			</div>
			<div class="col col-md-6">
				<h3><?php echo __('MACHINES', $lang, '../') ?></h3>
				<form class="navbar-form navbar-left" role="form" method="POST">
					<div class="form-group">
						<label for="maquina"><?php echo __('MACHINE', $lang, '../') ?>: </label>
						<input type="text" class="form-control" id="maquina" name="maquina">
					</div>
					<button type="button" class="btn btn-success" onclick="filtro()"><?php echo __('FILTER', $lang, '../') ?></button>
				</form>
				<br/><br/><br/>
				<div class="pre-scrollable" data-spy="scroll" data-target=".table" id="tabla">
					<?php include("methods/baja_maquinas.php"); ?>
			  </div>
			</div>
		</div>
	</div>
<?php require ("s_footer.php"); ?>

<div id="baja" class="modalDialog">
  <div style="height:35%">
    <a href="#close" title="Close" class="close">X</a>
    <h2><?php echo __('Are you sure to drop?', $lang, '../') ?></h2>
    <div>
			<form class="navbar-form navbar-left" role="form" method="POST" action="bajas.php">
				<input type="hidden" class="form-control" id="id" name="id">
				<input type="hidden" class="form-control" id="md" name="md">
				<div class="form-group">
					<label for="contraseña"><?php echo __('Password', $lang, '../') ?>: </label>
					<input type="password" class="form-control" id="psw" name="psw" pattern=".{6,}" required>
				</div>
				<br/><br/>
				<input type="submit" class="btn btn-success" value="<?php echo __('Yes', $lang, '../') ?>"/>
				<a href="#close" title="Close" class="btn btn-danger"><?php echo __('No', $lang, '../') ?></a>
			</form>
    </div>
  </div>
</div>
