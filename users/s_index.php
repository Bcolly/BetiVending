<?php
	require_once("../lang/language.php");
	require_once("seguridad.php");
	include_once("../conexion.php");

	if (isset($_GET["PAG"])) $PAG=$_GET["PAG"];
	else $PAG = null;
	if (isset($_GET["OBJ"])) $OBJ=$_GET["OBJ"];
	if (isset($_GET["ACC"])){
		$ACC=$_GET["ACC"];
		if ($ACC=="CERRAR"){
			$PAG=null;
			$OBJ=null;
		}
	}
	else $ACC = null;
	$usuario=$_SESSION["user"];
	$userid=$_SESSION["userid"];
?>

<!doctype html>
<html class="no-js" lang="">
	<head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>myVENDING</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Cambiando por la siguiene linea se evita zoom en los moviles...-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
    <link rel="apple-touch-icon" href="../apple-touch-icon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="../css/bootstrap_wizard.css">
		<script src="../js/ajax.js"></script>
		<script src="../js/filtro.js"></script>
		<script type="text/javascript" src="../js/jquery-1.12.0.min.js"></script>
		<script type="text/javascript" src="../js/graficos/Chart.bundle.min.js"></script>
		<script>
			function openNav() {
			    document.getElementById("ajustes").style.width = "250px";
			}
			function closeNav() {
			    document.getElementById("ajustes").style.width = "0";
			}
			</script>
  </head>
  <body>
		<!-- MENU AJUSTES -->
		<div id="ajustes" class="sidenav">
		  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<!--<a href="s_users.php"><?php echo __('Users', $lang, '../') ?></a>
			<a href=""><?php echo __('Stores', $lang, '../') ?></a>-->
			<a href="bajas.php"><?php echo __('Drop devices/machines', $lang, '../') ?></a>
			<a href="s_ajustes.php"><?php echo __('Change password', $lang, '../') ?></a>
		</div>
		<!-- MENU SUPERIOR -->
		<nav id='supmenu' class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="z-index:1">
		  <div class="container">
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="s_listadispositivos.php">myVENDING</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<form class="navbar-form navbar-left" role="form">
						<!--<a href="s_maquinas.php">-->
						<a href="s_listamaquinas.php">
							<button type="button" class="btn btn-success"><?php echo __('Machines', $lang, '../') ?></button>
						</a>
						<a href="s_listaproductos.php">
							<button type="button" class="btn btn-success"><?php echo __('Products', $lang, '../') ?></button>
						</a>
						<a href="estadisticas.php">
							<button type="button" class="btn btn-default"><?php echo __('Stadistics', $lang, '../') ?></button>
						</a>
						<a href="s_locales.php">
							<button type="button" class="btn btn-default"><?php echo __('Shops', $lang, '../') ?></button>
						</a>
					</form>
					<form class="navbar-form navbar-right" role="form" action="../control.php" method="POST">
						<input class="btn btn-link" type="button"
							style="width:35px; height:35px; background-image:url('../img/settings_green.png'); cursor:pointer"
							onclick="openNav()" />
						<button type="submit" class="btn btn-success" name="ACC" value="DESCONECTAR"><?php echo __('Log out', $lang, '../') ?></button>
						<?php require_once('../lang/lenguajehtml.php'); ?>
					</form>
				</div>
		  </div>
		</nav>
		<!-- TITULO -->
		<div class="jumbotron">
		  <div class="container">
				<font size=7>myVENDING</font><br/>
				<font size=5><?php echo $usuario;?></font>
		  </div>
		</div>

    <div class="container"> <!--Se cierra en s_footer.php-->
