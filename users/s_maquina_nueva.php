<?php require ("s_index.php");

	$id = -1;
	if (isset($_GET["dnm"])) $id=$_GET["dnm"];
	elseif (isset($_GET["dnm"])) $id=$_POST["dnm"];

	$con = 1;
	$basededatos=conectardb();
	$sql=query("SELECT *
							FROM v_dispositivo
							WHERE userid=$userid AND id=$id",
		$basededatos, $con);
	$con++; //2
	if ($sql->rowCount() < 1) {
	    //si no existe, se envia a la página de autentificacion
	    echo "<script> location.href='../index.php';</script>";
	    //ademas se cierra el script
	    exit();
	}

	//POST
	if (isset($_POST['send'])){
		$disp = $sql -> fetch();
		execute("INSERT INTO v_maquinas (nombre, idlocal, dispositivoid)
			VALUES ('$_POST[nombremaquina]', $disp[idlocal] , $_POST[dnm])", $basededatos, $con);
		$con++; //3

		$sql=query("SELECT id
					FROM v_maquinas
					WHERE nombre='$_POST[nombremaquina]' AND dispositivoid=$_POST[dnm]",
		$basededatos, $con);
		$con++; //4
		$maq = $sql -> fetch();

		execute("INSERT INTO v_user_maquinas VALUES ($userid, $maq[id])", $basededatos, $con);
		$con++; //5

		for($i = 1; $i <= $_POST['selgroup']; $i++){
			$values = '';
			for($x = 1; $x <= $_POST['n'.$i]; $x++){
				$sel = $_POST["g$i"].$x;
				$values .= "($maq[id], '$sel')";
				if ($x < $_POST['n'.$i]) $values .= ',';
			}
			execute("INSERT INTO v_seleccion (idmaquina, sel) VALUES ".$values, $basededatos, $con);
			$con++; //6
		}
		echo "<div class='alert alert-success'>".
			__('Machine added correctly', $lang, '../')
			."</div>";
	}

?>
	<div class="container">
		<div class="row">
			<form class="navbar-form" role="form" method="POST">
				<input type="hidden" class="form-control" id="dnm" name="dnm" value="<?php echo $id; ?>"/>
				<div class="form-group">
					<label for="nombremaquina"><?php echo __('Name', $lang, '../') ?>: </label>
					<input type="text" class="form-control" id="nombremaquina" name="nombremaquina" required/>
				</div><br/>
				<div class="form-group">
					<label for="selgroup"><?php echo __('Selection groups', $lang, '../') ?>: </label>
					<select class="form-control" id="selgroup" name="selgroup" onchange="cambiagrupos()">
						<option value="0">0</option>
						<option value="1" selected="selected">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div><br/>
				<div class="form-group">
					<label><?php echo __('Groups', $lang, '../') ?>: </label>
				</div><br/>
				<div class="form-group" id="grupos">
					<input type="text" class="form-control" id="g1" name="g1" required pattern="[A-Za-z]"/>
					<input type="number" class="form-control" id="n1" name="n1" min = '1'/>
				</div><br/>
				<div class="form-group">
					<button type="submit" class="btn btn-success" name='send'><?php echo __('Send', $lang, '../') ?></button>
				</div>
			</form>
		</div>
		<div class="row">
			<form class="navbar-form" role="form" action="s_listadispositivos.php">
				<div class="form-group">
					<button type="submit" class="btn btn-success"><?php echo __('Exit', $lang, '../') ?></button>
				</div>
			</form>
		</div>
	</div>
<?php include_once ("s_footer.php"); ?>

<script>
	function cambiagrupos(){
		var gr = document.getElementById("selgroup").value;
		var i;
		var text = '';
		for (i = 1; i <= gr; i++){
			text += '<input type="text" class="form-control" id="g'+i+'" name="g'+i+'"/>'+
			'<input type="number" class="form-control" id="n'+i+'" name="n'+i+'" min= "1"/><br/>'
		}
		document.getElementById("grupos").innerHTML = text;
	}
</script>
