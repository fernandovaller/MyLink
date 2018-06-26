<?php
$current_view = $config['VIEW_PATH'];
$action = getURL(2, 'home');

$links = new Link();	
$categorias = new Categoria();

$dados_categoria = $categorias->findAll();
$form_options = $categorias->formSelectOptions('id','categoria');	

$dados_links = $links->findAllUltimos();

switch ($action) {
	default:				
	$view = $current_view . 'home.phtml';		
	break;

	case 'pesquisar':	
	$q = $_POST['q'];
	$dados_links = $links->findNome($q);
	$view = $current_view . 'home.phtml';	
	break;
}
