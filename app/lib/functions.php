<?php

function getConn(){	
	
	try {

		switch (DB_DRIVER) {
			case 'mysql' : $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") ); break;
			case 'pgsql' : $db = new PDO('pgsql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';', DB_USER, DB_PASS); break;
			case 'sqlite': $db = new PDO("sqlite:".DB_SQLITE, '', '',array( PDO::ATTR_PERSISTENT => true)); break;
		}
				
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		
	} catch (Exception $e) {
		//echo $e->getMessage();
		if($e->getCode() == 1044) echo '[Error: 1] Infelizmente estamos tendo problemas de conexao no momento, tente novamente.'; //Usuario
		if($e->getCode() == 1045) echo '[Error: 2] Infelizmente estamos tendo problemas de conexao no momento, tente novamente.'; //Senha			
		if($e->getCode() == 1049) echo '[Error: 3] Infelizmente estamos tendo problemas de conexao no momento, tente novamente'; //Banco
		if($e->getCode() == 2002) echo '[Error: 4] Infelizmente estamos tendo problemas de conexao no momento, tente novamente.'; //Host
	}

    return $db;
}