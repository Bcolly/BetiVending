<?php include_once("../lang/language.php"); ?>
<?php require_once ("seguridad.php"); ?>
<?php include_once ("../conexion.php"); ?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
    <script>
      var loadFile = function(event) {
        var output = document.getElementById('output');
				output.style.display = 'inline';
        output.src = URL.createObjectURL(event.target.files[0]);
      };

			function cerrar() {
				ventana=window.self;
				ventana.opener=window.self;
				ventana.close();
			}

			function comprobarNuevoProducto(prod, ean1, ean2, precio, familia) {
				alert('comprobaciones para enviar');
				var preciop = "[0-9]{1,2}([.,][0-9]{1,2})?";
				return false;
			}
    </script>
	</head>
	<body>
		<div class="container">
	  	<form class="container" method="POST" onsubmit="return comprobarNuevoProducto(this.product.value,
			this.ean1.value, this.ean2.value, this.precio.value, this.familia.value)">
	      <div class="form-group">
					<label for="nombre"><?php echo __('Product name', $lang) ?></label>
					<input type="text" class="form-control" id="nombre" name="product"
						   placeholder="<?php echo __('Product name', $lang) ?>"/>
				</div>
				<div class="form-group">
					<label for="ean1">Code1</label>
					<input type="text" class="form-control" id="ean1" name="ean1"
						   placeholder="<?php echo __('Code1', $lang) ?>"/>
				</div>
	      <div class="form-group">
					<label for="ean2">Code2</label>
					<input type="text" class="form-control" id="ean2" name="ean2"
						   placeholder="<?php echo __('Code2', $lang) ?>"/>
				</div>
				<div class="form-group">
					<label for="precio">Price</label>
					<input type="text" class="form-control" id="precio" name="precio"
						   placeholder="<?php echo __('Price', $lang) ?>" required pattern="[0-9]{1,2}([.,][0-9]{1,2})?"/>
				</div>
				<div class="form-group">
	        <label for="familia"><?php echo __('Family', $lang, '../') ?></label>
	        <select class="form-control" id="familia" name="familia">
	          <option value="" selected="selected">- <?php echo __('select', $lang, '../') ?> -</option>
	          <!--<option value="windows">Windows</option>
	          <option value="mac">Mac</option>
	          <option value="linux">Linux</option>
	          <option value="otro">Otro</option>-->
	        </select>
				</div>
	      <div class="form-group">
	        <label for="file-input"><?php echo __('Photo', $lang, '../') ?> : </label>
	        <input type="file" accept="image/*" onchange="loadFile(event)" name="file-input"/>
	      </div>
				<button type="submit" class="btn btn-default" name="submit" value=""><?php echo __('Send', $lang) ?></button>
				<input type="button" class="btn btn-default" value="<?php echo __('Close', $lang, '../') ?>" onclick="cerrar()" />
	  	</form>
	    <div>
	      <img id="output" width="50%" height="50%" style="display:none"/>
	    </div>

<?php
  if (isset($_POST['submit'])){
  	//execute("INSERT into v_productos_seleccion (idmaquina, sel, idproducto, tiempo, cantidad)
  	//values ($_POST[id],'$_POST[seleccion]',$_POST[prod],'$date', $_POST[cant])", $basededatos, $con);
  	echo __('Product added correctly', $lang, '../');
  }
?>

		</div>
	</body>
</html>
