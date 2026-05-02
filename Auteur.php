<?php 
require_once("connexion.php");

class Auteur{
 private $conn;
private $table = "auteurs";

public $id;
public $nom;
public $prenom;

public $nationalite;

public function __construct($db){
        $this->conn = $db;
}
public function create(){
    $sql= "INSERT INTO " . $this->table . " (nom, prenom, nationalite)
            VALUES (:nom, :prenom, :nationalite)";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":nom", $this->nom);
    $stmt->bindParam(":prenom", $this->prenom);
    $stmt->bindParam(":nationalite", $this->nationalite);

    return $stmt->execute();
}
public function read() {
    $query = "SELECT * FROM " . $this->table . " ORDER BY nom";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
}
 public function readOne(){
    $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function update(){
    $query = "UPDATE " . $this->table . " 
                  SET nom = :nom, prenom = :prenom, nationalite = :nationalite 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":nationalite", $this->nationalite);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
}

public function delete(){
    $sql = "DELETE FROM " . $this->table . " WHERE id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $this->id);

    return $stmt->execute();
    
}
}
?>