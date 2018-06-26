<?php
//defined, is a boolean.
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
const DS = DIRECTORY_SEPARATOR;

require APPLICATION_PATH . DS . 'config' . DS . 'config.php';

$page = getURL(1, 'home');

$model     = $config['MODEL_PATH'] . $page . 'Model.php';
$view      = $config['VIEW_PATH'] . $page . '.phtml';

$_404      = $config['VIEW_PATH'] . '404.phtml';
$page_view = $_404;

//Carrega o model
if(file_exists($model)) 
	require $model;

//Carrega a view 
if(file_exists($view))
	$page_view = $view;

include $config['VIEW_PATH']. 'layout.phtml';