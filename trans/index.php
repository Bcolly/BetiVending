<?php include('lenguajes.php'); ?>
<?php include_once("../language.php"); ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script src="trans.js"></script>
		<link rel="stylesheet" type="text/css" href="trans.css" media="screen" />
		
		<link rel="apple-touch-icon" href="../apple-touch-icon.png">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/main.css">
        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	</head>
	<body>
	<?php
		$lenguajes = scandir("../lenguajes");
		$buffer=array();
		$x = 0;
		foreach ($lenguajes as $l) {
			if ($x > 1){
				$buffer[] = $l;
			}
			else $x++;
		}
	?>
		<div class="container">
			<div class="row" id="select">
				<div class="col col-md-3 col-md-offset-1">
					<h5><?php echo __('FROM', $lang, '../') ?>:</h5>
					<select id="from" onchange="carga()">
					<?php
					echo '<option value="language_en.php">' . lenguaje() . '</option>';
					foreach ($buffer as $b) {
						echo '<option value="'. $b .'">' . lenguaje($b) . '</option>';
					}
					?>
					</select>
				</div>
				<div class="flecha col col-md-2 align-self-end"><h3>=></h3></div>	
				<div class="col col-md-3">
					<h5><?php echo __('TO', $lang, '../') ?>:</h5>
					<select id="to" onchange="carga()">
					<?php
					echo '<option value="language_en.php">' . lenguaje() . '</option>';
					foreach ($buffer as $b) {
						echo '<option value="'. $b .'">' . lenguaje($b) . '</option>';
					}
					?>
						<option value="0"><?php echo __('New Language', $lang, '../') ?></option>
					</select>
				</div>
				<div class="col col-md-2">
					<h5><?php echo __('Language', $lang) ?>:</h5>
					<select id="language" onchange="window.location='../cambia_lang.php?lang='+this.value+'&url='+window.location;">
						<option value="en">
							English
						</option>
						<option value="es">
							Español
						</option>
						<option value="eu">
							Euskera
						</option>
					</select>
				</div>
			</div>
			<div class="row" id="options">
				<div class="col col-md-3 col-md-offset-1">
					<input type="button" value="<?php echo __('New translation', $lang, '../') ?>" onclick="nueva()"/>
					<input type="button" value="<?php echo __('SAVE', $lang, '../') ?>" onclick="save()"/>
				</div>
				<div class="col col-md-4 col-md-offset-2" id="new" style="display: none;">
					<form>
						<label for="newlan"><?php echo __('New Language', $lang, '../') ?>:</label>
						<input type="text" id="text"/>
						<input type="button" value="<?php echo __('NEW', $lang, '../') ?>" onclick="newlan(text.value)"/>
					</form>
				</div>
			</div>
			<div class="row"><div class="container" id="lines" onload="carga()"></div></div>
			<!--<div id="message"></div>-->
		</div>
	</body>
</html>