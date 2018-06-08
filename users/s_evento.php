<?php include_once("../language.php"); ?>
<?php include_once ("seguridad.php"); ?>
<?php include_once ("../conexion.php"); ?>
<?php $id = $_GET['id']; ?>
<html>
<head>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
		<script language="JavaScript">
			function cerrar() {
			ventana=window.self;
			ventana.opener=window.self;
			ventana.close(); }
		</script>
</head>
<body>
	<div class="container">
		<b><?php echo __("EVENTS", $lang, "../").':';
			try {
				$sql=$basededatos->query("SELECT * FROM v_evento WHERE idmaquina=$id");
				$num = $sql->rowCount();
			} catch (exception $e) {
				echo "No se puede la consulta 2";
				exit;
			} 
			echo $num;
		?></b><br/>
		<div style="overflow-y: scroll; height: 400px;">
		<?php
			try {
				$sql=$basededatos->query("SELECT * FROM v_evento WHERE idmaquina=$id");
			} catch (exception $e) {
				echo "No se puede la consulta 2";
				exit;
			}
			echo '<ul>';
			foreach ($sql as $evento) {
				echo "<li>$evento[fecha_hora] :  $evento[sel]</li>";
			}
			echo '</ul>';
		?>
		</div>
		<button type="button" class="btn btn-info" onclick="limpiar('<?php echo$id; ?>')"><?php echo __("CLEAR", $lang, "../")?></button>
		<button type="button" class="btn btn-info" onclick="cerrar()"><?php echo __("CLOSE", $lang, "../")?></button>
	</div>
</body>
</html>