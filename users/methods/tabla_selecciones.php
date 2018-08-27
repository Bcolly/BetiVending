<?php
		if (isset($_GET["js"])){
			require_once ("../seguridad.php");
			require_once ("../../conexion.php");
			require_once ("../../lang/language.php");
			$pre = "../";
		} else $pre = "";

		$id=$_GET["id"];

		if (isset($_GET["ocultos"])) {
			if ($_GET["ocultos"]=="true") $ocultos="";
			else $ocultos=" AND visible_log=1";
		}

		if (isset($_GET["sel"]) && !isset($_GET["prod"]))
			$query="SELECT sel,uDEXprecio,visible_log,max FROM v_seleccion WHERE idmaquina=$id AND sel LIKE '%$_GET[sel]%'".$ocultos;

		elseif (!isset($_GET["sel"]) && isset($_GET["prod"]))
			$query="SELECT sel,uDEXprecio,visible_log,max FROM (v_productos_seleccion INNER JOIN v_productos ON idproducto=id) NATURAL JOIN v_seleccion
			WHERE idmaquina=$id AND producto LIKE '%$_GET[prod]%'".$ocultos;

		elseif (isset($_GET["sel"]) && isset($_GET["prod"]))
			$query="SELECT sel,uDEXprecio,visible_log,max FROM (v_productos_seleccion INNER JOIN v_productos ON idproducto=id) NATURAL JOIN v_seleccion
			WHERE idmaquina=$id AND producto LIKE '%$_GET[prod]%' AND sel LIKE '%$_GET[sel]%'".$ocultos;

		else
			$query="SELECT sel,uDEXprecio,visible_log,max FROM v_seleccion WHERE idmaquina=$id".$ocultos;

		$con=1;
		$basededatos=conectardb();
		$sql=query($query, $basededatos, $con);
		$con++;
?>
		<table class="table table-striped">
			<tr>
				<th>
					<input type="button" class="btn btn-primary" onclick="ocultar(<?php echo $id; ?>)" value="<?php echo __('HIDE', $lang, $pre.'../')?>"></input>
				</th>
				<th><?php echo __('Selection', $lang, $pre.'../');?></th>
				<th><?php echo __('Product', $lang, $pre.'../');?></th>
				<th><?php echo __('Price', $lang, $pre.'../');?></th>
				<th>
					<?php echo __('Cuantity', $lang, $pre.'../');?><br/>
					<button type="button" class="btn btn-success" onclick="llenarAll(<?php echo $id; ?>)"><?php echo __("FILL ALL", $lang, $pre."../");?></button>
				</th>
				<th><?php echo __('To go', $lang, $pre.'../');?></th>
				<th><?php echo __('Date', $lang, $pre.'../');?></th>
				<th></th><th></th>
			</tr>
<?php
		foreach($sql as $sel){
			if (isset($_GET["prod"])) {
				$query="SELECT * FROM v_productos_seleccion INNER JOIN v_productos ON idproducto=id
				WHERE idmaquina=$id AND sel='$sel[sel]' AND producto LIKE '%$_GET[prod]%'";

			} else {
				$query="SELECT * FROM v_productos_seleccion INNER JOIN v_productos ON idproducto=id
				WHERE idmaquina=$id AND sel='$sel[sel]'";
			}

			$sql2=query($query, $basededatos, $con);
			$con++;
			$num = $sql2->rowCount();

			if ($num<1) {
				echo "<tr>";
				echo "<td><center>";
				if ($sel["visible_log"] == 1) echo "<input type='checkbox' id='$sel[sel]'>";
				else 						  echo "<input type='checkbox' id='$sel[sel]' checked>";

				echo "</center></td>";
?>
					<td><?php echo $sel['sel']; ?></td>
					<td>--</td>
					<td><? echo $sel['uDEXprecio']; ?></td>
					<td>--</td>
					<td>--</td>
					<td></td>
					<td><button type="button" class="btn btn-primary" onclick="abrir('addproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("ADD", $lang, $pre."../"); ?></button></td>
				</tr>
<?php
			} else {
				foreach ($sql2 as $producto) {
					echo "<tr>";
					echo "<td><center>";
					if ($sel["visible_log"] == 1) echo "<input type='checkbox' id='$sel[sel]'>";
					else 						  echo "<input type='checkbox' id='$sel[sel]' checked>";

					echo "</center></td>";
?>
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
					<td><button type="button" class="btn btn-default" onclick="abrir('changeproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("CHANGE", $lang, $pre."../"); ?></button></td>
					<td><button type="button" class="btn btn-danger" onclick="vaciar(<?php echo $id; ?>, '<?php echo $sel['sel']; ?>')" ><?php echo __("CLEAR", $lang, $pre."../"); ?></button></td>
				<tr/>
<?php
				}
			}
		}
		$basededatos=null;
	?>
</table>

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
			<td><button type="button" class="btn btn-primary" onclick="abrir('addproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("ADD", $lang, $pre."../"); ?></button></td>
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
				<input type='number' style="width:30%;" id='cantidad_sel' name='cantidad_sel' value='0' min='0' max='<?php echo $sel['max']-$producto['cantidad']; ?>'/>
				<button type='button' class='btn btn-success' onclick="llenar(<?php echo $id; ?>,'<?php echo $sel['sel']; ?>', document.getElementById('cantidad_sel').value);" >+</button>
			</td>
			<td><?php echo $sel['max']-$producto['cantidad']; ?></td>
			<td><?php echo $producto['tiempo']; ?></td>
			<td><button type="button" class="btn btn-default" onclick="abrir('changeproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("CHANGE", $lang, $pre."../"); ?></button></td>
			<td><button type="button" class="btn btn-danger" onclick="vaciar(<?php echo $id; ?>, '<?php echo $sel['sel']; ?>')" ><?php echo __("CLEAR", $lang, $pre."../"); ?></button></td>
		<tr/>
<?php
	}
?>
