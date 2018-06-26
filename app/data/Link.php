<?php  

class Link {

  private $con;
  private $table = 'links';

  private $id_categoria;
  private $titulo;
  private $link;
  private $descricao;
  private $data;
  

  function __construct() {
    $this->con = getConn();   
  }

  //metodos CRUD
  public function find($id){
    $sql = "SELECT * FROM $this->table WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    //} else { return false; }
  }

  public function findAll(){
    $sql = "SELECT * FROM $this->table ORDER BY id DESC";
    $stmt = $this->con->prepare($sql);
    //$stmt->bindParam(':id', $id);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      //var_dump($stmt);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //} else { return false; }
  }   

  public function findAllUltimos(){
    $sql = "SELECT * FROM $this->table ORDER BY id DESC LIMIT 10";
    $stmt = $this->con->prepare($sql);
    //$stmt->bindParam(':id', $id);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //} else { return false; }
  }    

  public function findNome($nome){
    $sql = "SELECT * FROM $this->table WHERE titulo LIKE concat('%', :nome, '%') OR descricao LIKE concat('%', :nome, '%') ORDER BY id DESC";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //} else { return false; }
  }  

  public function findAllCategoria($id){
    $sql = "SELECT * FROM $this->table WHERE id_categoria LIKE :id ORDER BY id DESC";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //} else { return false; }
  }   

  public function insert(){
    $sql = "INSERT INTO $this->table (id_categoria, titulo, link, descricao, data) VALUES (:id_categoria, :titulo, :link, :descricao, :data)";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id_categoria', $this->id_categoria);
    $stmt->bindParam(':titulo', $this->titulo);
    $stmt->bindParam(':link', $this->link);
    $stmt->bindParam(':descricao', $this->descricao);
    $stmt->bindParam(':data', $this->data);
    return $stmt->execute();

  }  

  public function update($id){
    $sql = "UPDATE $this->table SET id_categoria = :id_categoria, titulo = :titulo, link = :link, descricao = :descricao WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id_categoria', $this->id_categoria);
    $stmt->bindParam(':titulo', $this->titulo);
    $stmt->bindParam(':link', $this->link);
    $stmt->bindParam(':descricao', $this->descricao);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();

  }  

  public function delete($id){
    $sql = "DELETE FROM $this->table WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();

  }  

  //metodos get/set
  public function getIdCategoria(){
    return $this->id_categoria;
  }

  public function setIdCategoria($id_categoria){
    $this->id_categoria = $id_categoria;
    return $this;
  }

  public function getTitulo(){
    return $this->titulo;
  }

  public function setTitulo($titulo){
    $this->titulo = $titulo;
    return $this;
  }

  public function getLink(){
    return $this->link;
  }

  public function setLink($link){
    $this->link = $link;
    return $this;
  }

  public function getDescricao(){
    return $this->descricao;
  }

  public function setDescricao($descricao){
    $this->descricao = $descricao;
    return $this;
  }

  public function setData($data){
    $this->data = $data;
    return $this;
  }


}

?>