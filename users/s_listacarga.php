<?php include_once("../lang/language.php"); ?>
<?php require_once ("seguridad.php"); ?>
<?php require_once ("../conexion.php"); ?>
<?php $id = $_GET['id']; ?>
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
      function descargarExcel(){
        //Creamos un Elemento Temporal en forma de enlace
        var tmpElemento = document.createElement('a');
        // obtenemos la información desde el div que lo contiene en el html
        // Obtenemos la información de la tabla
        var data_type = 'data:application/vnd.ms-excel';
        var tabla_div = document.getElementById('tabla');
        var tabla_html = tabla_div.outerHTML.replace(/ /g, '%20');
        tmpElemento.href = data_type + ', ' + tabla_html;
        //Asignamos el nombre a nuestro EXCEL
        tmpElemento.download = 'Nombre_De_Mi_Excel.xls';
        // Simulamos el click al elemento creado para descargarlo
        tmpElemento.click();
      }

      $(document).ready(function() {
      	$(".botonExcel").click(function(event) {
      		$("#tabla").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
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
          $sql=query("SELECT nombre FROM v_maquinas WHERE id=$id", $basededatos, $con);
          $con++;
          echo __("Loading list", $lang, "../").' of <b>'.$sql->fetch()['nombre'].'</b>';
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
          $productos=query("SELECT producto, ean1, ean2, familia, (max-cantidad) as falta
          FROM (v_productos_seleccion INNER JOIN v_productos ON idproducto=id)
            NATURAL JOIN v_seleccion
          WHERE idmaquina=$id AND producto != 'carril vacio' ;", $basededatos, $con);
          $con++;

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
