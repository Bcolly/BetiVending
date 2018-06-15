<?php require ("s_index.php"); ?>
<?php
	$con=1;
	$basededatos = conectardb();
	$productos=query("SELECT * FROM v_productos;", $basededatos, $con);
	$con++;
?>

	<form class="navbar-form navbar-left" role="form" method="POST">
		<div class="form-group">
			<label for="producto"><?php echo __('PRODUCT', $lang, '../') ?> : </label>
			<input type="text" class="form-control" id="producto" name="producto">
		</div>
		<div class="form-group">
			<label for="familia"><?php echo __('FAMILY', $lang, '../') ?> : </label>
			<select class="form-control" id="familia" name="familia">
			  <option value="" selected="selected">- <?php echo __('select', $lang, '../') ?> -</option>
			  <!--<option value="windows">Windows</option>
			  <option value="mac">Mac</option>
			  <option value="linux">Linux</option>
			  <option value="otro">Otro</option>-->
			</select>
		</div>
		<button type="button" class="btn btn-success" onclick="filtro()"><?php echo __('FILTER', $lang, '../') ?></button>
	</form>
	<br/><br/><br/>
	<div data-spy="scroll" data-target=".table" id="tabla">
		<table class="table table-striped">
		<tr><th></th><th><?php echo __('PRODUCT', $lang, '../')?></th><th><?php echo __('FAMILY', $lang, '../')?></th><th><?php echo __('PRICE AVG', $lang, '../')?></th><th><?php echo __('ADD TO MACHINES', $lang, '../')?></th></tr>
	<?php
		foreach($productos as $producto){
			echo "<tr><td><img src='$producto[foto_th]'/></td>
			<td> $producto[producto]</td>
			<td>$producto[familia]</td>
			<td>$producto[PVP]</td>";
			echo "<td><p><select id='$producto[id]' class='form-control'>";
			$maquinas=query("SELECT m.id, m.nombre FROM v_maquinas as m, v_dispositivo as d, v_user as u
				WHERE m.dispositivoid = d.id AND d.userid = u.id AND u.usuario = 'inaki'", $basededatos, $con);
			$con++;
			foreach ($maquinas as $m) {
				echo "<option value='$m[id]'>$m[nombre]</option>";
			}
			echo "</select></p>";
			?>
			<p><input type="button" class="btn btn-info" onclick="addtomach(<?php echo $producto['id']; ?>)" value="<?php echo __('ADD', $lang, '../')?>" /></p>
			<?php
			echo "</td></tr>";
		}
		$basededatos = null; #cerramos la conexión
	 ?>
		</table>
    </div>

<?php require ("s_footer.php"); ?>
