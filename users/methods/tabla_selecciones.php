<?php
		require_once ("../../conexion.php");
		require_once("../../language.php");
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
		<tr><th><input type="button" class="btn btn-primary" onclick="ocultar(<?php echo $id; ?>)" value="<?php echo __('HIDE', $lang, '../'); ?>">
		</input></th><th><?php echo __('Selection', $lang, '../'); ?></th><th><?php echo __('Product', $lang, '../'); ?></th><th><?php echo __('Price', $lang, '../'); ?></th>
		<th><?php echo __('Cuantity', $lang, '../'); ?></th><th><?php echo __('Date', $lang, '../'); ?></th><th></th><th></th></tr>
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
				<td><button type="button" class="btn btn-primary" onclick="abrir('addproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("ADD", $lang, "../"); ?></button></td>
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
				<td><a href=''><?php echo $producto['producto']; ?></a><br>
				<a href=''><img align='center' height='100' src='<?php echo $producto['foto_th']; ?>'/></a></td>
				<td><?php echo $sel['uDEXprecio']; ?></td>
				<td><input type='number' id='cantidad_sel' name='cantidad_sel' value='<?php echo $producto['cantidad']; ?>' min='0' max='<?php echo $sel['max']; ?>'/>
				<button type='button' class='btn btn-success' onclick="llenar(<?php echo $id; ?>,<?php echo $sel['sel']; ?>, document.getElementById('cantidad_sel').value)" >+</button></td>
				<td><?php echo $producto['tiempo']; ?></td>
				<td><button type="button" class="btn btn-default" onclick="abrir('changeproduct.php?id=<?php echo $id; ?>&sel=<?php echo $sel['sel']; ?>')"><?php echo __("CHANGE", $lang, "../"); ?></button></td>
				<td><button type="button" class="btn btn-danger" onclick="vaciar(<?php echo $id; ?>, '<?php echo $sel['sel']; ?>')" ><?php echo __("CLEAR", $lang, "../"); ?></button></td>
				<tr/>
<?php
				}
			}
		}
		$basededatos=null;
	?>	
</table>