<?php
$esp = "";
if (strpos($_SERVER['REQUEST_URI'], 'users/') == true)$esp = "../";
require_once("language.php");
?>

<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
	<?php echo __('Language', $lang, $esp) ?> <span class="caret"/>
</button>
<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li role="presentation">
		<a role="menuitem" tabindex="-1" onclick="window.location='<?php echo $esp; ?>lang/cambia_lang.php?lang=en&url='+window.location;">
		<img src="<?php echo $esp; ?>img/flags/16/United Kingdom(Great Britain).png" alt="United Kingdom(Great Britain)"/>
		English</a>
	</li>
	<li role="presentation">
		<a role="menuitem" tabindex="-1" onclick="window.location='<?php echo $esp; ?>lang/cambia_lang.php?lang=es&url='+window.location;">
		<img src="<?php echo $esp; ?>img/flags/16/Spain.png" alt="Spain"/>
		Espa�ol</a>
	</li>
	<li role="presentation">
		<a role="menuitem" tabindex="-1" onclick="window.location='<?php echo $esp; ?>lang/cambia_lang.php?lang=eu&url='+window.location;">
		<img src="<?php echo $esp; ?>img/flags/16/Basque Country.png" alt="Basque Country"/>
		Euskera</a>
	</li>
</ul>
