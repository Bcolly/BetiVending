<?php include_once("../lang/language.php"); ?>
<?php require_once ("seguridad.php"); ?>
<?php require_once ("../conexion.php"); ?>
<?php
			$ruta = $_GET['ruta'];
			$userid=$_SESSION["userid"];
?>
<html>
	<head>
		<meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	  <meta name="description" content="">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="apple-touch-icon" href="../apple-touch-icon.png">
	  <link rel="stylesheet" href="../css/bootstrap.min.css">
	  <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	  <link rel="stylesheet" href="../css/main.css">
	  <link rel="stylesheet" href="../css/bootstrap_wizard.css">
		<script src="../js/ajax.js"></script>
    <script type="text/javascript" src="../js/jquery-1.12.0.min.js"></script>
		<script language="JavaScript">
      $(document).ready(function() {
      	$(".botonExcel").click(function(event) {
      		$("#datos_a_enviar").val( $("<div>").append( $("#tabla").eq(0).clone()).html());
      		$("#FormularioExportacion").submit();
      });
      });

			function cerrar() {
				ventana=window.self;
				ventana.opener=window.self;
				ventana.close(); }
		</script>
	</head>
	<body>
		<div class="container">
      <div class="row">
        <h3>
<?php
          $con = 1;
          $basededatos = conectardb();
          $sql=query("SELECT v_maquinas.id, nombre FROM v_maquinas INNER JOIN v_locales ON idlocal=v_locales.id
						WHERE ruta='$ruta' AND userid=$userid", $basededatos, $con);
          $con++;
          echo '<p>'.__("Loading list of", $lang, "../").' <b>'.$ruta.'</b></p>';
					echo '<p>'.__("See machines of route", $lang, "../");
					$productos = [];
					foreach ($sql as $row) {
						echo ' '."<a href='s_listacarga.php?id=$row[id]'>$row[nombre]</a>";
						$pm=query("SELECT id, producto, ean1, ean2, familia, (max-cantidad) as falta
						FROM (v_productos_seleccion INNER JOIN v_productos ON idproducto=id)
							NATURAL JOIN v_seleccion
							WHERE idmaquina=$row[id] AND producto != 'carril vacio';", $basededatos, $con);
						$con++;
						foreach ($pm as $p) {
							$idp = $p["id"];
							if (!isset($productos["$idp"])){
								$productos["$idp"] = [
									"producto" => $p["producto"],
									"ean1" => $p["ean1"],
									"ean2" => $p["ean2"],
									"familia" => $p["familia"],
									"falta" => $p["falta"]
								];
							} else {
								$productos["$idp"]["falta"] += $p["falta"];
							}
						}
					}
					echo '</p>';
?>
        </h3>
        <table id="tabla" class="table table-striped">
      		<tr>
      			<th><?php echo __('PRODUCT', $lang, '../') ?><span class="caret" style="visibility: collapse;"/></th>
      			<th><?php echo __('CODE1', $lang, '../') ?><span class="caret" style="visibility: collapse;"/></th>
            <th><?php echo __('CODE2', $lang, '../') ?><span class="caret" style="visibility: collapse;"/></th>
            <th><?php echo __('FAMILY', $lang, '../') ?><span class="caret" style="visibility: collapse;"/></th>
      			<th><?php echo __('NEEDED', $lang, '../') ?><span class="caret" style="visibility: collapse;"/></th>
      		</tr>
<?php
          foreach ($productos as $p) {
            echo "<tr>
                    <td>$p[producto]</td>";
            if (strcmp($p['ean1'], '0') != 0)
              echo "<td>$p[ean1]</td>";
            else
              echo "<td>--</td>";

            if (strcmp($p['ean2'], '0') != 0)
              echo "<td>$p[ean2]</td>";
            else
              echo "<td>--</td>";

            echo "  <td>$p[familia]</td>
                    <td>$p[falta]</td>
                  </tr>";
          }
?>
        </table>
			</div>
      <div class="row">
        <form action="methods/ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
          <button type="button" class="btn btn-info botonExcel"><?php echo __("Download", $lang, "../")?> XLS</button>
  		    <button type="button" class="btn btn-info" onclick="cerrar()"><?php echo __("CLOSE", $lang, "../")?></button>
          <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        </form>
      </div>
		</div>
	</body>
</html>
