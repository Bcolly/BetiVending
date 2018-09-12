<?php
	//Conexion con la base de datos
	function conectardb(){
		//parametros local
		$host="mysql:host=localhost;dbname=betiVENDING";
		$usr="root";
		$pass="";
		/*
		//parametros web
		$host="mysql:host=localhost;dbname=betiV";"betiVuser";
		$usr="betiVuser";
		$pass="betiV-2013";
		*/
		try{
			$basededatos = new PDO ($host,$usr,$pass);
			$basededatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$basededatos->exec("SET NAMES 'utf8'");
		} catch (Exception $e){
			echo "Conexion no disponible. Contacte con el administrador";
			exit;
		}
		return $basededatos;
	}

	function query($q, $db, $con){
		try{
			$response = $db->query($q);
		} catch (exception $e) {
		  echo "No se puede la consulta ".$con;
		  exit;
		}
		return $response;
	}

	function execute($q, $db, $con){
		try{
			$query = $db->prepare($q);
			$query->execute();
		}
		catch (exception $e) {
			echo "No se puede la consulta ".$con;
			exit;
		}
	}

	function execute_expunge($q, $db, $stream, $nm, $con){
		try{
			$query = $db->prepare($q);
			$query->execute();
		}
		catch (exception $e) {
			echo "No se puede la consulta ".$con;
			imap_delete($stream,$nm); //Marca el mail para ser borrado
			imap_expunge($stream);    //Borra los mail marcados para borrar
			exit;
		}
	}
?>
