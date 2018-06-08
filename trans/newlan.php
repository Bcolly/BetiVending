<?php
include("listalang.php");
include("listauso.php");
if (isset($_GET["lan"])) {
	$iso = array_search($_GET["lan"], $idiomas);
	echo $_GET["lan"].'<br>';
	echo $iso.'<br>';
	$new = "language_".$iso.".php";
	echo $new.'<br>';
	$lenguajes = scandir("../lenguajes");
	$existe = false;
	foreach ($lenguajes as $l) {
		echo $l.'<br/>';
		echo $existe ? 'true' : 'false';
		echo '<br/>';
		if ($l == "$new") $existe = true;
	}
	if (!$existe) {
		$fp = fopen("../lenguajes/$new", "w");
		fwrite($fp, '<?php' . PHP_EOL);
		fwrite($fp, '$texts = array(' . PHP_EOL);
		fwrite($fp, ');' . PHP_EOL);
		fwrite($fp, '?>');
		echo 'creado<br/>';
		//$fp.close();
		
		$language[$iso] = $_GET["lan"];
		$keys = array_keys($language);
		echo serialize($language).'<br />';
		echo serialize($keys).'<br />';
		
		$fp = fopen("listauso.php", "w");
		fwrite($fp, '<?php' . PHP_EOL);
		fwrite($fp, '$language = array(' . PHP_EOL);
		$x = 0;
		for ($i = 0; $i < count($language); $i++) {
			if ($i > 0) {
				fwrite($fp, "," . PHP_EOL);
				echo ",<br/>";
			}
			$key = $keys[$i];
			$idi = $language[$key];
			fwrite($fp, "'$key' => '$idi'");
			echo "'$key' => '$idi'";
		}
		fwrite($fp, PHP_EOL . ');' . PHP_EOL);
		fwrite($fp, '?>');
		$fp.close();
	}
}
?>