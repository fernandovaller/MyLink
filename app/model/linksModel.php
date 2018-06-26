<?php
$current_view = $config['VIEW_PATH'];

$action       = getURL(2);
//$id           = getURL(3);

$links = new Link();
$categorias = new Categoria();

$id           = filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
$id_categoria = filter_input(INPUT_POST, "id_categoria", FILTER_SANITIZE_NUMBER_INT);
$titulo       = filter_input(INPUT_POST, "titulo", FILTER_SANITIZE_STRING);
$link         = filter_input(INPUT_POST, "link", FILTER_SANITIZE_URL);
$descricao    = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING);
$data         = date("Y-m-d G:i:s");

switch ($action) {

	case 'new':			
	$links->setIdCategoria($id_categoria);	
	$links->setTitulo($titulo);	
	$links->setLink($link);	
	$links->setDescricao($descricao);	
	$links->setData($data);	
	if($links->insert()){
		header("Location: /");		
	}	
	exit();
	break;	

	case 'update':		
	$links->setIdCategoria($id_categoria);	
	$links->setTitulo($titulo);	
	$links->setLink($link);	
	$links->setDescricao($descricao);	
	$links->setData($data );	
	if($links->update($id)){
		header("Location: /links/all");
	}	
	exit();
	break;

	case 'all':
	$dados_categoria = $categorias->findAll();
	$form_options = $categorias->formSelectOptions('id','categoria');	

	$dados = $links->findAll();
	$view = $current_view . 'links/all.phtml';
	break;	

	case 'delete':
	if(isset($id)){
		if($links->delete($id)){
			header("Location: /links/all/");
			exit();
		}	
	}		
	break;	

	case 'edit':
	if(isset($id)){
	 $dados = $links->find($id);
	 echo json_encode($dados);
	 exit();
	 //var_dump($dados);
	}		
	break;	

	default:
	$view = $current_view . 'home.phtml';
	$dados = array(1,2,3,4,5,6,7);
	break;		

}

// var_dump($current_view);
// var_dump($action);