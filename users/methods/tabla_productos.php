<?php
    if (isset($_GET["prod"]) || isset($_GET["fam"])) {
  		require_once ("../../conexion.php");
  		require_once("../../lang/language.php");
    }

    $query="SELECT * FROM v_productos";
		if (isset($_GET["prod"]) && !isset($_GET["fam"]))
			$query.=" WHERE producto LIKE '%$_GET[prod]%'";

		elseif (!isset($_GET["prod"]) && isset($_GET["fam"]))
			$query.=" WHERE familia='$_GET[fam]'";

		elseif (isset($_GET["prod"]) && isset($_GET["fam"]))
			$query.=" WHERE producto LIKE '%$_GET[prod]%' AND familia='$_GET[fam]'";

		$con=1;
		$basededatos=conectardb();
		$productos=query($query, $basededatos, $con);
		$con++;
?>
    <script type="text/javascript" src="../js/ajax.js"></script>
		<table class="table table-striped">
      <tr>
        <th></th>
        <th><?php echo __('PRODUCT', $lang, '../')?></th>
        <th><?php echo __('FAMILY', $lang, '../')?></th>
        <th><?php echo __('PRICE AVG', $lang, '../')?></th>
        <th><?php echo __('ADD TO MACHINES', $lang, '../')?></th>
      </tr>
<?php
      foreach($productos as $producto){
        echo "<tr>
          <td><img src='$producto[foto_th]'/></td>
          <td>$producto[producto]</td>
          <td>$producto[familia]</td>
          <td>$producto[PVP]</td>";
        echo "<td>
            <p><select id='$producto[id]' class='form-control'>";
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
        echo "</td>
          </tr>";
      }
      $basededatos = null; //cerramos la conexión
?>
    </table>
