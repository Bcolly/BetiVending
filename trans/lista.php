<?php
	if (isset($_GET["len"])){
		if ($_GET["from"] != 'language_en.php'){
			include ("../lenguajes/$_GET[from]");
			$keys = array_values($texts);
			$aux_text = $texts;
			include ("../lenguajes/$_GET[len]");
		} else {
			include ("../lenguajes/$_GET[len]");
			$keys = array_keys($texts);
		}
		$x = 0;
		foreach ($keys as $k) {
			if ($k != '') {
				echo "<div class='row'>
					<div class='col col-md-3 col-md-offset-1'>
						<input type='text' value='$k' name='from' />
					</div>
					<div class='flecha col col-md-2 align-self-start'><h3>=></h3></div>";
				if ($aux_text) $k = array_keys($aux_text, $k)[0];
				echo "<div class='col col-md-3'>
						<input type='text' value='$texts[$k]' name='to'/>
					</div>
				</div>";
			}
			$x++;
		}
	}
?>