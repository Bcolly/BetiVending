<?php require ("s_index.php");

//SELECT userid FROM v_maquinas as m INNER JOIN v_dispositivo as d on d.id=m.dispositivoid WHERE userid=1 AND m.id=2

$id = -1;
if (isset($_GET["OBJ"])) $id=$_GET["OBJ"];

$con = 1;
$basededatos=conectardb();
$sql=query("SELECT userid
						FROM v_maquinas as m INNER JOIN v_dispositivo as d on d.id=m.dispositivoid
						WHERE userid=$userid AND m.id=$id",
	$basededatos, $con);

if ($sql->rowCount() < 1) {
    //si no existe, se envia a la página de autentificacion
    echo "<script> location.href='../index.php'; </script>";
    //ademas se cierra el script
    exit();
}


$maquina = comprobar_maquina($id);
if ($maquina != null) { ?>
	<h3><b><?php echo __('Machine', $lang, '../')?>: </b><?php 	echo $maquina["nombre"]; ?></h3>
<?php }?>
	<div class="container">
		<div class="row">
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
			<?php if ($maquina != null) { ?>
			<div class="col col-md-3">
				<?php
				$sql=query("SELECT * FROM v_evento WHERE idmaquina=$id", $basededatos, $con);
				$con++;
				$num = $sql->rowCount();
				if ($num > 0) {
					echo '<b>' . __("EVENTS", $lang, "../").': '.$num;
				?></b><br/>
				<button type="button" class="btn btn-info" onclick="abrir('s_evento.php?id=<?php echo $id; ?>')"><?php echo __("SHOW EVENTS", $lang, "../")?></button>
				<button type="button" class="btn btn-info" onclick="limpiar('<?php echo $id; ?>')"><?php echo __("CLEAR", $lang, "../")?></button>
			<?php }
			} ?>
			</div>
		</div>
	</div>
	</br>
	<div data-spy="scroll" data-target=".table" id="tabla">
		<table class="table table-striped">
			<tr>
				<th>
					<input type="button" class="btn btn-primary" onclick="ocultar(<?php echo $id; ?>)" value="<?php echo __('HIDE', $lang, '../')?>"></input>
				</th>
				<th><?php echo __('Selection', $lang, '../');?></th>
				<th><?php echo __('Product', $lang, '../');?></th>
				<th><?php echo __('Price', $lang, '../');?></th>
				<th>
					<?php echo __('Cuantity', $lang, '../');?><br/>
					<button type="button" class="btn btn-success" onclick="llenarAll(<?php echo $id; ?>)"><?php echo __("FILL ALL", $lang, "../");?></button>
				</th>
				<th><?php echo __('To go', $lang, '../');?></th>
				<th><?php echo __('Date', $lang, '../');?></th>
				<th></th><th></th>
			</tr>
<?php
	if ($maquina != null) {
		$lista_sel=query("SELECT sel,uDEXprecio,max FROM v_seleccion WHERE idmaquina=$id AND visible_log=1 ORDER BY sel", $basededatos, $con);
		$con++;
		foreach ($lista_sel as $sel) {
			mostrarsel($id, $sel);
		}
?>
		</table>
	</div>
<?php
		$basededatos=null;
	}

include_once ("s_footer.php"); ?>
<?php
//------FUNCIONES----------
	function comprobar_maquina($id){
		global $basededatos, $con, $usuario;
		$maquina = null;
		//falta asegurarse de que el dueño de la maquina es el mismo que esta en la sesion.
		$sql=query("SELECT nombre FROM v_maquinas WHERE id=$id", $basededatos, $con);
		$con++;
		if ($sql->rowCount() > 0)
			$maquina = $sql->fetch();
		return $maquina;
	}

	function mostrarsel($id, $sel){
		global $lang, $basededatos, $con;
		$productos=query("SELECT * FROM v_productos_seleccion INNER JOIN v_productos ON idproducto=id
						  WHERE idmaquina=$id AND sel='$sel[sel]'", $basededatos, $con);
		$con++;
		if ($productos->rowCount() < 1) {
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
			foreach ($productos as $producto)
				mostrarproducto($id, $sel, $producto);
		}
	}

	function mostrarproducto($id, $sel, $producto){
		global $lang;
?>
		<tr>
			<td><center><input type='checkbox' id='<?php echo $sel['sel']; ?>'></center></td>
			<td><?php echo $sel['sel']; ?></td>
			<td>
				<a href=''><?php echo $producto['producto']; ?></a><br>
				<a href=''><img align='center' height='100' src='<?php echo $producto['foto_th']; ?>'/></a>
			</td>
			<td><?php echo $sel['uDEXprecio']; ?></td>
			<td>
				<input type='number' id='cantidad_sel_<?php echo $sel['sel']; ?>' name='cantidad_sel_<?php echo $sel['sel']; ?>' value='<?php echo $producto['cantidad']; ?>' min='0' max='<?php echo $sel['max']; ?>'/>
				<button type='button' class='btn btn-success' onclick="llenar(<?php echo $id; ?>,'<?php echo $sel['sel']; ?>', cantidad_sel_<?php echo $sel['sel']; ?>.value)" >+</button>
			</td>
			<td><?php echo $sel['max']-$producto['cantidad']; ?></td>
			<td><?php echo $producto['tiempo']; ?></td>
			<td><button type="button" class="btn btn-default" onclick="abrir('changeproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("CHANGE", $lang, "../"); ?></button></td>
			<td><button type="button" class="btn btn-danger" onclick="vaciar(<?php echo $id; ?>, '<?php echo $sel['sel']; ?>')" ><?php echo __("CLEAR", $lang, "../"); ?></button></td>
		<tr/>
<?php
	}
?>
