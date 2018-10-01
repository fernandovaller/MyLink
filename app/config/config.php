<?php
/***************************************************************
* CONEXAO DB
*/
switch ($_SERVER['SERVER_NAME']) {

	case 'mylinks.local':	
	$DB_DRIVER = 'mysql';
	$DB_HOST = 'localhost';
	$DB_NAME = 'mylinks';
	$DB_USER = 'root';
	$DB_PWD  = 'root2017';
	break;

	// Usar o sistema com banco SQLite
	// case 'localhost':	
	// $DB_DRIVER = 'sqlite';
	// $DB_SQLITE = 'data';	
	// break;	

	default:
	var_dump('Erro-Config-DB:' . $_SERVER['SERVER_NAME']);
	exit();
	break;
}

define('DB_DRIVER' , $DB_DRIVER);
define('DB_HOST'   , $DB_HOST);
define('DB_NAME'   , $DB_NAME);
define('DB_USER'   , $DB_USER);
define('DB_PWD'    , $DB_PWD);
define('DB_SQLITE' , APPLICATION_PATH . DS . 'db'. DS . $DB_SQLITE);

$config = [
    'MODEL_PATH' => APPLICATION_PATH . DS. 'model' . DS,
    'VIEW_PATH'  => APPLICATION_PATH . DS. 'view' . DS,
    'LIB_PATH'   => APPLICATION_PATH . DS. 'lib' . DS,
    'DATA_PATH'  => APPLICATION_PATH . DS. 'data' . DS
];

require $config['LIB_PATH'] . 'functions.php';
require $config['LIB_PATH'] . 'helper.php';

//Autoload das class
function my_autoload($class_name) {
	include(APPLICATION_PATH . DS .'data'. DS . $class_name.".php");		
}
spl_autoload_register("my_autoload");