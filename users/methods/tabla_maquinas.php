<?php
	if (isset($_GET["js"])){
		require_once ("../seguridad.php");
		require_once ("../../conexion.php");
		require_once("../../lang/language.php");
		$pre = "../";
	} else $pre = "";

		$userid=$_SESSION["userid"];

		$query = "SELECT DISTINCT ruta FROM v_locales WHERE userid = $userid ";

		if (isset($_GET["rut"])){
				$query .= " AND ruta LIKE '%$_GET[rut]%'";
		}

		$query.=";";

		$con=1;
		$basededatos=conectardb();
		$rutas=query($query, $basededatos, $con);
		$con++;

		foreach ($rutas as $ruta) {
?>
			<div class="col col-md-2">
				<table class="table table-striped">
					<tr>
						<th><?php echo $ruta["ruta"]; ?></th>
						<th>

						</th>
					</tr>
<?php
					$maquinas=query("SELECT m.id, nombre
						FROM (v_maquinas as m INNER JOIN v_user_maquinas as um ON m.id=um.maquinaid), v_locales as l
						WHERE idlocal=l.id AND ruta='$ruta[ruta]' AND um.userid=$userid", $basededatos, $con);
					$con++;
					foreach ($maquinas as $maquina) {
?>
					<tr>
						<td><a href='s_maquina.php?OBJ=<?php echo $maquina["id"] ?>'><?php echo $maquina["nombre"] ?></a></td>
						<td>
							<img src='../img/delete.png' height='20' width='20' alt="<?php echo __('Delete', $lang, $pre.'../'); ?>"
								title="<?php echo __('Delete', $lang, $pre.'../'); ?>"
								onclick="quitarRuta(<?php echo $maquina["id"] ?>)" />
						</td>
					</tr>
<?php
					}
?>
				</table>
			</div>

<?php
			}
?>
			<div class="col col-md-2">
				<table class="table table-striped">
					<tr>
						<th><?php echo __('None', $lang,  $pre.'../'); ?></th>
						<th></th>
					</tr>
<?php
					$msr=query("SELECT id, nombre FROM v_maquinas
						INNER JOIN v_user_maquinas ON id=maquinaid
						WHERE idlocal=0 AND userid = $userid", $basededatos, $con);

					foreach ($msr as $maquina) {
?>
						<tr>
							<td><a href='s_maquina.php?OBJ=<?php echo $maquina["id"] ?>'><?php echo $maquina["nombre"] ?></a></td>
							<td></td>
						</tr>
<?php
					}
?>
				</table>
			</div>
<?php
			$rutas=query($query, $basededatos, $con);
			$con++;
?>
			<div class="col col-md-4" id="formsruta">
				<form class="navbar-form navbar-left" role="form" method="POST">
					<h3><?php echo __('Select route', $lang, '../'); ?></h3>
					<div class="form-group">
						<input type="hidden" id="mid"/>
						<select class="form-control" id="sruta" name="sruta">
<?php
						foreach ($rutas as $ruta) {
							echo "<option value='$ruta[id]'>$ruta[ruta]</option>";
						}
?>
						</select>
					</div>
					<button type="button" class="btn btn-success" onclick="cambiaRuta()">
						<?php echo __('ADD', $lang, '../'); ?></button>
				</form>
			</div>

<?php
			$rutas=query($query, $basededatos, $con);
			$con++;
			$maquinas=query("SELECT id, nombre FROM v_maquinas INNER JOIN v_user_maquinas ON id=maquinaid
				WHERE userid=$userid", $basededatos, $con);
			$con++;
?>
			<div class="col col-md-4" id="formsmaquina">
				<form class="navbar-form navbar-left" role="form" method="POST">
					<h3><?php echo __('Select route and machine', $lang, '../'); ?></h3>
					<div class="form-group">
						<select class="form-control" id="smaquina" name="smaquina">
<?php
						foreach ($maquinas as $m) {
							echo "<option value='$m[id]'>$m[nombre]</option>";
						}
?>
						</select>
						<b> <?php echo __('to', $lang, '../'); ?> </b>
						<select class="form-control" id="sruta" name="sruta">
<?php
						foreach ($rutas as $ruta) {
							echo "<option value='$ruta[id]'>$ruta[ruta]</option>";
						}
?>
						</select>
					</div>
					<button type="button" class="btn btn-success" onclick=""><?php echo __('ADD', $lang, '../') ?></button>
				</form>
			</div>
<?php
			$basededatos = null; //cerramos la conexión
?>
