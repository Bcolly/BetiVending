<?php
//Inicio la sesión
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if (isset($_SESSION["user"])) {
    //si existe, se envia a la página de usuario
    header("Location: users/s_listadispositivos.php");
    //ademas se cierra el script
    exit();
}

require('lang/language.php'); ?>

<!doctype html>
<html class="no-js" lang=""> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>myVENDING</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
		<script src="js/comprobar.js"></script>
		<script src="js/cookie.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>
  </head>
  <body onload="checkCookie_eu()">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		  <div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
        <a class="navbar-brand" href="#">myVENDING</a>
			</div>
			<div class="navbar-form navbar-right">
				<?php require('lang/lenguajehtml.php'); ?>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<form class="navbar-form navbar-right" role="form" action="control.php" method="POST">
					<div class="form-group">
						<input type="text" placeholder="<?php echo __('Email', $lang) ?>" class="form-control" name="user">
					</div>
					<div class="form-group">
						<input type="password" placeholder="<?php echo __('Password', $lang) ?>" class="form-control" name="psw">
					</div>
					<button type="submit" class="btn btn-success" name="ACC" value="ENTRAR"><?php echo __('Log in', $lang) ?></button>
					<button type="submit" class="btn btn-success" name="ACC" value="REGISTRARSE"><?php echo __('Sign up', $lang) ?></button>
				</form>
			</div>
			</div><!--/.navbar-collapse -->
		  </div>
		</nav>

<!-- ***************************************Orrialdearen zati nagusia*************************************************-->
		<?php
			if (isset($_GET["ACC"])) {
				$ACC=$_GET["ACC"];
				if ($ACC=="REGISTRARSE") include_once ("registrarse.php");
			} else include_once ("principal.php");
		?>
		<hr/>
		<footer>
			<p>&copy; Betivending, S. L. 2016</p>
		</footer>
		</div> <!-- /container -->
		<div id="cookie_directive_container" class="container" style="display: none">
      <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
          <div class="navbar-inner navbar-content-center" id="cookie_accept">
            <a onclick="closeCookiebar()" class="btn btn-default pull-right"><?php echo __('Close', $lang) ?></a>
            <p class="text-muted credit">
              <?php echo __('By using our website you are consenting to our use of cookies in accordance with our <a href="/cookies">cookie policy</a>.', $lang) ?>
            </p>
          </div>
        </div>
      </nav>
    </div>
  </body>
</html>
