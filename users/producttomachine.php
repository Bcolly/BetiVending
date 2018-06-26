<?php include_once("../lang/language.php"); ?>
<?php require_once ("seguridad.php"); ?>
<?php include_once ("../conexion.php"); ?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="apple-touch-icon" href="../apple-touch-icon.png">
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<style>
			input[type=text] {
				width: 200px;
			}
			input[type=number] {
				width: 70px;
			}
		</style>
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/bootstrap_wizard.css">
		<script src="../js/ajax.js"></script>
		<script src="../js/addproduct.js"></script>
	</head>
	<body>
		<table class="table table-striped">
		<tr><th><?php echo __('Selection', $lang, '../')?></th><th><?php echo __('Cuantity', $lang, '../')?></th><th><?php echo __('Date', $lang, '../')?></th><th></th></tr>
<?php
if (isset($_GET['id']) and isset($_GET['prod'])){
	$basededatos = conectardb();
	$con=1;
	$sql = query("SELECT sel FROM v_seleccion WHERE idmaquina=$_GET[id] ORDER BY sel", $basededatos, $con);
	$con++;

	foreach ($sql as $sel) {
		$sql2 = query("SELECT * FROM v_productos_seleccion INNER JOIN v_productos ON idproducto=id
		WHERE idmaquina=$_GET[id] AND sel='$sel[sel]'", $basededatos, $con);
		$num = $sql2->rowCount();
?>
	<form class="container" method="POST">
		<tr>
			<td><?php echo $sel['sel']; ?></td>
			<input type="hidden" value="<?php echo $sel['sel']; ?>" name="seleccion" />
			<td><input type='number' name='cant' value='1' min='0' max='<?php echo $sel['max']; ?>'/></td>
			<td><input id="date" name="date" type="date"></td>
			<td><button type="submit" name="submit" class="btn btn-primary"><?php echo __("ADD", $lang, "../"); ?></button></td>
		</tr>
	</form>

<?php
	}
	$con++;
}
?>
	</body>
</html>
<?php
if (isset($_POST['submit'])){
	execute("INSERT into v_productos_seleccion (idmaquina, sel, idproducto, tiempo, cantidad)
	values ($_GET[id],'$_POST[seleccion]',$_GET[prod],'$_POST[date]', $_POST[cant])", $basededatos, $con);
	echo __('Product added correctly', $lang, '../');
}
?>
