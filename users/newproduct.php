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

			function comprobarNuevoProducto(prod, ean1, ean2, precio, oferta, familia) {
				alert('comprobaciones para enviar');
				var preciop = "^[0-9]{1,2}([.,][0-9]{1,2})?$";
				var correcto = true;
				if (prod == "")
					correcto = false;
				if (ean1.localeCompare(ean2) == 0)
					correcto = false;
				if (!preciop.test(precio) || !preciop.test(oferta) || precio < oferta)
						correcto = false;

				return correcto;
			}
    </script>
	</head>
	<body>
		<div class="container">
	  	<form class="container" enctype="multipart/form-data" method="POST" onsubmit="return comprobarNuevoProducto(this.product.value,
			this.ean1.value, this.ean2.value, this.precio.value, this.oferta.value, this.familia.value)">
	      <div class="form-group">
					<label for="nombre"><?php echo __('Product name', $lang) ?></label>
					<input type="text" class="form-control" id="nombre" name="product" required
						   placeholder="<?php echo __('Product name', $lang) ?>"/>
				</div>
				<div class="form-group">
					<label for="ean1"><?php echo __('Code1', $lang) ?></label>
					<input type="text" class="form-control" id="ean1" name="ean1"
						   placeholder="<?php echo __('Code1', $lang) ?>"/>
				</div>
	      <div class="form-group">
					<label for="ean2"><?php echo __('Code2', $lang) ?></label>
					<input type="text" class="form-control" id="ean2" name="ean2"
						   placeholder="<?php echo __('Code2', $lang) ?>"/>
				</div>
				<div class="form-group">
					<label for="precio"><?php echo __('Price', $lang) ?></label>
					<input type="text" class="form-control" id="precio" name="precio"
						   placeholder="<?php echo __('Price', $lang) ?>" pattern="^[0-9]{1,2}([.,][0-9]{1,2})?$"/>
				</div>
				<div class="form-group">
					<label for="oferta"><?php echo __('Discount price', $lang) ?></label>
					<input type="text" class="form-control" id="oferta" name="oferta"
						   placeholder="<?php echo __('Discount price', $lang) ?>" pattern="^[0-9]{1,2}([.,][0-9]{1,2})?$"/>
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
		foreach($_POST as $campo => $valor)
			echo "$campo -> $valor <br>";
		foreach($_FILES as $campo => $valor)
				echo "$campo -> ".print_r($valor)." <br>";
		$query = "INSERT INTO v_productos (ean1, ean2, producto, PVP, PVPoferta, foto_th, familia) VALUES ";
		$values = "(";
		$correcto = true;
		if (empty($_POST["product"]))
			$correcto = false;
		elseif (strcmp($_POST["ean1"], $_POST["ean2"]) == 0) {
			$correcto = false;
		}
		else {
			if (!empty($_POST["ean1"]))
				$values .= "'".$_POST["ean1"]."', ";
			else
				$values .= "0, ";
			if (!empty($_POST["ean2"]))
				$values .= "'".$_POST["ean2"]."', ";
			else
				$values .= "0, ";

			$values .= "'".$_POST["product"]."', ";
		}
		preg_match("/^[0-9]{1,2}([.,][0-9]{1,2})?$/", $_POST["precio"], $output);
		if (count($output) < 1)
			$correcto = false;
		else
			$values .= $_POST["precio"].", ";

		preg_match("/^[0-9]{1,2}([.,][0-9]{1,2})?$/", $_POST["oferta"], $output2);
		if (!empty($_POST["ean2"]) && count($output2) < 1) {
			$correcto = false;
			$values .= "0, ";
		} elseif (empty($_POST["ean2"]))
			$values .= "0, ";
		else
			$values .= $_POST["oferta"].", ";

		if (strcmp($_POST["precio"], $_POST["oferta"]) < 0)
			$correcto = false;

		if (subirimg($_FILES["file-input"]))
			$values .= "'http://localhost/betiV/img/uploads/".$_FILES["file-input"]["name"]."', ";
		else
			$values .= "'', ";

		$values .= "'".$_POST["familia"]."')<br/>";
		if ($correcto) {
			$query .= $values;
			echo $query;
			$basededatos = conectardb();
			execute($query, $basededatos, 1);
	  	echo __('Product added correctly', $lang, '../');
		}
  }
?>

		</div>
	</body>
</html>

<?php
	//https://www.w3schools.com/php/php_file_upload.asp
	function subirimg($img) {
		if ($img["size"] > 0) {
			$target_dir = "../img/uploads/";
			$target_file = $target_dir . basename($img["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
			$check = getimagesize($img["tmp_name"]);
			if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".<br/>";
					$uploadOk = 1;
			} else {
					echo "File is not an image.<br/>";
					$uploadOk = 0;
			}
			// Check if file already exists
			if (file_exists($target_file)) {
					echo "Sorry, file already exists.<br/>";
					$uploadOk = 0;
			}
			// Check file size
			if ($img["size"] > 500000) {
					echo "Sorry, your file is too large.<br/>";
					$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
					$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.<br/>";
			// if everything is ok, try to upload file
			} else {
					if (move_uploaded_file($img["tmp_name"], $target_file)) {
							echo "The file ". basename($img["name"]). " has been uploaded.<br/>";
							return true;
					} else {
							echo "Sorry, there was an error uploading your file.<br/>";
					}
			}
		}
		return false;
	}
?>
