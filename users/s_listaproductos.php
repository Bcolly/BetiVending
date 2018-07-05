<?php require ("s_index.php"); ?>
<?php
	$con=1;
	$basededatos = conectardb();
	$productos=query("SELECT * FROM v_productos;", $basededatos, $con);
	$con++;
?>
	<div class="row">
		<div class="col col-md-8">
			<form class="navbar-form navbar-left" role="form">
				<div class="form-group">
					<label for="producto"><?php echo __('PRODUCT', $lang, '../') ?> : </label>
					<input type="text" class="form-control" id="producto" name="producto">
				</div>
				<div class="form-group">
					<label for="familia"><?php echo __('FAMILY', $lang, '../') ?> : </label>
					<select class="form-control" id="familia" name="familia">
					  <option value="" selected="selected">- <?php echo __('select', $lang, '../') ?> -</option>
<?php
						$familias = query("SELECT DISTINCT familia FROM v_productos WHERE familia <> 'INTERNA' AND familia <> ''", $basededatos, $con);
						$con++;
						foreach ($familias as $row) {
							echo "<option value='$row[familia]'>$row[familia]</option>";
						}
?>
					</select>
				</div>
				<button type="button" class="btn btn-success" onclick="prodfiltro()"><?php echo __('FILTER', $lang, '../') ?></button>
			</form>
		</div>
		<div class="col-md-3 col-md-offset-1">
			<form class="navbar-form navbar-left" role="form">
				<label for="familia"><?php echo __('Need more products?', $lang, '../') ?></label>
				<button type="button" class="btn btn-primary" onclick="abrir('newproduct.php')"><?php echo __('ADD', $lang, '../') ?></button>
			</form>
		</div>
	</div>
	<div class="row" data-spy="scroll" data-target=".table" id="tabla">
		<table class="table table-striped">
			<?php include ("./methods/tabla_productos.php"); ?>
		</table>
  </div>

<?php require ("s_footer.php"); ?>
