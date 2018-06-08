<?php
	include("listauso.php");
	function lenguaje($lan = 'language_en.php'){
		global $language;
		$l = substr($lan, 9, -4);
		$res = $l;
		if (isset($language[$l])) {
			$res = $language[$l];
		}
		return $res;
	}
?>