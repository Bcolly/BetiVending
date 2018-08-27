<?php require ("s_index.php"); ?>
<?php
$dispositivo=unserialize($_GET["OBJ"]);
#var_dump($dispositivo);
if (!$dispositivo) $dispositivo=unserialize($_POST["OBJ"]);
#var_dump($dispositivo);

$con=1;
$basededatos=conectardb();

if ($ACC=="GRABAR") {
	$identificador=$_GET["identificador"];
	$fecha=$_GET["fecha"];
	$hora=$_GET["hora"];
	$mac=$_GET["mac"];
	$iplocal=$_GET["iplocal"];
	$ip=$_GET["ip"];
	$tipo=$_GET["tipo"];
	$conexion=$_GET["conexion"];
	$obs=$_GET["obs"];
	$enlace=$_GET["enlace"];
	$frecuencia=$_GET["frecuencia"];
	$ID_dispositivo=$_GET["ID_dispositivo"];

	$sql=query("SELECT nombre,frecuencia FROM v_dispositivo WHERE id='$ID_dispositivo'", $basededatos, $con);
	$con++;

	$row = $sql->fetch();

	if ($row["nombre"] != $identificador) $idquery = " ,solicitud_iden='$identificador'";
	else $idquery = "";
	if ($row["frecuencia"] != $frecuencia) $frquery = " ,solicitud_frecuencia='$frecuencia'";
	else $frquery = "";

	$query = "UPDATE v_dispositivo SET tipo='$tipo',conexion='$conexion',obs='$obs',enlace='$enlace',
	frecuencia=$frecuencia".$frquery."".$idquery." WHERE id='$ID_dispositivo'";

	try{
		$stmt = $basededatos->prepare($query);  // Prepare statement
		$stmt->execute();  // execute the query
		echo "<div class='alert alert-success'>".$stmt->rowCount()." Registros actualizados correctamente.</div>";
	} catch (exception $e) {
		echo"<div class='alert alert-danger'>Consuta fallida. Informe al administrador de este error:<br />...<br />".$sql."<br />...<br />".$e->getMessage()."</div>";
		exit;
	}
	echo "<script> location.href='dispositivo.php?OBJ=".serialize($dispositivo)."'; </script>";
	//header("location: dispositivo.php");
}

if ($ACC=="BORRAR") {
	$identificador=$_GET["identificador"];
	$ID_dispositivo=$_GET["ID_dispositivo"];
?>
	<div class='alert alert-danger'><center>
		<h1><?php echo __('CAUTION', $lang, '../') ?>!</h1>
		<h2>
			<?php echo __('YOU WILL DELETE THE DEVICE', $lang, '../');
			 			echo ' '.$identificador; ?><br />
			<p>
				<form role='form' action='dispositivo.php'>
					<input type='hidden' name='OBJ' value='<?php echo serialize($dispositivo); ?>'>
					<input type='hidden' name='PAG' value='$PAG'>
					<input type='hidden' name='ID_dispositivo' value='<?php echo $ID_dispositivo; ?>'>
					<input type='hidden' name='ACC' value='EZABATU'>
					<button name='ACC2' value='VOLVER' type='submit' class='btn btn-default'><?php echo __('RETURN WITHOUT DELETE', $lang, '../') ?></button>
					<button name='ACC2' value='BORRAR' type='submit' class='btn btn-default'><?php echo __('DELETE', $lang, '../') ?></button>
				</form>
			</p>
		</h2>
	</center></div>
<?php
	exit;
	}

if ($ACC=="EZABATU") {
$ACC2=$_GET["ACC2"];
if ($ACC2=="BORRAR") {
try{
    $ID_dispositivo=$_GET["ID_dispositivo"];
    $sql = "DELETE FROM v_dispositivo WHERE id='$ID_dispositivo'";
		echo $sql;
    $stmt = $basededatos->prepare($sql);  // Prepare statement
    $stmt->execute();  // execute the query
    echo "<div class='alert alert-success'>".$stmt->rowCount()." Registros actualizados correctamente.</div>";
    } catch (exception $e) {
    echo"<div class='alert alert-danger'>Consuta fallida. Informe al administrador de este error:<br />...<br />".$sql."<br />...<br />".$e->getMessage()."</div>";
    exit;
    }
		echo "<script> location.href='s_listadispositivos.php'; </script>";
    //header("location: s_listadispositivos.php");
    }
	}
