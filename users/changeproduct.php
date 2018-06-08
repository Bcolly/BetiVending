<?php include_once("../language.php"); ?>
<?php include_once ("seguridad.php"); ?>
<?php include_once ("../conexion.php"); ?>
<?php 
	try{
		$sql = $basededatos->query("SELECT * FROM (v_productos_seleccion NATURAL JOIN v_seleccion) INNER JOIN v_productos ON idproducto = id
		WHERE sel='$_GET[sel]' AND idmaquina=$_GET[id]");
		$row = $sql->fetch();
	} catch (exception $e) {
		echo "No se puede la consulta 1";
		exit;
	}
	$date = explode('-', $row['tiempo']);
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="../apple-touch-icon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            padding-top: 30px;
            padding-bottom: 20px;
        }
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
	<div class="container"><div class="row">
	<div class="col col-sm-3">
		<form class="container" method="POST" class="form">
			<input type="hidden" name="maquina" value="<?php echo $_GET['id']; ?>" /> 
			<input type="hidden" name="seleccion" value="<?php echo $_GET['sel']; ?>" />
			<p><?php echo __('Product', $lang, '../') ?>: <input type="text" name="product" value="<?php echo $row['producto']; ?>" onchange="mostrar(this.value)"/></p>
			<p><?php echo __('Sell-by-date', $lang, '../') ?> (yyyy/mm/dd):<br/><input type="number" name="year" min=<?php echo (new DateTime)->format("Y"); ?> value="<?php echo $date[0]; ?>" /><b>/</b>
			<input type="number" name="month" min=1 max=12 value="<?php echo $date[1]; ?>" /><b>/</b>
			<input type="number" name="day" min=1 max=31 value="<?php echo $date[2]; ?>" /></p>
			<p><?php echo __('Price', $lang, '../') ?>: <input type="number" step="0.05" name="price" min=0 value="<?php echo $row['uDEXprecio']; ?>" /></p>
			<p><?php echo __('Max Cuantity', $lang, '../') ?>: <input type="number" name="max" min=1/></p>
			<p><input type="submit" class="btn" value="<?php echo __('UPDATE', $lang, '../') ?>" /></p>
		</form>
		<input type="button" class="btn" value="<?php echo __('CLOSE', $lang, '../') ?>" onclick="cerrar()"/>
	</div>
	<div class="col col-sm-3" id="products" style="display: none; overflow-y: scroll; height: 400px;"></div>
	</div></div>
<?php
}
?>
</body>
</html>
<?php
if (isset($_POST['product'])){
	$producto = '';
	$date = '';
	if ($_POST['product'] != $row['product']){
		$producto = 'idproducto = (SELECT id FROM v_productos WHERE producto = "'. $_POST['product'] .'")';
	}
	$auxdate = "$_POST[year]-$_POST[month]-$_POST[day]";
	if (($_POST['year'] != '' and $_POST['month'] != '' and $_POST['day'] != '') and ($auxdate != $row['tiempo'])){
		$date = "tiempo = '$auxdate'";
		if ($producto != '') $date = ', '.$date;
	}
	try{
		echo 'UPDATE v_productos_seleccion SET '.$producto.' '.$date.' WHERE sel="'.$_POST['seleccion'].'" AND idmaquina='.$_POST['maquina'];
		$sql = $basededatos->prepare('UPDATE v_productos_seleccion SET '.$producto.' '.$date.' WHERE sel="'.$_POST['seleccion'].'" AND idmaquina='.$_POST['maquina']);
		$sql->execute();
		echo __('Product updated correctly', $lang, '../').'<br/>';
	} catch (exception $e) {
		echo "No se puede la consulta 2";
	}
}
if (isset($_POST['price']) and ($_POST['price'] != $row['uDEXprecio'])){
	try{
		$sql = $basededatos->prepare("UPDATE v_seleccion SET uDEXprecio = $_POST[price] WHERE sel='$_POST[seleccion]' AND idmaquina=$_POST[maquina]");
		$sql->execute();
		echo __('Price updated correctly', $lang, '../');
	} catch (exception $e) {
		echo "No se puede la consulta 3";
	}
}
if ($_POST['max'] != ''){
	try{
		$sql = $basededatos->prepare("UPDATE v_seleccion SET max = $_POST[max] WHERE idmaquina = $_POST[maquina] AND sel = '$_POST[seleccion]'");
		$sql->execute();
		echo __('Max changed correctly', $lang, '../');
	} catch (exception $e) {
		echo "No se puede la consulta 4";
		exit;
	}
}
?>