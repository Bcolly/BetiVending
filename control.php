<?php
require_once ("conexion.php");
session_start();

$ACC=$_POST["ACC"];
switch($ACC) {
	case "ENTRAR":
		entrar();
		break;
	case "DESCONECTAR":
		desconectar();
		break;
	case "REGISTRARSE":
		header ("Location: index.php?ACC=REGISTRARSE");
		break;
	case "ENVIAR":
		enviar();
}

//vemos si el usuario y contraseña es váildo
function comprobar_email($email){ 
   	$mail_correcto = 1; //true
	
	//comprobamos longitud y si tiene @
	if ((strlen($email) < 6) 
	|| (substr_count($email,"@") != 1) 
	|| (substr($email,0,1) == "@") 
	|| (substr($email,strlen($email)-1,1) == "@")){
		//miro si tiene caracter . 
         if (substr_count($email,".") < 1){ 
			$term_dom = substr(strrchr ($email, '.'),1); 
            //compruebo que la terminación del dominio sea correcta 
            if (strlen($term_dom)<1 || strlen($term_dom)>5 || (strstr($term_dom,"@")) ){
				//compruebo que lo de antes del dominio sea correcto 
				$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
				$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
				if ($caracter_ult == "@" || $caracter_ult == "."){ 
					$mail_correcto = 0; //false
				}
			}
		}
	}
	/*
   	if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
      	if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
         	//miro si tiene caracter . 
         	if (substr_count($email,".")>= 1){ 
            	//obtengo la terminacion del dominio 
            	$term_dom = substr(strrchr ($email, '.'),1); 
            	//compruebo que la terminación del dominio sea correcta 
            	if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
					//compruebo que lo de antes del dominio sea correcto 
					$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
					$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
					if ($caracter_ult != "@" && $caracter_ult != "."){ 
						$mail_correcto = 1; //true
					} 
            	} 
         	} 
      	}
   	}*/
   	return $mail_correcto;
}

function comprobar_pass($ps1, $ps2){ 
   	$correcto = 1; //true
	if ($ps1 != $ps2 or strlen($ps1)<5) $correcto = 0; //false
   	return $correcto;
}

function entrar(){
	$basededatos=conectardb();
	$passcript=sha1($_POST["psw"]);
	$sql=query("SELECT * FROM v_user WHERE mail = '$_POST[user]' AND passwd = '$passcript'", $basededatos, 1);
	
	foreach ($sql as $row) {
		if ($_POST["user"]==$row['mail'] && $passcript==$row['passwd']){
			$_SESSION["autentificado"]= "SI";
			$_SESSION["user"]= $row['usuario'];
			header ("Location: ./users/s_listadispositivos.php");
			exit();
		}
	}
	$basededatos = null;
	$_SESSION["autentificado"]= "NO";
	//header ("Location: ./sarrera.php?errorusuario=SI");
}

function desconectar(){
	$_SESSION["autentificado"]= "NO";
	$_SESSION["user"]= NULL;
	header ("Location: ./index.php");
}

function enviar(){
	if ((comprobar_email($_POST["mail"]) == 1)
	&&  (comprobar_pass ($_POST['psw1'], $_POST['psw2']) == 1)) {    
		$mail=$_POST["mail"];
		$user=$_POST["user"];
		$con=1;
		$basededatos=conectardb();
		$users=query("SELECT * FROM v_user where mail='$mail'", $basededatos, $con);
		$con++;
		
		if ($users->rowCount() < 1) {
			echo "Lo introducimos en la base de datos.<br />";
			$psw = sha1($_POST["psw1"]);
			
			execute("INSERT INTO v_user (usuario,passwd,mail) values ('$user','$psw','$mail')", $basededatos, $con);
			$con++;
			
			#Entramos en el sistema
			$_SESSION["autentificado"]= "SI";  
			echo "Mail: ".$mail."<br />";
			echo "Usuario: ".$user."<br />";
			$_SESSION["user"]= $user; #aqui habria que cambiarlo or el nombre de usuario
			#***********************************************************
			#Aqui hay que crear las tablas de datos de usuario
			#***********************************************************
			header ("Location: ./users/s_index.php");   
		}
		$basededatos = null;
	}
}
?>