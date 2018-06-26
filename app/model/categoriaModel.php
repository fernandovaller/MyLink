<?php
$current_view = $config['VIEW_PATH'];

$action       = getURL(2);
//$id           = getURL(3);

$links = new Link();	
$categorias = new Categoria();

$id            = filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
$categoria     = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
$categoria_url = href($categoria);

$categoria_dados = $categorias->findUrl($action);	
$dados_categoria = $categorias->findAll();
$form_options = $categorias->formSelectOptions('id','categoria');	

$dados_links = $links->findAllCategoria($categoria_dados['id']);	

switch ($action) {

	case 'new':	
	$categorias->setCategoria($categoria);
	$categorias->setUrl($categoria_url);
	if($categorias->insert()){
		header("Location: /");			
	}		
	exit();
	break;	

	case 'update':	
	$categorias->setCategoria($categoria);
	$categorias->setUrl($categoria_url);
	if($categorias->update($id)){
		header("Location: /categoria/all");			
	}		
	exit();
	break;

	case 'all':		
	$dados = $categorias->findAll();
	$view = $current_view . 'categorias/all.phtml';
	break;	

	case 'edit':
	if(isset($id)){
	 $dados = $categorias->find($id);
	 echo json_encode($dados);
	 exit();	 
	}		
	break;			

	case 'delete':
	if(isset($id)){
		if($categorias->delete($id)){
			header("Location: /categoria/all/");
			exit();
		}	
	}		
	break;	

	default:		
	$view = $current_view . 'home.phtml';			
	break;
}
