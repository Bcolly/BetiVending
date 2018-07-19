<?php require ("s_index.php"); ?>
  <h3><?php echo __('List of shops', $lang, '../') ?>: </h3>
	<form class="navbar-form navbar-left" role="form" method="POST">
		<div class="form-group">
			<label for="direccion"><?php echo __('ADDRESS', $lang, '../') ?>: </label>
			<input type="text" class="form-control" id="d1" name="d1">
		</div>
    <div class="form-group">
			<label for="ruta"><?php echo __('ROUTE', $lang, '../') ?>: </label>
			<input type="text" class="form-control" id="r1" name="r1">
		</div>
		<button type="button" class="btn btn-success" onclick="filtrolocal(d1.value, r1.value)"><?php echo __('FILTER', $lang, '../') ?></button>
	</form>
	<br/><br/><br/>
	<div class="pre-scrollable" data-spy="scroll" data-target=".table" id="tabla">
		<?php include("methods/tabla_locales.php"); ?>
    </div>
	<h3><a href="#newlocal"><i class="glyphicon glyphicon-plus"></i></a> <?php echo __('Add new shop', $lang, '../') ?> </h3>
  <div id="newlocal" class="modalDialog">
  	<div style="height:60%">
  		<a href="#close" title="Close" class="close">X</a>
  		<h2><?php echo __('New Shop', $lang, '../') ?></h2>
      <div>
        <form class="navbar-form navbar-left" role="form" method="POST" action="methods/newlocal.php">
      		<div class="form-group">
      			<label for="direccion"><?php echo __('Street', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="direccion" name="direccion" required>
      		</div>
          <div class="form-group">
      			<label for="numero"><?php echo __('Number', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="numero" name="numero"
            pattern="[0-9]+">
      		</div>
          <div class="form-group">
      			<label for="cp"><?php echo __('Post Code', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="cp" name="cp"
            pattern="[0-9]{5}" required>
      		</div>
          <div class="form-group">
      			<label for="municipio"><?php echo __('City', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="municipio" name="municipio"
            pattern="\D+" required>
      		</div>
          <div class="form-group">
      			<label for="provincia"><?php echo __('Province', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="provincia" name="provincia"
            pattern="\D+" required>
      		</div>
          <div class="form-group">
      			<label for="pais"><?php echo __('Country', $lang, '../') ?>: </label>
      			<input type="text" class="form-control" id="pais" name="pais"
            pattern="\D+" required>
      		</div>
          <div class="form-group">
      			<label for="ruta"><?php echo __('Route', $lang, '../') ?>: </label>
            <select class="form-control" id="ruta" name="ruta">
  					  <option value="" selected="selected"> </option>
<?php
              $con = 1;
              $basededatos=conectardb();
              $locales=query("SELECT DISTINCT ruta FROM v_locales	WHERE userid = $userid AND ruta IS NOT NULL", $basededatos, $con);
              $con++;
              foreach ($locales as $row) {
                echo "<option value='$row[ruta]'>$row[ruta]</option>";
              }
?>
            </select>
      		</div>
          <br/></br>
          <button type="submit" class="btn btn-success" ><?php echo __('SEND', $lang, '../') ?></button>
      	</form>
      </div>
  	</div>
</div>

<div id="newruta" class="modalDialog">
  <div style="height:25%">
    <a href="#close" title="Close" class="close">X</a>
    <h2><?php echo __('New Route', $lang, '../') ?></h2>
    <div>
      <form class="navbar-form navbar-left" role="form" method="POST" action="methods/newruta.php">
        <div class="form-group">
          <label for="nombre"><?php echo __('Route name', $lang, '../') ?>: </label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <input type="hidden" class="form-control" id="idlocal" name="idlocal" />
        <button type="submit" class="btn btn-success" ><?php echo __('SEND', $lang, '../') ?></button>
      </form>
    </div>
  </div>
</div>

<div id="changeruta" class="modalDialog">
  <div style="height:25%">
    <a href="#close" title="Close" class="close">X</a>
    <h2><?php echo __('New Route', $lang, '../') ?></h2>
    <div>
      <form class="navbar-form navbar-left" role="form" method="POST" action="methods/cambiaruta.php">
        <div class="form-group">
          <label for="ruta"><?php echo __('Route', $lang, '../') ?>: </label>
          <select class="form-control" id="nuevaruta" name="nuevaruta">
<?php
            $locales=query("SELECT DISTINCT ruta FROM v_locales	WHERE userid = $userid AND ruta IS NOT NULL", $basededatos, $con);
            $con++;
            foreach ($locales as $row) {
              echo "<option value='$row[ruta]'>$row[ruta]</option>";
            }
?>
          </select>
        </div>
        <input type="hidden" class="form-control" id="idlocal2" name="idlocal2" /></br>
        <button type="submit" class="btn btn-success" ><?php echo __('SEND', $lang, '../') ?></button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    function setLocal(id){
      document.getElementById("idlocal").value = id;
    }
    function setLocal2(id){
      document.getElementById("idlocal2").value = id;
    }
</script>

<?php require ("s_footer.php"); ?>
