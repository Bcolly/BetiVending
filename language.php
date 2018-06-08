<?php 
function getUserLanguage(){
	$lista=explode(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	$idiomas=array();
	for($i=1; $i<sizeof($lista); $i++){
		$idiomas[]=substr($lista[$i], 0, 2);
	}
	return $idiomas;	
}

function __($str, $lang = null, $c = ""){
	if ($lang != null){
		if (file_exists($c.'lenguajes/language_'.$lang.'.php')){
			include($c.'lenguajes/language_'.$lang.'.php');
			if (isset($texts[$str])) $str = $texts[$str];
		}
	}
	return $str;
}

if (!isset($_COOKIE["zurgaia_lang"])){
	$idiomas_web=array('es','en','eu');

	$idiomas=getUserLanguage();
	$bien=false;
	$i=0;
	while(!$bien && $i<sizeof($idiomas)){
		if (in_array($idiomas[$i], $idiomas_web)){
			$lang=$idiomas[$i];
			$bien=true;
		}
		else $i++;
	} 
	if (!$bien) $lang='en'; 
} else $lang=$_COOKIE["zurgaia_lang"];


?>