?>
 <!--   <script src="../js/bootstrap-modalmanager.js"></script>
    <script src="../js/bootstrap-modal.js"></script>
<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760">
	<div class="modal-body">
	-->
	<p><h3><?php echo __('DEVICE', $lang, '../')." ";
						if (!isset($ID_dispositivo)) $ID_dispositivo = $dispositivo["nombre"];
						echo $ID_dispositivo ?></h3></p>
	<form role="form">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Identifier', $lang, '../') ?>:</label>
				<input type="text" name="identificador" class="form-control" value="<?php echo $dispositivo["nombre"]."\""?>" disabled="disabled">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Connection Date', $lang, '../') ?>:</label>
				<input type="text" name="fecha" class="form-control" value="<?php echo $dispositivo["fecha"]."\""?>" readonly="readonly">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Connection Time', $lang, '../') ?>:</label>
				<input type="text" name="hora" class="form-control" value="<?php echo $dispositivo["hora"]."\""?>" readonly="readonly">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('MAC addres', $lang, '../') ?>:</label>
				<input type="text" name = "mac" class="form-control" value="<?php echo $dispositivo["MAC"]."\""?>" readonly="readonly">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Local IP', $lang, '../') ?>:</label>
				<input type="text" name="iplocal" class="form-control" value="<?php echo $dispositivo["IPlocal"]."\""?>" readonly="readonly">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Public IP', $lang, '../') ?>:</label>
				<input type="text" name="ip" class="form-control" value="<?php echo $dispositivo["IPpublica"]."\""?>" readonly="readonly">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Type', $lang, '../') ?>:</label>
				<input type="text" name="tipo" class="form-control" value="<?php echo $dispositivo["tipo"]."\""?>" disabled="disabled">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Observations', $lang, '../') ?>:</label>
				<input type="text" name="obs" class="form-control" value="<?php echo $dispositivo["obs"]."\""?>" disabled="disabled">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="ejemplo_email_1"><?php echo __('Data forwarding frequency', $lang, '../') ?>:</label>
				<input type="text" name="frecuencia" class="form-control" value="<?php echo $dispositivo["frecuencia"]."\""?>" disabled="disabled">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
				<label for="conexion"><?php echo __('Connection', $lang, '../') ?>:</label>
				<input type="text" name="conexion" class="form-control" value="<?php echo $dispositivo["conexion"]."\""?>" disabled="disabled">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
				<label for="enlace"><?php echo __('Link', $lang, '../') ?>:</label>
				<input type="text" name="enlace" class="form-control" value="<?php echo $dispositivo["enlace"]."\""?>" disabled="disabled">
				</div>
			</div>
		</div>

		<br /><br />
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="ejemplo_email_1"><?php echo __('Actions', $lang, '../') ?>:</label><br/>
					<input type="hidden" name="PAG" value="DIS">
					<button name="ACC" value="CERRAR" id='cerrar' type="submit" class="btn btn-default" style='display:none'><?php echo __('Close', $lang, '../') ?></button>
					<input type="hidden" name="ID_dispositivo" value='<?php echo $dispositivo["id"]?>'>
					<!--<?php var_dump($dispositivo)?>-->
					<input type="hidden" name="OBJ" value='<?php echo serialize($dispositivo)?>'>
					<?php
					echo "<button name='ACC' value='GRABAR' id='grabar' type='submit' class='btn btn-default' style='display:none'>". __('SAVE', $lang, '../') ."</button>";
					echo "<button name='ACC' value='BORRAR' id='borrar' type='submit' class='btn btn-default' style='display:none'>". __('DELETE', $lang, '../') ."</button>";
					echo "<button id='edit' name='ACC' value='EDITAR' type='button' onclick='editar()' class='btn btn-default'>". __('EDIT', $lang, '../') ."</button>";
					?>
				</div>
			</div>
			<div class="col-sm-4">
				<input type="button" name="colicitud" class="form-control" value="<?php echo __('SEND REQUEST', $lang, '../') ?>" onclick="cambiarflag(<?php echo $dispositivo["id"]; ?>)"/>
			</div>
			</div>
		</div>
	</form>
<?php require ("s_footer.php"); ?>
