<?php
require_once("../language.php");
require_once ("seguridad.php");
include_once ("../conexion.php");

if (isset($_GET["PAG"])) $PAG=$_GET["PAG"];
else $PAG = NULL;
if (isset($_GET["OBJ"])) $OBJ=$_GET["OBJ"];
if (isset($_GET["ACC"])){
	$ACC=$_GET["ACC"];
	if ($ACC=="CERRAR"){
		$PAG=NULL;
		$OBJ=NULL;
	}
}
else $ACC = NULL;
$usuario=$_SESSION["user"];
?> 
<!doctype html>
<html class="no-js" lang="">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <!--<html class="no-js" lang="">--> <!--<![endif]-->
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
        <style>
            body {
                padding-top: 30px;
                padding-bottom: 20px;
            }
			#tabla{
				height: 300px; /* %-height of the viewport */
				overflow-y: auto;
			}
        </style>
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/bootstrap_wizard.css">
		<script src="../js/ajax.js"></script>
    </head>
	
    <body>
		<!-- MENU SUPERIOR -->
		<!--[if lt IE 8]>
			<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
					<a href="s_maquinas.php">
						<button type="button" class="btn btn-success"><?php echo __('Machines', $lang, '../') ?></button>
					</a>
					<a href="s_listaproductos.php">
						<button type="button" class="btn btn-success"><?php echo __('Products', $lang, '../') ?></button>
					</a>
					<a href="estadisticas.php">
						<button type="button" class="btn btn-default"><?php echo __('Stadistics', $lang, '../') ?></button>
					</a>
					<a href="s_locales.php">
						<button type="button" class="btn btn-default"><?php echo __('Stores', $lang, '../') ?></button>
					</a>
					<a href="s_users.php">
						<button type="button" class="btn btn-default"><?php echo __('Users', $lang, '../') ?></button>
					</a>
				</form>  
				<form class="navbar-form navbar-right" role="form" action="../control.php" method="POST">
					<input class="btn btn-link" type='button' style='width:35px; height:35px; background-image:url("../img/settings_green.png");' /> 
					<button type="submit" class="btn btn-success" name="ACC" value="DESCONECTAR"><?php echo __('Log out', $lang, '../') ?></button>
					<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						<?php echo __('Language', $lang, '../') ?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" onclick="window.location='../cambia_lang.php?lang=en&url='+window.location;">
								<img src="../img/flags/16/United Kingdom(Great Britain).png" alt="United Kingdom(Great Britain)"/>
							English</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" onclick="window.location='../cambia_lang.php?lang=es&url='+window.location;">
								<img src="../img/flags/16/Spain.png" alt="Spain"/>
							Español</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" onclick="window.location='../cambia_lang.php?lang=eu&url='+window.location;">
								<img src="../img/flags/16/Basque Country.png" alt="Basque Country"/>
							Euskera</a>
						</li>
					</ul>
				</form>
				
			</div><!--/.navbar-collapse -->
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
	<?php 
	/*switch ($PAG) {
		case "DIS":
			include("dispositivo.php");
			break;
		case "BERRI":
			include("nuevodispositivo.php");
			break;
		case "PRO":
			include("s_listaproductos.php");
			break;
		case "MAQ":
			include("s_maquina.php");
			break;
		#aqui podria haber mas casos. Para evitar errores cualquier caso no deseado sera tratado como ningun valor
		default:
			include("s_listadispositivos.php");
	} */
	?>

