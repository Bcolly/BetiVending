<?php require ("s_index.php"); ?>
<?php
$id = -1;
$con=1
if (isset($_GET["OBJ"])) $id=$_GET["OBJ"];

if ($id>0){
	$basededatos=conectardb();
	$sql=query("SELECT nombre FROM v_maquinas WHERE id=$id", $basededatos, $con);
	$con++;
	$maquina = $sql->fetch();
?>
	<h3><b><?php echo __('Machine', $lang, '../')?>: </b><?php 	echo $maquina["nombre"]; ?></h3>
	<div class="container"><div class="row">
		<div class="col col-md-9">
			<form class="navbar-form navbar-left" role="form">
				<div class="form-group">
					<label for="seleccion"><?php echo __('SELECTION', $lang, '../')?>: </label>
					<input type="text" class="form-control" id="seleccion" name="seleccion">
				</div>
				<div class="form-group">
					<label for="producto"><?php echo __('PRODUCT', $lang, '../')?> : </label>
					<input type="text" class="form-control" id="producto" name="producto">
				</div>
				<button type="button" class="btn btn-success" onclick="selfiltro(<?php echo $id; ?>)"><?php echo __('FILTER', $lang, '../')?></button>
				<input type="checkbox" id="ocultos" name="ocultos"><?php echo __('Show hidden products', $lang, '../')?>
			</form>
		</div>
		<div class="col col-md-3">
			<?php
			$sql=query("SELECT * FROM v_evento WHERE idmaquina=$id", $basededatos, $con);
			$con++;
			$num = $sql->rowCount();
			if ($num > 0) {
				echo '<b>' . __("EVENTS", $lang, "../").':';
				echo $num;
			?></b><br/>
			<button type="button" class="btn btn-info" onclick="abrir('s_evento.php?id=<?php echo$id; ?>')"><?php echo __("SHOW EVENTS", $lang, "../")?></button>
			<button type="button" class="btn btn-info" onclick="limpiar('<?php echo$id; ?>')"><?php echo __("CLEAR", $lang, "../")?></button>
			<?php } ?>
		</div>
	</div></div>
	</br>
	<div data-spy="scroll" data-target=".table" id="tabla">
		<table class="table table-striped">
		<tr><th><input type="button" class="btn btn-primary" onclick="ocultar(<?php echo $id; ?>)" value="<?php echo __('HIDE', $lang, '../')?>">
		</input></th><th><?php echo __('Selection', $lang, '../')?></th><th><?php echo __('Product', $lang, '../')?></th><th><?php echo __('Price', $lang, '../')?></th>
		<th><?php echo __('Cuantity', $lang, '../')?></th><th><?php echo __('To go', $lang, '../')?></th><th><?php echo __('Date', $lang, '../')?></th><th></th><th></th></tr>
<?php
		$sql=query("SELECT sel,uDEXprecio,max FROM v_seleccion WHERE idmaquina=$id AND visible_log=1 ORDER BY sel", $basededatos, $con);
		$con++;
		foreach ($sql as $sel) {
			$sql2=query("SELECT * FROM v_productos_seleccion INNER JOIN v_productos ON idproducto=id 
				WHERE idmaquina=$id AND sel='$sel[sel]'", $basededatos, $con);
			$con++;
			$num = $sql2->rowCount();
			if ($num<1) {
?>
				<tr>
				<td><center><input type='checkbox' id='<?php echo $sel['sel']; ?>'></center></td>
				<td><?php echo $sel['sel']; ?></td>
				<td>--</td>
				<td><? echo $sel['uDEXprecio']; ?></td>
				<td>--</td>
				<td>--</td>
				<td></td>
				<td><button type="button" class="btn btn-primary" onclick="abrir('addproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("ADD", $lang, "../"); ?></button></td>
				</tr>
<?php
			} else {
				foreach ($sql2 as $producto) {
?>
				<tr>
				<td><center><input type='checkbox' id='<?php echo $sel['sel']; ?>'></center></td>
				<td><?php echo $sel['sel']; ?></td>
				<td><a href=''><?php echo $producto['producto']; ?></a><br>
				<a href=''><img align='center' height='100' src='<?php echo $producto['foto_th']; ?>'/></a></td>
				<td><?php echo $sel['uDEXprecio']; ?></td>
				<td><input type='number' style="width:30%;" id='cantidad_sel' name='cantidad_sel' value='<?php $producto['cantidad']; ?>' min='0' max='<?php echo $sel['max']; ?>'/>
				<button type='button' class='btn btn-success' onclick="llenar(<?php echo $id; ?>,<?php echo $sel['sel']; ?>, document.getElementById('cantidad_sel').value)" >+</button></td>
				<td><?php echo $sel['max']-$producto['cantidad']; ?></td>
				<td><?php echo $producto['tiempo']; ?></td>
				<td><button type="button" class="btn btn-default" onclick="abrir('changeproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("CHANGE", $lang, "../"); ?></button></td>
				<td><button type="button" class="btn btn-danger" onclick="vaciar(<?php echo $id; ?>, '<?php echo $sel['sel']; ?>')" ><?php echo __("CLEAR", $lang, "../"); ?></button></td>
				<tr/>
<?php
				}
			}
		}
?>
		</table>
	</div>
<?php
$basededatos=null;
}
require ("s_footer.php"); ?>