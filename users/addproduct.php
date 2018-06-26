<?php include_once("../lang/language.php"); ?>
<?php require_once ("seguridad.php"); ?>
<?php require_once ("../conexion.php"); ?>
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
	<script language="JavaScript">
			function cerrar() {
			ventana=window.self;
			ventana.opener=window.self;
			ventana.close(); }
	</script>
</head>
<body>
<?php
if (isset($_GET['id']) && isset($_GET['sel'])){
?>
	<div class="container">
		<div class="row">
			<div class="col col-sm-3">
				<form class="container" method="POST">
					<input type="hidden" name="maquina" value="<?php echo $_GET['id']; ?>" />
					<input type="hidden" name="seleccion" value="<?php echo $_GET['sel']; ?>" />
					<p>
						<?php echo __('Product', $lang, '../') ?>:
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
					</p>
					<p>
						<?php echo __('Sell-by-date', $lang, '../') ?> (yyyy/mm/dd):<br/><input type="number" name="year" min=<?php echo (new DateTime)->format("Y"); ?> /><b>/</b>
						<input type="number" name="month" min=1 max=12 /><b>/</b>
						<input type="number" name="day" min=1 max=31 /></p>
					<p><?php echo __('Cuantity', $lang, '../') ?>: <input type="number" name="cant" min=0 value="0"/></p>
					<p><?php echo __('Max Cuantity', $lang, '../') ?>: <input type="number" name="max" min=1/></p>
					<p>
						<input type="submit" value="<?php echo __('ADD', $lang, '../') ?>" />
						<input type="button" value="<?php echo __('CLOSE', $lang, '../') ?>" onclick="cerrar()" />
					</p>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col col-sm-3" id="products" style="display: none; overflow-y: scroll; height: 400px;"></div>
		</div>
	</div>
<?php
}
?>
</body>
</html>
<?php
if (isset($_POST['product'])){
	if ($_POST['year'] != '' and $_POST['month'] != '' and $_POST['day'] != ''){
		$date = "$_POST[year]-$_POST[month]-$_POST[day]";
		$query = "INSERT into v_productos_seleccion (idmaquina, sel, idproducto, tiempo, cantidad)
		values ($_POST[maquina],'$_POST[seleccion]',(SELECT id FROM v_productos WHERE producto='$_POST[product]'),'$date', $_POST[cant])";
	} else {
		$query = "INSERT into v_productos_seleccion (idmaquina, sel, idproducto, cantidad)
		values ($_POST[maquina],'$_POST[seleccion]',(SELECT id FROM v_productos WHERE producto='$_POST[product]'), $_POST[cant])";
	}
	$con = 1;
	execute($query, $basededatos, $con);
	$con++;

	if ($_POST['max'] != ''){
		execute("UPDATE v_seleccion SET max = $_POST[max] WHERE idmaquina = $_POST[maquina] AND sel = '$_POST[seleccion]'", $basededatos, $con);
		$con++;
		echo __('Max added correctly', $lang, '../');
	}
}
$basededatos = null;
?>
