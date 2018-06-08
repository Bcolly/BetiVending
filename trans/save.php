<?php
	$from_lang = $_GET["fromlang"];
	if ($from_lang != 'language_en.php') {
		include ("../lenguajes/$from_lang");
		$keys = array_keys($texts);
		$from = array();
		foreach ($keys as $k){
			if ($texts[$k] != ''){
				array_push($from, $k);
			}
		}
	} else $from = unserialize($_GET["from"]);
	$to = unserialize($_GET["to"]);
	//echo 'from:\r\n'.$from;
	//echo 'to:\r\n'.$to;
	
	$fp = fopen("../lenguajes/$_GET[lan]", "w"); 
	fwrite($fp, '<?php' . PHP_EOL);
	fwrite($fp, '$texts = array(' . PHP_EOL);
	//echo 'EMPIEZA \r\n';
	
	for ($i = 0; $i < count($from); $i++) {
		if ($i > 0) {
			//echo ",\r\n";
			fwrite($fp, "," . PHP_EOL);
		}
		fwrite($fp, "'".$from[$i]."' => '" . $to[$i]."'");
		//echo "'".$from[$i]."' => '" . $to[$i]."'";
	}
	fwrite($fp, PHP_EOL);
	fwrite($fp, ');' . PHP_EOL);
	fwrite($fp, '?>');
	fclose($fp);
?>