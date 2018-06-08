<?php
if (isset($_GET["lang"]) && isset($_GET["url"])){
	setcookie('zurgaia_lang',$_GET["lang"], time()+60*60*24*30, '/');
	header("Location: ".$_GET["url"]);
}	
exit();
?>