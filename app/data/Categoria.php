<?php  

class Categoria {

  private $con;
  private $table = 'categorias';

  private $categoria;
  private $url;
  

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

  public function findUrl($url){
    $sql = "SELECT * FROM $this->table WHERE url = :url";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':url', $url);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    //} else { return false; }
  }  

  public function findID($id){
    $sql = "SELECT * FROM $this->table WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      $aux = $stmt->fetch(PDO::FETCH_ASSOC);
      return $aux['categoria'];
    //} else { return false; }
  }

  public function findAll(){
    $sql = "SELECT * FROM $this->table ORDER BY categoria ASC";
    $stmt = $this->con->prepare($sql);
    //$stmt->bindParam(':id', $id);    
    $stmt->execute();
    //if($stmt->rowCount() > 0){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //} else { return false; }
  }     

  public function insert(){
    $sql = "INSERT INTO $this->table (categoria, url) VALUES (:categoria, :url)";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':categoria', $this->categoria);
    $stmt->bindParam(':url', $this->url);
    return $stmt->execute();

  }  

  public function update($id){
    $sql = "UPDATE $this->table SET categoria = :categoria, url = :url WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':categoria', $this->categoria);
    $stmt->bindParam(':url', $this->url);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();

  }  

  public function delete($id){
    $sql = "DELETE FROM $this->table WHERE id = :id";
    $stmt = $this->con->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
  }  


  public function formSelectOptions($value, $option, $selected = ''){    
    $sql = "SELECT * FROM $this->table ORDER BY categoria ASC";
    $stm = $this->con->query($sql);
    $stm->execute();

    //if($stm->rowCount() > 0){                             
      $cursos = $stm->fetchAll(PDO::FETCH_ASSOC);

      foreach ($cursos as $key => $row) {     
        if(is_array($selected)){      
          $sd = in_array($row[$value], $selected) ? 'selected="selected"' : '';
          $op .= "<option value=\"$row[$value]\" $sd>$row[$option]</option>";
        } else {
          $sd = ($selected == $row[$value]) ? 'selected="selected"' : '';
          $op .= "<option value=\"$row[$value]\" $sd>$row[$option]</option>";  
        }
      }
      return $op;     
    //} else { return false; }

  }   

  //metodos get/set
  public function getCategoria(){
    return $this->categoria;
  }

  public function setCategoria($categoria){
    $this->categoria = $categoria;
    return $this;
  }

  public function getUrl(){
    return $this->url;
  }

  public function setUrl($url){
    $this->url = $url;
    return $this;
  }



}

?>