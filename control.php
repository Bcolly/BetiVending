<?php
require_once ("conexion.php");
session_start();

$ACC=$_POST["ACC"];
switch($ACC) {
	case "ENTRAR":
		entrar();
		break;
	case "DESCONECTAR":
		session_destroy();
		header ("Location: index.php");
		break;
	case "REGISTRARSE":
		header ("Location: index.php?ACC=REGISTRARSE");
		break;
	case "ENVIAR":
		enviar();
}

//vemos si el usuario y contraseña es váildo
function comprobar_email($email){
   	$correcto = true;

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
					$correcto = false;
				}
			}
		}
	}
   	return $correcto;
}

function comprobar_pass($ps1, $ps2){
   	$correcto = true;
	if ($ps1 != $ps2 or strlen($ps1)<5) $correcto = false;
   	return $correcto;
}

function entrar(){
	$basededatos=conectardb();
	$passcript=sha1($_POST["psw"]);
	$sql=query("SELECT * FROM v_user WHERE mail = '$_POST[user]' AND passwd = '$passcript'", $basededatos, 1);

	$row = $sql -> fetch();
	if ($_POST["user"]==$row['mail'] && $passcript==$row['passwd']){
		$_SESSION["user"]= $row['usuario'];
		$_SESSION["userid"]= $row['id'];
		header ("Location: ./users/s_listadispositivos.php");
		exit();
	}
	$basededatos = null;
	header ("Location: index.php");
}

function enviar(){
	if (comprobar_email($_POST["mail"])
	&&  comprobar_pass ($_POST['psw1'], $_POST['psw2'])) {
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

			//Entramos en el sistema
			echo "Mail: ".$mail."<br />";
			echo "Usuario: ".$user."<br />";

			$sql=query("SELECT * FROM v_user WHERE mail = '$mail' AND passwd = '$psw'", $basededatos, $con);
			$con++;

			foreach ($sql as $row) {
				if ($mail==$row['mail'] && $psw==$row['passwd']){
					$_SESSION["user"]= $user; //aqui habria que cambiarlo or el nombre de usuario
					$_SESSION["userid"]= $row['id'];
					header ("Location: ./users/s_index.php");
					exit();
				}
			}
		}
		$basededatos = null;
	}
}
?>
